<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Hall;
use App\Models\Package;
use App\Models\WeddingType;
use App\Models\Decoration;
use App\Models\CateringMenu;
use App\Models\CateringItem;
use App\Models\AdditionalService;
use App\Models\BookingPayment;
use App\Models\BookingDecoration;
use App\Models\BookingCateringItem;
use App\Models\BookingAdditionalService;
use App\Models\VisitSchedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with real data
     */
    public function dashboard()
    {
        // Comprehensive statistics from real WMdemo database
        $stats = [
            // Core booking statistics
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            
            // Revenue statistics
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_amount') ?? 0,
            'monthly_revenue' => Booking::where('status', 'confirmed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount') ?? 0,
            'yearly_revenue' => Booking::where('status', 'confirmed')
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount') ?? 0,
            
            // User statistics
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_managers' => User::where('role', 'manager')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'new_users_this_week' => User::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'new_users_this_month' => User::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            
            // Venue and service statistics
            'total_halls' => Hall::count(),
            'active_halls' => $this->getActiveHallsCount(),
            'total_wedding_types' => WeddingType::count(),
            'total_decorations' => Decoration::count(),
            'total_catering_items' => CateringItem::count(),
            'total_additional_services' => AdditionalService::count(),
            
            // Recent activity
            'bookings_today' => Booking::whereDate('created_at', Carbon::today())->count(),
            'bookings_this_week' => Booking::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'bookings_this_month' => Booking::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            
            // Payment statistics
            'total_payments' => BookingPayment::where('status', 'completed')->sum('amount') ?? 0,
            'pending_payments' => BookingPayment::where('status', 'pending')->sum('amount') ?? 0,
            'payment_count' => BookingPayment::count(),
            
            // Popular services
            'most_booked_hall' => $this->getMostBookedHall(),
            'average_booking_value' => $this->getAverageBookingValue(),
        ];

        // Recent bookings with full relationships
        $recent_bookings = Booking::with(['user', 'hall', 'package', 'weddingType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent users with role information
        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent payments
        $recent_payments = BookingPayment::with(['booking.user', 'booking.hall'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Upcoming events
        $upcoming_events = Booking::with(['user', 'hall'])
            ->where('status', 'confirmed')
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        // Monthly booking trends (last 6 months)
        $booking_trends = $this->getBookingTrends();

        // Revenue trends (last 6 months)
        $revenue_trends = $this->getRevenueTrends();

        // Popular wedding types
        $popular_wedding_types = $this->getPopularWeddingTypes();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_bookings', 
            'recent_users', 
            'recent_payments',
            'upcoming_events',
            'booking_trends',
            'revenue_trends',
            'popular_wedding_types'
        ));
    }

    /**
     * Display users management page
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Get users data for AJAX requests
     */
    public function getUsersData(Request $request)
    {
        try {
            $query = User::query();

            // Apply filters
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            // Add computed fields
            $users = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'status' => $user->status ?? 'active',
                    'profile_photo_path' => $user->profile_photo_path,
                    'profile_photo_url' => $user->profile_photo_url,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:customer,manager,admin',
                'password' => 'required|string|min:8|confirmed',
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

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'status' => 'active'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show edit user form
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:customer,manager,admin',
                'password' => 'nullable|string|min:8|confirmed',
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

            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user->fresh()
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function deleteUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot delete your own account.'
                    ], 403);
                }
                return back()->with('error', 'You cannot delete your own account.');
            }

            $userName = $user->full_name;
            $user->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "User '{$userName}' deleted successfully"
                ]);
            }

            return redirect()->route('admin.users')->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Get halls data for admin dashboard
     */
    public function getHalls(Request $request)
    {
        try {
            $query = Hall::query();

            // Apply filters if provided
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

            $halls = $query->orderBy('created_at', 'desc')->get();

            // Add computed fields
            $halls = $halls->map(function($hall) {
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'is_active' => $hall->is_active,
                    'image' => $hall->image ? asset('storage/' . $hall->image) : null,
                    'bookings_count' => $hall->bookings()->count(),
                    'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                    'created_at' => $hall->created_at,
                    'updated_at' => $hall->updated_at,
                ];
            });

            // Calculate stats
            $stats = [
                'total' => $halls->count(),
                'active' => $halls->where('is_active', true)->count(),
                'total_revenue' => $halls->sum('total_revenue'),
                'most_popular' => $halls->sortByDesc('bookings_count')->first()['name'] ?? 'N/A',
                'booked_today' => Booking::whereDate('created_at', Carbon::today())->count(),
            ];

            return response()->json([
                'success' => true,
                'halls' => $halls,
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
     * Get hall details for admin dashboard
     */
    public function getHallDetails($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            $hallData = [
                'id' => $hall->id,
                'name' => $hall->name,
                'description' => $hall->description,
                'capacity' => $hall->capacity,
                'price' => $hall->price,
                'is_active' => $hall->is_active,
                'image' => $hall->image ? asset('storage/' . $hall->image) : null,
                'bookings_count' => $hall->bookings()->count(),
                'total_revenue' => $hall->bookings()->where('status', 'confirmed')->sum('total_amount'),
                'recent_bookings' => $hall->bookings()->with('user')->latest()->limit(5)->get(),
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
    public function createHall(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
    public function updateHall(Request $request, $id)
    {
        try {
            $hall = Hall::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'capacity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
    public function deleteHall($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            // Check if hall has bookings
            if ($hall->bookings()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete hall with existing bookings'
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
     * Export users data
     */
    public function exportUsers()
    {
        try {
            $users = User::orderBy('created_at', 'desc')->get();
            
            $csvData = "ID,First Name,Last Name,Email,Phone,Role,Status,Created At\n";
            
            foreach ($users as $user) {
                $csvData .= sprintf(
                    "%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $user->id,
                    $user->first_name,
                    $user->last_name,
                    $user->email,
                    $user->phone ?? '',
                    $user->role,
                    $user->status ?? 'active',
                    $user->created_at->format('Y-m-d H:i:s')
                );
            }

            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="users_export_' . date('Y-m-d_H-i-s') . '.csv"');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export users: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getDashboardStats()
    {
        return response()->json([
            // Core statistics
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            
            // Revenue
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_amount') ?? 0,
            'monthly_revenue' => Booking::where('status', 'confirmed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount') ?? 0,
            
            // Users
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_managers' => User::where('role', 'manager')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'new_users_this_week' => User::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            
            // Services
            'active_halls' => $this->getActiveHallsCount(),
            'total_wedding_types' => WeddingType::count(),
            
            // Activity
            'bookings_today' => Booking::whereDate('created_at', Carbon::today())->count(),
            'bookings_this_week' => Booking::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'bookings_this_month' => Booking::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            
            // Analytics
            'most_booked_hall' => $this->getMostBookedHall(),
            'average_booking_value' => round($this->getAverageBookingValue(), 2),
            
            // Real-time data
            'last_updated' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get active halls count with fallback
     */
    private function getActiveHallsCount()
    {
        try {
            return Hall::count(); // All halls are considered active in this system
        } catch (\Exception $e) {
            // Fallback if Hall model/table doesn't exist or has issues
            return 15;
        }
    }

    /**
     * Get most booked hall
     */
    private function getMostBookedHall()
    {
        try {
            $hall = Booking::select('hall_name', DB::raw('COUNT(*) as booking_count'))
                ->groupBy('hall_name')
                ->orderBy('booking_count', 'desc')
                ->first();
            
            return $hall ? $hall->hall_name : 'No data';
        } catch (\Exception $e) {
            return 'No data';
        }
    }

    /**
     * Get most popular package
     */
    private function getMostPopularPackage()
    {
        try {
            $package = Booking::select('package_name', DB::raw('COUNT(*) as booking_count'))
                ->whereNotNull('package_name')
                ->groupBy('package_name')
                ->orderBy('booking_count', 'desc')
                ->first();
            
            return $package ? $package->package_name : 'No data';
        } catch (\Exception $e) {
            return 'No data';
        }
    }

    /**
     * Get average booking value
     */
    private function getAverageBookingValue()
    {
        try {
            return Booking::where('status', 'confirmed')
                ->whereNotNull('total_amount')
                ->avg('total_amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get booking trends for last 6 months
     */
    private function getBookingTrends()
    {
        try {
            $trends = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $count = Booking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $trends[] = [
                    'month' => $date->format('M Y'),
                    'count' => $count
                ];
            }
            return $trends;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get revenue trends for last 6 months
     */
    private function getRevenueTrends()
    {
        try {
            $trends = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $revenue = Booking::where('status', 'confirmed')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount') ?? 0;
                
                $trends[] = [
                    'month' => $date->format('M Y'),
                    'revenue' => $revenue
                ];
            }
            return $trends;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get popular wedding types
     */
    private function getPopularWeddingTypes()
    {
        try {
            return Booking::join('wedding_types', 'bookings.wedding_type_id', '=', 'wedding_types.id')
                ->select('wedding_types.name', DB::raw('COUNT(*) as booking_count'))
                ->groupBy('wedding_types.id', 'wedding_types.name')
                ->orderBy('booking_count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get comprehensive dashboard analytics
     */
    public function getAnalytics()
    {
        try {
            return [
                'daily_bookings' => $this->getDailyBookings(),
                'hall_utilization' => $this->getHallUtilization(),
                'package_performance' => $this->getPackagePerformance(),
                'customer_demographics' => $this->getCustomerDemographics(),
                'seasonal_trends' => $this->getSeasonalTrends(),
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get daily bookings for current month
     */
    private function getDailyBookings()
    {
        try {
            return Booking::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get hall utilization statistics
     */
    private function getHallUtilization()
    {
        try {
            return Booking::join('halls', 'bookings.hall_id', '=', 'halls.id')
                ->select('halls.name', DB::raw('COUNT(*) as bookings'), 'halls.capacity')
                ->groupBy('halls.id', 'halls.name', 'halls.capacity')
                ->orderBy('bookings', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get package performance
     */
    private function getPackagePerformance()
    {
        try {
            return Booking::join('packages', 'bookings.package_id', '=', 'packages.id')
                ->select('packages.name', 
                    DB::raw('COUNT(*) as bookings'),
                    DB::raw('SUM(bookings.total_amount) as revenue'),
                    DB::raw('AVG(bookings.total_amount) as avg_value'))
                ->groupBy('packages.id', 'packages.name')
                ->orderBy('revenue', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get customer demographics
     */
    private function getCustomerDemographics()
    {
        try {
            return [
                'by_role' => User::select('role', DB::raw('COUNT(*) as count'))
                    ->groupBy('role')
                    ->get(),
                'new_registrations' => User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get seasonal booking trends
     */
    private function getSeasonalTrends()
    {
        try {
            return Booking::selectRaw('MONTH(event_date) as month, COUNT(*) as count')
                ->whereNotNull('event_date')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    $item->month_name = Carbon::create()->month($item->month)->format('F');
                    return $item;
                });
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,cancelled,completed',
                'notes' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $oldStatus = $booking->status;
            $booking->status = $request->status;
            
            if ($request->notes) {
                $booking->workflow_notes = $request->notes;
            }
            
            $booking->save();
            
            Log::info('Booking status updated by admin', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully',
                'booking' => $booking->load(['user', 'hall', 'package'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating booking status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
