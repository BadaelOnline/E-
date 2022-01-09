<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\StoreProduct\StoreProductRequest;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Stores\StoreService;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    use GeneralTrait;
    private $StoreService;

    public function __construct(StoreService $StoreService)
    {
        $this->StoreService=$StoreService;
    }
    /****________________   admins dashboard functions ________________****/
    /****________________   Store's approved ________________****/
    public function aprrove( $id)
    {
        return $this->StoreService->aprrove($id);
    }
    /****________________   Store's list ________________****/
    public function dashgetAll()
    {
        return $this->StoreService->dashgetAll();
    }
    /****____________________________________________________****/
    /****________________   client side functions ________________****/
    public function getAll()
    {
        return $this->StoreService->getAll();
    }
    public function getById($id)
    {
        return $this->StoreService->getById($id);
    }
    public function getTrashed()
    {
        return $this->StoreService->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->StoreService->create($request);
    }
    public function update(Request $request,$id)
    {
        return  $this->StoreService->update( $request,$id);
    }
    public function search($name)
    {
        return $this->StoreService->search($name);
    }
    public function trash($id)
    {
        return $this->StoreService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->StoreService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->StoreService->delete($id);
    }
    public function getSectionInStore($id)
    {
        return  $this->StoreService->getSectionInStore($id);
    }
}
