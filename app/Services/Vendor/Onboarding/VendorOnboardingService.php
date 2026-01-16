<?php

namespace App\Services\Vendor\Onboarding;

use App\Models\Vendor;
use App\Models\VendorBusinessProfile;
use App\Models\VendorDocument;
use App\Models\VendorPackageAssignment;
use App\Models\VendorPackage;
use App\Models\VendorPaymentAttempt;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorOnboardingService
{
    use UploadTrait;

    /**
     * Save vendor commercial data
     */
    public function saveCommercialData(Vendor $vendor, array $data): Vendor
    {
        DB::beginTransaction();
        
        try {
            // Upload ID card file
            $idCardPath = null;
            if (isset($data['id_card_file'])) {
                $idCardPath = $this->uploadPublicFile(
                    $data['id_card_file'],
                    'uploads/vendor/documents/id_cards'
                );
            }

            // Upload commercial file
            $commercialPath = null;
            if (isset($data['commercial_file'])) {
                $commercialPath = $this->uploadPublicFile(
                    $data['commercial_file'],
                    'uploads/vendor/documents/commercial'
                );
            }

            // Upsert vendor business profile
            $profileData = [
                'vendor_id' => $vendor->id,
                'commercial_name' => $data['commercial_name'],
                'commercial_register_number' => $data['commercial_register_number'] ?? null,
                'freelance_doc_number' => $data['freelance_document_number'] ?? null,
                'bank_id' => $data['bank_id'],
                'bank_account_number' => $data['bank_account_number'],
                'id_card_file' => $idCardPath,
                'commercial_file' => $commercialPath,
                'accept_terms' => $data['accept_terms'],
                'status' => 'pending',
            ];

            VendorBusinessProfile::updateOrCreate(
                ['vendor_id' => $vendor->id],
                $profileData
            );

            // Attach brands
            if (isset($data['brands']) && is_array($data['brands'])) {
                $vendor->brands()->sync($data['brands']);
            }

            // Create vendor_documents entries
            if ($idCardPath) {
                VendorDocument::updateOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'type' => 'id_card'
                    ],
                    [
                        'file_path' => $idCardPath
                    ]
                );
            }

            if ($commercialPath) {
                VendorDocument::updateOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'type' => 'commercial_register'
                    ],
                    [
                        'file_path' => $commercialPath
                    ]
                );
            }

            // Update vendor status and onboarding step
            $vendor->update([
                'onboarding_step' => 'commercial_completed',
                'status' => 'subscription_pending',
            ]);

            DB::commit();

            return $vendor->fresh(['businessProfile', 'brands', 'documents']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Select package for vendor
     */
    public function selectPackage(Vendor $vendor, array $data): VendorPackageAssignment
    {
        DB::beginTransaction();
        
        try {
            // Get package
            $package = VendorPackage::findOrFail($data['package_id']);

            // Determine price based on billing period (stored in cents)
            $price = $data['billing_period'] === 'yearly' 
                ? $package->yearly_price 
                : $package->monthly_price;

            // Create package assignment
            $assignment = VendorPackageAssignment::create([
                'vendor_id' => $vendor->id,
                'vendor_package_id' => $package->id,
                'billing_cycle' => $data['billing_period'],
                'price' => $price,
                'currency' => 'SAR',
                'status' => 'pending',
                'starts_at' => null,
                'ends_at' => null,
            ]);

            // Update vendor status
            $vendor->update([
                'onboarding_step' => 'subscription_selected',
                'status' => 'payment_pending',
            ]);

            DB::commit();

            return $assignment->fresh(['package', 'vendor']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Confirm payment for vendor subscription
     */
    public function confirmPayment(Vendor $vendor, array $data): VendorPaymentAttempt
    {
        DB::beginTransaction();
        
        try {
            // Get package assignment
            $assignment = VendorPackageAssignment::where('vendor_id', $vendor->id)
                ->findOrFail($data['vendor_subscription_id']);

            // Calculate amounts (all in cents)
            $amount = $assignment->price;
            $vat = 0; // Can be calculated if needed: ($amount * 15) / 100 for 15% VAT
            $total = $amount + $vat;

            // Determine simulate status (default: pending)
            $simulateStatus = $data['simulate_status'] ?? 'pending';

            // Create payment attempt
            $attempt = VendorPaymentAttempt::create([
                'vendor_id' => $vendor->id,
                'vendor_package_assignment_id' => $assignment->id,
                'payment_method_id' => $data['payment_method_id'],
                'billing_period' => $assignment->billing_cycle,
                'amount' => $amount,
                'vat' => $vat,
                'total' => $total,
                'status' => $simulateStatus,
                'reference' => $data['reference'] ?? 'SIM-' . strtoupper(uniqid()),
                'meta' => $data['meta'] ?? null,
            ]);

            // Handle different payment statuses
            if ($simulateStatus === 'paid') {
                // Calculate subscription period
                $startsAt = now();
                $endsAt = $assignment->billing_cycle === 'yearly' 
                    ? $startsAt->copy()->addYear() 
                    : $startsAt->copy()->addMonth();

                // Update assignment
                $assignment->update([
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                ]);

                // Update vendor
                $vendor->update([
                    'onboarding_step' => 'payment_completed',
                    'status' => 'awaiting_approval',
                ]);

            } elseif ($simulateStatus === 'failed') {
                $vendor->update([
                    'status' => 'payment_failed',
                ]);

            } else { // pending or initiated
                // Keep vendor status as payment_pending
                $vendor->update([
                    'status' => 'payment_pending',
                ]);
            }

            DB::commit();

            return $attempt->fresh(['vendor', 'packageAssignment', 'paymentMethod']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
