<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Services\GroupService;
use App\Traits\DeviceStautsTrait;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use DeviceStautsTrait;
    public function __construct(
        protected GroupService $groupService,
    ) {}
    public function getAll(Request $request)
    {
        try {
            if($request->has('idCategory')){
                $groupsWithDevices = $this->groupService->filterByColumnsAndRelation(
                ["id_user" => auth()->user()->id],
                 "=",
                'devices',
                ["id_category"=>$request->input('idCategory')]);
            }
            else{
                $groupsWithDevices = $this->groupService->filterByColumnsAndRelation(["id_user" => auth()->user()->id], "=", 'devices.category');
                foreach ($groupsWithDevices as $g){
                    $g->devices = $this->checkDeviceStatus($g->devices);
                }
            }
            // $allDevice = $this->groupService->filterByColumns(["id_user"=>auth()->user()->id]);
            return response()->json(["groups" => $groupsWithDevices]);
        } catch (Error $ex) {
            $statusCode = ($ex->getCode() >= 100 && $ex->getCode() <= 599) ? $ex->getCode() : 500;
            return response()->json(["message" => $ex->getMessage()], $statusCode);
        }
    }
    public function store(GroupRequest $request)
    {
        //Ovo radi i nastavi sutra dalje sa ovim!!
        // dd($request->all());
        try {
            DB::beginTransaction();
            $name = $request->input('name');
            $ids = $request->input('ids');
            $userId = auth()->user()->id;

            $group = $this->groupService->create(['name' => $name, 'id_user' => $userId]);
            // dd($group);
            // dd($this->groupService->getById($group->id));
            $this->groupService->getById($group->id)->devices()->attach($ids);
            // $group->createWithRelation('devices',$ids);
            DB::commit();
            return response()->json(["message" => "Uspesno ste dodali grupu"], 201);
        } catch (Error $ex) {
            DB::rollBack();
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
    public function addDeviceInGroup(Request $request, $id)
    {
        // dd($request->all());
        try {
            $category = $this->groupService->getById($id);
            $category->devices()->sync($request->input('ids'));
            return response()->json(["message" => 'Uspesno ste dodali uredjaje u kategoriju ' . $category->name], 201);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
    public function removeGroup($id)
    {
        try {
            $this->groupService->delete($id);
            return response()->json(["message" => 'Uspesno ste obrisali kategoriju'], 204);
        } catch (Error $ex) {
            return response()->json(["message" => $ex->getMessage()], $ex->getCode());
        }
    }
}
