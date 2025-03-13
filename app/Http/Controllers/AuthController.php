<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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


    public function register(Request $request)
    {
        $user = $this->authService->register($request->all());


        if (!$user["status"] ?? null) {
            return response()->json(
                [
                    "statusCode" => Response::HTTP_BAD_REQUEST,
                    "message" => "BAD REQUEST",
                    "errors" => $user["errors"]
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
            [
                "statusCode" => Response::HTTP_CREATED,
                "message" => "SUCCESS",
                "data" => [
                    "employeeId" => $user['data']['user']['employee_id'],
                    "employeeName" => $user['data']['userProfile']['name'],
                ]
            ],
            Response::HTTP_CREATED
        );
    }

    public function login(Request $request)
    {
        $result = $this->authService->login($request->all());

        return (!$result['status']) ? Helper::responseError($result["data"], "UNAUTHORIZED") : Helper::responseSuccess($result["data"], "SUCCESS");
    }

    public function logout(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $user->tokens()->delete();

        return [
            "statusCode" => 200,
            "message" => "SUCCESS Log out"
        ];
    }
}
