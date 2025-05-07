<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReadingDataRequest;
use App\Http\Requests\TermostatRequest;
use App\Services\DeviceService;
use App\Services\ReadingService;
use App\Services\TermostatService;
use Illuminate\Http\Request;

class TermostatController extends Controller
{
    //
    public function __construct(
        protected TermostatService $termostatService,
        protected DeviceService $deviceService,
        protected ReadingService $readingService
    ) {}

    public function store(TermostatRequest $request){
        try{
            $userId = auth()->user()->id;
            $termostatName = $request->input('name');
            $data = ['name'=>$termostatName,'id_user'=>$userId];
            if($request->has('idDevice')){
                $idDevice = $request->input('idDevice');
                $this->termostatService->addDeviceToTermostat($idDevice,$data);
            }
            else{
                $this->termostatService->create($data);
            }
            return response()->json(['message'=>'Termostat je uspesno kreiran'],201);
        }
        catch(\Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],$status);
        }
    }

    public function getAll(){
        try{
            $userId = auth()->user()->id;
            $termostats = $this->termostatService->filterByColumns(['id_user'=>$userId],"=");
            return response()->json(['termostats'=>$termostats],200);
        }
        catch(\Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],$status);
        }
    }
    public function setDeviceInTermostat($id,Request $request){
        try{
            $deviceId = $request->input('idDevice');
            $this->termostatService->setDeviceInTermostat($id,$deviceId);

            return response()->json(['message'=>'Uspesno ste povezali uredjaj i termostat',201]);
        }
        catch(\Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],$status);
        }
    }


    //Ova metoda sluzi za upis onoga sto termostat ocita!
    public function storeReading(ReadingDataRequest $request){
        try{
            $this->readingService->storeReading($request);

            return response()->json(["message"=>"Uspesno ste upisali trenutnu temperaturu"],201);
        }
        catch(\Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],$status);
        }
    }
}
