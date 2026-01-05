<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function list(array $filters = [], int $perPage = 10)
    {
        $query = Category::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status']) && in_array($filters['status'], [Category::STATUS_ACTIVE, Category::STATUS_INACTIVE])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        $slug = $this->makeUniqueSlug($data['name_en'] ?? $data['name_ar']);
        $data['slug'] = $slug;
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (!empty($data['name_en']) || !empty($data['name_ar'])) {
            $base = $data['name_en'] ?? $data['name_ar'] ?? $category->name_en;
            $slug = $this->makeUniqueSlug($base, $category->id);
            $data['slug'] = $slug;
        }

        $category->update($data);

        return $category;
    }

    public function toggleStatus(Category $category): Category
    {
        $category->status = $category->isActive() ? Category::STATUS_INACTIVE : Category::STATUS_ACTIVE;
        $category->save();
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    protected function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Category::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }
}
