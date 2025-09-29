<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Join drivers with users to get core info
        $drivers = Driver::join('users', 'drivers.user_id', '=', 'users.id')
            ->select(
                'drivers.*',
                'users.email',
                'users.phone',
                'users.role',
                'users.profile_picture',
                'users.status'
            )
            ->latest('drivers.created_at')
            ->get();

        return view('drivers', compact('drivers'));
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

        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'identity_card_number'   => 'required|string|unique:drivers,identity_card_number',
            'driving_licence_number' => 'required|string|unique:drivers,driving_licence_number',
            'personal_number'        => 'nullable|string|max:255',
            'licence_date_issue'     => 'nullable|date',
            'department_id'          => 'nullable',
            'licence_date_expiry'    => 'nullable|date|after_or_equal:licence_date_issue',
            'identity_card_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'driving_licence_file'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'passport_photo_file'    => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'email'                  => 'nullable|email|max:255',
            'phone'                  => 'nullable|string|max:255',
            'role'                   => 'nullable|string|max:50',
            'password'               => 'nullable|string|min:6'
        ]);

        Log::info('Validation passed', $validated);

        $driver = new Driver([
            'name'                   => $validated['name'],
            'identity_card_number'   => $validated['identity_card_number'],
            'driving_licence_number' => $validated['driving_licence_number'],
            'personal_number'        => $validated['personal_number'] ?? null,
            'licence_date_issue'     => $validated['licence_date_issue'] ?? null,
            'licence_date_expiry'    => $validated['licence_date_expiry'] ?? null,
            'department_id'          => $validated['department_id'] ?? null,
            'verified'               => $request->boolean('verified')
        ]);

        // Upload files
        if ($request->hasFile('identity_card_file')) {
            $driver->identity_card_file = $request->file('identity_card_file')->store('drivers/ids', 'public');
        }
        if ($request->hasFile('driving_licence_file')) {
            $driver->driving_licence_file = $request->file('driving_licence_file')->store('drivers/licences', 'public');
        }
        if ($request->hasFile('passport_photo_file')) {
            $driver->passport_photo_file = $request->file('passport_photo_file')->store('drivers/photos', 'public');
        }

        try {
            $driver->save();
            Log::info('Driver saved successfully', ['driver_id' => $driver->id]);
        } catch (\Exception $e) {
            Log::error('Driver save failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Driver save failed: ' . $e->getMessage());
        }

        // Create User regardless of verification
        try {
            $user = User::updateOrCreate(
                ['email' => $validated['email'] ?? 'driver'.$driver->id.'@example.com'], // fallback email if none provided
                [
                    'name'            => $driver->name,
                    'email'           => $validated['email'] ?? 'driver'.$driver->id.'@example.com',
                    'phone'           => $validated['phone'] ?? null,
                    'code'            => $driver->identity_card_number,
                    'role'            => $validated['role'] ?? 'driver',
                    'status'          => true,
                    'profile_picture' => $driver->passport_photo_file ?? null,
                    'password'        => Hash::make($validated['password'] ?? 'password123'),
                ]
            );

            // Save user_id in drivers table
            $driver->user_id = $user->id;
            $driver->save();

            Log::info('User created and linked to driver', ['user_id' => $user->id, 'driver_id' => $driver->id]);
        } catch (\Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Driver added successfully.');
    }


    /**
     * Display the specified resource.
     */
      public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
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

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
            'password' => 'nullable|min:4|confirmed',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function upgrade(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string',
            'role' => 'required',
            'code' => 'nullable',

            'password' => 'nullable|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old image if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->role = $request->role;
        $user->status = $request->has('status');

        if ($request->filled('code')) {
                $user->code = $request->code;
            }
        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        // Delete files
        if ($driver->identity_card_file) Storage::disk('public')->delete($driver->identity_card_file);
        if ($driver->driving_licence_file) Storage::disk('public')->delete($driver->driving_licence_file);
        if ($driver->passport_photo_file) Storage::disk('public')->delete($driver->passport_photo_file);

        $driver->delete();

        return redirect()->back()->with('success', 'Driver deleted successfully.');
    }
}
