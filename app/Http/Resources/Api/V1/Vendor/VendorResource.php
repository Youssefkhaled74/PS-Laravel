<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,
            'whatsapp_phone' => $this->whatsapp_phone,
            'bio' => $this->bio,
            'avatar_url' => $this->avatar ? asset($this->avatar) : null,
            'location_text' => $this->location_text,
            'national_address' => $this->national_address,
            'national_id' => $this->national_id,
            'status' => $this->status,
            'onboarding_step' => $this->onboarding_step,
            'phone_verified_at' => $this->phone_verified_at?->toIso8601String(),
            'status_message' => $this->getStatusMessage(),
            
            // Business Profile
            'business_profile' => $this->whenLoaded('businessProfile', function () {
                return [
                    'commercial_name' => $this->businessProfile->commercial_name,
                    'commercial_register_number' => $this->businessProfile->commercial_register_number,
                    'freelance_doc_number' => $this->businessProfile->freelance_doc_number,
                    'bank_id' => $this->businessProfile->bank_id,
                    'bank_account_number' => $this->businessProfile->bank_account_number,
                    'id_card_file_url' => $this->businessProfile->id_card_file ? asset($this->businessProfile->id_card_file) : null,
                    'commercial_file_url' => $this->businessProfile->commercial_file ? asset($this->businessProfile->commercial_file) : null,
                    'accept_terms' => $this->businessProfile->accept_terms,
                    'status' => $this->businessProfile->status,
                    'bank' => $this->businessProfile->bank ? [
                        'id' => $this->businessProfile->bank->id,
                        'name' => $this->getLocalizedBankName($this->businessProfile->bank),
                        'logo_url' => $this->businessProfile->bank->logo ? asset($this->businessProfile->bank->logo) : null,
                    ] : null,
                ];
            }),

            // Brands
            'brands' => $this->whenLoaded('brands', function () {
                return $this->brands->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $this->getLocalizedBrandName($brand),
                        'logo_url' => $brand->brandLogoUrl(),
                    ];
                });
            }),

            // Documents
            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'type' => $document->type,
                        'file_url' => asset($document->file_path),
                        'created_at' => $document->created_at->toIso8601String(),
                    ];
                });
            }),

            // Active Package Assignment
            'active_subscription' => $this->whenLoaded('activePackageAssignment', function () {
                if ($this->activePackageAssignment) {
                    return new VendorPackageAssignmentResource($this->activePackageAssignment);
                }
                return null;
            }),

            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    /**
     * Get status message based on vendor status
     */
    protected function getStatusMessage(): string
    {
        $locale = app()->getLocale();
        
        return match($this->status) {
            'pending' => __('vendor.status.pending'),
            'otp_pending' => __('vendor.status.otp_pending'),
            'subscription_pending' => __('vendor.status.subscription_pending'),
            'payment_pending' => __('vendor.status.payment_pending'),
            'payment_failed' => __('vendor.status.payment_failed'),
            'awaiting_approval' => __('vendor.status.awaiting_approval'),
            'active' => __('vendor.status.active'),
            'rejected' => __('vendor.status.rejected'),
            'suspended' => __('vendor.status.suspended'),
            default => __('vendor.status.pending'),
        };
    }

    /**
     * Get localized bank name
     */
    protected function getLocalizedBankName($bank): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $bank->name_ar : $bank->name_en;
    }

    /**
     * Get localized brand name
     */
    protected function getLocalizedBrandName($brand): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $brand->name_ar : $brand->name_en;
    }
}

