<?php

namespace App\Services\Admin;

use App\Models\LegalPage;
use App\Models\LegalPageRevision;
use Illuminate\Support\Arr;

class LegalPageService
{
    public function all()
    {
        return LegalPage::all();
    }

    public function findByKey(string $key): ?LegalPage
    {
        return LegalPage::where('key', $key)->first();
    }

    public function update(LegalPage $page, array $data, ?int $adminId = null): LegalPage
    {
        // create revision
        LegalPageRevision::create([
            'legal_page_id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'status' => $page->status,
            'version' => $page->version,
            'updated_by_admin_id' => $page->updated_by_admin_id,
        ]);

        $page->title = Arr::get($data, 'title', $page->title);
        $page->content = Arr::get($data, 'content', $page->content);
        $page->status = Arr::get($data, 'status', $page->status);
        $page->version = $page->version + 1;
        $page->updated_by_admin_id = $adminId;
        $page->save();

        return $page;
    }
}
