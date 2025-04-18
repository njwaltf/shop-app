<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => ['required', 'unique:users', 'max:20', 'min:4'],
            'password' => ['required', 'min:5'],
            'email' => ['email', 'required', 'max:100', 'unique:users'],
            'role' => ['required']
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        User::create($validatedData);
        return redirect('/')->with('success', '<strong>Account created!</strong> <br>Please login');

    }
}
