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
            'login' => 'required',
        ]);

        $loginValue = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Log out existing session
        if (Auth::check()) {
            Auth::logout();
            session()->flush();
        }

        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // Email login requires password
            if (empty($password)) {
                return back()
                    ->withErrors(['password' => 'Please input a password'])
                    ->withInput($request->only('login'));
            }

            if (!Auth::attempt(['email' => $loginValue, 'password' => $password], $remember)) {
                return back()
                    ->with('error', 'Invalid credentials.')
                    ->withInput($request->only('login'));
            }

            //  Correctly fetch user role after login
            $user = Auth::user();
            if ($user && $user->role) {
                $user->syncRoles([$user->role]);
            }

        } else {
            // login without password
            $user = User::where('code', $loginValue)->first();

            if (!$user) {
                return back()
                    ->with('error', 'Invalid credentials.')
                    ->withInput($request->only('login'));
            }

            Auth::login($user, $remember);

            if ($user && $user->role) {
                $user->syncRoles([$user->role]);
            }
        }

        $request->session()->regenerate();

        return redirect()->intended('index')->with('success', 'Very nice to have you back!');
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
