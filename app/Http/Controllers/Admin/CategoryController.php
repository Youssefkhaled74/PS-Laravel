<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Services\Admin\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service)
    {
    }

    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $filters = request()->only(['search', 'status']);
        $categories = $this->service->list($filters, (int) $perPage);
        return view('admin.categories.index', compact('categories', 'filters'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();
        $this->service->create($data);
        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.saved_success'));
    }

    public function edit($id)
    {
        $category = $this->service->find($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', __('admin.categories.not_found'));
        }
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = $this->service->find($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', __('admin.categories.not_found'));
        }
        $this->service->update($category, $request->validated());
        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.updated_success'));
    }

    public function toggleStatus($id)
    {
        $category = $this->service->find($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', __('admin.categories.not_found'));
        }
        $this->service->toggleStatus($category);
        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.status_toggled'));
    }

    public function destroy($id)
    {
        $category = $this->service->find($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', __('admin.categories.not_found'));
        }
        $this->service->delete($category);
        return redirect()->route('admin.categories.index')->with('success', __('admin.categories.deleted_success'));
    }
}
