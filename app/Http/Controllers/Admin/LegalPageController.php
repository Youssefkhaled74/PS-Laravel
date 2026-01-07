<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LegalPageUpdateRequest;
use App\Services\Admin\LegalPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LegalPageController extends Controller
{
    protected LegalPageService $service;

    public function __construct(LegalPageService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pages = $this->service->all();
        return view('admin.legal-pages.index', compact('pages'));
    }

    public function edit($key)
    {
        $page = $this->service->findByKey($key);
        if (! $page) {
            abort(404);
        }
        return view('admin.legal-pages.edit', compact('page'));
    }

    public function update(LegalPageUpdateRequest $request, $key): RedirectResponse
    {
        $page = $this->service->findByKey($key);
        if (! $page) {
            abort(404);
        }

        $this->service->update($page, $request->validated(), auth('admin')->id());

        return redirect()->route('admin.legal-pages.edit', $page->key)->with('success', __('admin.legal_pages.saved_success'));
    }

    public function preview(Request $request, $key)
    {
        $page = $this->service->findByKey($key);
        if (! $page) abort(404);

        $lang = $request->get('lang', app()->getLocale());
        $content = $page->content[$lang] ?? '';
        return view('admin.legal-pages.preview', compact('content'));
    }
}
