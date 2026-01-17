<?php

namespace App\Services\SpecialOrders;

use App\Models\SpecialOrder;
use App\Models\Vendor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class VendorSpecialOrderService
{
    public function listForVendor(Vendor $vendor, array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(50, $perPage));

        $q = SpecialOrder::where('vendor_id', $vendor->id);
        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        $q->orderBy('created_at', 'desc');
        return $q->paginate($perPage);
    }

    public function showForVendor(Vendor $vendor, int $id): SpecialOrder
    {
        return SpecialOrder::where('vendor_id', $vendor->id)->where('id', $id)->firstOrFail();
    }

    public function decide(Vendor $vendor, int $id, array $data): SpecialOrder
    {
        return DB::transaction(function () use ($vendor, $id, $data) {
            $order = SpecialOrder::where('vendor_id', $vendor->id)->where('id', $id)->firstOrFail();

            $decision = $data['decision'] ?? null;
            if ($decision === 'accept') {
                $order->status = 'accepted';
                $order->rejection_reason = null;
            } elseif ($decision === 'reject') {
                $order->status = 'rejected';
                $order->rejection_reason = $data['rejection_reason'] ?? null;
            }
            $order->save();

            // TODO: Notify user about decision

            return $order->refresh();
        });
    }
}
