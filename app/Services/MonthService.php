<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class MonthService
{
    public function index($year)
    {
        $monthsPath = 'uploads/' . $year;

        if (Storage::disk('public')->exists($monthsPath)) {
            return array_map(function ($monthPath) {
                return basename($monthPath);
            }, Storage::disk('public')->directories($monthsPath));
        }

        return [];
    }

    public function store($year, $month)
    {
        $path = 'uploads/' . $year . '/' . $month;

        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        return ['success' => true, 'month' => $month];
    }

    public function destroy($year, $month)
    {
        $path = 'uploads/' . $year . '/' . $month;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->deleteDirectory($path);
            return ['success' => true];
        }

        return ['success' => false, 'status' => 404];
    }
}
