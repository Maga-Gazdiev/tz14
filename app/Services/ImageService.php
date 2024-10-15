<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadPhoto(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $letter = $request->input('letter');

        $path = "uploads/$year/$month/$letter";
        $filePath = $request->file('photo')->store($path, 'public');

        return [
            'photo_url' => Storage::url($filePath),
            'photo_name' => basename($filePath)
        ];
    }

    public function deletePhoto($year, $month, $photoName)
    {
        $path = "uploads/$year/$month/$photoName";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return ['message' => 'Фото удалено'];
        }

        return ['message' => 'Фото не найдено', 'status' => 404];
    }

    public function getPhotos($year, $month, $letter)
    {
        $path = "uploads/$year/$month/$letter";
        $photos = Storage::disk('public')->files($path);

        return array_map(function ($filePath) {
            return [
                'photo_url' => Storage::url($filePath),
                'photo_name' => basename($filePath)
            ];
        }, $photos);
    }
}
