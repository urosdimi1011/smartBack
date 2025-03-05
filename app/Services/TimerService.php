<?php

namespace App\Services;

use App\Models\Timer;
use Illuminate\Support\Facades\DB;
use App\Repositories\TimerRepository;
use App\Services\OwnService;
use Exception;
use Carbon\Carbon;

class TimerService extends OwnService
{
    public function __construct(protected TimerRepository $atributi)
    {
        parent::__construct($atributi);
    }

    public function addTimer($data, $id_user)
    {
        try {
            DB::beginTransaction();
            $date = Carbon::parse($data['time'])->setTimezone('Europe/Belgrade');
            $dateTime =  $date->format('H:i');
                $dataMy = [
                    "name" => $data['name'],
                    "time" => $dateTime,
                    "active"=>true,
                    "status" => boolval($data['status']),
                    "id_user" => $id_user
                ];

                $daysIds = $data['idsDays'];
                $deviceIds = $data['idsDevice'];
                $model = $this->atributi->create($dataMy);
                $model->devices()->attach($deviceIds);
                $model->days()->attach($daysIds);
                DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function changeTimer($data,$id){
        try {
            DB::beginTransaction();
            $date = Carbon::parse($data['time'])->setTimezone('Europe/Belgrade');
            $dateTime =  $date->format('H:i');
                $dataMy = [
                    "name" => $data['name'],
                    "time" => $dateTime,
                    "active"=>$data['active'],
                    "status" => boolval($data['status'])
                ];

                $daysIds = $data['idsDays'];
                $deviceIds = $data['idsDevice'];
                $model = $this->update($id,$dataMy);
                $model->devices()->sync($deviceIds);
                $model->days()->sync($daysIds);
                DB::commit();
                return $model;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    public function getActiveTimer($currentDay,$currentTime){
        return Timer::where('active',true)
        ->whereHas('days',function ($query) use ($currentDay){
            $query->where('name',$currentDay);
        })->where('time','LIKE',$currentTime."%")
        ->get();
    }

    public function deleteTimer($id) {
        try{
            return $this->atributi->delete($id);
        }
        catch(Exception $ex){
            throw $ex;
        }
    }

}
