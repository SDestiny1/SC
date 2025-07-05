<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{

public function index()
{
    $grupos = Group::with('carrera')->orderBy('created_at', 'desc')->get();
    return view('groups.index', compact('grupos'));
}
public function create()
{
    return view('groups.create'); // Asegúrate de que esta vista exista
}
public function show(string $id)
{
    $grupo = Group::with('carrera')->findOrFail($id);
    return view('groups.show', compact('grupo'));
}
public function edit($id)
{
    $student = User::where('_id', $id)->where('rol', 'alumno')->with('grupo')->firstOrFail();
    $groups = Group::all();
    return view('students.edit', compact('student', 'groups'));
}
}
$grupos = Group::with('carrera')->get();



