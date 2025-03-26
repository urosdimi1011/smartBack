<?php

namespace App\Traits;
use Carbon\Carbon;

trait DeviceStautsTrait
{
    public function checkDeviceStatus($devices)
    {
        $time = Carbon::now()->setTimezone("Europe/Belgrade")->format("Y-m-d H:i:s");

        return $devices->map(function($device) use ($time) {
            $updateDate = Carbon::parse($device->updated_date);
            $device->is_out_of_range = $updateDate->diffInSeconds($time) > 10 ? true : false;

            return $device;
        });
    }
}
