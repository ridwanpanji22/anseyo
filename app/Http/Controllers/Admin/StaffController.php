<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $staffs = User::orderBy('name')->get();
            
        \Log::info('Staff index - Total users: ' . $staffs->count());
        \Log::info('Staff index - Admin users: ' . $staffs->where('role', 'admin')->count());
        \Log::info('Staff index - All users: ' . json_encode($staffs->pluck('name', 'role')->toArray()));
            
        return view('admin.staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Staff create request data:', $request->all());
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,cashier,kitchen,waiter',
                'is_active' => 'nullable|boolean',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->boolean('is_active'),
            ];

            \Log::info('Staff data to be created:', $data);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                \Log::info('Photo upload started');
                $photo = $request->file('photo');
                \Log::info('Photo file info:', [
                    'original_name' => $photo->getClientOriginalName(),
                    'size' => $photo->getSize(),
                    'mime_type' => $photo->getMimeType(),
                    'is_valid' => $photo->isValid()
                ]);
                
                $photoName = time() . '_' . $photo->getClientOriginalName();
                \Log::info('Photo name generated:', ['photo_name' => $photoName]);
                
                try {
                    $photo->storeAs('staff-photos', $photoName, 'public');
                    \Log::info('Photo stored successfully');
                    
                    $data['photo'] = 'staff-photos/' . $photoName;
                    \Log::info('Photo path saved:', ['photo_path' => $data['photo']]);
                    
                    // Verify file exists
                    $exists = Storage::disk('public')->exists('staff-photos/' . $photoName);
                    \Log::info('File exists after upload:', ['exists' => $exists]);
                    
                } catch (Exception $e) {
                    \Log::error('Photo upload failed:', ['error' => $e->getMessage()]);
                }
            } else {
                \Log::info('No photo file in request');
            }

            User::create($data);

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staf berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error creating staff: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $staff): View
    {
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff): View
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:admin,cashier,kitchen,waiter',
                'is_active' => 'nullable|boolean',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($staff->photo && Storage::disk('public')->exists($staff->photo)) {
                    Storage::disk('public')->delete($staff->photo);
                }
                
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('staff-photos', $photoName, 'public');
                $data['photo'] = 'staff-photos/' . $photoName;
            }

            $staff->update($data);

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staf berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating staff: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        if ($staff->role === 'admin') {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Admin tidak dapat dihapus');
        }
        
        // Delete photo if exists
        if ($staff->photo && Storage::disk('public')->exists($staff->photo)) {
            Storage::disk('public')->delete($staff->photo);
        }
        
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staf berhasil dihapus');
    }
}
