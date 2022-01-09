<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    public function singleUploade(Request $request)
    {
        $image = $request->file('image');
        $folder = public_path('images/products' . '/' . 19 . '/');
        $filename = time() . '.' . $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
//             $location = storage_path('/app/public/images'  . '/' . 5 . '/' . $filename);
//            Image::make($image)->resize(800,400)->save($location); //resizing and saving the image

        }
        $file_extension= $request-> image-> getClientOriginalExtension();
//   $file_name = time().$file_extension;
        $path='images/brands';
        $request->image->move($folder,$filename);

    }

}
