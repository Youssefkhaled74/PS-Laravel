<?php

namespace App\Services\Admin;

use App\Models\Bank;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class BankService
{
    public function __construct(protected UploadService $uploader)
    {
    }

    public function paginate(array $filters = [], int $perPage = 10)
    {
        $q = Bank::query();

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(function ($q2) use ($s) {
                $q2->where('name_en', 'like', "%{$s}%")
                   ->orWhere('name_ar', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status']) && in_array($filters['status'], [Bank::STATUS_ACTIVE, Bank::STATUS_INACTIVE])) {
            $q->where('status', $filters['status']);
        }

        $q->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        return $q->paginate($perPage)->withQueryString();
    }

    public function create(array $data, ?UploadedFile $logo = null): Bank
    {
        $bank = Bank::create(Arr::except($data, ['logo']));
        if ($logo) {
            $path = $this->uploader->uploadPublicImage($logo, 'uploads/banks', 'bank_'.$bank->id);
            $bank->logo = $path;
            $bank->save();
        }
        return $bank;
    }

    public function update(Bank $bank, array $data, ?UploadedFile $logo = null): Bank
    {
        $bank->update(Arr::except($data, ['logo']));
        if ($logo) {
            $this->uploader->deletePublicFile($bank->logo);
            $path = $this->uploader->uploadPublicImage($logo, 'uploads/banks', 'bank_'.$bank->id);
            $bank->logo = $path;
            $bank->save();
        }
        return $bank;
    }

    public function toggleStatus(Bank $bank): Bank
    {
        $bank->status = $bank->status === Bank::STATUS_ACTIVE ? Bank::STATUS_INACTIVE : Bank::STATUS_ACTIVE;
        $bank->save();
        return $bank;
    }

    public function delete(Bank $bank): void
    {
        $this->uploader->deletePublicFile($bank->logo);
        $bank->delete();
    }
}
