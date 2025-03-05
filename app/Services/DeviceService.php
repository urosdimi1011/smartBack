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

    public function changeStatus($id, $status)
    {
        $device = $this->getById($id);
        return $this->atributi->changeStatusOfDevice($device, $status);
    }
    public function filterByColumns($filters, $operator = "=")
    {
        return $this->atributi->filterByColumns($filters, $operator)->get();
    }

    public function filterByColumnsAndRelation($filters, $operator = "=",$relation)
    {
        // dd($filters,$operator,$relation);
        return $this->atributi->filterByColumnsAndRelation($filters, $operator,$relation);
    }
    public function updateColumnForCall($id,$column)
    {
        return $this->update($id,$column);
    }
}
