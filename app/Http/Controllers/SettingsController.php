<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function update(Request $request)
{
    $user = auth()->user();

    $user->name = $request->input('nombre');
    $user->email = $request->input('email');

    $saved = $user->save();

    if ($saved) {
        return redirect()->route('settings')->with('success', 'Configuración actualizada.');
    } else {
        return back()->with('error', 'No se pudo actualizar el usuario.');
    }
}

}
