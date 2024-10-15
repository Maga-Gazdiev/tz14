<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreYearRequest;
use App\Services\YearService;

class YearController extends Controller
{
    protected $yearService;

    public function __construct(YearService $yearService)
    {
        $this->yearService = $yearService;
    }

    public function index()
    {
        $years = $this->yearService->index();
        return response()->json($years);
    }

    public function store(StoreYearRequest $request)
    {
        $year = $request->input('year');
        $result = $this->yearService->store($year);
        return response()->json($result);
    }

    public function destroy($year)
    {
        $result = $this->yearService->destroy($year);
        return response()->json($result['success'] ? ['success' => true] : ['success' => false], $result['status'] ?? 200);
    }
}
