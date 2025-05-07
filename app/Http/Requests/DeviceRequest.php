<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class DeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id_category' => 'required',
            'idGrupe' => 'nullable',
            'name' => 'required',
            'ssid' => 'required',
            'wifiPassword' => 'required',
            'board'=>'nullable|numeric',
            'pin'=>'nullable|numeric',
            'hasBrightness'=>'nullable|numeric'
        ];
    }
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
