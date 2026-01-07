<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function __construct(protected OtpService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $filters = $request->only(['search', 'purpose', 'status']);
        $otps = $this->service->paginate($filters, (int) $perPage);
        return view('admin.otps.index', compact('otps', 'filters'));
    }

    public function show($id)
    {
        $otp = $this->service->getOtp((int)$id);
        if (! $otp) {
            return redirect()->route('admin.otps.index')->with('error', __('admin.otps.not_found'));
        }
        return view('admin.otps.show', compact('otp'));
    }

    public function revoke($id)
    {
        $adminId = auth('admin')->id();
        $ok = $this->service->revoke((int)$id, null, $adminId);
        return redirect()->route('admin.otps.index')->with('success', __('admin.otps.revoked_success'));
    }

    public function destroy($id)
    {
        $adminId = auth('admin')->id();
        $this->service->delete((int)$id, $adminId);
        return redirect()->route('admin.otps.index')->with('success', __('admin.otps.deleted_success'));
    }

    public function resend($id)
    {
        $res = $this->service->resend((int)$id);
        if (empty($res['ok'])) {
            return redirect()->back()->with('error', __('admin.otps.resend_not_supported'));
        }
        return redirect()->back()->with('success', __('admin.otps.resent_success'));
    }
}
