<?php

namespace App\Helpers;

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
}
