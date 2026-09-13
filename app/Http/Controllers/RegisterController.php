<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Handle the registration submission.
     */
    public function store(Request $request)
    {
        $userData = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = new User;
        $user->username = $userData['username'];
        $user->password = $userData['password'];

        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
