<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function showAllSchedules()
    {

        $result = $this->scheduleService->findAllSchedules();

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
                "data" => [$result["data"]]
            ]
        );
    }
}
