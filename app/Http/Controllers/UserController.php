<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $result = $this->userService->findAll();

        if (!$result['status']) {
            return response()->json(
                [
                    "statusCode" => Response::HTTP_NO_CONTENT,
                    "message" => "NO CONTENT",
                    "errors" => $result['errors']
                ],
                Response::HTTP_NO_CONTENT
            );
        }

        return response()->json(
            [
                "statusCode" => Response::HTTP_OK,
                "message" => "SUCCESS",
                "data" => $result['data']
            ]
        );
    }

    public function show(Request $request)
    {
        $result = $this->userService->findById($request->route('id'));
        if (!$result['status']) {
            return response()->json(
                [
                    "statusCode" => Response::HTTP_NOT_FOUND,
                    "message" => "NOT FOUND",
                    "errors" => $result['errors']
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                "statusCode" => Response::HTTP_OK,
                "message" => "SUCCESS",
                "data" => $result['data']
            ]
        );
    }

    public function update(Request $request)
    {
        $result = $this->userService->updateById($request);
        if (!$result['status']) {
            return response()->json(
                [
                    "statusCode" => Response::HTTP_NOT_FOUND,
                    "message" => "NOT FOUND",
                    "errors" => $result['errors']
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                "statusCode" => Response::HTTP_OK,
                "message" => "SUCCESS",
                "data" => $result['data']
            ]
        );
    }
}
