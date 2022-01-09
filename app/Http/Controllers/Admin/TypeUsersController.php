<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Admin\TypeUserService;
use App\Service\Admin\UserService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class TypeUsersController extends Controller
{
    use GeneralTrait;
    private $TypeUserService;
    public function __construct(TypeUserService $TypeUserService)
    {
//        $this->middleware(['role:superadministrator']);
//        $this->middleware(['permission:user-read'])->only('getAll','getById');
//        $this->middleware(['permission:user-create'])->only('create');
//        $this->middleware(['permission:user-update'])->only('update');
//        $this->middleware(['permission:user-delete'])->only(['trash','restoreTrashed','getTrashed']);
        $this->TypeUserService=$TypeUserService;
    }
    public function getAll()
    {
        return $this->TypeUserService->getAll();
    }
    public function getById($id)
    {
        return $this->TypeUserService->getById($id);
    }
    public function getTrashed()
    {
        return $this->TypeUserService->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->TypeUserService->create($request);
    }
    public function update(Request $request,$id)
    {
        return $this->TypeUserService->update($request,$id);
    }
    public function search($title)
    {
        return $this->TypeUserService->search($title);
    }
    public function trash($id)
    {
        return $this->TypeUserService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->TypeUserService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->TypeUserService->delete($id);
    }
}
