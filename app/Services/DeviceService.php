<?php

namespace App\Services;

use App\Repositories\DeviceRepository;
use App\Services\OwnService;

class DeviceService extends OwnService
{
    public function __construct(public DeviceRepository $atributi)
    {
        parent::__construct($atributi);
    }

    public function changeStatus($id, $status,$pin="")
    {
        if(strlen((string)$id) == 5){
            $device=$this->filterByColumns(['board'=>$id,'pin'=>$pin],"=","and")->first();
        }
        else{
            $device = $this->getById($id);
        }
        if(!$device){
            abort(404, "Uređaj nije pronađen.");
        }
        $this->atributi->changeStatusOfDevice($device, $status);
        return $device;
    }
    public function filterByColumns($filters, $operator = "=",$operatorOfFilter="or")
    {
        return $this->atributi->filterByColumns($filters, $operator,$operatorOfFilter);
    }

    public function filterByColumnsAndRelation($filters, $operator = "=",$relation,$operatorOfFilter = "OR")
    {
        // dd($filters,$operator,$relation);
        return $this->atributi->filterByColumnsAndRelation($filters, $operator,$relation,$operatorOfFilter);
    }
    public function updateColumnForCall($id,array $columns)
    {
        return $this->update($id,$columns);
    }

    public function getAllDeviceForTermostat($user,$type=null){
        return $this->filterByColumnsAndRelation(['category.name'=>$type,'id_user'=>[$user->id]],'in',['termostat','category',"termostat.data"]);
    }

    public function changeDataOfTermostat($id,$request){
        // Dohvati uređaj (Device) putem ID-a
        $device = $this->getById($id);
        $device->status = $request->input('status');
        $device->save();
//        $this->update($id,['status'=>$request->input('status')]);
//        $device->status = $request->input('status');
//        dd($request->all());

        // Proveri da li je termostat ID poslat u zahtevu
        $termostatId = $request->input('termostat_id');
//        if (!$termostatId) {
//            $poruka = "Termostat ID nije poslat";
//            throw $poruka;
//        }

        // Podaci za ažuriranje u pivot tabeli
        $pivotData = [
            'maintain_temperature' => $request->input('maintain_temperature'),
            'desired_temperature' => $request->input('desired_temperature'),
        ];

//        dd($pivotData);
        // Ažuriraj pivot podatke
        $device->termostat()->updateExistingPivot($termostatId, $pivotData);
    }
}
