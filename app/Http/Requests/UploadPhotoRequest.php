<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'year' => 'required',
            'month' => 'required',
            'letter' => 'required|string|max:1',
            'photo' => 'required|image|max:2048',
        ];
    }
}
