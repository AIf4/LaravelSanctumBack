<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function createUser(Request $request)
    {
        // Valida los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'rol_id' => 'required|exists:rols,id'
        ]);

        // Crea un nuevo usuario
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->rol_id = $validatedData['rol_id'];
        $user->save();

        // Retorna una respuesta JSON
        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('app-token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    public function getAllUsers()
    {
        return response()->json(['users' => User::with('rol')->get()], 200);
    }

    public function getUserById($id)
    {
        $user = User::with('rol')->find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json(['users' => $user], 200);
    }


    public function updateUser($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'rol_id' => 'required|exists:rols,id'
        ]);

        $user->name = $validatedData['name'];
        $user->password = Hash::make($validatedData['password']);
        $user->rol_id = $validatedData['rol_id'];
        $user->update();

        return response()->json(['users' => $user], 200);
    }

    public function deleteUser($id)
    {
        $user = User::with('rol')->find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $user->delete();
        return response()->json(['users' => $user], 200);
    }
}
