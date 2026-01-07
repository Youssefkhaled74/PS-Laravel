<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Services\Admin\VendorService;
use App\Services\Admin\VendorBusinessService;
use App\Services\Admin\VendorDocumentService;
use App\Http\Requests\Admin\VendorAccountUpdateRequest;
use App\Http\Requests\Admin\VendorBusinessUpdateRequest;
use App\Http\Requests\Admin\VendorDocumentsUpdateRequest;
use Illuminate\Support\Facades\Redirect;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::with('businessProfile');

        if ($q = $request->query('q')) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $vendors = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(Vendor $vendor)
    {
        $vendor->load('businessProfile', 'documents', 'vendorPackageAssignments', 'paymentSelections');
        return view('admin.vendors.show', compact('vendor'));
    }

    public function updateAccount(VendorAccountUpdateRequest $request, Vendor $vendor, VendorService $service)
    {
        $service->updateAccount($vendor, $request->validated());
        return Redirect::route('admin.vendors.show', $vendor->id)->with('success', __('admin.updated_success'));
    }

    public function updateBusiness(VendorBusinessUpdateRequest $request, Vendor $vendor, VendorBusinessService $service)
    {
        $service->updateBusiness($vendor, $request->validated());
        return Redirect::route('admin.vendors.show', $vendor->id)->with('success', __('admin.updated_success'));
    }

    public function updateDocuments(VendorDocumentsUpdateRequest $request, Vendor $vendor, VendorDocumentService $service)
    {
        $service->handleUploads($vendor, $request);
        return Redirect::route('admin.vendors.show', $vendor->id)->with('success', __('admin.updated_success'));
    }

    public function toggleStatus(Vendor $vendor, VendorService $service)
    {
        $service->toggleStatus($vendor);
        return Redirect::route('admin.vendors.show', $vendor->id)->with('success', __('admin.status_toggled'));
    }
}
