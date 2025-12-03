<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;



class LoginComponent extends Component
{
    public $email, $password;

    public function render()
    {
        return view('livewire.login-component')->layout('components.layouts.login');
    }

    public function proses()
    {
        $credential = $this->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        if (Auth::attempt($credential)) {
            //  $request->
            session()->regenerate();

            return redirect()->route('home');
        }
        return back()->withErrors([
            'email' => 'autentikasi gagal',
        ])->onlyInput('email');
    }

    public function keluar()
    {
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect()->route('home');
    }
}
