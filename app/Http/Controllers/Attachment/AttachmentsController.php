<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Service\Attachments\AttachmentService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    use GeneralTrait;
    private $attachmentsService;
    public function __construct(AttachmentService $attachmentsService)
    {
        $this->attachmentsService=$attachmentsService;
//        $this->user = JWTAuth::parseToken()->authenticate();
//        $this->middleware('can:Read Brand')->only(['getAll','getById','getTrashed']);
//        $this->middleware('can:Create Brand')->only('create');
//        $this->middleware('can:Update Brand')->only('update');
//        $this->middleware('can:Delete Brand')->only(['trash','delete']);
//        $this->middleware('can:Restore Brand')->only('restoreTrashed');
    }

    public function getAll()
    {
        return $this->attachmentsService->getAll();
    }
    public function getById($id)
    {
        return $this->attachmentsService->getById($id);
    }
    public function getTrashed()
    {
        return $this->attachmentsService->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->attachmentsService->create($request);
    }
    public function update(Request $request,$id)
    {
        return $this->attachmentsService->update($request,$id);
    }
    public function search($title)
    {
        return $this->attachmentsService->search($title);
    }
    public function trash($id)
    {
        return $this->attachmentsService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->attachmentsService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->attachmentsService->delete($id);
    }
    public function upload(\Symfony\Component\HttpFoundation\Request $request)
    {
        return $this->attachmentsService->upload($request);
    }
    public function update_upload(Request $request,$id)
    {
        return $this->attachmentsService->update_upload($request,$id);
    }
}
