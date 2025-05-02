<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomAuthController extends Controller
{

    public function index()
    {
        return view('login');   // your Blade file
    }

    public function customSignin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');
        Log::info('Attempted credentials:', $credentials);
        
        // Check if the user is already authenticated and log them out to prevent conflicts
        if (Auth::check()) {
            Auth::logout(); // Log out any authenticated user
            session()->flush(); // Clear the session
        }

        // Try to authenticate a normal user first (using users table)
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // User login successful
            Log::info('User found:', $user ? $user->toArray() : null);

            Auth::login($user, $remember);
            $request->session()->regenerate();

            session([
                'user_type' => 'user',
                'user_id'   => $user->id,
                'user_email'=> $user->email,
                'user_name'=> $user->name,
                'user_phone'=> $user->phone,
                'user_image' => $user->profile_picture,
            ]);

            return redirect()->intended('index')->with('success', 'It\'s nice to see you!');
        }

        // If user login fails, try the employee table (employees table)
        $employee = Employee::where('email', $credentials['email'])->first();
        
        if ($employee && Hash::check($credentials['password'], $employee->password)) {
            // Employee login successful
            Log::info('Employee found:', $employee ? $employee->toArray() : null);

            Auth::guard('employee')->login($employee, $remember); // Use the custom guard
            $request->session()->regenerate();

            session([
                'user_type' => 'employee',
                'user_id'   => $employee->id,
                'user_email'=> $employee->email,
                'user_phone'=> $employee->contact_number,
                'user_name'=> $employee->first_name. ' ' . $employee->last_name,
                'user_image' => $employee->profile_photo,

            ]);

            return redirect()->intended('index')->with('success', 'It\'s nice to see you!');
        }

        // If neither login succeeds, return an error
        return redirect()->back()
            ->with('error', 'These credentials do not match our records.')
            ->withInput($request->only('email'));
    }

    

    
    
    public function showLoginForm()
    {
        return view('login');
    }

    public function registration()
    {
        Session::flash('error', 'Please contact the administrator to create an account.');
        return redirect()->route('register-2'); // assuming your login route is named 'login'
    }
      

    public function customRegister(Request $request)
    {
        
    }


    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'confirmpassword' => Hash::make($data['confirmpassword'])
      ]);
    }    
    

    public function dashboard()
    {
        if(Auth::check()){
            return view('index');
        }
  
        return redirect("signin")->withSuccess('You are not allowed to access');
    }
    

    public function signOut(Request $request)
    {
        // Clear the custom session data
        $request->session()->forget('user_type');
        $request->session()->forget('user_id');
        $request->session()->forget('user_email');

        // Optionally clear all session data
        $request->session()->flush();

        // Log the user out
        Auth::logout();

        // Redirect to the login page
        return redirect()->route('login');
    }
 

}
