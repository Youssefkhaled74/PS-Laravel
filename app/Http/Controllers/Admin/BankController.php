<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BankStoreRequest;
use App\Http\Requests\Admin\BankUpdateRequest;
use App\Models\Bank;
use App\Services\Admin\BankService;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct(protected BankService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['search', 'status']);
        $banks = $this->service->paginate($filters, (int) $perPage);
        return view('admin.banks.index', compact('banks', 'filters'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(BankStoreRequest $request)
    {
        $data = $request->validated();
        $logo = $request->file('logo');
        $this->service->create($data, $logo);
        return redirect()->route('admin.banks.index')->with('success', __('admin.banks.saved_success'));
    }

    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(BankUpdateRequest $request, Bank $bank)
    {
        $data = $request->validated();
        $logo = $request->file('logo');
        $this->service->update($bank, $data, $logo);
        return redirect()->route('admin.banks.index')->with('success', __('admin.banks.updated_success'));
    }

    public function toggle(Bank $bank)
    {
        $this->service->toggleStatus($bank);
        return redirect()->route('admin.banks.index')->with('success', __('admin.banks.status_toggled'));
    }

    public function destroy(Bank $bank)
    {
        $this->service->delete($bank);
        return redirect()->route('admin.banks.index')->with('success', __('admin.banks.deleted_success'));
    }
}
