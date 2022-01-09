<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Admin\PermissionService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    use GeneralTrait;
    private $permissionService;
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService=$permissionService;
    }
    public function getAll()
    {
        $response= $this->permissionService->getAll();
        return $response;
    }
    public function getById($id)
    {
        $response= $this->permissionService->getById($id);
        return $response;
    }
    public function getTrashed()
    {
        $response= $this->permissionService->getTrashed();
        return $response;
    }
    public function create(Request $request)
    {
        $response= $this->permissionService->create($request);
        return $response;
    }
    public function update(Request $request,$id)
    {
        $response= $this->permissionService->update($request,$id);
        return $response;
    }
    public function search($title)
    {
        $response= $this->permissionService->search($title);
        return $response;
    }
    public function trash($id)
    {
        $response= $this->permissionService->trash($id);
        return $response;
    }
    public function restoreTrashed($id)
    {
        $response= $this->permissionService->restoreTrashed($id);
        return $response;
    }
    public function delete($id)
    {
        $response= $this->permissionService->delete($id);
        return $response;
    }
}
