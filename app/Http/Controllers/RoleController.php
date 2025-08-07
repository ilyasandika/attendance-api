<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Role\RoleRequest;
use App\Services\RoleServices;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServices $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getRoleDropdown()
    {
        $data = $this->roleService->getRoleList(true);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getRoleList(Request $request)
    {
        $data = $this->roleService->getRoleList(false, $request->query('search'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getRoleById(Request $request)
    {
        $data = $this->roleService->getRoleById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function createRole(RoleRequest $request)
    {
        $data = $request->validated();
        $data = $this->roleService->createRole($data);
        return Helper::responseSuccessTry($data, __('successMessages.create_success'));
    }

    public function updateRoleById(RoleRequest $request)
    {
        $data = $request->validated();
        $data = $this->roleService->updateRole($data, $request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function deleteRoleById(Request $request)
    {
        $data = $this->roleService->deleteRole($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.delete_success'));
    }
}
