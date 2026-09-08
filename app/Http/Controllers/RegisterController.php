<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $userData = $request->validate([
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create($userData);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}