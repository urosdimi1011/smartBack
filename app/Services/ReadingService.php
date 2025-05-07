<?php
namespace App\Services;

use App\Models\Feature;
use App\Repositories\ReadingRepository;
use App\Repositories\TermostatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingService extends OwnService
{
    public function __construct(
        public ReadingRepository $atributi,
        public TermostatRepository $termostatRepository // Dodato
    ) {
        parent::__construct($atributi);
    }

    public function storeReading(Request $request)
    {
        $termostatFeatureId = $request->input('id_termostat_features');
        $value = $request->input('value');
        $readingDate = $request->input('reading_date');

        return DB::transaction(function () use ($termostatFeatureId, $value, $readingDate) {
            $reading = $this->create([
                'id_termostat_features' => $termostatFeatureId,
                'value' => $value,
                'reading_date' => $readingDate,
                'created_date' => now(),
            ]);

            //Sad moram da pronadjem sve uredjaje koji su na ovom tremostatu
            //prvo moram da vidim koji
            $termostatFeature = $this->termostatRepository->filterByColumnsAndRelation(['features.pivot.id'=>$termostatFeatureId],'=',['devices.termostat','features'])->first();

            foreach ($termostatFeature->devices as $device) {
                if (is_null($device->pivot->desired_temperature)) {
                    $device->pivot->desired_temperature = $value;
                    $device->pivot->save();
                }
                if($device->pivot->maintain_temperature){
                    $newStatus = $this->shouldBeTurnedOn($device,(int)$value);
                    if(!is_null($newStatus) && $device->status !== $newStatus){
                        $device->status = $newStatus;
                        $device->save();
                    }

                }
            }
            return $reading;
        });
    }

    protected function shouldBeTurnedOn($device, $readingValue)
    {
        $desired = $device->pivot->desired_temperature;
        $category = $device->id_category; // npr. 'heating' ili 'cooling'

        if (is_null($desired)) {
            return null; // ne menjaj status ako nije postavljena željena temperatura
        }
        //Heating
        if ($category == 4) {
            return $desired >= $readingValue ? 1 : 0;
        }
        //Cooling
        if ($category == 3) {
            return $desired <= $readingValue ? 1 : 0;
        }

        return null;
    }

}
