<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show Register Form
    public function create(){
        return view('users.register');
    }

    //Create new user
    public function store(Request $request){
        //validate the form
        $formfield = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required' ,'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        //Hash the password
        $formfield['password'] = bcrypt($formfield['password']);

        //create and save the user
        $user = User::create($formfield);

        //sign them in
        auth()->login($user);

        //redirect to the home page
        return redirect('/')->with('message', 'User created and logged in');
    }

    //logout user
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'User logged out');
    }

    //Show login form
    public function login(){
        return view('users.login');
    }

    //authenticate user
    public function authenticate(Request $request){
        $formfield = $request->validate([
            'email' => ['required' ,'email'],
            'password' => ['required'],
        ]);

        if(auth()->attempt($formfield)){
            $request->session()->regenerate();
            return redirect('/')->with('message', 'User logged in');
        }

        return back()->withErrors([
            'email' => 'invalid credentials.',
        ])->onlyInput('email');
    }
    
}
