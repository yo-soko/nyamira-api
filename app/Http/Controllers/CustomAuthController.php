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
        if (Auth::check()) {
            if ($user->role === 'class_teacher') {
                return redirect()->intended('tdashboard')->with('success', 'Very nice to have you back!');
            } elseif ($user->role === 'student') {
                return redirect()->intended('sdashboard')->with('success', 'Very nice to have you back!');
            } elseif ($user->role === 'teacher') {
                return redirect()->intended('teacher/dashboard')->with('success', 'Very nice to have you back!');
            } else {
                return redirect()->intended('index')->with('success', 'Very nice to have you back!');
            }
          
        }
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

        // -------------------------------
        // Determine if login is email or code
        // -------------------------------
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            // Email logins always require password
            if (empty($password)) {
                return redirect()->back()
                ->with('error', 'password is required')
                ->withInput($request->only('login'));

            }

            if (!Auth::attempt(['email' => $loginValue, 'password' => $password], $remember)) {
                  return redirect()->back()
                ->with('error', 'Invalid credentials')
                ->withInput($request->only('login'));
            }

            $user = Auth::user();

        } else {
            // Code login
            $user = User::where('code', $loginValue)->first();

            if (!$user) {
                return redirect()->back()
                ->with('error', 'Invalid credentials')
                ->withInput($request->only('login'));
            }

            // If credentials not updated yet, allow login without password
            if (!$user->credentials_updated) {
                Auth::login($user, $remember);
                session(['force_update_user' => $user->id]);
                return redirect()->route('under-maintenance');
            }

            // If credentials updated, password is required
            if (empty($password) || !Hash::check($password, $user->password)) {
               return redirect()->back()
                ->with('error', 'Password is required together with the username')
                ->withInput($request->only('login'));
            }

            Auth::login($user, $remember);
        }
        
        if (!$user->credentials_updated) {
            session(['force_update_user' => $user->id]);
            return redirect()->route('under-maintenance');
        }

        // Sync role if available
        if ($user && $user->role) {
            $user->syncRoles([$user->role]);
        }

        $request->session()->regenerate();

        // -------------------------------
        // Normal role-based redirect
        // -------------------------------
        if ($user->role === 'class_teacher') {
            return redirect()->intended('tdashboard')->with('success', 'Very nice to have you back!');
        } elseif ($user->role === 'student') {
            return redirect()->intended('sdashboard')->with('success', 'Very nice to have you back!');
        } elseif ($user->role === 'teacher') {
            return redirect()->intended('teacher/dashboard')->with('success', 'Very nice to have you back!');
        } else {
            return redirect()->intended('index')->with('success', 'Very nice to have you back!');
        }
    }


    public function showUpdateCredentials()
    {
        $userId = session('force_update_user');
      
        $user = User::find($userId);

        return view('under-maintenance', compact('user'));
    }

    public function updateCredentials(Request $request)
    {
        $userId = session('force_update_user');
        $user = User::findOrFail($userId);

        $request->validate([
            'code' => 'required|string:users,code,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|confirmed|min:4',
        ]);

        // Update user details
        $user->code = $request->code;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->credentials_updated = 1;
        $user->save();

        // Clear session flag
        session()->forget('force_update_user');


       
        return redirect()->route('login')->with('success', 'Credentials updated! now sign in');
        
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
  
        return redirect("login")->withSuccess('You are not allowed to access');
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
