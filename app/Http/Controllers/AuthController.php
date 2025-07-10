<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // tu vista de login
    }

    public function login(Request $request)
    {
        $request->validate([
            '_id' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('_id', $request->_id)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['_id' => 'Credenciales incorrectas'])->withInput();
        }

        Auth::login($user, $request->has('remember'));
        return redirect()->intended('/dashboard'); // Ruta post-login
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
