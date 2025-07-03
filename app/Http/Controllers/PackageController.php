<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of packages for admin
     */
    public function index()
    {
        try {
            $packages = Package::orderBy('created_at', 'desc')->get();
            
            // Transform packages for display
            $packages = $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'base_price' => $package->price,
                    'image_path' => $package->image ? 'storage/packages/' . $package->image : 'images/default-package.jpg',
                    'is_active' => $package->is_active ?? true,
                    'highlight_features' => $package->features ?? [],
                    'highlight' => $package->highlight ?? false,
                    'guest_capacity_min' => 50, // Default values - can be added to database later
                    'guest_capacity_max' => 300,
                    'created_at' => $package->created_at,
                    'updated_at' => $package->updated_at,
                ];
            });

            // Redirect to dashboard since packages are now integrated there
            return redirect()->route('admin.dashboard')->with('info', 'Package management is now integrated in the main dashboard. Click "Packages" in the sidebar.');
        } catch (\Exception $e) {
            Log::error('Error loading packages: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Error loading packages. Please try again.');
        }
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'min_guests' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|gte:min_guests',
                'additional_guest_price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|min:100|max:2048',
                'features' => 'nullable|array',
                'highlight' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $packageData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'min_guests' => $request->min_guests,
                'max_guests' => $request->max_guests,
                'additional_guest_price' => $request->additional_guest_price,
                'highlight' => $request->boolean('highlight', false),
                'features' => $request->features ?? [],
                'is_active' => true, // Set new packages as active by default
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                // Store in public/storage/packages directory
                $image->storeAs('public/packages', $imageName);
                $packageData['image'] = $imageName;
            }

            $package = Package::create($packageData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package created successfully',
                    'package' => $package
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Package created successfully. View it in the Packages section.');

        } catch (\Exception $e) {
            Log::error('Error creating package: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create package: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create package: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified package
     */
    public function show(Package $package)
    {
        try {
            // Get package with related data
            $packageData = [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'image' => $package->image,
                'image_url' => $package->image ? asset('storage/packages/' . $package->image) : asset('images/default-package.jpg'),
                'features' => $package->features ?? [],
                'highlight' => $package->highlight ?? false,
                'is_active' => $package->is_active ?? true,
                'bookings_count' => $package->bookings()->count(),
                'total_revenue' => $package->bookings()->where('status', 'confirmed')->sum('total_amount'),
                'created_at' => $package->created_at,
                'updated_at' => $package->updated_at,
            ];

            // Get recent bookings for this package
            $recentBookings = $package->bookings()
                ->with(['user', 'hall'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Redirect to dashboard since package details are now in modal
            return redirect()->route('admin.dashboard')->with('info', 'Package details are now available in the dashboard. Click "Packages" in the sidebar and use "View Details".');
        } catch (\Exception $e) {
            Log::error('Error showing package: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Package not found.');
        }
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(Package $package)
    {
        try {
            // Always return JSON for AJAX requests (dashboard integration)
            return response()->json([
                'success' => true,
                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'min_guests' => $package->min_guests ?? 50,
                    'max_guests' => $package->max_guests ?? 150,
                    'additional_guest_price' => $package->additional_guest_price ?? 2500,
                    'image' => $package->image,
                    'image_url' => $package->image ? asset('storage/packages/' . $package->image) : null,
                    'features' => $package->features ?? [],
                    'highlight' => $package->highlight ?? false,
                    'is_active' => $package->is_active ?? true,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing package: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Package not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, Package $package)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'min_guests' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|gte:min_guests',
                'additional_guest_price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|min:100|max:2048',
                'features' => 'nullable|array',
                'highlight' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $packageData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'min_guests' => $request->min_guests,
                'max_guests' => $request->max_guests,
                'additional_guest_price' => $request->additional_guest_price,
                'highlight' => $request->boolean('highlight', false),
                'features' => $request->features ?? [],
                'is_active' => $request->boolean('is_active', true), // Maintain active status
            ];

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($package->image) {
                    Storage::delete('public/packages/' . $package->image);
                    $packageData['image'] = null;
                }
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($package->image) {
                    Storage::delete('public/packages/' . $package->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                $image->storeAs('public/packages', $imageName);
                $packageData['image'] = $imageName;
            }

            $package->update($packageData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package updated successfully',
                    'package' => $package->fresh()
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Package updated successfully. View changes in the Packages section.');

        } catch (\Exception $e) {
            Log::error('Error updating package: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update package: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update package: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified package
     */
    public function destroy(Request $request, Package $package)
    {
        try {
            // Check if package has active bookings
            $activeBookings = $package->bookings()
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('event_date', '>=', now())
                ->count();

            if ($activeBookings > 0) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot delete package. It has {$activeBookings} active booking(s)."
                    ], 400);
                }
                return back()->with('error', "Cannot delete package. It has {$activeBookings} active booking(s).");
            }

            $packageName = $package->name;

            // Delete image if exists
            if ($package->image) {
                Storage::delete('public/packages/' . $package->image);
            }

            $package->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Package '{$packageName}' deleted successfully"
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Package deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting package: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete package: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }

    /**
     * Toggle package status (active/inactive)
     */
    public function toggleStatus(Request $request, Package $package)
    {
        try {
            $package->update([
                'is_active' => !($package->is_active ?? true)
            ]);

            $status = $package->is_active ? 'activated' : 'deactivated';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Package {$status} successfully",
                    'is_active' => $package->is_active
                ]);
            }

            return back()->with('success', "Package {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('Error toggling package status: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update package status'
                ], 500);
            }
            
            return back()->with('error', 'Failed to update package status.');
        }
    }

    /**
     * Calculate package price with customizations
     */
    public function calculatePrice(Request $request, Package $package)
    {
        try {
            $guestCount = $request->input('guest_count', 100);
            $additionalServices = $request->input('additional_services', []);

            $totalPrice = $package->calculateTotalPrice($guestCount, $additionalServices);

            return response()->json([
                'success' => true,
                'base_price' => $package->price,
                'total_price' => $totalPrice,
                'guest_count' => $guestCount,
                'breakdown' => [
                    'package_price' => $package->price,
                    'guest_additional' => max(0, ($guestCount - 100) * 2500), // Example calculation
                    'services_total' => 0, // Calculate based on additional services
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating package price: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate price'
            ], 500);
        }
    }

    /**
     * Get packages data for API/AJAX requests
     */
    public function getPackagesData()
    {
        try {
            $packages = Package::orderBy('created_at', 'desc')->get();
            
            $packagesData = $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'image' => $package->image,
                    'image_url' => $package->image ? asset('storage/packages/' . $package->image) : asset('images/default-package.jpg'),
                    'features' => $package->features ?? [],
                    'highlight' => $package->highlight ?? false,
                    'is_active' => $package->is_active ?? true,
                    'bookings_count' => $package->bookings()->count(),
                    'created_at' => $package->created_at,
                    'updated_at' => $package->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'packages' => $packagesData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting packages data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages data'
            ], 500);
        }
    }

    /**
     * Get packages data specifically for admin dashboard
     */
    public function getAdminPackagesData()
    {
        try {
            $packages = Package::orderBy('created_at', 'desc')->get();
            
            $packagesData = $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'image' => $package->image ? asset('storage/packages/' . $package->image) : asset('images/default-package.jpg'),
                    'image_filename' => $package->image,
                    'features' => $package->features ?? [],
                    'highlight' => $package->highlight ?? false,
                    'is_active' => $package->is_active ?? true,
                    'bookings_count' => $package->bookings()->count(),
                    'total_revenue' => $package->bookings()->where('status', 'confirmed')->sum('total_amount'),
                    'created_at' => $package->created_at,
                    'updated_at' => $package->updated_at,
                ];
            });

            // Calculate statistics
            $stats = [
                'total' => $packages->count(),
                'active' => $packages->where('is_active', true)->count(),
                'popular' => $packagesData->sortByDesc('bookings_count')->first(),
                'total_revenue' => $packagesData->sum('total_revenue'),
            ];

            return response()->json([
                'success' => true,
                'packages' => $packagesData->values(),
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting admin packages data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages data'
            ], 500);
        }
    }

    /**
     * Get package details for API (dashboard modal)
     */
    public function getPackageDetails(Package $package)
    {
        try {
            $packageData = [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'image' => $package->image ? asset('storage/packages/' . $package->image) : null,
                'features' => $package->features ?? [],
                'highlight' => $package->highlight ?? false,
                'is_active' => $package->is_active ?? true,
                'bookings_count' => $package->bookings()->count(),
                'total_revenue' => $package->bookings()->where('status', 'confirmed')->sum('total_amount'),
                'created_at' => $package->created_at,
                'updated_at' => $package->updated_at,
            ];

            // Get recent bookings for this package
            $recentBookings = $package->bookings()
                ->with(['user', 'hall'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($booking) {
                    return [
                        'customer_name' => $booking->user->name ?? $booking->contact_name ?? 'Unknown',
                        'event_date' => $booking->event_date ? $booking->event_date->format('M d, Y') : 'Date TBD',
                        'amount' => $booking->total_amount ?? $booking->advance_payment_amount ?? 0,
                    ];
                });

            $packageData['recent_bookings'] = $recentBookings;

            return response()->json([
                'success' => true,
                'package' => $packageData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting package details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load package details'
            ], 500);
        }
    }

    /**
     * Get packages for public display (customer-facing)
     */
    public function getPublicPackages()
    {
        try {
            // Custom order: Basic, Golden, Infinity
            $packages = Package::where('is_active', true)
                ->orderByRaw("CASE 
                    WHEN name = 'Basic Package' THEN 1 
                    WHEN name = 'Golden Package' THEN 2 
                    WHEN name = 'Infinity Package' THEN 3 
                    ELSE 4 
                END")
                ->get();

            // If this is an API request, return JSON
            if (request()->expectsJson()) {
                $packagesData = $packages->map(function($package) {
                    return [
                        'id' => $package->id,
                        'name' => $package->name,
                        'description' => $package->description,
                        'price' => $package->price,
                        'image' => $package->image ? asset('storage/packages/' . $package->image) : asset('images/default-package.jpg'),
                        'features' => $package->features ?? [],
                        'highlight' => $package->highlight ?? false,
                    ];
                });

                return response()->json([
                    'success' => true,
                    'packages' => $packagesData
                ]);
            }

            // For web requests, return the view with packages data
            return view('packages', compact('packages'));

        } catch (\Exception $e) {
            Log::error('Error getting public packages: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load packages'
                ], 500);
            }
            
            // Return view with empty packages on error
            return view('packages', ['packages' => collect()]);
        }
    }
}