<?php

namespace App\Services\Admin;

use App\Models\User;

class UserService
{
    public function list(array $filters = [], int $perPage = 10)
    {
        $query = User::query()->withCount('addresses');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?User
    {
        return User::with(['addresses' => function ($q) {
            $q->orderBy('is_default', 'desc')->orderBy('id', 'desc');
        }])->find($id);
    }
}
