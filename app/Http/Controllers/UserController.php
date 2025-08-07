<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $result = $this->userService->findAll($request->query("search"));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function show(Request $request)
    {
        $result = $this->userService->findById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function showUserByUserLogin(Request $request)
    {
        $result = $this->userService->findById(Auth::user()->id);
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function updateUserById(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $data = $this->userService->updateById($request, $data, $data['id']);
        return Helper::responseSuccessTry($data, __('successMessages.user_update_success'), 204);
    }

    public function updateUserByLogin (UpdateUserRequest $request){
        $data = $request->validated();
        $data = $this->userService->updateById($request, $data, (int)Auth::user()->id);
        return Helper::responseSuccessTry($data, __('successMessages.user_update_success'), 204);
    }

    public function destroy(Request $request)
    {
        $result = $this->userService->deleteById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showLatest()
    {
        $result = $this->userService->findLatest();
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function overviewByDepartment()
    {
        $result = $this->userService->overviewByDepartment();
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }
}
