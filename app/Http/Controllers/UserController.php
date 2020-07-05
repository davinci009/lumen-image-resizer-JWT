<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function __construct()
    {
        //
    }

    public function index()
    {
        $users = User::all();
        return Response()->json($users, 200);        
    }

    public function store(Request $request)
    {
        if ($request->isJson()){
            $this->validate($request, [
                'name' => 'required', 
                'email' => 'required', 
                'password' => 'required', 
            ]);

            $data = $request->json()->all();
            $user = User::create([
                'name' => $data['name'], 
                'email' => $data['email'], 
                'password' => Hash::make($data['password'])
            ]);

            return Response()->json([
                'msg' => 'Usuario creado satisfactoriamente', 'data' => $user
            ], 201);
        }
    }

    public function getTokens(Request $request)
    {
        if ($request->isJson()){
            $data = $request->json()->all();
            $user = User::where('email', $data['email'])->first();
            
            if($user && Hash::check($data['password'], $user->password))
            {
                return Response()->json('exitosamentew logueado', 200);
            }

            // return Response()->json([
            //     'msg' => 'Usuario creado satisfactoriamente', 'data' => $user
            // ], 201);
        }
    }
}
