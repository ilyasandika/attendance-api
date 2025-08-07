<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Resources\DepartmentCollection;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService){
        $this->departmentService = $departmentService;
    }

    public function getDepartmentDropdown(){
        $data = $this->departmentService->getDepartmentList(true);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }
    public function getDepartmentList(Request $request)
    {
        $data = $this->departmentService->getDepartmentList(false, $request->query('search'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getDepartmentById(Request $request) {
        $data = $this->departmentService->getDepartmentById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function createDepartment(DepartmentRequest $request) {
        $data = $request->validated();
        $data = $this->departmentService->createDepartment($data);
        return Helper::responseSuccessTry($data, __('successMessages.create_success'));
    }

    public function updateDepartment(DepartmentRequest $request) {
        $data = $request->validated();
        $data = $this->departmentService->updateDepartment($data, $request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function deleteDepartment(Request $request) {
        $data = $this->departmentService->deleteDepartment($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.delete_success'));
    }
}
