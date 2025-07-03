<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle profile photo upload with detailed error logging
        if ($request->hasFile('profile_photo')) {
            try {
                $file = $request->file('profile_photo');
                
                // Log file details for debugging
                \Log::info('Profile photo upload attempt', [
                    'user_id' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                    'file_extension' => $file->getClientOriginalExtension()
                ]);
                
                // Validate file
                if (!$file->isValid()) {
                    throw new \Exception('Uploaded file is not valid');
                }
                
                // Check file size (2MB limit)
                if ($file->getSize() > 2097152) {
                    throw new \Exception('File size exceeds 2MB limit');
                }
                
                // Check file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    throw new \Exception('File type not allowed. Only JPEG, PNG, GIF, and WebP are supported.');
                }
                
                // Delete old profile photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                    \Log::info('Deleted old profile photo', ['path' => $user->profile_photo_path]);
                }
                
                // Generate unique filename
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = 'profile-photos/' . $filename;
                
                // Ensure directory exists
                $fullPath = storage_path('app/public/profile-photos');
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0755, true);
                    \Log::info('Created profile-photos directory', ['path' => $fullPath]);
                }
                
                // Store the file
                $storedPath = $file->storeAs('profile-photos', $filename, 'public');
                
                if (!$storedPath) {
                    throw new \Exception('Failed to store file to disk');
                }
                
                // Verify file was actually stored
                if (!Storage::disk('public')->exists($storedPath)) {
                    throw new \Exception('File was not found after storage operation');
                }
                
                $data['profile_photo_path'] = $path;
                
                \Log::info('Profile photo uploaded successfully', [
                    'user_id' => $user->id,
                    'stored_path' => $storedPath,
                    'final_path' => $path
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Profile photo upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return Redirect::route('profile.edit')->withErrors([
                    'profile_photo' => 'Failed to upload profile photo: ' . $e->getMessage()
                ])->withInput();
            }
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Clear any cached user data
        Auth::login($user->fresh());

        // Check if profile photo was uploaded
        $message = 'profile-updated';
        if ($request->hasFile('profile_photo') && isset($data['profile_photo_path'])) {
            $message = 'profile-photo-updated';
        }
        
        return Redirect::route('profile.edit')->with('status', $message);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('home');
    }
}