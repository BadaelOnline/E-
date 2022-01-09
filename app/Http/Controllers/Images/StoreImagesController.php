<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StoreImagesController extends Controller
{
    public function upload(Request $request, $id)
    {
        $image = $request->file('image');
        $folder = public_path('images/stores' . '/' . $id . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl='images/stores' . '/' . $id . '/' . $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $request->image->move($folder, $filename);
        return $imageUrl;

    }

    public function uploadLogo(Request $request)
    {
        $image = $request->file('image');
        $folder = public_path('images/stores/logo' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $request->image->move($folder, $filename);
        return ['images/stores/logo' . '/'  . $filename];

    }

    public function uploadMultiple(Request $request, $id)
    {
        if (!$request->hasFile('images')) {
            return response()->json(['not found the files'], 400);
        }
        $files = $request->file(['images']);
        $errors = [];
        $folder = public_path('images/stores/' . $id . '/');

        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        foreach ($files as $file) {

        $filename[] = time() . '.' . $file->getClientOriginalName() ;
            $file->move($folder,  time() . '.' . $file->getClientOriginalName() );
        }
        foreach ($filename as $f) {
            $imageUrl[]='images/stores/' . $id  . '/' .  $f;
        }
        return $imageUrl;
        }
    }

