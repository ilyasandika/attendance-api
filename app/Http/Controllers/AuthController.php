<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data = $this->authService->register($data);
        return Helper::responseSuccessTry($data, __('successMessages.register_success'), 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $data = $this->authService->login($data);
        return Helper::responseSuccessTry($data, __('successMessages.login_success'), 200);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request);
        return (!$result['status']) ? Helper::responseError($result["errors"], "UNAUTHORIZED") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

}
