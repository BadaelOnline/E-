<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Admin\RoleService;
use App\Service\Admin\UserService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use GeneralTrait;
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api');
        //        $this->middleware(['role:superadministrator']);
//        $this->middleware(['permission:user-read'])->only('getAll','getById');
//        $this->middleware(['permission:user-create'])->only('create');
//        $this->middleware(['permission:user-update'])->only('update');
//        $this->middleware(['permission:user-delete'])->only(['trash','restoreTrashed','getTrashed']);
        $this->userService=$userService;
    }
    public function getAll()
    {
        return $this->userService->getAll();
    }
    public function getById($id)
    {
        return $this->userService->getById($id);
    }
    public function getTrashed()
    {
        return $this->userService->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->userService->create($request);
    }
    public function update(Request $request,$id)
    {
        return $this->userService->update($request,$id);
    }
    public function search($title)
    {
        return $this->userService->search($title);
    }
    public function trash($id)
    {
        return $this->userService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->userService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->userService->delete($id);
    }
    public function profile($id)
    {
        return $this->userService->profile($id);
    }
}
