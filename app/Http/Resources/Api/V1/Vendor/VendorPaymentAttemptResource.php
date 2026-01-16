<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorPaymentAttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'vendor_subscription_id' => $this->vendor_package_assignment_id,
            'billing_period' => $this->billing_period,
            'amount' => $this->amount,
            'amount_sar' => $this->getAmountInSAR(),
            'vat' => $this->vat,
            'vat_sar' => $this->getVatInSAR(),
            'total' => $this->total,
            'total_sar' => $this->getTotalInSAR(),
            'status' => $this->status,
            'reference' => $this->reference,
            'meta' => $this->meta,
            
            // Payment Method
            'payment_method' => $this->whenLoaded('paymentMethod', function () {
                return new PaymentMethodResource($this->paymentMethod);
            }),

            // Package Assignment
            'subscription' => $this->whenLoaded('packageAssignment', function () {
                return new VendorPackageAssignmentResource($this->packageAssignment);
            }),

            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
