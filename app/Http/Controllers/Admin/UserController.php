<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $filters = $request->only(['search']);
        $users = $this->service->list($filters, (int) $perPage);
        return view('admin.users.index', compact('users', 'filters'));
    }

    public function show($id)
    {
        $user = $this->service->find($id);
        if (! $user) {
            return redirect()->route('admin.users.index')->with('error', __('admin.users.not_found'));
        }
        return view('admin.users.show', compact('user'));
    }
}
