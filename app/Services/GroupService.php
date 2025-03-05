<?php
namespace App\Services;

use App\Repositories\GroupRepository;
use App\Services\OwnService;

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
        // dd($filters,$operator,$relation);
        return $this->atributi->filterByColumnsAndRelation($filters, $operator,$relation,$relationFilters);
    }
    public function changeStatusOfDeviceInGroup($id,$status){
        $group = $this->getById($id);
        return $group->devices()->update(["status"=>$status]);
    }
}
