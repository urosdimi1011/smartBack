<?php
namespace App\Services;

use App\Repositories\GroupRepository;
use App\Services\OwnService;
use Carbon\Carbon;

class GroupService extends OwnService
{
    public function __construct(public GroupRepository $atributi)
    {
        parent::__construct($atributi);
    }
    public function filterByColumns($filters, $operator = "=")
    {
        return $this->atributi->filterByColumns($filters, $operator)->get();
    }

    public function filterByColumnsAndRelation($filters, $operator = "=",$relation,$relationFilters=[])
    {
        return $this->atributi->filterByColumnsAndRelation($filters, $operator,$relation,$relationFilters);
    }
    public function changeStatusOfDeviceInGroup($id,$status,$ids){
        $group = $this->getById($id);
        $time = Carbon::now()->setTimezone("Europe/Belgrade")->format("Y-m-d H:i:s");
        return $group->devices()->whereIn('devices.id',$ids)->update(["status"=>$status,"updated_date"=>$time]);
    }
}
