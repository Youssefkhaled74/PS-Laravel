<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorPackage;
use App\Models\Vendor;
use App\Services\Admin\VendorPackageAssignmentService;
use Illuminate\Http\Request;

class VendorPackageAssignmentController extends Controller
{
    public function __construct(protected VendorPackageAssignmentService $service)
    {
    }

    public function assign(Request $request, Vendor $vendor)
    {
        $request->validate([
            'vendor_package_id' => 'required|exists:vendor_packages,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'starts_at' => 'nullable|date',
        ]);

        $pkg = VendorPackage::findOrFail($request->vendor_package_id);
        $startsAt = $request->starts_at ? \Carbon\Carbon::parse($request->starts_at) : null;
        $admin = auth('admin')->user();

        $this->service->assignToVendor($vendor, $pkg, $request->billing_cycle, $startsAt, $admin);

        return redirect()->back()->with('success', __('admin.vendor_package_assign.assigned_success'));
    }
}
