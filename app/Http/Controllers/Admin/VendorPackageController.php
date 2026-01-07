<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VendorPackageStoreRequest;
use App\Http\Requests\Admin\VendorPackageUpdateRequest;
use App\Models\VendorPackage;
use App\Services\Admin\VendorPackageService;
use Illuminate\Http\Request;

class VendorPackageController extends Controller
{
    public function __construct(protected VendorPackageService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['search', 'status']);
        $packages = $this->service->paginate($filters, (int) $perPage);
        return view('admin.vendor-packages.index', compact('packages', 'filters'));
    }

    public function create()
    {
        return view('admin.vendor-packages.create');
    }

    public function store(VendorPackageStoreRequest $request)
    {
        $data = $request->validated();
        $this->service->create($data);
        return redirect()->route('admin.vendor-packages.index')->with('success', __('admin.vendor_packages.saved_success'));
    }

    public function edit(VendorPackage $vendorPackage)
    {
        return view('admin.vendor-packages.edit', ['package' => $vendorPackage]);
    }

    public function update(VendorPackageUpdateRequest $request, VendorPackage $vendorPackage)
    {
        $data = $request->validated();
        $this->service->update($vendorPackage, $data);
        return redirect()->route('admin.vendor-packages.index')->with('success', __('admin.vendor_packages.updated_success'));
    }

    public function toggle(VendorPackage $vendorPackage)
    {
        $this->service->toggle($vendorPackage);
        return redirect()->route('admin.vendor-packages.index')->with('success', __('admin.vendor_packages.status_toggled'));
    }

    public function destroy(VendorPackage $vendorPackage)
    {
        $this->service->delete($vendorPackage);
        return redirect()->route('admin.vendor-packages.index')->with('success', __('admin.vendor_packages.deleted_success'));
    }
}
