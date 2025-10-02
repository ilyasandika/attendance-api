<?php

namespace App\Helpers;

use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Helper
{
    public static function returnIfNotFound($data, $message)
    {
        if (!$data) {
            return [
                'status' => false,
//                'errors' => ["message" => $message]
                'errors' => is_array($message) ? $message : [$message]
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


    public static function responseError($errors, $message)
    {
        return response()->json(
            [
                "statusCode" => Response::HTTP_NOT_FOUND,
                "message" => $message,
//                "errors" => $errors
                 "errors" => is_array($errors) ? $errors : [$errors]
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    public static function responseSuccess($data, $message)
    {

        $a1 =  [
            "statusCode" => Response::HTTP_OK,
            "message" => $message,
            "payload" => $data
        ];

        return response()->json($a1);
    }

    public static function responseSuccessTry($data = null, string $message = 'Berhasil.', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'payload' => $data,
        ], $statusCode);
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

    public static function isOff($userId = null): bool
    {
        $today = Carbon::today();
        $dayName = strtolower($today->format('l'));

        if (Holiday::whereDate('date', $today)->exists()) {
            return true;
        }

        $user = User::with('schedule.shift.shiftDay')->find($userId);

        if (!$user || !$user->schedule || !$user->schedule->shift) {
            return true;
        }

        $shiftDay = $user->schedule->shift->shiftDay->firstWhere('name', $dayName);

        return !$shiftDay || !$shiftDay->is_on;
    }

    public static function findOrError($modelOrQuery, $id)
    {
        if (is_string($modelOrQuery)) {
            /** @var \Illuminate\Database\Eloquent\Model|null $modelOrQuery */
            $query = $modelOrQuery::query();
        } else {
            $query = $modelOrQuery;
        }


        $model = $query->find($id);
        if (!$model) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return $model;
    }
}
