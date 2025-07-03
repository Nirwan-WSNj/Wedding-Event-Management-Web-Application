<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Package;
use App\Models\WeddingType;
use App\Models\Decoration;
use App\Models\CateringMenu;
use App\Models\CateringItem;
use App\Models\AdditionalService;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // HALL MANAGEMENT
    public function getHalls()
    {
        $halls = Hall::with(['bookings' => function($query) {
            $query->where('event_date', '>=', Carbon::today())
                  ->where('status', 'confirmed');
        }])->get();

        return response()->json([
            'success' => true,
            'halls' => $halls->map(function($hall) {
                return [
                    'id' => $hall->id,
                    'name' => $hall->name,
                    'description' => $hall->description,
                    'capacity' => $hall->capacity,
                    'price' => $hall->price,
                    'image' => $hall->image,
                    'is_active' => $hall->is_active,
                    'upcoming_bookings' => $hall->bookings->count(),
                    'created_at' => $hall->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $hall->updated_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    public function createHall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:halls,name',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:50|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('halls', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->has('features')) {
            $data['features'] = json_encode($request->features);
        }

        $hall = Hall::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Hall created successfully',
            'hall' => $hall
        ]);
    }

    public function updateHall(Request $request, $id)
    {
        $hall = Hall::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:halls,name,' . $id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:50|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($hall->image) {
                Storage::disk('public')->delete($hall->image);
            }
            $imagePath = $request->file('image')->store('halls', 'public');
            $data['image'] = $imagePath;
        }

        if ($request->has('features')) {
            $data['features'] = json_encode($request->features);
        }

        $hall->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Hall updated successfully',
            'hall' => $hall
        ]);
    }

    public function deleteHall($id)
    {
        $hall = Hall::findOrFail($id);

        // Check for upcoming bookings
        $upcomingBookings = $hall->bookings()
            ->where('event_date', '>=', Carbon::today())
            ->where('status', 'confirmed')
            ->count();

        if ($upcomingBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete hall with upcoming confirmed bookings'
            ], 422);
        }

        // Delete image
        if ($hall->image) {
            Storage::disk('public')->delete($hall->image);
        }

        $hall->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hall deleted successfully'
        ]);
    }

    // PACKAGE MANAGEMENT
    public function getPackages()
    {
        $packages = Package::with(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }])->get();

        return response()->json([
            'success' => true,
            'packages' => $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'image' => $package->image,
                    'is_active' => $package->is_active,
                    'booking_count' => $package->bookings->count(),
                    'created_at' => $package->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $package->updated_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    public function createPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:packages,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('packages', 'public');
            $data['image'] = $imagePath;
        }

        $package = Package::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'package' => $package
        ]);
    }

    public function updatePackage(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:packages,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $imagePath = $request->file('image')->store('packages', 'public');
            $data['image'] = $imagePath;
        }

        $package->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
            'package' => $package
        ]);
    }
    public function viewPackage($id)
    {
        try {
            $package = Package::with(['bookings' => function($query) {
                $query->where('status', 'confirmed');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'price' => $package->price,
                    'min_guests' => $package->min_guests,
                    'max_guests' => $package->max_guests,
                    'additional_guest_price' => $package->additional_guest_price,
                    'features' => $package->features ? (is_string($package->features) ? json_decode($package->features, true) : $package->features) : [],
                    'image' => $package->image,
                    'is_active' => $package->is_active,
                    'highlight' => $package->highlight,
                    'booking_count' => $package->bookings->count(),
                    'total_revenue' => $package->bookings->sum('package_price'),
                    'created_at' => $package->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $package->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error viewing package: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load package details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePackage($id)
    {
        $package = Package::findOrFail($id);

        // Check for upcoming bookings
        $upcomingBookings = $package->bookings()
            ->where('event_date', '>=', Carbon::today())
            ->where('status', 'confirmed')
            ->count();

        if ($upcomingBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete package with upcoming confirmed bookings'
            ], 422);
        }

        // Delete image
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }

    // WEDDING TYPE MANAGEMENT
    public function getWeddingTypes()
    {
        $weddingTypes = WeddingType::with(['decorations'])->get();

        return response()->json([
            'success' => true,
            'wedding_types' => $weddingTypes
        ]);
    }

    public function createWeddingType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:wedding_types,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $weddingType = WeddingType::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Wedding type created successfully',
            'wedding_type' => $weddingType
        ]);
    }

    public function updateWeddingType(Request $request, $id)
    {
        $weddingType = WeddingType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:wedding_types,name,' . $id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $weddingType->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Wedding type updated successfully',
            'wedding_type' => $weddingType
        ]);
    }

    public function deleteWeddingType($id)
    {
        $weddingType = WeddingType::findOrFail($id);
        $weddingType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wedding type deleted successfully'
        ]);
    }

    // DECORATION MANAGEMENT
    public function getDecorations()
    {
        $decorations = Decoration::all();

        return response()->json([
            'success' => true,
            'decorations' => $decorations
        ]);
    }

    public function createDecoration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('decorations', 'public');
            $data['image'] = $imagePath;
        }

        $decoration = Decoration::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Decoration created successfully',
            'decoration' => $decoration
        ]);
    }

    public function updateDecoration(Request $request, $id)
    {
        $decoration = Decoration::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($decoration->image) {
                Storage::disk('public')->delete($decoration->image);
            }
            $imagePath = $request->file('image')->store('decorations', 'public');
            $data['image'] = $imagePath;
        }

        $decoration->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Decoration updated successfully',
            'decoration' => $decoration
        ]);
    }

    public function deleteDecoration($id)
    {
        $decoration = Decoration::findOrFail($id);

        // Delete image
        if ($decoration->image) {
            Storage::disk('public')->delete($decoration->image);
        }

        $decoration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Decoration deleted successfully'
        ]);
    }

    // CATERING MENU MANAGEMENT
    public function getCateringMenus()
    {
        $cateringMenus = CateringMenu::with(['cateringItems'])->get();

        return response()->json([
            'success' => true,
            'catering_menus' => $cateringMenus
        ]);
    }

    public function createCateringMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price_per_person' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cateringMenu = CateringMenu::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Catering menu created successfully',
            'catering_menu' => $cateringMenu
        ]);
    }

    public function updateCateringMenu(Request $request, $id)
    {
        $cateringMenu = CateringMenu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price_per_person' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cateringMenu->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Catering menu updated successfully',
            'catering_menu' => $cateringMenu
        ]);
    }

    public function deleteCateringMenu($id)
    {
        $cateringMenu = CateringMenu::findOrFail($id);
        $cateringMenu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catering menu deleted successfully'
        ]);
    }

    // CATERING ITEM MANAGEMENT
    public function getCateringItems()
    {
        $cateringItems = CateringItem::with(['cateringMenu'])->get();

        return response()->json([
            'success' => true,
            'catering_items' => $cateringItems
        ]);
    }

    public function createCateringItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catering_menu_id' => 'required|exists:catering_menus,id',
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('catering-items', 'public');
            $data['image'] = $imagePath;
        }

        $cateringItem = CateringItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Catering item created successfully',
            'catering_item' => $cateringItem->load('cateringMenu')
        ]);
    }

    public function updateCateringItem(Request $request, $id)
    {
        $cateringItem = CateringItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'catering_menu_id' => 'required|exists:catering_menus,id',
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($cateringItem->image) {
                Storage::disk('public')->delete($cateringItem->image);
            }
            $imagePath = $request->file('image')->store('catering-items', 'public');
            $data['image'] = $imagePath;
        }

        $cateringItem->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Catering item updated successfully',
            'catering_item' => $cateringItem->load('cateringMenu')
        ]);
    }

    public function deleteCateringItem($id)
    {
        $cateringItem = CateringItem::findOrFail($id);

        // Delete image
        if ($cateringItem->image) {
            Storage::disk('public')->delete($cateringItem->image);
        }

        $cateringItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catering item deleted successfully'
        ]);
    }

    // ADDITIONAL SERVICE MANAGEMENT
    public function getAdditionalServices()
    {
        $additionalServices = AdditionalService::all();

        return response()->json([
            'success' => true,
            'additional_services' => $additionalServices
        ]);
    }

    public function createAdditionalService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:compulsory,optional,paid',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('additional-services', 'public');
            $data['image'] = $imagePath;
        }

        $additionalService = AdditionalService::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Additional service created successfully',
            'additional_service' => $additionalService
        ]);
    }

    public function updateAdditionalService(Request $request, $id)
    {
        $additionalService = AdditionalService::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:compulsory,optional,paid',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($additionalService->image) {
                Storage::disk('public')->delete($additionalService->image);
            }
            $imagePath = $request->file('image')->store('additional-services', 'public');
            $data['image'] = $imagePath;
        }

        $additionalService->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Additional service updated successfully',
            'additional_service' => $additionalService
        ]);
    }

    public function deleteAdditionalService($id)
    {
        $additionalService = AdditionalService::findOrFail($id);

        // Delete image
        if ($additionalService->image) {
            Storage::disk('public')->delete($additionalService->image);
        }

        $additionalService->delete();

        return response()->json([
            'success' => true,
            'message' => 'Additional service deleted successfully'
        ]);
    }

    // ENHANCED DASHBOARD STATISTICS WITH REAL-TIME DATA
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            $thisWeek = Carbon::now()->startOfWeek();
            $thisMonth = Carbon::now()->startOfMonth();
            
            // Basic counts
            $totalHalls = Hall::count();
            $activeHalls = Hall::where('is_active', true)->count();
            $totalPackages = Package::count();
            $activePackages = Package::where('is_active', true)->count();
            $totalBookings = Booking::count();
            $confirmedBookings = Booking::where('status', 'confirmed')->count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $totalUsers = User::count();
            $totalCustomers = User::where('role', 'customer')->count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalManagers = User::where('role', 'manager')->count();
            
            // Revenue calculations
            $totalRevenue = Booking::where('advance_payment_paid', true)->sum('advance_payment_amount');
            $monthlyRevenue = Booking::where('advance_payment_paid', true)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('advance_payment_amount');
            
            // Time-based statistics
            $bookingsToday = Booking::whereDate('created_at', $today)->count();
            $bookingsThisWeek = Booking::where('created_at', '>=', $thisWeek)->count();
            $bookingsThisMonth = Booking::where('created_at', '>=', $thisMonth)->count();
            $newUsersThisWeek = User::where('created_at', '>=', $thisWeek)->count();
            
            // Most popular hall
            $mostBookedHall = Booking::select('hall_name')
                ->selectRaw('COUNT(*) as booking_count')
                ->where('advance_payment_paid', true)
                ->groupBy('hall_name')
                ->orderBy('booking_count', 'desc')
                ->first();
            
            // Average booking value
            $averageBookingValue = Booking::where('advance_payment_paid', true)
                ->avg('advance_payment_amount');
            
            // Additional service counts
            $weddingTypesCount = WeddingType::count();
            $decorationsCount = Decoration::count();
            $cateringMenusCount = CateringMenu::count();
            $additionalServicesCount = AdditionalService::count();
            
            $stats = [
                'total_halls' => $totalHalls,
                'active_halls' => $activeHalls,
                'total_packages' => $totalPackages,
                'active_packages' => $activePackages,
                'total_bookings' => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'pending_bookings' => $pendingBookings,
                'total_revenue' => $totalRevenue,
                'monthly_revenue' => $monthlyRevenue,
                'total_users' => $totalUsers,
                'total_customers' => $totalCustomers,
                'total_admins' => $totalAdmins,
                'total_managers' => $totalManagers,
                'bookings_today' => $bookingsToday,
                'bookings_this_week' => $bookingsThisWeek,
                'bookings_this_month' => $bookingsThisMonth,
                'new_users_this_week' => $newUsersThisWeek,
                'most_booked_hall' => $mostBookedHall ? $mostBookedHall->hall_name : 'No data',
                'average_booking_value' => round($averageBookingValue, 2),
                'total_wedding_types' => $weddingTypesCount,
                'decorations_count' => $decorationsCount,
                'catering_menus_count' => $cateringMenusCount,
                'additional_services_count' => $additionalServicesCount,
                'last_updated' => Carbon::now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading dashboard stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ENHANCED BOOKING MANAGEMENT
    public function getBookings(Request $request)
    {
        try {
            $query = Booking::with(['user', 'hall', 'package']);
            
            // Apply filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }
            
            if ($request->has('hall_id') && $request->hall_id !== '') {
                $query->where('hall_id', $request->hall_id);
            }
            
            if ($request->has('date_from') && $request->date_from !== '') {
                $query->whereDate('event_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to') && $request->date_to !== '') {
                $query->whereDate('event_date', '<=', $request->date_to);
            }
            
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('contact_name', 'like', "%{$search}%")
                      ->orWhere('contact_email', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                });
            }
            
            // Pagination
            $perPage = $request->get('per_page', 15);
            $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Calculate stats
            $stats = [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'total_revenue' => Booking::where('advance_payment_paid', true)->sum('advance_payment_amount')
            ];
            
            return response()->json([
                'success' => true,
                'bookings' => $bookings->items(),
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total()
                ],
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading bookings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
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
            
            $booking->status = $request->status;
            if ($request->notes) {
                $booking->workflow_notes = $request->notes;
            }
            $booking->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully',
                'booking' => $booking->load(['user', 'hall', 'package'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating booking status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // ENHANCED VISIT MANAGEMENT
    public function getVisitRequests(Request $request)
    {
        try {
            $query = Booking::with(['user', 'hall'])
                ->where('visit_submitted', true);
            
            // Apply filters
            if ($request->has('status') && $request->status !== '') {
                if ($request->status === 'pending') {
                    $query->where('visit_confirmed', false);
                } elseif ($request->status === 'approved') {
                    $query->where('visit_confirmed', true);
                }
            }
            
            if ($request->has('hall_id') && $request->hall_id !== '') {
                $query->where('hall_id', $request->hall_id);
            }
            
            if ($request->has('date') && $request->date !== '') {
                $query->whereDate('visit_date', $request->date);
            }
            
            $visits = $query->orderBy('created_at', 'desc')->paginate(15);
            
            // Calculate stats
            $stats = [
                'pending' => Booking::where('visit_submitted', true)
                    ->where('visit_confirmed', false)->count(),
                'approved' => Booking::where('visit_confirmed', true)->count(),
                'completed' => Booking::where('visit_confirmed', true)
                    ->where('advance_payment_paid', true)->count(),
                'conversion_rate' => $this->calculateVisitConversionRate()
            ];
            
            return response()->json([
                'success' => true,
                'visits' => $visits->items(),
                'pagination' => [
                    'current_page' => $visits->currentPage(),
                    'last_page' => $visits->lastPage(),
                    'per_page' => $visits->perPage(),
                    'total' => $visits->total()
                ],
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading visit requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load visit requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function approveVisit(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if (!$booking->visit_submitted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit request has not been submitted'
                ], 422);
            }
            
            if ($booking->visit_confirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit has already been confirmed'
                ], 422);
            }
            
            $booking->visit_confirmed = true;
            $booking->visit_confirmed_at = now();
            $booking->visit_confirmed_by = auth()->id();
            $booking->visit_confirmation_notes = $request->get('notes');
            
            // Calculate advance payment (20% of total)
            $totalAmount = $booking->calculateTotalAmount();
            $booking->advance_payment_required = true;
            $booking->advance_payment_amount = round($totalAmount * 0.20, 2);
            
            $booking->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Visit approved successfully',
                'booking' => $booking->load(['user', 'hall'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error approving visit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function rejectVisit(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $booking->status = 'cancelled';
            $booking->cancellation_reason = $request->get('reason', 'Visit request rejected by admin');
            $booking->cancelled_at = now();
            $booking->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Visit rejected successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error rejecting visit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject visit',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function calculateVisitConversionRate()
    {
        $totalVisits = Booking::where('visit_submitted', true)->count();
        $convertedVisits = Booking::where('visit_confirmed', true)
            ->where('advance_payment_paid', true)->count();
            
        if ($totalVisits === 0) {
            return 0;
        }
        
        return round(($convertedVisits / $totalVisits) * 100, 1);
    }
    
    // ENHANCED USER MANAGEMENT
    public function getUsers(Request $request)
    {
        try {
            $query = User::query();
            
            // Apply filters
            if ($request->has('role') && $request->role !== '') {
                $query->where('role', $request->role);
            }
            
            if ($request->has('status') && $request->status !== '') {
                // Assuming you have a status field
                $query->where('status', $request->status);
            }
            
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $users = $query->orderBy('created_at', 'desc')->paginate(15);
            
            return response()->json([
                'success' => true,
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading users: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // RECENT ACTIVITIES
    public function getRecentActivities()
    {
        try {
            $recentBookings = Booking::with(['user', 'hall', 'package'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $recentUsers = User::where('role', 'customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'recent_bookings' => $recentBookings,
                'recent_users' => $recentUsers
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading recent activities: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load recent activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}