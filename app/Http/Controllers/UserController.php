<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\changePasswordMail;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use \App\Http\Requests\changePassword;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->getAll();
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $exists = $this->userService->filterByColumns(['email' => $request->input('email')], '=');

            if (count($exists)) {
                return response()->json(["message" => "Email vec postoji"], 500);
            }
            $user = $this->userService->create($data);

            $user->sendEmailVerificationNotification();


            return response()->json(["message" => "Uspesno ste se registrovali"], 201);
        } catch (Exception $ex) {
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }
    public function changePassword($idUser){
        $user = $this->userService->getById($idUser);
        if(!$user){
            return response()->json(['message'=>"Korisnik ne postoji"],404);
        }
        $token = Str::random(60);
        $url = "https://smarteraback.ramenidom.com/api/confirmPassword?token=".$token;

        Mail::to($user->email)->queue(new changePasswordMail($url,$user->id));
    }
    public function confirmPassword(changePassword $request){
        $all = $request->all();

        try{
            $all['password'] = Hash::make($all['password']);
            $this->userService->update($all['userId'],['password'=>$all['password']]);
//            return view("")
        }
        catch(Exception $ex){
            return response()->json(["message"=>$ex->getMessage()],500);
        }
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
