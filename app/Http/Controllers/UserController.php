<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // Or paginate() if you prefer
        return view('users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'role' => 'required',
            'code' => 'required',
            'password' => 'required|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profiles', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'code' => $request->code,
            'role' => $request->role,
            'status' => $request->has('status'),
            'profile_picture' => $imagePath,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
     

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($request)
    {
        $user = User::findOrFail($request->id);
        return view('users.edit', compact('user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'code' => 'required',
            'role' => 'required',
            'password' => 'nullable|confirmed|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->code = $request->code;
        $user->role = $request->role;
        $user->status = $request->has('status');
    
        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $imagePath;
        }
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->update();
    
        return redirect()->back()->with('success', 'User updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();
    
        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
