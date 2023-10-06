<?php

namespace App\Http\Controllers;

use App\Models\Proyect;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectController extends Controller
{

    public function createProyect(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string'
        ]);

        $proyect = new Proyect();
        $proyect->title = $validatedData['title'];
        $proyect->description = $validatedData['description'];
        $proyect->start_date = new DateTime($validatedData['start_date']);
        $proyect->end_date = new DateTime($validatedData['end_date']);
        $proyect->user_id = Auth::id();
        $proyect->save();

        return response()->json(['message' => 'proyecto creado con éxito'], 201);
    }

    public function getAllProyects()
    {
        return response()->json(['proyects' => Proyect::with(['user', 'task.user'])->get()], 200);
    }

    public function getAllProyectsByUserId($user_id)
    {
        return response()->json(['proyects' => Proyect::with([
            'user',
            'task' => function ($query) use ($user_id) {
                $query->whereHas('user', function ($query) use ($user_id) {
                    $query->where('users.id', $user_id);
                });
            }
        ])->get()], 200);
    }

    public function getProyectById($id)
    {
        $proyect = Proyect::with('user')->find($id);
        if (!$proyect) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
        return response()->json(['proyect' => $proyect], 200);
    }

    public function getByParam($param)
    {
        $proyect = Proyect::with('user')->where('title', 'LIKE', '%' . $param . '%')->get();
        if (!$proyect) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
        return response()->json(['proyect' => $proyect], 200);
    }


    public function updateProyect($id, Request $request)
    {

        $proyect = Proyect::with('user')->find($id);
        if (!$proyect) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|string',
            'end_date' => 'required|string'
        ]);

        $proyect->title = $validatedData['title'];
        $proyect->description = $validatedData['description'];
        $proyect->start_date = Carbon::parse($validatedData['start_date']);
        $proyect->end_date = Carbon::parse($validatedData['end_date']);
        $proyect->update();

        return response()->json(['proyect' => $proyect], 200);
    }

    public function deleteProyect($id)
    {
        $proyect = Proyect::with('user')->find($id);
        if (!$proyect) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
        $proyect->delete();
        return response()->json(['proyect' => $proyect], 200);
    }
}
