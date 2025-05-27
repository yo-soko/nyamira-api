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
            'login'    => 'required', // email or code
            'password' => 'nullable', // only required if email is used
        ]);

        $loginValue = $request->input('login');
        $password   = $request->input('password');
        $remember   = $request->boolean('remember');

        // Log out existing session
        if (Auth::check()) {
            Auth::logout();
            session()->flush();
        }

        $user = null;

        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // Login using email and password
            $user = User::where('email', $loginValue)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return back()
                    ->with('error', 'Invalid email or password.')
                    ->withInput($request->only('login'));
            }
        }
        else {
            // Login using code (no password required)
            $user = User::where('code', $loginValue)->first();

            if (!$user) {
                return back()
                    ->with('error', 'Invalid code.')
                    ->withInput($request->only('login'));
            }
        }

        // Login the user
        Auth::login($user, $remember);
        $request->session()->regenerate();

        session([
            'user_type'   => $user->role, // e.g. 'Employee', 'Admin', etc.
            'user_id'     => $user->id,
            'user_email'  => $user->email,
            'user_name'   => $user->name,
            'user_phone'  => $user->phone,
            'user_image'  => $user->profile_picture,
        ]);

        return redirect()->intended('index')->with('success', 'Welcome back!');
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
