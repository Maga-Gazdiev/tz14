<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonthRequest;
use App\Services\MonthService;

class MonthController extends Controller
{
    protected $monthService;

    public function __construct(MonthService $monthService)
    {
        $this->monthService = $monthService;
    }

    public function index($year)
    {
        $months = $this->monthService->index($year);
        return response()->json($months);
    }

    public function store(StoreMonthRequest $request, $year)
    {
        $month = $request->input('month');
        $result = $this->monthService->store($year, $month);
        return response()->json($result);
    }

    public function destroy($year, $month)
    {
        $result = $this->monthService->destroy($year, $month);
        return response()->json($result['success'] ? ['success' => true] : ['success' => false], $result['status'] ?? 200);
    }
}
