<?php

namespace App\Http\Controllers\Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\Items\ItemService;

class ItemController extends Controller
{
    protected ItemService $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            // component uses 'search' param name
            'q' => $request->input('search'),
            'per_page' => $request->input('per_page', 20),
        ];

        $items = $this->service->list($filters);

        return view('admin.items.index', compact('items','filters'));
    }

    public function show($id)
    {
        $item = $this->service->findOrFail($id);
        return view('admin.items.show', compact('item'));
    }
}
