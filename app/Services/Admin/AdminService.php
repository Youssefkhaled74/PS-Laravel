<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function paginate(?string $search, ?string $status, int $perPage = 10)
    {
        $query = Admin::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, [Admin::STATUS_ACTIVE, Admin::STATUS_INACTIVE])) {
            $query->where('status', $status);
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Admin
    {
        return Admin::find($id);
    }

    public function create(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    public function update(Admin $admin, array $data): Admin
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return $admin;
    }

    public function toggleStatus(Admin $admin, Admin $currentAdmin): Admin
    {
        if ($admin->id === $currentAdmin->id) {
            throw new \RuntimeException(__('admin.admins.cannot_modify_self'));
        }

        $admin->status = $admin->isActive() ? Admin::STATUS_INACTIVE : Admin::STATUS_ACTIVE;
        $admin->save();
        return $admin;
    }

    public function delete(Admin $admin, Admin $currentAdmin): void
    {
        if ($admin->id === $currentAdmin->id) {
            throw new \RuntimeException(__('admin.admins.cannot_modify_self'));
        }
        $admin->delete();
    }
}
