<?php

namespace App\Services\Admin;

use App\Models\Vendor;

class VendorDocumentService
{
    public function handleUploads(Vendor $vendor, $request)
    {
        $dir = public_path('uploads/vendors/'.$vendor->id);
        if (! file_exists($dir)) mkdir($dir, 0755, true);

        $files = ['id_card', 'commercial_register', 'freelance_doc'];
        foreach ($files as $f) {
            if ($request->hasFile($f)) {
                $file = $request->file($f);
                $name = $f.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->move($dir, $name);
                $path = 'uploads/vendors/'.$vendor->id.'/'.$name;

                // store or update vendor_documents table
                $doc = $vendor->documents()->updateOrCreate([
                    'type' => $f,
                ], [
                    'file_path' => $path,
                ]);
            }
        }
    }
}
