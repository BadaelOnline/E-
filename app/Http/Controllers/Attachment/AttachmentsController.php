<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\BrandRequest;
use App\Service\Attachments\AttachmentService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    use GeneralTrait;
    private $attatchmentsService;
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
    public function list()
    {
        return $this->attatchmentsService->list();
    }
    public function getAll()
    {
        return $this->attatchmentsService->getAll();
    }
    public function getById($id)
    {
        return $this->attatchmentsService->getById($id);
    }
    public function getTrashed()
    {
        return $this->attatchmentsService->getTrashed();
    }
    public function create(BrandRequest $request)
    {
        return $this->attatchmentsService->create($request);
    }
    public function update(BrandRequest $request,$id)
    {
        return $this->attatchmentsService->update($request,$id);
    }
    public function search($title)
    {
        return $this->attatchmentsService->search($title);
    }
    public function trash($id)
    {
        return $this->attatchmentsService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->attatchmentsService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->attatchmentsService->delete($id);
    }
    public function upload(\Symfony\Component\HttpFoundation\Request $request)
    {
        return $this->attatchmentsService->upload($request);
    }
    public function update_upload(Request $request,$id)
    {
        return $this->attatchmentsService->update_upload($request,$id);
    }
}
