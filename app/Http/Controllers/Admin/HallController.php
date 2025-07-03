<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HallController extends Controller
{
    /**
     * Get all halls with statistics
     */
    public function index(Request $request)
    {
        try {
            $query = Hall::query();

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('capacity')) {
                $capacity = $request->capacity;
                if ($capacity === '0-100') {
                    $query->where('capacity', '<', 100);
                } elseif ($capacity === '100-300') {
                    $query->whereBetween('capacity', [100, 300]);
                } elseif ($capacity === '300-500') {
                    $query->whereBetween('capacity', [300, 500]);
                } elseif ($capacity === '500+') {
                    $query->where('capacity', '>', 500);
                }
            }

            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            if ($request->filled('price_range')) {
                $range = $request->price_range;
                if ($range === '0-50000') {
                    $query->where('price', '<', 50000);
                } elseif ($range === '50000-100000') {
                    $query->whereBetween('price', [50000, 100000]);
                } elseif ($range === '100000-200000') {
                    $query->whereBetween('price', [100000, 200000]);
                } elseif ($range === '200000+') {
                    $query->where('price', '>', 200000);
                }
            }

            $halls = $query->orderBy('created_at', 'desc')->get();

            // Add computed fields
            $halls = $halls->map(function($hall) {
                $bookingsCount = $hall->bookings()->count();
                $totalRevenue = $hall->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount');
                $upcomingBookings = $hall->bookings()->where('event_date', '>=', Carbon::today())->count();
                
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'is_active' => $hall->is_active,
                    'image' => $hall->image ? asset('storage/' . $hall->image) : null,
                    'bookings_count' => $bookingsCount,
                    'total_revenue' => $totalRevenue,
                    'upcoming_bookings' => $upcomingBookings,
                    'availability_status' => $upcomingBookings > 0 ? 'busy' : 'available',
                    'created_at' => $hall->created_at,
                    'updated_at' => $hall->updated_at,
                ];
            });

            // Calculate stats
            $stats = [
                'total' => $halls->count(),
                'active' => $halls->where('is_active', true)->count(),
                'inactive' => $halls->where('is_active', false)->count(),
                'total_revenue' => $halls->sum('total_revenue'),
                'most_popular' => $halls->sortByDesc('bookings_count')->first()['name'] ?? 'N/A',
                'booked_today' => Booking::whereDate('created_at', Carbon::today())->count(),
                'average_capacity' => $halls->avg('capacity'),
                'average_price' => $halls->avg('price'),
            ];

            return response()->json([
                'success' => true,
                'halls' => $halls->values(),
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load halls: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific hall details
     */
    public function show($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            $recentBookings = $hall->bookings()
                ->with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $upcomingEvents = $hall->bookings()
                ->where('event_date', '>=', Carbon::today())
                ->where('advance_payment_paid', true)
                ->orderBy('event_date', 'asc')
                ->limit(5)
                ->get();

            $monthlyRevenue = $hall->bookings()
                ->where('advance_payment_paid', true)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('advance_payment_amount');

            $hallData = [
                'id' => $hall->id,
                'name' => $hall->name,
                'description' => $hall->description,
                'capacity' => $hall->capacity,
                'price' => $hall->price,
                'is_active' => $hall->is_active,
                'image' => $hall->image ? asset('storage/' . $hall->image) : null,
                'features' => $hall->features ? json_decode($hall->features, true) : [],
                'bookings_count' => $hall->bookings()->count(),
                'total_revenue' => $hall->bookings()->where('advance_payment_paid', true)->sum('advance_payment_amount'),
                'monthly_revenue' => $monthlyRevenue,
                'recent_bookings' => $recentBookings,
                'upcoming_events' => $upcomingEvents,
                'created_at' => $hall->created_at,
                'updated_at' => $hall->updated_at,
            ];

            return response()->json([
                'success' => true,
                'hall' => $hallData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hall not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new hall
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:halls,name',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1|max:2000',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $hallData = [
                'name' => $request->name,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'price' => $request->price,
                'is_active' => $request->boolean('is_active', true),
                'features' => $request->features ? json_encode($request->features) : null,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('halls', $imageName, 'public');
                $hallData['image'] = $imagePath;
            }

            $hall = Hall::create($hallData);

            return response()->json([
                'success' => true,
                'message' => 'Hall created successfully',
                'hall' => $hall
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create hall: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a hall
     */
    public function update(Request $request, $id)
    {
        try {
            $hall = Hall::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:halls,name,' . $id,
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1|max:2000',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'capacity' => $request->capacity,
                'price' => $request->price,
                'is_active' => $request->boolean('is_active', true),
                'features' => $request->features ? json_encode($request->features) : null,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($hall->image && Storage::disk('public')->exists($hall->image)) {
                    Storage::disk('public')->delete($hall->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('halls', $imageName, 'public');
                $updateData['image'] = $imagePath;
            }

            $hall->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Hall updated successfully',
                'hall' => $hall->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update hall: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a hall
     */
    public function destroy($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            // Check if hall has bookings
            $bookingsCount = $hall->bookings()->count();
            if ($bookingsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete hall '{$hall->name}' as it has {$bookingsCount} existing bookings"
                ], 400);
            }

            // Delete image if exists
            if ($hall->image && Storage::disk('public')->exists($hall->image)) {
                Storage::disk('public')->delete($hall->image);
            }

            $hallName = $hall->name;
            $hall->delete();

            return response()->json([
                'success' => true,
                'message' => "Hall '{$hallName}' deleted successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hall: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle hall status
     */
    public function toggleStatus($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            $hall->is_active = !$hall->is_active;
            $hall->save();

            $status = $hall->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Hall '{$hall->name}' {$status} successfully",
                'hall' => $hall
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle hall status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions on halls
     */
    public function bulkAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:activate,deactivate,delete',
                'hall_ids' => 'required|array|min:1',
                'hall_ids.*' => 'integer|exists:halls,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $action = $request->action;
            $hallIds = $request->hall_ids;
            $halls = Hall::whereIn('id', $hallIds)->get();

            $results = [];

            foreach ($halls as $hall) {
                try {
                    switch ($action) {
                        case 'activate':
                            $hall->is_active = true;
                            $hall->save();
                            $results[] = "Hall '{$hall->name}' activated";
                            break;
                        
                        case 'deactivate':
                            $hall->is_active = false;
                            $hall->save();
                            $results[] = "Hall '{$hall->name}' deactivated";
                            break;
                        
                        case 'delete':
                            if ($hall->bookings()->count() > 0) {
                                $results[] = "Cannot delete hall '{$hall->name}' - has existing bookings";
                            } else {
                                if ($hall->image && Storage::disk('public')->exists($hall->image)) {
                                    Storage::disk('public')->delete($hall->image);
                                }
                                $hall->delete();
                                $results[] = "Hall '{$hall->name}' deleted";
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $results[] = "Failed to {$action} hall '{$hall->name}': " . $e->getMessage();
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
     * Get hall statistics
     */
    public function getStats()
    {
        try {
            $totalHalls = Hall::count();
            $activeHalls = Hall::where('is_active', true)->count();
            $totalBookings = Booking::count();
            $totalRevenue = Booking::where('advance_payment_paid', true)->sum('advance_payment_amount');
            
            $mostPopularHall = Booking::select('hall_name', \DB::raw('COUNT(*) as booking_count'))
                ->groupBy('hall_name')
                ->orderBy('booking_count', 'desc')
                ->first();

            $averageCapacity = Hall::avg('capacity');
            $averagePrice = Hall::avg('price');

            $monthlyStats = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyBookings = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $monthlyStats[] = [
                    'month' => $date->format('M Y'),
                    'bookings' => $monthlyBookings
                ];
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_halls' => $totalHalls,
                    'active_halls' => $activeHalls,
                    'inactive_halls' => $totalHalls - $activeHalls,
                    'total_bookings' => $totalBookings,
                    'total_revenue' => $totalRevenue,
                    'most_popular_hall' => $mostPopularHall ? $mostPopularHall->hall_name : 'N/A',
                    'average_capacity' => round($averageCapacity, 0),
                    'average_price' => round($averagePrice, 2),
                    'monthly_trends' => $monthlyStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get hall statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get hall availability
     */
    public function getAvailability($id, Request $request)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->addMonths(3)->format('Y-m-d'));

            $bookedDates = Booking::where('hall_id', $id)
                ->where('advance_payment_paid', true)
                ->whereBetween('event_date', [$startDate, $endDate])
                ->pluck('event_date')
                ->toArray();

            $visitDates = Booking::where('hall_id', $id)
                ->where('visit_submitted', true)
                ->whereBetween('visit_date', [$startDate, $endDate])
                ->pluck('visit_date')
                ->toArray();

            return response()->json([
                'success' => true,
                'hall' => [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'capacity' => $hall->capacity
                ],
                'availability' => [
                    'booked_dates' => $bookedDates,
                    'visit_dates' => $visitDates,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get hall availability: ' . $e->getMessage()
            ], 500);
        }
    }
}