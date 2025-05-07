<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterDeviceInCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'typeId'=>"nullable|exists:devices,id_category"
        ];
    }
}
