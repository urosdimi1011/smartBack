<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'Korisničko ime je obavezno.',
            'email.required' => 'Email je obavezan.',
            'email.email' => 'Unesite validan email.',
            'password.required' => 'Lozinka je obavezna.'
        ];
    }
    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //         'message' => 'Validation Failed',
    //         'errors' => $validator->errors()
    //     ], 422));
    // }
}
