<?php

namespace App\Service\Attachments;

use App\Http\Requests\Brands\BrandRequest;
use App\Models\Attachments\Attachment;
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
        $this->attachment = $attachment;
        $this->PAGINATION_COUNT = 25;
    }

    private function fillAttachment($request_arr, $record_num, $folder)
    {
        return (
        [
            'path' => $this->upload($request_arr['path'], $folder),
            'activity_id' => $request_arr['activity_id'],
            'attachments_type_id' => $request_arr['attachments_type_id'],
            'record_num' => $record_num
        ]);
    }


    /****Get All attachment  ****/
    public function getAll()
    {
        try {
            $attachments = $this->attachment->all();
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
            $attachment = $this->attachment->findOrFail($id);
            if (!isset($attachment)) {
                return $this->returnSuccessMessage('This attachment not found', 'done');
            }
            return $this->returnData('attachment', $attachment, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException) {
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }

    public function getByActivity($activity_id)
    {
        try {
            $attachment = $this->attachment->where('activity_id', $activity_id)->get();
            return !isset($attachment) ?
                $this->returnSuccessMessage('This attachment not found', 'done') :
                $this->returnData('attachment', $attachment, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException) {
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
            $attachment = $this->attachment->where('is_active', 0)->get();

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
            $attachment = $this->attachment->find($id);
            if (is_null($attachment)) {
                return $response = $this->returnSuccessMessage('attachment', 'This attachment not found');
            } else {
                $attachment->is_active = true;
                $attachment->save();
                return $this->returnData('attachment', $attachment, 'This attachment Is trashed Now');
            }
        } catch (\Exception $ex) {
            if ($ex instanceof AccessDeniedException)
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
            $attachment = $this->attachment->find($id);
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
    public function create($request, $record_num)
    {
        try {
            DB::beginTransaction();
            $folder = public_path('images/attachments/stores' . '/' . $record_num . '/');
            $attachment = $this->attachment->create(
                $this->fillAttachment($request, $record_num, $folder)
            );
            DB::commit();
            return $this->returnData('attachment', $attachment, 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('attachment', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update Product   ***
     * @param $id
     * @return JsonResponse
     */
    public function update($request, $id)
    {
        $request->validated();
        try {
            $attachment = $this->attachment->find($id);
            if (!$attachment)
                return $this->returnError('400', 'not found this attachment');
            if (!($request->has('attachments.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $unTransattachment = $this->attachment->where('attachments.id', $id)
                ->update([
                    'slug' => $request['slug'],
                    'is_active' => $request['is_active'],
//                    'image' => $request['image'],
                ]);
            $request_attachments = array_values($request->attachment);
            foreach ($request_attachments as $request_attachment) {
                $this->attachmentTranslation->where('attachment_id', $id)
                    ->where('local', $request_attachments['local'])
                    ->update([
                        'name' => $request_attachments ['name'],
                        'local' => $request_attachments['local'],
                        'description' => $request_attachments['description'],
                        'brand_id' => $id
                    ]);
            }
            DB::commit();
            return $this->returnData('Brand', [$id, $request_attachments], 'done');
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
            $attachment->destroy($id);
            return $this->returnData('attachment', $attachment, 'This attachment Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function upload($image, $folder)
    {
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[] = 'images/brands/' . $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder, $filename);
        return $filename;
    }

    public function update_upload(Request $request, $id)
    {
        $brand = $this->attachmentModel->find($id);
        if (!isset($brand)) {
            return $this->returnSuccessMessage('This Brand not found', 'done');
        }
        $old_image = $brand->image;
        $image = $request->file('image');
        $old_images = public_path('images/brands' . '/' . $old_image);
        if (File::exists($old_images)) {
            unlink($old_images);
        }
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $brand->update(['image' => $filename]);
        /**update in database**/
        $image->move($folder, $filename);
        return $filename;
    }
}
