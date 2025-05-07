<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadingDataRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_termostat_features' => 'required|integer|exists:termostat_features,id',
            'value' => 'required|numeric',
            'reading_date' => 'required|date',
        ];
    }
}
