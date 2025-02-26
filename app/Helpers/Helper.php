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
                "data" => $data["data"]
            ]
        );
    }

    public static function isWithinRadius(float $userLat, float $userLng, float $targetLat, float $targetLng, int $radius)
    {
        $earthRadius = 6371000; // dalam meter

        // Konversi latitude dan longitude ke radian
        $latFrom = deg2rad($userLat);
        $lngFrom = deg2rad($userLng);
        $latTo = deg2rad($targetLat);
        $lngTo = deg2rad($targetLng);

        // Haversine formula
        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Hasil dalam meter

        return $distance <= $radius; // true jika dalam radius, false jika di luar
    }
}
