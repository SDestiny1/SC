<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Group;

class StudentController extends Controller
{

        public function index()
{
    $students = User::where('rol', 'alumno')->with('grupo')->get();
    return view('students.index', compact('students'));
}



    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
    'name' => 'required|string|max:100',
    'email' => 'required|email|unique:students,email',
    'age' => 'required|integer|min:1',
    'specialty' => 'required|string',
    'course' => 'required|string',
]);


        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

public function edit($id)
{
    $student = User::where('_id', $id)->where('rol', 'alumno')->with('grupo')->firstOrFail();
    $groups = Group::all();
    return view('students.edit', compact('student', 'groups'));
}

public function update(Request $request, $id)
{
    $student = User::where('_id', $id)->where('rol', 'alumno')->firstOrFail();

    $request->validate([
        'nombre' => 'required|string|max:100',
        'correo' => 'required|email|unique:users,correo,' . $id . ',_id',
        'age' => 'required|integer|min:1',
        'course' => 'required|string|max:100',
    ]);

    $student->update([
        'nombre' => $request->nombre,
        'correo' => $request->correo,
        'age' => $request->age,
        'course' => $request->course,
    ]);

    return redirect()->route('students.index')->with('success', 'Alumno actualizado correctamente.');
}


public function destroy($id)
{
    $student = User::where('_id', $id)->where('rol', 'alumno')->firstOrFail();
    $student->delete();
    return back()->with('success', 'Alumno eliminado correctamente.');
}

}
