<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit(): View
    {
        return view('admin.profile.edit');
    }

    /**
     * Update the profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            if ($request->filled('new_password')) {
                $data['password'] = Hash::make($request->new_password);
            }
        }

        $user->update($data);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
