<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Crear un token para el usuario
        $token = $user->createToken('todo-app-token')->plainTextToken;

        return response()->json([
            'message' => 'usuario registrado',
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function login(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verificar si el usuario existe
        $user = User::where('email', $request->email)->first();

        // Verificar si la contraseña es correcta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'error con las credenciales'], 401);
        }

        // Crear un token para el usuario
        $token = $user->createToken('todo-app-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    // Método para cerrar sesión 
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Cerro sesión',
        ]);
    }

    
    // Método para obtener al usuario autenticado
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
