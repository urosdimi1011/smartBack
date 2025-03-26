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
            $device=$this->filterByColumns(['board'=>$id,'pin'=>$pin])->first();
        }
        else{
            $device = $this->getById($id);
        }
        return $this->atributi->changeStatusOfDevice($device, $status);
    }
    public function filterByColumns($filters, $operator = "=")
    {
        return $this->atributi->filterByColumns($filters, $operator);
    }

    public function filterByColumnsAndRelation($filters, $operator = "=",$relation)
    {
        // dd($filters,$operator,$relation);
        return $this->atributi->filterByColumnsAndRelation($filters, $operator,$relation);
    }
    public function updateColumnForCall($id,array $columns)
    {
        return $this->update($id,$columns);
    }
}
