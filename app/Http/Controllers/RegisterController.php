<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //$nombre = $request->firstname;
        $attributes = request()->validate([
            'identity' => 'required',
            'firstname' => 'required',
            'country' => 'required',
            'lastname' => 'required',
            // 'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            //'terms' => 'required'
        ]);
        $user = User::create($attributes);
        //auth()->login($user);
                     

        return view('bienvenido')->with('data', $user);
        //return redirect('/dashboard');
    }
}
