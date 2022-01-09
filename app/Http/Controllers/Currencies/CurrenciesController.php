<?php

namespace App\Http\Controllers\Currencies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\BrandRequest;
use App\Service\Brands\BrandsService;
use App\Service\Currencies\CurrenciesService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    use GeneralTrait;
    private $currenciesService;
    public function __construct(CurrenciesService $currenciesService)
    {
        $this->currenciesService=$currenciesService;
//        $this->user = JWTAuth::parseToken()->authenticate();
//        $this->middleware('can:Read Brand')->only(['getAll','getById','getTrashed']);
//        $this->middleware('can:Create Brand')->only('create');
//        $this->middleware('can:Update Brand')->only('update');
//        $this->middleware('can:Delete Brand')->only(['trash','delete']);
//        $this->middleware('can:Restore Brand')->only('restoreTrashed');


    }
    public function list()
    {
        return $this->currenciesService->list();
    }
    public function getAll()
    {
        return $this->currenciesService->getAll();
    }
    public function getById($id)
    {
        return $this->currenciesService->getById($id);
    }
    public function getTrashed()
    {
        return $this->currenciesService->getTrashed();
    }
    public function create(BrandRequest $request)
    {
        return $this->currenciesService->create($request);
    }
    public function update(BrandRequest $request,$id)
    {
        return $this->currenciesService->update($request,$id);
    }
    public function search($title)
    {
        return $this->currenciesService->search($title);
    }
    public function trash($id)
    {
        return $this->currenciesService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->currenciesService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->currenciesService->delete($id);
    }
    public function upload(\Symfony\Component\HttpFoundation\Request $request)
    {
        return $this->currenciesService->upload($request);
    }
    public function update_upload(Request $request,$id)
    {
        return $this->currenciesService->update_upload($request,$id);
    }
}
