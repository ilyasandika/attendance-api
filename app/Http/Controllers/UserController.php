<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(Request $request)
    {
        $data = $this->userService->getUsers($request->query("search"));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getUserById(Request $request)
    {
        $data = $this->userService->getUserById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getCurrentUser()
    {
        $data = $this->userService->getUserById(Auth::user()->id);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function updateUserById(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $data = $this->userService->updateUserById($data, $data['id']);
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function updateCurrentUser(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $data = $this->userService->updateUserById($data, Auth::user()->id);
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function deleteUserById(Request $request)
    {
        $data = $this->userService->deleteUserById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.delete_success'));
    }

    public function getLatestUsers()
    {
        $data = $this->userService->getLatestUsers();
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getUsersByDepartment()
    {
        $data = $this->userService->getUsersByDepartment();
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }
}
