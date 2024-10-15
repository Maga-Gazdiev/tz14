<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class YearService
{
    public function index()
    {
        return array_map(function ($yearPath) {
            return basename($yearPath);
        }, Storage::disk('public')->directories('uploads'));
    }

    public function store($year)
    {
        $path = 'uploads/' . $year;

        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        return ['success' => true, 'year' => $year];
    }

    public function destroy($year)
    {
        $path = 'uploads/' . $year;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->deleteDirectory($path);
            return ['success' => true];
        }

        return ['success' => false, 'status' => 404];
    }
}
