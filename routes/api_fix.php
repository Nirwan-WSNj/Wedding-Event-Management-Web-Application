<?php
// Temporary API fix for edit package functionality

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Fixed edit package API route
Route::get('/admin/packages/{package}/edit-fixed', function (Package $package) {
    try {
        return response()->json([
            'success' => true,
            'package' => [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'image' => $package->image,
                'image_url' => $package->image ? asset('storage/packages/' . $package->image) : null,
                'features' => $package->features ?? [],
                'highlight' => $package->highlight ?? false,
                'is_active' => $package->is_active ?? true,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Package not found: ' . $e->getMessage()
        ], 404);
    }
})->middleware(['auth', 'role:admin']);
?>