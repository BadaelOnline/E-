<?php

namespace App\Http\Controllers\Category;

use App\Http\Requests\Category\SectionRequest;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Service\Categories\SectionService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SectionsController extends Controller
{
    use GeneralTrait;
    private $sectionService;

    /* ProductsController constructor.
    */
    public function __construct(SectionService $SectionService)
    {
        $this->sectionService=$SectionService;
//        $this->user = JWTAuth::parseToken()->authenticate();
        $this->middleware('can:Read Section')->only(['getAll','getById','getTrashed']);
        $this->middleware('can:Create Section')->only('create');
        $this->middleware('can:Update Section')->only('update');
        $this->middleware('can:Delete Section')->only(['trash','delete']);
        $this->middleware('can:Restore Section')->only('restoreTrashed');
    }
    public function getAll()
    {
        return $this->sectionService->getAll();
    }
    public function getById($id )
    {
        return $this->sectionService->getById($id);
    }
    public function getCategoryBySection()
    {
        return $this->sectionService->getCategoryBySection();
    }
    public function getTrashed()
    {
        return $this->sectionService->getTrashed();
    }
    public function create(SectionRequest $request)
    {
        return $this->sectionService->create($request);
    }
    public function update(SectionRequest $request,$id)
    {
        return $this->sectionService->update($request,$id);
    }
    public function search($name)
    {
        return $this->sectionService->search($name);
    }
    public function trash($id)
    {
        return $this->sectionService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->sectionService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->sectionService->delete($id);
    }
    public function upload(Request $request)
    {
        return $this->sectionService->upload($request);
    }
    public function update_upload(Request $request,$id)
    {
        return $this->sectionService->update_upload($request,$id);
    }
}

