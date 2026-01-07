<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRegisterRequest;
use App\Http\Requests\VendorCompleteBusinessRequest;
use App\Http\Requests\VendorChoosePackageRequest;
use App\Http\Requests\VendorChoosePaymentRequest;
use App\Services\VendorOnboardingService;
use App\Models\VendorPackage;
use Illuminate\Http\Request;

class VendorOnboardingController extends Controller
{
    public function __construct(protected VendorOnboardingService $service)
    {
    }

    public function register(VendorRegisterRequest $request)
    {
        $vendor = $this->service->registerVendor($request->validated());
        return redirect()->route('vendor.register.success');
    }

    public function completeBusiness(VendorCompleteBusinessRequest $request)
    {
        $vendor = auth('vendor')->user();
        $profile = $this->service->updateBusinessProfile($vendor, $request->validated());

        // handle files
        if ($request->hasFile('id_card_image')) {
            $this->service->uploadDocument($vendor, 'id_card', $request->file('id_card_image'));
        }
        if ($request->hasFile('commercial_register_file')) {
            $this->service->uploadDocument($vendor, 'commercial_register', $request->file('commercial_register_file'));
        }
        if ($request->hasFile('freelance_doc_file')) {
            $this->service->uploadDocument($vendor, 'freelance_doc', $request->file('freelance_doc_file'));
        }

        return redirect()->route('vendor.complete.success');
    }

    public function choosePackage(VendorChoosePackageRequest $request)
    {
        $vendor = auth('vendor')->user();
        $pkg = VendorPackage::findOrFail($request->vendor_package_id);
        $startsAt = $request->starts_at ? \Carbon\Carbon::parse($request->starts_at) : null;
        $this->service->choosePackage($vendor, $pkg, $request->billing_cycle, $startsAt);
        return redirect()->route('vendor.package.success');
    }

    public function choosePayment(VendorChoosePaymentRequest $request)
    {
        $vendor = auth('vendor')->user();
        $this->service->choosePaymentMethod($vendor, $request->validated());
        return redirect()->route('vendor.payment.success');
    }
}
