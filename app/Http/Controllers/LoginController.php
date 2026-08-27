<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $creds = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($creds)){
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back() ->withErrors([
            'username' => 'Maaf, nama yang anda gunakan tidak sesuai dengan password.',
        ])->onlyInput('username');
    }
}
