<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Services\ImageService;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function uploadPhoto(UploadPhotoRequest $request)
    {
        $photoData = $this->imageService->uploadPhoto($request);
        return response()->json($photoData);
    }

    public function deletePhoto($year, $month, $photoName)
    {
        $result = $this->imageService->deletePhoto($year, $month, $photoName);
        return response()->json($result['message'], $result['status'] ?? 200);
    }

    public function getPhotos($year, $month, $letter)
    {
        $photos = $this->imageService->getPhotos($year, $month, $letter);
        return response()->json($photos);
    }
}
