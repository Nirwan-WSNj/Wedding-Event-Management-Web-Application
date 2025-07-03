<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PackageController extends Controller
{
    /**
     * Get all packages with statistics
     */
    public function index(Request $request)
    {
        try {
            $query = Package::query();

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            if ($request->filled('price_range')) {
                $range = $request->price_range;
                if ($range === '0-100000') {
                    $query->where('price', '<', 100000);
                } elseif ($range === '100000-200000') {
                    $query->whereBetween('price', [100000, 200000]);
                } elseif ($range === '200000-300000') {
                    $query->whereBetween('price', [200000, 300000]);
                } elseif ($range === '300000+') {
                    $query->where('price', '>', 300000);
                }
            }

            if ($request->filled('highlight')) {
                $query->where('highlight', $request->highlight);
            }

            $packages = $query->orderBy('created_at', 'desc')->get();

            // Add computed fields
            $packages = $packages->map(function($package) {
                $bookingsCount = $package->bookings()->count();
                $totalRevenue = $package->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount');
                $averageRating = 4.5; // Placeholder for rating system
                
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'min_guests' => $package->min_guests ?? 50,
                    'max_guests' => $package->max_guests ?? 150,
                    'additional_guest_price' => $package->additional_guest_price ?? 2500,
                    'is_active' => $package->is_active,
                    'highlight' => $package->highlight ?? false,
                    'image' => $package->image ? asset('storage/' . $package->image) : null,
                    'features' => $package->features ? json_decode($package->features, true) : [],
                    'bookings_count' => $bookingsCount,
                    'total_revenue' => $totalRevenue,
                    'average_rating' => $averageRating,
                    'popularity_score' => $bookingsCount * 10 + $totalRevenue / 1000,
                    'created_at' => $package->created_at,
                    'updated_at' => $package->updated_at,
                ];
            });

            // Calculate stats
            $stats = [
                'total' => $packages->count(),
                'active' => $packages->where('is_active', true)->count(),
                'inactive' => $packages->where('is_active', false)->count(),
                'highlighted' => $packages->where('highlight', true)->count(),
                'total_revenue' => $packages->sum('total_revenue'),
                'most_popular' => $packages->sortByDesc('bookings_count')->first()['name'] ?? 'N/A',
                'average_price' => $packages->avg('price'),
                'price_range' => [
                    'min' => $packages->min('price'),
                    'max' => $packages->max('price')
                ]
            ];

            return response()->json([
                'success' => true,
                'packages' => $packages->values(),
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific package details
     */
    public function show($id)
    {
        try {
            $package = Package::findOrFail($id);
            
            $recentBookings = $package->bookings()
                ->with(['user', 'hall'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $monthlyRevenue = $package->bookings()
                ->where('advance_payment_paid', true)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('advance_payment_amount');

            $packageData = [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'min_guests' => $package->min_guests ?? 50,
                'max_guests' => $package->max_guests ?? 150,
                'additional_guest_price' => $package->additional_guest_price ?? 2500,
                'is_active' => $package->is_active,
                'highlight' => $package->highlight ?? false,
                'image' => $package->image ? asset('storage/' . $package->image) : null,
                'features' => $package->features ? json_decode($package->features, true) : [],
                'bookings_count' => $package->bookings()->count(),
                'total_revenue' => $package->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount'),
                'monthly_revenue' => $monthlyRevenue,
                'recent_bookings' => $recentBookings,
                'created_at' => $package->created_at,
                'updated_at' => $package->updated_at,
            ];

            return response()->json([
                'success' => true,
                'package' => $packageData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new package
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:packages,name',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'min_guests' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|gte:min_guests',
                'additional_guest_price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'highlight' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|min:100',
                'features' => 'nullable|array',
                'features.*' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $packageData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'min_guests' => $request->min_guests,
                'max_guests' => $request->max_guests,
                'additional_guest_price' => $request->additional_guest_price,
                'is_active' => $request->boolean('is_active', true),
                'highlight' => $request->boolean('highlight', false),
                'features' => $request->features ? json_encode($request->features) : null,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('packages', $imageName, 'public');
                $packageData['image'] = $imagePath;
            }

            $package = Package::create($packageData);

            return response()->json([
                'success' => true,
                'message' => 'Package created successfully',
                'package' => $package
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create package: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a package
     */
    public function update(Request $request, $id)
    {
        try {
            $package = Package::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:packages,name,' . $id,
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'min_guests' => 'required|integer|min:1',
                'max_guests' => 'required|integer|min:1|gte:min_guests',
                'additional_guest_price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'highlight' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|min:100',
                'features' => 'nullable|array',
                'features.*' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'min_guests' => $request->min_guests,
                'max_guests' => $request->max_guests,
                'additional_guest_price' => $request->additional_guest_price,
                'is_active' => $request->boolean('is_active', true),
                'highlight' => $request->boolean('highlight', false),
                'features' => $request->features ? json_encode($request->features) : null,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($package->image && Storage::disk('public')->exists($package->image)) {
                    Storage::disk('public')->delete($package->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('packages', $imageName, 'public');
                $updateData['image'] = $imagePath;
            }

            $package->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully',
                'package' => $package->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update package: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a package
     */
    public function destroy($id)
    {
        try {
            $package = Package::findOrFail($id);
            
            // Check if package has bookings
            $bookingsCount = $package->bookings()->count();
            if ($bookingsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete package '{$package->name}' as it has {$bookingsCount} existing bookings"
                ], 400);
            }

            // Delete image if exists
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }

            $packageName = $package->name;
            $package->delete();

            return response()->json([
                'success' => true,
                'message' => "Package '{$packageName}' deleted successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle package status
     */
    public function toggleStatus($id)
    {
        try {
            $package = Package::findOrFail($id);
            $package->is_active = !$package->is_active;
            $package->save();

            $status = $package->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Package '{$package->name}' {$status} successfully",
                'package' => $package
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle package status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions on packages
     */
    public function bulkAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:activate,deactivate,delete,highlight,unhighlight',
                'package_ids' => 'required|array|min:1',
                'package_ids.*' => 'integer|exists:packages,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $action = $request->action;
            $packageIds = $request->package_ids;
            $packages = Package::whereIn('id', $packageIds)->get();

            $results = [];

            foreach ($packages as $package) {
                try {
                    switch ($action) {
                        case 'activate':
                            $package->is_active = true;
                            $package->save();
                            $results[] = "Package '{$package->name}' activated";
                            break;
                        
                        case 'deactivate':
                            $package->is_active = false;
                            $package->save();
                            $results[] = "Package '{$package->name}' deactivated";
                            break;
                        
                        case 'highlight':
                            $package->highlight = true;
                            $package->save();
                            $results[] = "Package '{$package->name}' highlighted";
                            break;
                        
                        case 'unhighlight':
                            $package->highlight = false;
                            $package->save();
                            $results[] = "Package '{$package->name}' unhighlighted";
                            break;
                        
                        case 'delete':
                            if ($package->bookings()->count() > 0) {
                                $results[] = "Cannot delete package '{$package->name}' - has existing bookings";
                            } else {
                                if ($package->image && Storage::disk('public')->exists($package->image)) {
                                    Storage::disk('public')->delete($package->image);
                                }
                                $package->delete();
                                $results[] = "Package '{$package->name}' deleted";
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $results[] = "Failed to {$action} package '{$package->name}': " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get package statistics
     */
    public function getStats()
    {
        try {
            $totalPackages = Package::count();
            $activePackages = Package::where('is_active', true)->count();
            $highlightedPackages = Package::where('highlight', true)->count();
            $totalBookings = Booking::count();
            $totalRevenue = Booking::where('advance_payment_paid', true)->sum('advance_payment_amount');
            
            $mostPopularPackage = Booking::join('packages', 'bookings.package_id', '=', 'packages.id')
                ->select('packages.name', \DB::raw('COUNT(*) as booking_count'))
                ->groupBy('packages.id', 'packages.name')
                ->orderBy('booking_count', 'desc')
                ->first();

            $averagePrice = Package::avg('price');
            $priceRange = [
                'min' => Package::min('price'),
                'max' => Package::max('price')
            ];

            $monthlyStats = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyBookings = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $monthlyRevenue = Booking::where('advance_payment_paid', true)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('advance_payment_amount');
                
                $monthlyStats[] = [
                    'month' => $date->format('M Y'),
                    'bookings' => $monthlyBookings,
                    'revenue' => $monthlyRevenue
                ];
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_packages' => $totalPackages,
                    'active_packages' => $activePackages,
                    'inactive_packages' => $totalPackages - $activePackages,
                    'highlighted_packages' => $highlightedPackages,
                    'total_bookings' => $totalBookings,
                    'total_revenue' => $totalRevenue,
                    'most_popular_package' => $mostPopularPackage ? $mostPopularPackage->name : 'N/A',
                    'average_price' => round($averagePrice, 2),
                    'price_range' => $priceRange,
                    'monthly_trends' => $monthlyStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get package statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}