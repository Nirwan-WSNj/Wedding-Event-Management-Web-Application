<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// Debug route for profile upload
Route::post('/debug-profile-upload', function (Request $request) {
    Log::info('=== DEBUG PROFILE UPLOAD START ===');
    
    try {
        // Log all request data
        Log::info('Request method: ' . $request->method());
        Log::info('Content type: ' . $request->header('Content-Type'));
        Log::info('Has file: ' . ($request->hasFile('profile_photo') ? 'Yes' : 'No'));
        Log::info('All input keys: ' . implode(', ', array_keys($request->all())));
        
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            Log::info('File details: ', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'valid' => $file->isValid(),
                'error' => $file->getError()
            ]);
            
            // Try to store the file
            $user = $request->user();
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            Log::info('File stored successfully: ' . $path);
            
            // Update user's profile photo path
            $user->profile_photo_path = $path;
            $user->save();
            
            Log::info('User profile updated', ['user_id' => $user->id, 'new_path' => $path]);
            
            $response = [
                'success' => true,
                'message' => 'File uploaded successfully',
                'path' => $path,
                'url' => asset('storage/' . $path)
            ];
            
            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($response);
            } else {
                return redirect()->back()->with('success', 'File uploaded successfully! Path: ' . $path);
            }
        } else {
            Log::warning('No file received in request');
            $response = [
                'success' => false,
                'message' => 'No file received',
                'files' => $request->allFiles()
            ];
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($response);
            } else {
                return redirect()->back()->with('error', 'No file received in the request');
            }
        }
        
    } catch (\Exception $e) {
        Log::error('Debug upload error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        $response = [
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ];
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($response);
        } else {
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }
})->middleware('auth')->name('debug.profile.upload');

// Simple upload test page
Route::get('/simple-upload-test', function () {
    return view('simple-upload-test');
})->name('simple.upload.test')->middleware('auth');

// Profile images test page
Route::get('/test-profile-images', function () {
    return view('test-profile-images');
})->name('test.profile.images')->middleware('auth');