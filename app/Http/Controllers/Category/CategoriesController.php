<?php

namespace App\Http\Controllers\Category;

use App\Http\Requests\Category\CategoryRequest;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Service\Categories\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoriesController extends Controller
{
    use GeneralTrait;
    private $CategoryService;
    private $response;

    public function __construct(CategoryService $CategoryService,Response  $response)
    {
        $this->CategoryService=$CategoryService;
        $this->response=$response;
//        $this->user = JWTAuth::parseToken()->authenticate();
        $this->middleware('can:Read Category')->only(['getAll','getById','getTrashed']);
        $this->middleware('can:Create Category')->only('create');
        $this->middleware('can:Update Category')->only('update');
        $this->middleware('can:Delete Category')->only(['trash','delete']);
        $this->middleware('can:Restore Category')->only('restoreTrashed');
    }
    public function list()
    {
        return $this->CategoryService->list();
    }
    public function getAll()
    {
        return $this->CategoryService->getAll();
    }
    public function getById($id )
    {
        return $this->CategoryService->getById($id);
    }
    public function getCategoryBySelf($id )
    {
        return $this->CategoryService->getCategoryBySelf($id);
    }
    public function getTrashed()
    {
     return $this->CategoryService->getTrashed();
    }
    public function create(CategoryRequest $request)
    {
        return $this->CategoryService->create($request);
    }
    public function update(CategoryRequest $request,$id)
    {
        return $this->CategoryService->update( $request,$id);
    }
    public function search($name)
    {
        return $this->CategoryService->search($name);
    }
    public function trash($id)
    {
        return $this->CategoryService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->CategoryService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->CategoryService->delete($id);
    }
    public function upload(Request $request)
    {
        return $this->CategoryService->upload($request);
    }
    public function update_upload(Request $request,$id)
    {
        return $this->CategoryService->update_upload($request,$id);

    }
}
