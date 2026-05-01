<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Resources\MessageResource;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(public DashboardService $dashboardService) {}

    public function stats(): MessageResource
    {
        return new MessageResource([
            'status' => HttpStatus::OK->value,
            'message' => 'Dashboard stats fetched successfully.',
            'data' => $this->dashboardService->getStats(),
        ]);
    }
}
