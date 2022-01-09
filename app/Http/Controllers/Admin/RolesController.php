<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Admin\RoleService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use GeneralTrait;
    private $roleService;
    public function __construct(RoleService $roleService)
    {
//        $this->roleService=$roleService;
//        $this->middleware(['role:superadministrator']);
//        $this->middleware(['permission:role-read'])->only('getAll','getById');
//        $this->middleware(['permission:role-create'])->only('create');
//        $this->middleware(['permission:role-update'])->only('update');
//        $this->middleware(['permission:role-delete'])->only(['trash','restoreTrashed','getTrashed']);
    }
    public function getAll()
    {
        return $this->roleService->getAll();
    }
    public function getById($id)
    {
        return $this->roleService->getById($id);
    }
    public function getTrashed()
    {
        return $this->roleService->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->roleService->create($request);
    }
    public function update(Request $request,$id)
    {
        return $this->roleService->update($request,$id);
    }
    public function search($title)
    {
        return $this->roleService->search($title);
    }
    public function trash($id)
    {
        return $this->roleService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->roleService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->roleService->delete($id);
    }
}
