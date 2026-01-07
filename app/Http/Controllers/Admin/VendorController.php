<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::with('businessProfile');

        if ($q = $request->query('q')) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $vendors = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(Vendor $vendor)
    {
        $vendor->load('businessProfile', 'documents');
        return view('admin.vendors.show', compact('vendor'));
    }
}
