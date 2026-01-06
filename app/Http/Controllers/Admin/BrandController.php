<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandStoreRequest;
use App\Http\Requests\Admin\BrandUpdateRequest;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(protected BrandService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['search', 'status']);
        $brands = $this->service->paginate($filters, (int) $perPage);
        return view('admin.brands.index', compact('brands', 'filters'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(BrandStoreRequest $request)
    {
        $data = $request->validated();
        $logo = $request->file('logo');
        $this->service->create($data, $logo);
        return redirect()->route('admin.brands.index')->with('success', __('admin.brands.saved_success'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        $data = $request->validated();
        $logo = $request->file('logo');
        $this->service->update($brand, $data, $logo);
        return redirect()->route('admin.brands.index')->with('success', __('admin.brands.updated_success'));
    }

    public function toggle(Brand $brand)
    {
        $this->service->toggleStatus($brand);
        return redirect()->route('admin.brands.index')->with('success', __('admin.brands.status_toggled'));
    }

    public function destroy(Brand $brand)
    {
        $this->service->delete($brand);
        return redirect()->route('admin.brands.index')->with('success', __('admin.brands.deleted_success'));
    }
}
