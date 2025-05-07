<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermostatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'=>"string|required",
            "idDevice"=>"nullable|numeric",
            "numberOfTermostate"=>"required|string",
            "locationOfSenzor"=>"required|string"
        ];
    }
}
