<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'photo_url' => $this->photo_url,
            'photo_name' => $this->photo_name,
        ];
    }
}
