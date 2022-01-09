<?php

namespace App\Http\Controllers\Images;

use App\Http\Controllers\Controller;
use App\Models\Images\ProductImage;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    use GeneralTrait;

    public function upload(Request $request,$id)
    {
        $image = $request->file('image');
        $folder = public_path('images/products' . '/' . $id . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[]='images/products/' . $id  . '/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder,$filename);
        return $imageUrl;
    }

    public function uploadMultiple(Request $request, $id)
    {
        if (!$request->hasFile('images')) {
            return response()->json(['not found the files'], 400);
        }
        $files = $request->file(['images']);
        $folder = public_path('images/products' . '/' . $id . '/');
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        foreach ($files as $file) {
            $filename[] = time() . '.' . $file->getClientOriginalName() ;
            $file->move($folder,  time() . '.' . $file->getClientOriginalName() );
        }
        foreach ($filename as $f) {
            $imageUrl[]='images/products/' . $id  . '/' .  $f;
        }
        return $imageUrl;
    }

    public function update_uploadMultiple(Request $request, $id)
    {
        if (!$request->hasFile('images')) {
            return response()->json(['not found the files'], 400);
        }
        $files = $request->file(['images']);
        $images_count=count($files);
        $folder = public_path('images/products' . '/' . $id . '/');
        foreach ($files as $file) {
            $filename[] = time() . '.' . $file->getClientOriginalName();
            $file->move($folder, time() . '.' . $file->getClientOriginalName());
        }
        foreach ($filename as $f) {
            $imageUrl[] = $id . '/' . $f;
        }
//        return $imageUrl;
        $old_images = $request->old_images;
        foreach ($old_images as $old_image) {
            $old = public_path('images/products' . '/' . $old_image);
            if (File::exists($old)) {
                unlink($old);
            }
        }
        $images = ProductImage::where('product_id', $id)->get();
        $imagesdb_count = count($images);
        if (is_null($images)) {
            return $this->returnSuccessMessage('This product not found', 'done');
        }
        for ($i = 1; $i <$imagesdb_count; $i++) {
        foreach ($images as $image) {
            $image->update([
                'product_id' => $id,
                'image' => $imageUrl[$i],
                'is_cover' => 0
            ]);
        }
    }
        return $imageUrl;
    }

    public function delete_image($id){
        try {
            $image=ProductImage::find($id);
            if (!is_null($image)) {
                $image_name=$image->image;
                $old = public_path( $image_name);
                if (File::exists($old)) {
                    unlink($old);
                }
                $delete=$image->destroy($id);
                return  $this->returnSuccessMessage('image', 'delete success');
            } else {
                return $this->returnSuccessMessage('image', 'image doesnt exist yet');
            }
        }catch (\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function get_is_cover($pro_id,$img_id){
        try{
             $images=ProductImage::where('product_id',$pro_id)->get();
            if (count($images) == 0) {
                return $this->returnSuccessMessage('Images', 'Images doesnt exist yet');
            }
            foreach($images as $image){
                $image->update([
                    'is_cover'=>0
                ]);
            }
            $cover_image=ProductImage::where('product_id',$pro_id)->find($img_id);
            $cover_image->update([
                'is_cover'=>1
            ]);
            return $this->returnSuccessMessage('This image selected to cover', '200');
        }catch (\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }

}
