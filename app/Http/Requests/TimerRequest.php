<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            "name" => "string|required",
            "time" => "string|required",
            "status" => "boolean|required",
            "active" => "boolean",
            "id" => "integer|exists:timer,id",
            "idsDevice" => "array|required|exists:devices,id",
            "idsDays" => "array|required|exists:days,id"
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Naziv tajmera je obavezan.',
            'name.string' => 'Naziv tajmera mora biti tekst.',

            'time.required' => 'Vreme je obavezno.',
            'time.string' => 'Vreme mora biti u formatu stringa.',

            'status.required' => 'Status je obavezan.',
            'status.boolean' => 'Status mora biti true ili false.',

            'active.boolean' => 'Aktivno polje mora biti true ili false.',

            'id.required' => 'ID tajmera je obavezan.',
            'id.integer' => 'ID tajmera mora biti broj.',
            'id.exists' => 'Izabrani ID tajmera ne postoji u bazi.',

            'idsDevice.required' => 'Morate izabrati bar jedan uređaj.',
            'idsDevice.array' => 'Uređaji moraju biti poslati kao niz.',
            'idsDevice.exists' => 'Jedan ili više izabranih uređaja ne postoji u bazi.',

            'idsDays.required' => 'Morate izabrati bar jedan dan.',
            'idsDays.array' => 'Dani moraju biti poslati kao niz.',
            'idsDays.exists' => 'Jedan ili više izabranih dana ne postoji u bazi.',
        ];
    }
}
