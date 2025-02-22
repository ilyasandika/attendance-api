<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class Helper
{
    public static function returnIfNotFound($data, $message)
    {
        if (!$data) {
            return [
                'status' => false,
                'errors' => ["message" => $message]
            ];
        }
    }

    public static function returnSuccess($data)
    {
        return [
            'status' => true,
            'data' => $data
        ];
    }


    public static function responseError($data, $message)
    {
        return response()->json(
            [
                "statusCode" => Response::HTTP_NOT_FOUND,
                "message" => $message,
                "errors" => $data['errors']
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    public static function responseSuccess($data, $message)
    {
        return response()->json(
            [
                "statusCode" => Response::HTTP_OK,
                "message" => $message,
                "data" => [$data["data"]]
            ]
        );
    }
}
