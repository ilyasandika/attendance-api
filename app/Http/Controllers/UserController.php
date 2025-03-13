<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showUserByUserLogin(Request $request)
    {
        $result = $this->userService->findById(Auth::user()->id);
        return (!$result['status']) ? Helper::responseError($result["data"], "NOT FOUND") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function update(Request $request)
    {
        $result = $this->userService->updateById($request);
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function destroy(Request $request)
    {
        $result = $this->userService->deleteById($request->route('id'));
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showLatest()
    {
        $result = $this->userService->findLatest();
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }

    public function showOverview()
    {
        $result = $this->userService->findOverview();
        return (!$result['status']) ? Helper::responseError($result, "NOT FOUND") : Helper::responseSuccess($result, "SUCCESS");
    }
}
