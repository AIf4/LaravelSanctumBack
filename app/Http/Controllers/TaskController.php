<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        // Valida los datos del formulario
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'state' => 'required|in:PENDING,INPROGRESS,COMPLETE',
            'proyect_id' => 'required|exists:proyects,id'
        ]);

        // Crea un nuevo usuario
        $task = new Task();
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->state = $validatedData['state'];
        $task->proyect_id = $validatedData['proyect_id'];
        $task->save();

        // Retorna una respuesta JSON
        return response()->json(['message' => 'Tarea creada con éxito'], 201);
    }

    public function getAllTask()
    {
        return response()->json(['tarea' => Task::with(['user', 'proyect'])->get()], 200);
    }

    public function getTaskById($id)
    {
        $task = Task::with('user')->find($id);
        if (!$task) {
            return response()->json(['message' => 'tarea no encontrada'], 404);
        }
        return response()->json(['task' => Task::find($id)->with(['user', 'proyect'])->get()], 200);
    }

    public function getTaskByProyectId($proyect_id)
    {
        $task = Task::with(['user', 'proyect'])->where('proyect_id', $proyect_id);

        if (!$task->count()) {
            return response()->json(['message' => 'El proyecto no tiene tareas asignadas'], 404);
        }
        return response()->json(['tasks' =>  $task->get()], 200);
    }

    public function updateTask($id, Request $request){
        $task = Task::with('user')->find($id);
        if (!$task) {
            return response()->json(['message' => 'tarea no encontrada'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'state' => 'required|in:PENDING,INPROGRESS,COMPLETE',
            'proyect_id' => 'required|exists:proyects,id'
        ]);

        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->state = $validatedData['state'];
        $task->proyect_id = $validatedData['proyect_id'];
        $task->update();

        return response()->json(['task' => $task], 200);
    }

    public function deleteTask($id){
        $task = Task::with('user')->find($id);
        if (!$task) {
            return response()->json(['message' => 'tarea no encontrada'], 404);
        }
        // elimino todas las relaciones con los usuarios en la tabla pivote
        $task->user()->detach();
        $task->delete();
        return response()->json(['task' => $task], 200);
    }



    public function assignUser(Request $request, Task $task)
    {
        $user = User::find($request->input('user_id'));
        $task->user()->attach($user->id);
        return response()->json(['message' => 'Usuario asignado con éxito'], 201);
    }

    public function unassignUser(Request $request, Task $task)
    {
        $user = User::find($request->input('user_id'));
        $task->user()->detach($user->id);
        return response()->json(['message' => 'Tarea desasignada con éxito'], 201);
    }
}
