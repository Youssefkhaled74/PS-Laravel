<?php

namespace App\Http\Controllers\Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorItem;
use App\Services\Admin\Items\ItemApprovalService;
use App\Http\Requests\Admin\Items\RejectItemRequest;

class ItemApprovalController extends Controller
{
    protected ItemApprovalService $service;

    public function __construct(ItemApprovalService $service)
    {
        $this->service = $service;
    }

    public function approve(Request $request, VendorItem $item)
    {
        $admin = $request->user();
        $this->service->approve($admin, $item);
        return redirect()->back()->with('success', __('admin.items.messages.approved'));
    }

    public function reject(RejectItemRequest $request, VendorItem $item)
    {
        $admin = $request->user();
        $this->service->reject($admin, $item, $request->input('rejection_reason'));
        return redirect()->back()->with('success', __('admin.items.messages.rejected'));
    }
}
