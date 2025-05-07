<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrightnessRequest;
use App\Http\Requests\DeviceRequest;
use App\Http\Requests\FilterDeviceInCategoryRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Resources\DeviceResource;
use App\Mail\DeviceMail;
use App\Services\DeviceService;
use App\Services\GroupService;
use App\Services\UserService;
use App\Traits\DeviceStautsTrait;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DeviceController extends Controller
{
    use DeviceStautsTrait;
    public function __construct(
        protected DeviceService $deviceService,
        protected UserService $userService,
        protected GroupService $groupService
    ) {}
    public function store(DeviceRequest $request)
    {
        try {
            $allData = $request->all();
            $dataToSend = $request->only(['id_category', 'name','hasBrightness']);
            $userId = auth()->user()->id;
            $userEmail = auth()->user()->email;
            $userName = auth()->user()->username;
            $pin = $request->has('pin') ? $request->input('pin') : "2";
            $board = $request->has('board') ? $request->input('board') : random_int(10000, 99999);

            if($request->has('board') && $pin != "2"){
                if (!count($this->deviceService->filterByColumns(['board'=>$board]))) {
                    return response()->json(["message" => "Board ne postoji. Molimo unesite validan board."], 404);
                }
            }

            while (count($this->deviceService->filterByColumns(['board'=>$board,'pin'=>$pin],"=","and"))){
                $board = random_int(10000, 99999);
            }
            //upisivanje uredjaja u bazu
            $device = $this->deviceService->create($dataToSend + ["status" => 0, "id_user" => $userId, "pin" => $pin, 'board' => $board]);
            if ($request->has("idGrupe")) {
                $idGrupe = $request->input("idGrupe");
                $device->groups()->attach($idGrupe);
            }


            //kreiranje linka

            if($request->has('pin')){
                $url = $this->generateTempLink(["ssid" => $allData['ssid'], "pass" => $allData['wifiPassword'], "korisnik" => $userName, "sifra" => $userName, "id_uredjaj" => $device->board, "name" => $device->name,"pin"=>$pin]);
            }
            else{
                $url = $this->generateTempLink(["ssid" => $allData['ssid'], "pass" => $allData['wifiPassword'], "korisnik" => $userName, "sifra" => $userName, "id_uredjaj" => $device->board, "name" => $device->name]);
            }

            //Ovde ce se sad poslati mejl korisniku !
            Mail::to($userEmail)->queue(new DeviceMail($device));

            return response()->json(["message" => "Uspesno ste dodali uredjaj", "url" => $url], 201);
        } catch (Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }

    private function generateTempLink($data)
    {
        $url = "http://192.168.4.1/setting?ssid=" . urlencode($data['ssid'])
            . "&pass=" . urldecode($data['pass'])
            . "&korisnik=" . urldecode($data['korisnik'])
            . "&sifra=" . urldecode($data['sifra'])
            . "&uredjaj=" . urldecode($data['id_uredjaj'])
            ."&nazivuredjaja=".urldecode($data['name']);
        if (isset($data['pin']) && $data['pin']) {
            $url.='&pin='.urldecode($data['pin']);
        }
        return $url;
    }

    public function getAll(FilterDeviceInCategoryRequest $request)
    {
        //Ovo koristimom ovde da bi omogucili filtriranje uredjaja po kategoriji koju prosledimo
        $filters = ["id_user" => auth()->user()->id];
        $operatorOfFIlter = "OR";
        if($request->has('typeId')){
            $typeId = $request->input('typeId');
            $operatorOfFIlter = "AND";
            $filters = ["id_user" => auth()->user()->id,"id_category" => $typeId];
        }
        $allDevice = $this->deviceService->filterByColumnsAndRelation($filters, "=", ['category'],$operatorOfFIlter);
        $allDeviceWithStatus = $this->checkDeviceStatus($allDevice);
        return response()->json(["devices" => $allDeviceWithStatus]);
    }
    public function changeStatusOfDevice($id, StatusRequest $request)
    {
        // Ovde negde treba da se implementira web socket!!
        try {
            $status = boolval($request->input('status'));

            if ($request->isMethod('post')) {
                $status = boolval($request->input('status'));
            }
            // Za GET metodu, koristimo query string
            elseif ($request->isMethod('get')) {
                $status = boolval($request->query('status'));
            }

            //Ovo je mnogo glupo, promeni ovu metodu changeStatus!
            if($request->has('pin')){
                $pin = $request->input('pin');
                $device = $this->deviceService->changeStatus($id, $status,$pin);
            }
            else{
                $device = $this->deviceService->changeStatus($id, $status);
            }
            $time = Carbon::now()->setTimezone("Europe/Belgrade")->format("Y-m-d H:i:s");

            $this->deviceService->updateColumnForCall($device->id, ["updated_date" => $time]);

            //Ovde je problem!!!
//            Mail::to(auth()->user()->email)->queue(new ChangeStatus($device));

            return response()->json([], 204);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }

    public function changeStatusOfDeviceInGroup($idGroup, StatusRequest $request)
    {
        try {
            $status = boolval($request->input('status'));
            $ids = $request->input('ids');

            $this->groupService->changeStatusOfDeviceInGroup($idGroup, $status,$ids);
            return response()->json([], 204);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }

    public function getStatusOfDevice($id, Request $request)
    {
        // Dohvati sve uređaje sa datim ID-em
        $devices = $this->deviceService->filterByColumns(["board" => (int)$id],"=",'and');
        dd($devices);
        // Ako postoji barem jedan uređaj
        if ($devices->isNotEmpty()) {
            $time = Carbon::now()->setTimezone("Europe/Belgrade")->format("Y-m-d H:i:s");
            // Ažuriraj svaki uređaj sa trenutnim vremenom
            foreach ($devices as $device) {
                $this->deviceService->updateColumnForCall($device->id, ["updated_date" => $time,'last_ip_address'=>$request->ip()]);
            }

            // Kreiraj niz sa pinom kao ključem i statusom kao vrednošću
            $deviceStatuses = $devices->mapWithKeys(function ($device) {
                return [$device->pin => (string)$device->status];
            });

            return response()->json($deviceStatuses);
        }

        // Ako nema uređaja sa datim ID-em
        return response()->json(["message" => "Ne postoji uređaj"], 400);
    }
    public function changeNameOfDevice($id, Request $request)
    {
        try {
            $this->deviceService->update($id, ["name" => $request->input('name')]);
            return response()->json([], 204);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
    public function removeDevice($id)
    {
        try {
            $this->deviceService->delete($id);
            return response()->json([], 204);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
    public function changeBrightnessForDevice($id,BrightnessRequest $request){
        try{
            $brightness = $request->input('brightness');
            if($brightness < 5)
            {
                $this->deviceService->update($id,['brightness'=>$request->input('brightness'),'status'=>0]);
            }
            else{
                $this->deviceService->update($id,['brightness'=>$request->input('brightness'),'status'=>1]);
            }
            return response()->json(['message'=>'Uspesno ste promenili osvetljenje uredjaju']);
        }
        catch (Exception $ex){
            return response()->json(['message'=>$ex->getMessage()],$ex->getCode());
        }
    }
    public function getAllDeviceForTermostat(Request $request){
        try{
            $user = auth()->user();
            if($type = $request->input('type')){
                $deviceOfTemperature = $this->deviceService->getAllDeviceForTermostat($user,[$type]);
            }
            else{
                $deviceOfTemperature = $this->deviceService->getAllDeviceForTermostat($user);
            }
            //OVO JE DTO
            return DeviceResource::collection($deviceOfTemperature);
        }
        catch (Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],500);
        }
    }
    public function setDataOfDeviceForTemperature(Request $request,$id){
        try{
            $this->deviceService->changeDataOfTermostat($id,$request);

            return response()->json(['message'=>"Uspesno ste promenili"]);
        }
        catch(Exception $ex){
            $status = is_numeric($ex->getCode()) ? (int) $ex->getCode() : 500;
            return response()->json(['message'=>$ex->getMessage()],500);
        }

    }
}
