<?php

namespace App\Services\Admin\Items;

use App\Models\VendorItem;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ItemApprovalService
{
    public function approve(Admin $admin, VendorItem $item): VendorItem
    {
        if ($item->status === 'approved') return $item;

        DB::beginTransaction();
        try {
            $data = [
                'status' => 'approved',
            ];

            if (Schema::hasColumn('vendor_items', 'rejection_reason')) {
                $data['rejection_reason'] = null;
            }

            if (Schema::hasColumn('vendor_items', 'approved_by_admin_id')) {
                $data['approved_by_admin_id'] = $admin->id;
            }
            if (Schema::hasColumn('vendor_items', 'approved_at')) {
                $data['approved_at'] = now();
            }

            $item->update($data);
            DB::commit();
            return $item->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reject(Admin $admin, VendorItem $item, string $reason): VendorItem
    {
        DB::beginTransaction();
        try {
            $data = [
                'status' => 'rejected',
            ];
            if (Schema::hasColumn('vendor_items', 'rejection_reason')) {
                $data['rejection_reason'] = $reason;
            }
            if (Schema::hasColumn('vendor_items', 'approved_by_admin_id')) {
                $data['approved_by_admin_id'] = $admin->id;
            }
            if (Schema::hasColumn('vendor_items', 'approved_at')) {
                $data['approved_at'] = now();
            }
            $item->update($data);
            DB::commit();
            return $item->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
