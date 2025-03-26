<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
