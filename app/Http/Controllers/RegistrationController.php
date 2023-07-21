<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegistrationController extends Controller
{
    public function store(RegisterRequest $request)
    {
        return User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
    }
}
