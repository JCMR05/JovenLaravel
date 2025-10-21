<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;


class RegisterController extends Controller
{
    public function showRegistrationForm(){
        return view('autenticacion.registro');
    }

    public function registration(UserRequest $request){
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'activo' => 1, //activar automaticamente 
        ]);

        $clientRole = Role::where('name', 'cliente')->first();
        if($clientRole){
            $user->assignRole($clientRole);
        }
        Auth::login($user);
        return redirect()->route('dashboard')->with('mensaje', 'Registro exitoso. ¡Bienvenido!');
    }
}
