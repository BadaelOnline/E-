<?php

namespace App\Service\Attachments;

use App\Http\Requests\Brands\BrandRequest;
use App\Models\Attachments\Attachment;
use App\Models\Currencies\Currency;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AttachmentService
{
    private $attachment;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Attachment $attachment)
    {
        $this->attachmentModel = $attachment;
        $this->PAGINATION_COUNT = 25;
    }
    /****Get All attachment  ****/
    public function getAll()
    {
       try {
        $attachments = $this->attachmentModel->get();
        if (count($attachments) > 0) {
            return $this->returnData('attachment', $attachments, 'done');
        } else {
            return $this->returnSuccessMessage('attachment', 'attachment doesnt exist yet');
        }
       } catch (\Exception $ex) {
           return $this->returnError('400', $ex->getMessage());
       }
    }
    /*__________________________________________________________________*/
    /****Get Active attachment By ID  ***
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        try {
            $attachment = $this->attachmentModel->findOrFail($id);
            if (!isset($brand)) {
                return  $this->returnSuccessMessage('This attachment not found', 'done');
            }
            return $this->returnData('attachment', $attachment, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException){
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }
    /*__________________________________________________________________*/
    /****ــــــ This Functions For Trashed attachment  ****/
    /****Get All Trashed attachment Or By ID  ****/
    public function getTrashed()
    {
        try {
            $attachment = $this->attachmentModel->where('is_active', 0)->get();

            if (count($attachment) > 0) {
                return $this->returnData('attachment', $attachment, 'done');
            } else {
                return $this->returnSuccessMessage('attachment', 'attachments trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore attachment Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            $attachment = $this->attachmentModel->find($id);
            if (is_null($attachment)) {
                return $response = $this->returnSuccessMessage('attachment', 'This attachment not found');
            } else {
                $attachment->is_active = true;
                $attachment->save();
                return $this->returnData('attachment', $attachment, 'This attachment Is trashed Now');
            }
        } catch (\Exception $ex) {
            if($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   attachment's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            $attachment = $this->attachmentModel->find($id);
            if (is_null($attachment)) {
                return $this->returnSuccessMessage('attachment', 'This attachments not found');
            } else {
                $attachment->is_active = false;
                $attachment->save();
                return $this->returnData('attachment', $attachment, 'This attachment Is trashed Now');
            }

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Create attachment   ***
     * @return JsonResponse
     */
    public function create(BrandRequest $request)
    {
        try {
            // $validated = $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /** transformation to collection */
            $allattachments = collect($request->attachment)->all();
            $folder = public_path('images/brands' . '/');
            DB::beginTransaction();
            // //create the default language's brand
            $unTransattachment_id = $this->attachmentModel->insertGetId([
                'slug' => $request['slug'],
                'is_active' => $request['is_active'],
                'image' => $this->upload( $request['image'],$folder),
            ]);
            //check the Brand and request
            if (isset($allattachments) && count($allattachments)) {
                //insert other traslations for attachment
                foreach ($allattachments as $allattachment) {
                    $transBrand_arr[] = [
                        'name' => $allattachment ['name'],
                        'local' => $allattachment['local'],
                        'description' => $allattachment['description'],
                        'brand_id' => $unTransBrand_id
                    ];
                }
                $this->attachmentTranslation->insert($transBrand_arr);
            }
            DB::commit();
            return $this->returnData('Brand', [$unTransBrand_id,$allbrands], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('Brand', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update Product   ***
     * @param $id
     * @return JsonResponse
     */
    public function update(BrandRequest $request, $id)
    {
        $request->validated();
        try {
            $attachment = $this->attachmentModel->find($id);
            if (!$attachment)
                return $this->returnError('400', 'not found this attachment');
            if (!($request->has('attachments.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $unTransattachment = $this->attachmentModel->where('attachments.id', $id)
                ->update([
                    'slug' => $request['slug'],
                    'is_active' => $request['is_active'],
//                    'image' => $request['image'],
                ]);
            $request_attachments = array_values($request->attachment);
            foreach ($request_attachments as $request_attachment) {
                $this->attachmentTranslation->where('attachment_id', $id)
                    ->where('local', $request_brand['local'])
                    ->update([
                        'name' => $request_brand ['name'],
                        'local' => $request_brand['local'],
                        'description' => $request_brand['description'],
                        'brand_id' => $id
                    ]);
            }
            DB::commit();
            return $this->returnData('Brand', [$id,$request_brands], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    public function search($title)
    {
        try {
            $attachment = $this->attachmentModel->searchTitle();
            if (!$attachment) {
                return $this->returnError('400', 'not found this attachment');
            } else {
                return $this->returnData('attachments', $attachment, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Delete Product   ***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try {
            $attachment = $this->attachmentModel->find($id);
            $attachment ->destroy($id);
            return $this->returnData('attachment', $attachment, 'This attachment Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function upload($image,$folder)
    {
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[]='images/brands/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder,$filename);
        return $filename;
    }
    public function update_upload(Request $request, $id)
    {
        $brand= $this->attachmentModel->find($id);
        if (!isset($brand)) {
            return $this->returnSuccessMessage('This Brand not found', 'done');
        }
        $old_image=$brand->image;
        $image = $request->file('image');
        $old_images=public_path('images/brands' . '/' .$old_image);
        if(File::exists($old_images)){
            unlink($old_images);
        }
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $brand->update(['image' => $filename]);/**update in database**/
        $image->move($folder,$filename);
        return $filename;
    }
}
