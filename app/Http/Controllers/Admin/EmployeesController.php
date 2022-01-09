<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Service\Admin\EmployeeService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    use GeneralTrait;
    private $employee;
    public function __construct(EmployeeService $employee)
    {
//        $this->middleware(['role:superadministrator']);
//        $this->middleware(['permission:user-read'])->only('getAll','getById');
//        $this->middleware(['permission:user-create'])->only('create');
//        $this->middleware(['permission:user-update'])->only('update');
//        $this->middleware(['permission:user-delete'])->only(['trash','restoreTrashed','getTrashed']);
        $this->employee=$employee;
    }
    public function getAll()
    {
        return $this->employee->getAll();
    }
    public function getById($id)
    {
        return $this->employee->getById($id);
    }
    public function getTrashed()
    {
        return $this->employee->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->employee->create($request);
    }
    public function update(Request $request,$id)
    {
        return $this->employee->update($request , $id);
    }
    public function search($title)
    {
        return $this->employee->search($title);
    }
    public function trash($id)
    {
        return $this->employee->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->employee->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->employee->delete($id);
    }
    public function profile($id)
    {
        return $this->employee->profile($id);
    }
}
