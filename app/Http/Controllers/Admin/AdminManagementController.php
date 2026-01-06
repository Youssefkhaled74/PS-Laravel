<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Services\Admin\AdminService;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

class AdminManagementController extends Controller
{
    public function __construct(protected AdminService $service)
    {
    }

    public function index()
    {
        $perPage = (int) request()->get('per_page', 10);
        $search = request()->get('search');
        $status = request()->get('status');
        $admins = $this->service->paginate($search, $status, $perPage);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(AdminStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.admins.index')->with('success', __('admin.admins.saved_success'));
    }

    public function edit($id)
    {
        $admin = $this->service->find($id);
        if (!$admin) return redirect()->route('admin.admins.index')->with('error', __('admin.admins.not_found'));
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(AdminUpdateRequest $request, $id): RedirectResponse
    {
        $admin = $this->service->find($id);
        if (!$admin) return redirect()->route('admin.admins.index')->with('error', __('admin.admins.not_found'));
        $this->service->update($admin, $request->validated());
        return redirect()->route('admin.admins.index')->with('success', __('admin.admins.updated_success'));
    }

    public function toggleStatus($id): RedirectResponse
    {
        $admin = $this->service->find($id);
        if (!$admin) return redirect()->route('admin.admins.index')->with('error', __('admin.admins.not_found'));

        try {
            $this->service->toggleStatus($admin, auth('admin')->user());
            return redirect()->route('admin.admins.index')->with('success', __('admin.admins.status_toggled'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')->with('error', $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        $admin = $this->service->find($id);
        if (!$admin) return redirect()->route('admin.admins.index')->with('error', __('admin.admins.not_found'));

        try {
            $this->service->delete($admin, auth('admin')->user());
            return redirect()->route('admin.admins.index')->with('success', __('admin.admins.deleted_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')->with('error', $e->getMessage());
        }
    }
}
