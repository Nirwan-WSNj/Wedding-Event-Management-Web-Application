<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HallController extends Controller
{
    /**
     * Display a listing of halls for admin
     */
    public function index()
    {
        try {
            $halls = Hall::orderBy('created_at', 'desc')->get();
            
            // Transform halls for display
            $halls = $halls->map(function($hall) {
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'image_path' => $hall->image ? 'images/' . $hall->image : 'images/default-hall.jpg',
                    'is_active' => $hall->is_active ?? true,
                    'bookings_count' => $hall->bookings()->count(),
                    'upcoming_bookings' => $hall->bookings()->where('event_date', '>=', now())->count(),
                    'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                    'created_at' => $hall->created_at,
                    'updated_at' => $hall->updated_at,
                ];
            });

            // Redirect to dashboard since halls are now integrated there
            return redirect()->route('admin.dashboard')->with('info', 'Hall management is now integrated in the main dashboard. Click "Halls" in the sidebar.');
        } catch (\Exception $e) {
            Log::error('Error loading halls: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Error loading halls. Please try again.');
        }
    }

    /**
     * Show the form for creating a new hall
     */
    public function create()
    {
        return view('admin.halls.create');
    }

    /**
     * Store a newly created hall
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:halls,name',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
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

            $hallData = [
                'name' => $request->name,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                // Store in public/storage/halls directory
                $image->storeAs('public/halls', $imageName);
                $hallData['image'] = $imageName;
            }

            $hall = Hall::create($hallData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hall created successfully',
                    'hall' => $hall
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Hall created successfully. View it in the Halls section.');

        } catch (\Exception $e) {
            Log::error('Error creating hall: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create hall: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create hall: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified hall
     */
    public function show(Hall $hall)
    {
        try {
            // Get hall with related data
            $hallData = [
                'id' => $hall->id,
                'name' => $hall->name,
                'description' => $hall->description,
                'capacity' => $hall->capacity,
                'price' => $hall->price,
                'image' => $hall->image,
                'image_url' => $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg'),
                'is_active' => $hall->is_active ?? true,
                'bookings_count' => $hall->bookings()->count(),
                'upcoming_bookings' => $hall->bookings()->where('event_date', '>=', now())->count(),
                'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                'average_booking_value' => $hall->bookings()->where('status', 'confirmed')->avg('total_amount'),
                'created_at' => $hall->created_at,
                'updated_at' => $hall->updated_at,
            ];

            // Get recent bookings for this hall
            $recentBookings = $hall->bookings()
                ->with(['user', 'package'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get upcoming bookings
            $upcomingBookings = $hall->bookings()
                ->with(['user', 'package'])
                ->where('event_date', '>=', now())
                ->orderBy('event_date', 'asc')
                ->limit(5)
                ->get();

            // Redirect to dashboard since hall details are now in modal
            return redirect()->route('admin.dashboard')->with('info', 'Hall details are now available in the dashboard. Click "Halls" in the sidebar and use "View Details".');
        } catch (\Exception $e) {
            Log::error('Error showing hall: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Hall not found.');
        }
    }

    /**
     * Show the form for editing the specified hall
     */
    public function edit(Hall $hall)
    {
        try {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'hall' => [
                        'id' => $hall->id,
                        'name' => $hall->name,
                        'description' => $hall->description,
                        'capacity' => $hall->capacity,
                        'price' => $hall->price,
                        'image' => $hall->image,
                        'image_url' => $hall->image ? asset('storage/halls/' . $hall->image) : null,
                        'is_active' => $hall->is_active ?? true,
                    ]
                ]);
            }

            return view('admin.halls.edit', compact('hall'));
        } catch (\Exception $e) {
            Log::error('Error editing hall: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hall not found'
                ], 404);
            }
            
            return redirect()->route('admin.dashboard')->with('error', 'Hall not found.');
        }
    }

    /**
     * Update the specified hall
     */
    public function update(Request $request, Hall $hall)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:halls,name,' . $hall->id,
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
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

            $hallData = [
                'name' => $request->name,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($hall->image) {
                    Storage::delete('public/halls/' . $hall->image);
                    $hallData['image'] = null;
                }
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($hall->image) {
                    Storage::delete('public/halls/' . $hall->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                
                $image->storeAs('public/halls', $imageName);
                $hallData['image'] = $imageName;
            }

            $hall->update($hallData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hall updated successfully',
                    'hall' => $hall->fresh()
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Hall updated successfully. View changes in the Halls section.');

        } catch (\Exception $e) {
            Log::error('Error updating hall: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update hall: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update hall: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified hall
     */
    public function destroy(Request $request, Hall $hall)
    {
        try {
            // Check if hall has active bookings
            $activeBookings = $hall->bookings()
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('event_date', '>=', now())
                ->count();

            if ($activeBookings > 0) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot delete hall. It has {$activeBookings} active booking(s)."
                    ], 400);
                }
                return back()->with('error', "Cannot delete hall. It has {$activeBookings} active booking(s).");
            }

            $hallName = $hall->name;

            // Delete image if exists
            if ($hall->image) {
                Storage::delete('public/halls/' . $hall->image);
            }

            $hall->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Hall '{$hallName}' deleted successfully"
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Hall deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting hall: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete hall: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete hall: ' . $e->getMessage());
        }
    }

    /**
     * Toggle hall status (active/inactive)
     */
    public function toggleStatus(Request $request, Hall $hall)
    {
        try {
            $hall->update([
                'is_active' => !($hall->is_active ?? true)
            ]);

            $status = $hall->is_active ? 'activated' : 'deactivated';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Hall {$status} successfully",
                    'is_active' => $hall->is_active
                ]);
            }

            return back()->with('success', "Hall {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('Error toggling hall status: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update hall status'
                ], 500);
            }
            
            return back()->with('error', 'Failed to update hall status.');
        }
    }

    /**
     * Check hall availability
     */
    public function checkAvailability(Request $request, Hall $hall)
    {
        try {
            $date = $request->input('date');
            $startTime = $request->input('start_time');
            $endTime = $request->input('end_time');

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date is required'
                ], 400);
            }

            $isAvailable = $hall->isAvailable($date, $startTime, $endTime);

            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'hall_id' => $hall->id,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking hall availability: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability'
            ], 500);
        }
    }

    /**
     * Get hall bookings
     */
    public function getBookings(Request $request, Hall $hall)
    {
        try {
            $bookings = $hall->bookings()
                ->with(['user', 'package'])
                ->orderBy('event_date', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'bookings' => $bookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting hall bookings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load bookings'
            ], 500);
        }
    }

    /**
     * Get halls data for API/AJAX requests
     */
    public function getHallsData()
    {
        try {
            $halls = Hall::orderBy('created_at', 'desc')->get();
            
            $hallsData = $halls->map(function($hall) {
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'image' => $hall->image,
                    'image_url' => $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg'),
                    'is_active' => $hall->is_active ?? true,
                    'bookings_count' => $hall->bookings()->count(),
                    'upcoming_bookings' => $hall->bookings()->where('event_date', '>=', now())->count(),
                    'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                    'created_at' => $hall->created_at,
                    'updated_at' => $hall->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'halls' => $hallsData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting halls data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load halls data'
            ], 500);
        }
    }

    /**
     * Get halls data specifically for admin dashboard
     */
    public function getAdminHallsData()
    {
        try {
            $halls = Hall::orderBy('created_at', 'desc')->get();
            
            $hallsData = $halls->map(function($hall) {
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'image' => $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg'),
                    'is_active' => $hall->is_active ?? true,
                    'bookings_count' => $hall->bookings()->count(),
                    'upcoming_bookings' => $hall->bookings()->where('event_date', '>=', now())->count(),
                    'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                    'average_booking_value' => $hall->bookings()->where('status', 'confirmed')->avg('total_amount'),
                    'created_at' => $hall->created_at,
                    'updated_at' => $hall->updated_at,
                ];
            });

            // Calculate statistics
            $stats = [
                'total' => $halls->count(),
                'active' => $halls->where('is_active', true)->count(),
                'most_popular' => $hallsData->sortByDesc('bookings_count')->first(),
                'total_revenue' => $hallsData->sum('total_revenue'),
                'average_capacity' => $halls->avg('capacity'),
            ];

            return response()->json([
                'success' => true,
                'halls' => $hallsData->values(),
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting admin halls data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load halls data'
            ], 500);
        }
    }

    /**
     * Get halls for public display (customer-facing)
     */
    public function getPublicHalls()
    {
        try {
            $halls = Hall::where('is_active', true)
                ->orderBy('price', 'asc')
                ->get();

            // If this is an API request, return JSON
            if (request()->expectsJson()) {
                $hallsData = $halls->map(function($hall) {
                    return [
                        'id' => $hall->id,
                        'name' => $hall->name,
                        'description' => $hall->description,
                        'capacity' => $hall->capacity,
                        'price' => $hall->price,
                        'image' => $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg'),
                        'features' => [
                            'Air Conditioning',
                            'Sound System',
                            'Lighting',
                            'Parking Available',
                            'Catering Facilities'
                        ] // Default features - can be added to database later
                    ];
                });

                return response()->json([
                    'success' => true,
                    'halls' => $hallsData
                ]);
            }

            // For web requests, return the view with halls data
            return view('halls', compact('halls'));

        } catch (\Exception $e) {
            Log::error('Error getting public halls: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load halls'
                ], 500);
            }
            
            // Return view with empty halls on error
            return view('halls', ['halls' => collect()]);
        }
    }

    /**
     * Update hall status
     */
    public function updateStatus(Request $request, Hall $hall)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_active' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value'
                ], 422);
            }

            $hall->update(['is_active' => $request->is_active]);

            $status = $hall->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Hall {$status} successfully",
                'is_active' => $hall->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating hall status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update hall status'
            ], 500);
        }
    }
}