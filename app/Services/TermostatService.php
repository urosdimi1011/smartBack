<?php

namespace App\Services;

// use App\Models\Timer;
// use Illuminate\Support\Facades\DB;
use App\Repositories\TermostatRepository;
use App\Services\OwnService;
use Illuminate\Support\Facades\DB;

// use Exception;
// use Carbon\Carbon;

class TermostatService extends OwnService
{
    public function __construct(protected TermostatRepository $atributi)
    {
        parent::__construct($atributi);
    }

    public function setDeviceInTermostat($termostatId,$deviceId){
            $termostat = $this->getById($termostatId);
            $termostat->devices()->attach($deviceId);
            return true;
    }

    public function addDeviceToTermostat($idDevice,$data){
        try{
            DB::beginTransaction();
            $termostat = $this->create($data);
            //Ovo je karakteristika koja se dodaje na termostat, 1 predstavlja karakteristuku 'temperatura' koju taj termostat moze da da!!!
            $termostat->features()->attach(1);
            $this->setDeviceInTermostat($termostat->id,$idDevice);
            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollBack();
            throw $ex;
        }
    }

}
