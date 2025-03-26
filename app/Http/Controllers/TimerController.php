<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimerRequest;
use App\Services\TimerService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    public function __construct(
        protected TimerService $timerService,
    ) {}
    public function setTimer(TimerRequest $request)
    {
        try {
            $allData = $request->all();
            $userId = auth()->user()->id;
            $this->timerService->addTimer($allData, $userId);
            return response()->json(["message" => "Uspesno ste setovali tajmer"], 201);
        } catch (Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }
    public function processTimers()
    {
        //dohvatanje vremena

        $now = Carbon::now()->setTimezone("Europe/Belgrade");
        Log::info('Trenutno vreme: ' . $now);

        //Za sat vremena mi kasni kasni nego na serveru
        // dd($now->toArray());
        $currentDay = strtolower($now->format('l')); //dohvatanje dana.
        $currentTime = $now->format('H:i');
        // dd($currentTime);
        Log::info($currentDay ." - ". $currentTime);
        $actives = $this->timerService->getActiveTimer($currentDay, $currentTime);
        // dd($actives);
        Log::info($actives);

        if ($actives->isEmpty()) {
            return response()->json(['message' => 'Nema aktivnih tajmera'], 200);
        }

        foreach ($actives as $timer) {
            foreach ($timer->devices as $device) {
                $device->update(['status' => $timer->status]);
            }
        }
        return response()->json(['message' => 'Tajmer se uspesno primenio'], 200);
    }

    public function getAll()
    {
        $timers = $this->timerService->filterByColumns(["id_user" => auth()->user()->id], "=")->with(['days', 'devices'])->get();
        return response()->json(["timers" => $timers]);
    }
    public function changeTimer($id, TimerRequest $request)
    {
        $allData = $request->all();
        try {
            $model = $this->timerService->changeTimer($allData, $id);
            return response()->json(["data" => $model], 200);
        } catch (Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }
    public function deleteTimer($id)
    {
        try {
            $this->timerService->deleteTimer($id);
            return response(["message"=>"Uspesno ste obrisali tajmer"],200);
        } catch (Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }
}
