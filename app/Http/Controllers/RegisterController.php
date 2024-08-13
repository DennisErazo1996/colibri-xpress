<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Mail;
use Auth;
use App\Mail\WelcomeMail;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\DB;

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
            'lastname' => 'required',
            'country' => 'required',
            'department' => 'required',
            'city' => 'required',
            'address' => 'required',
            // 'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required',
            'password' => 'required|confirmed|min:5|max:255',
            //'terms' => 'required'
        ]);
        $user = User::create($attributes);
        $userId = $user->id;
        $lockNum = DB::select("SELECT 'CX-' || LPAD(:id::TEXT, 4, '10') AS locker_number", ['id'=>$user->id]);
        foreach($lockNum as $ln){
            $lockerNumber = $ln->locker_number;
        }
        


        Mail::to($user->email)->send(new WelcomeMail($user, $lockerNumber));
        Mail::to('dennis.erazo@outlook.com')->send(new RegisterMail($user, $lockerNumber));
        //auth()->login($user);
                     
        
        return view('bienvenido')
            ->with('data', $user)
            ->with('id', $userId);
        //return redirect('/dashboard');
    }
}
