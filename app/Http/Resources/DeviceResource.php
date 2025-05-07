<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{

    public function toArray($request)
    {
        $termostat = $this->termostat->first(); // jer znaš da ih ima najviše jedan

        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "id_category"=> $this->id_category,
            "status" => $this->status,
            "id_user"=> $this->id_user,
            "created_date"=> $this->created_date,
            "updated_date"=> $this->updated_date,
            "pin"=> $this->pin,
            "board"=> $this->board,
            "last_ip_address"=> $this->last_ip_address,
            "termostat" => $termostat ? [
                "id" => $termostat->id,
                "name" => $termostat->name,
                "desired_temperature" => $termostat->info->desired_temperature ?? null,
                "maintain_temperature" => $termostat->info->maintain_temperature,
                "last_reading_of_termostat" => optional($termostat->data->first()?->reading)->{'reading_data.value'},
            ] : null
        ];
    }
}
