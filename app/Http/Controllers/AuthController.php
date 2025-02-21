<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
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
}
