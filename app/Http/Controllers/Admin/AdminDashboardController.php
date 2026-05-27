<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use App\Models\Booking;
use App\Models\CateringItem;
use App\Models\CateringMenu;
use App\Models\Decoration;
use App\Models\Hall;
use App\Models\Package;
use App\Models\User;
use App\Models\WeddingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $stats = $this->buildDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }

    public function getDashboardStats()
    {
        try {
            $stats = $this->buildDashboardStats();

            // Keep both shapes for old dashboard JS and newer API consumers.
            return response()->json(array_merge([
                'success' => true,
                'stats' => $stats,
            ], $stats));
        } catch (\Throwable $e) {
            Log::error('Error loading dashboard stats', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function getRecentActivities()
    {
        try {
            $recentBookings = Booking::with($this->safeBookingRelations(['user', 'hall', 'package']))
                ->latest()
                ->limit(10)
                ->get();

            $recentUsers = User::query()
                ->when($this->hasColumn('users', 'role'), fn ($query) => $query->where('role', 'customer'))
                ->latest()
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'recent_bookings' => $recentBookings,
                'recent_users' => $recentUsers,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading recent activities', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load recent activities',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    // HALL MANAGEMENT
    public function getHalls(Request $request = null)
    {
        try {
            $query = Hall::query();

            if ($request) {
                if ($request->filled('search')) {
                    $search = $request->string('search')->toString();
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                        if ($this->hasColumn('halls', 'description')) {
                            $q->orWhere('description', 'like', "%{$search}%");
                        }
                    });
                }

                if ($request->filled('status') && $this->hasColumn('halls', 'is_active')) {
                    $query->where('is_active', $request->boolean('status'));
                }
            }

            $halls = $query->latest()->get();
            $mapped = $halls->map(fn ($hall) => $this->mapHall($hall));

            return response()->json([
                'success' => true,
                'halls' => $mapped,
                'stats' => [
                    'total' => $mapped->count(),
                    'active' => $mapped->where('is_active', true)->count(),
                    'total_revenue' => $mapped->sum('total_revenue'),
                    'most_popular' => optional($mapped->sortByDesc('bookings_count')->first())['name'] ?? 'N/A',
                    'booked_today' => $this->hasColumn('bookings', 'created_at')
                        ? Booking::whereDate('created_at', Carbon::today())->count()
                        : 0,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading halls', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load halls',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function createHall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:halls,name',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:10000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'features' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->onlyExistingColumns('halls', [
            'name' => $request->name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active', true),
            'features' => $request->has('features') ? json_encode($request->features) : null,
        ]);

        if ($request->hasFile('image') && $this->hasColumn('halls', 'image')) {
            $data['image'] = $request->file('image')->store('halls', 'public');
        }

        $hall = Hall::create($data);

        return response()->json(['success' => true, 'message' => 'Hall created successfully', 'hall' => $this->mapHall($hall)]);
    }

    public function updateHall(Request $request, $id)
    {
        $hall = Hall::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:halls,name,' . $id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:10000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'features' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->onlyExistingColumns('halls', [
            'name' => $request->name,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active', true),
            'features' => $request->has('features') ? json_encode($request->features) : null,
        ]);

        if ($request->hasFile('image') && $this->hasColumn('halls', 'image')) {
            if ($hall->image) {
                Storage::disk('public')->delete($hall->image);
            }
            $data['image'] = $request->file('image')->store('halls', 'public');
        }

        $hall->update($data);

        return response()->json(['success' => true, 'message' => 'Hall updated successfully', 'hall' => $this->mapHall($hall->fresh())]);
    }

    public function deleteHall($id)
    {
        $hall = Hall::findOrFail($id);

        $upcomingBookings = $hall->bookings()
            ->when($this->hasColumn('bookings', 'event_date'), fn ($query) => $query->where('event_date', '>=', Carbon::today()))
            ->when($this->hasColumn('bookings', 'status'), fn ($query) => $query->where('status', 'confirmed'))
            ->count();

        if ($upcomingBookings > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete hall with upcoming confirmed bookings'], 422);
        }

        if ($hall->image) {
            Storage::disk('public')->delete($hall->image);
        }

        $hall->delete();

        return response()->json(['success' => true, 'message' => 'Hall deleted successfully']);
    }

    // PACKAGE MANAGEMENT
    public function getPackages(Request $request = null)
    {
        try {
            $query = Package::query();

            if ($request && $request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                    if ($this->hasColumn('packages', 'description')) {
                        $q->orWhere('description', 'like', "%{$search}%");
                    }
                });
            }

            $packages = $query->latest()->get()->map(fn ($package) => $this->mapPackage($package));

            return response()->json([
                'success' => true,
                'packages' => $packages,
                'stats' => [
                    'total' => $packages->count(),
                    'active' => $packages->where('is_active', true)->count(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading packages', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function createPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:packages,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'highlight' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'features' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->packagePayload($request);

        if ($request->hasFile('image') && $this->hasColumn('packages', 'image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);

        return response()->json(['success' => true, 'message' => 'Package created successfully', 'package' => $this->mapPackage($package)]);
    }

    public function updatePackage(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:packages,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'highlight' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'features' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->packagePayload($request);

        if ($request->hasFile('image') && $this->hasColumn('packages', 'image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($data);

        return response()->json(['success' => true, 'message' => 'Package updated successfully', 'package' => $this->mapPackage($package->fresh())]);
    }

    public function viewPackage($id)
    {
        $package = Package::with($this->safeRelation(Package::class, 'bookings') ? ['bookings'] : [])->findOrFail($id);

        return response()->json(['success' => true, 'package' => $this->mapPackage($package, true)]);
    }

    public function deletePackage($id)
    {
        $package = Package::findOrFail($id);

        $upcomingBookings = $package->bookings()
            ->when($this->hasColumn('bookings', 'event_date'), fn ($query) => $query->where('event_date', '>=', Carbon::today()))
            ->when($this->hasColumn('bookings', 'status'), fn ($query) => $query->where('status', 'confirmed'))
            ->count();

        if ($upcomingBookings > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete package with upcoming confirmed bookings'], 422);
        }

        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();

        return response()->json(['success' => true, 'message' => 'Package deleted successfully']);
    }

    // WEDDING TYPE MANAGEMENT
    public function getWeddingTypes()
    {
        return response()->json(['success' => true, 'wedding_types' => WeddingType::latest()->get()]);
    }

    public function createWeddingType(Request $request)
    {
        return $this->storeSimpleModel($request, WeddingType::class, 'wedding_types', 'wedding_type', [
            'name' => 'required|string|max:191|unique:wedding_types,name',
            'description' => 'nullable|string',
        ]);
    }

    public function updateWeddingType(Request $request, $id)
    {
        return $this->updateSimpleModel($request, WeddingType::class, 'wedding_types', 'wedding_type', $id, [
            'name' => 'required|string|max:191|unique:wedding_types,name,' . $id,
            'description' => 'nullable|string',
        ]);
    }

    public function deleteWeddingType($id)
    {
        return $this->deleteSimpleModel(WeddingType::class, 'wedding type', $id);
    }

    // DECORATION MANAGEMENT
    public function getDecorations()
    {
        return response()->json(['success' => true, 'decorations' => Decoration::latest()->get()]);
    }

    public function createDecoration(Request $request)
    {
        return $this->storeSimpleModel($request, Decoration::class, 'decorations', 'decoration', [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'decorations');
    }

    public function updateDecoration(Request $request, $id)
    {
        return $this->updateSimpleModel($request, Decoration::class, 'decorations', 'decoration', $id, [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'decorations');
    }

    public function deleteDecoration($id)
    {
        return $this->deleteSimpleModel(Decoration::class, 'decoration', $id);
    }

    // CATERING MENU MANAGEMENT
    public function getCateringMenus()
    {
        $menus = CateringMenu::latest()->get();

        return response()->json(['success' => true, 'catering_menus' => $menus]);
    }

    public function createCateringMenu(Request $request)
    {
        return $this->storeSimpleModel($request, CateringMenu::class, 'catering_menus', 'catering_menu', [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'details' => 'nullable',
            'package_id' => 'nullable|exists:packages,id',
            'price_per_person' => 'nullable|numeric|min:0',
        ]);
    }

    public function updateCateringMenu(Request $request, $id)
    {
        return $this->updateSimpleModel($request, CateringMenu::class, 'catering_menus', 'catering_menu', $id, [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'details' => 'nullable',
            'package_id' => 'nullable|exists:packages,id',
            'price_per_person' => 'nullable|numeric|min:0',
        ]);
    }

    public function deleteCateringMenu($id)
    {
        return $this->deleteSimpleModel(CateringMenu::class, 'catering menu', $id);
    }

    // CATERING ITEM MANAGEMENT
    public function getCateringItems()
    {
        return response()->json(['success' => true, 'catering_items' => CateringItem::latest()->get()]);
    }

    public function createCateringItem(Request $request)
    {
        return $this->storeSimpleModel($request, CateringItem::class, 'catering_items', 'catering_item', [
            'name' => 'required|string|max:191',
            'category' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'catering_menu_id' => 'nullable|exists:catering_menus,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'catering-items');
    }

    public function updateCateringItem(Request $request, $id)
    {
        return $this->updateSimpleModel($request, CateringItem::class, 'catering_items', 'catering_item', $id, [
            'name' => 'required|string|max:191',
            'category' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'catering_menu_id' => 'nullable|exists:catering_menus,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'catering-items');
    }

    public function deleteCateringItem($id)
    {
        return $this->deleteSimpleModel(CateringItem::class, 'catering item', $id);
    }

    // ADDITIONAL SERVICE MANAGEMENT
    public function getAdditionalServices()
    {
        return response()->json(['success' => true, 'additional_services' => AdditionalService::latest()->get()]);
    }

    public function createAdditionalService(Request $request)
    {
        return $this->storeSimpleModel($request, AdditionalService::class, 'additional_services', 'additional_service', [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'type' => 'nullable|in:compulsory,optional,paid',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'additional-services');
    }

    public function updateAdditionalService(Request $request, $id)
    {
        return $this->updateSimpleModel($request, AdditionalService::class, 'additional_services', 'additional_service', $id, [
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'type' => 'nullable|in:compulsory,optional,paid',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], 'additional-services');
    }

    public function deleteAdditionalService($id)
    {
        return $this->deleteSimpleModel(AdditionalService::class, 'additional service', $id);
    }

    // BOOKING MANAGEMENT
    public function getBookings(Request $request)
    {
        try {
            $query = Booking::with($this->safeBookingRelations(['user', 'hall', 'package']));

            if ($request->filled('status') && $this->hasColumn('bookings', 'status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('hall_id') && $this->hasColumn('bookings', 'hall_id')) {
                $query->where('hall_id', $request->hall_id);
            }

            $dateColumn = $this->hasColumn('bookings', 'event_date') ? 'event_date' : ($this->hasColumn('bookings', 'hall_booking_date') ? 'hall_booking_date' : null);
            if ($dateColumn && $request->filled('date_from')) {
                $query->whereDate($dateColumn, '>=', $request->date_from);
            }
            if ($dateColumn && $request->filled('date_to')) {
                $query->whereDate($dateColumn, '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    foreach (['contact_name', 'contact_email', 'id'] as $column) {
                        if ($this->hasColumn('bookings', $column)) {
                            $q->orWhere($column, 'like', "%{$search}%");
                        }
                    }
                });
            }

            $bookings = $query->latest()->paginate((int) $request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'bookings' => $bookings->items(),
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
                'stats' => $this->bookingStats(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading bookings', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->onlyExistingColumns('bookings', [
            'status' => $request->status,
            'workflow_notes' => $request->notes,
        ]);

        $booking->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'booking' => $booking->fresh()->load($this->safeBookingRelations(['user', 'hall', 'package'])),
        ]);
    }

    // VISIT MANAGEMENT
    public function getVisitRequests(Request $request)
    {
        try {
            $query = Booking::with($this->safeBookingRelations(['user', 'hall']));

            if ($this->hasColumn('bookings', 'visit_submitted')) {
                $query->where('visit_submitted', true);
            }

            if ($request->filled('status') && $this->hasColumn('bookings', 'visit_confirmed')) {
                if ($request->status === 'pending') {
                    $query->where('visit_confirmed', false);
                } elseif ($request->status === 'approved') {
                    $query->where('visit_confirmed', true);
                }
            }

            if ($request->filled('hall_id') && $this->hasColumn('bookings', 'hall_id')) {
                $query->where('hall_id', $request->hall_id);
            }

            if ($request->filled('date') && $this->hasColumn('bookings', 'visit_date')) {
                $query->whereDate('visit_date', $request->date);
            }

            $visits = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'visits' => $visits->items(),
                'pagination' => [
                    'current_page' => $visits->currentPage(),
                    'last_page' => $visits->lastPage(),
                    'per_page' => $visits->perPage(),
                    'total' => $visits->total(),
                ],
                'stats' => [
                    'pending' => $this->visitCount(false),
                    'approved' => $this->visitCount(true),
                    'completed' => $this->paidBookingsQuery()->count(),
                    'conversion_rate' => $this->calculateVisitConversionRate(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading visit requests', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load visit requests',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function approveVisit(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $totalAmount = method_exists($booking, 'calculateTotalAmount') ? $booking->calculateTotalAmount() : (float) ($booking->total_amount ?? $booking->package_price ?? 0);

        $booking->update($this->onlyExistingColumns('bookings', [
            'visit_confirmed' => true,
            'visit_confirmed_at' => now(),
            'visit_confirmed_by' => auth()->id(),
            'visit_confirmation_notes' => $request->get('notes'),
            'advance_payment_required' => true,
            'advance_payment_amount' => round($totalAmount * 0.20, 2),
            'workflow_step' => 'payment_pending',
            'workflow_notes' => 'Visit approved. Advance payment required.',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Visit approved successfully',
            'booking' => $booking->fresh()->load($this->safeBookingRelations(['user', 'hall'])),
        ]);
    }

    public function rejectVisit(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($this->onlyExistingColumns('bookings', [
            'status' => 'cancelled',
            'visit_rejected' => true,
            'visit_rejected_at' => now(),
            'visit_rejected_by' => auth()->id(),
            'visit_rejection_reason' => $request->get('reason', 'Visit request rejected by admin'),
            'cancellation_reason' => $request->get('reason', 'Visit request rejected by admin'),
            'cancelled_at' => now(),
        ]));

        return response()->json(['success' => true, 'message' => 'Visit rejected successfully']);
    }

    // USER MANAGEMENT
    public function getUsers(Request $request)
    {
        try {
            $query = User::query();

            if ($request->filled('role') && $this->hasColumn('users', 'role')) {
                $query->where('role', $request->role);
            }

            if ($request->filled('status') && $this->hasColumn('users', 'status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    foreach (['first_name', 'last_name', 'email', 'phone'] as $column) {
                        if ($this->hasColumn('users', $column)) {
                            $q->orWhere($column, 'like', "%{$search}%");
                        }
                    }
                });
            }

            $users = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'users' => collect($users->items())->map(fn ($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'role' => $user->role ?? 'customer',
                    'status' => $user->status ?? 'active',
                    'profile_photo_path' => $user->profile_photo_path ?? null,
                    'profile_photo_url' => $user->profile_photo_url ?? null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error loading users', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load users',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function buildDashboardStats(): array
    {
        $confirmedBookings = $this->hasColumn('bookings', 'status') ? Booking::where('status', 'confirmed')->count() : 0;
        $pendingBookings = $this->hasColumn('bookings', 'status') ? Booking::where('status', 'pending')->count() : 0;
        $averageBookingValue = $this->paidBookingsQuery()->avg($this->revenueColumn()) ?? 0;

        return [
            'total_halls' => Schema::hasTable('halls') ? Hall::count() : 0,
            'active_halls' => Schema::hasTable('halls') ? $this->activeCount(Hall::query(), 'halls') : 0,
            'total_packages' => Schema::hasTable('packages') ? Package::count() : 0,
            'active_packages' => Schema::hasTable('packages') ? $this->activeCount(Package::query(), 'packages') : 0,
            'total_bookings' => Schema::hasTable('bookings') ? Booking::count() : 0,
            'confirmed_bookings' => $confirmedBookings,
            'pending_bookings' => $pendingBookings,
            'total_revenue' => $this->totalRevenue(),
            'monthly_revenue' => $this->monthlyRevenue(),
            'total_users' => Schema::hasTable('users') ? User::count() : 0,
            'total_customers' => $this->roleCount('customer'),
            'total_admins' => $this->roleCount('admin'),
            'total_managers' => $this->roleCount('manager'),
            'bookings_today' => $this->dateCount('bookings', 'created_at', Carbon::today()),
            'bookings_this_week' => $this->dateSinceCount('bookings', 'created_at', Carbon::now()->startOfWeek()),
            'bookings_this_month' => $this->dateSinceCount('bookings', 'created_at', Carbon::now()->startOfMonth()),
            'new_users_this_week' => $this->dateSinceCount('users', 'created_at', Carbon::now()->startOfWeek()),
            'most_booked_hall' => $this->mostBookedHall(),
            'average_booking_value' => round((float) $averageBookingValue, 2),
            'total_wedding_types' => Schema::hasTable('wedding_types') ? WeddingType::count() : 0,
            'decorations_count' => Schema::hasTable('decorations') ? Decoration::count() : 0,
            'catering_menus_count' => Schema::hasTable('catering_menus') ? CateringMenu::count() : 0,
            'additional_services_count' => Schema::hasTable('additional_services') ? AdditionalService::count() : 0,
            'last_updated' => Carbon::now()->toISOString(),
        ];
    }

    private function bookingStats(): array
    {
        return [
            'total' => Booking::count(),
            'pending' => $this->hasColumn('bookings', 'status') ? Booking::where('status', 'pending')->count() : 0,
            'confirmed' => $this->hasColumn('bookings', 'status') ? Booking::where('status', 'confirmed')->count() : 0,
            'cancelled' => $this->hasColumn('bookings', 'status') ? Booking::where('status', 'cancelled')->count() : 0,
            'total_revenue' => $this->totalRevenue(),
        ];
    }

    private function paidBookingsQuery()
    {
        $query = Booking::query();

        if ($this->hasColumn('bookings', 'advance_payment_paid')) {
            return $query->where('advance_payment_paid', true);
        }

        if ($this->hasColumn('bookings', 'status')) {
            return $query->where('status', 'confirmed');
        }

        return $query;
    }

    private function totalRevenue(): float
    {
        $column = $this->revenueColumn();

        return $column ? (float) $this->paidBookingsQuery()->sum($column) : 0;
    }

    private function monthlyRevenue(): float
    {
        $column = $this->revenueColumn();
        if (!$column || !$this->hasColumn('bookings', 'created_at')) {
            return 0;
        }

        return (float) $this->paidBookingsQuery()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum($column);
    }

    private function revenueColumn(): ?string
    {
        foreach (['advance_payment_amount', 'total_amount', 'package_price'] as $column) {
            if ($this->hasColumn('bookings', $column)) {
                return $column;
            }
        }

        return null;
    }

    private function mostBookedHall(): string
    {
        if (!$this->hasColumn('bookings', 'hall_name')) {
            return 'No data';
        }

        $query = Booking::select('hall_name')->selectRaw('COUNT(*) as booking_count')->groupBy('hall_name')->orderByDesc('booking_count');

        if ($this->hasColumn('bookings', 'advance_payment_paid')) {
            $query->where('advance_payment_paid', true);
        }

        return optional($query->first())->hall_name ?? 'No data';
    }

    private function roleCount(string $role): int
    {
        return $this->hasColumn('users', 'role') ? User::where('role', $role)->count() : 0;
    }

    private function activeCount($query, string $table): int
    {
        return $this->hasColumn($table, 'is_active') ? $query->where('is_active', true)->count() : $query->count();
    }

    private function dateCount(string $table, string $column, Carbon $date): int
    {
        if (!$this->hasColumn($table, $column)) {
            return 0;
        }

        return $table === 'users'
            ? User::whereDate($column, $date)->count()
            : Booking::whereDate($column, $date)->count();
    }

    private function dateSinceCount(string $table, string $column, Carbon $date): int
    {
        if (!$this->hasColumn($table, $column)) {
            return 0;
        }

        return $table === 'users'
            ? User::where($column, '>=', $date)->count()
            : Booking::where($column, '>=', $date)->count();
    }

    private function visitCount(bool $confirmed): int
    {
        if (!$this->hasColumn('bookings', 'visit_confirmed')) {
            return 0;
        }

        return Booking::query()
            ->when($this->hasColumn('bookings', 'visit_submitted'), fn ($query) => $query->where('visit_submitted', true))
            ->where('visit_confirmed', $confirmed)
            ->count();
    }

    private function calculateVisitConversionRate(): float
    {
        if (!$this->hasColumn('bookings', 'visit_submitted')) {
            return 0;
        }

        $totalVisits = Booking::where('visit_submitted', true)->count();
        if ($totalVisits === 0) {
            return 0;
        }

        $converted = $this->paidBookingsQuery()
            ->when($this->hasColumn('bookings', 'visit_confirmed'), fn ($query) => $query->where('visit_confirmed', true))
            ->count();

        return round(($converted / $totalVisits) * 100, 1);
    }

    private function mapHall(Hall $hall): array
    {
        $bookings = method_exists($hall, 'bookings') ? $hall->bookings() : null;

        return [
            'id' => $hall->id,
            'name' => $hall->name,
            'description' => $hall->description ?? '',
            'capacity' => $hall->capacity ?? 0,
            'price' => $hall->price ?? 0,
            'image' => $hall->image ? asset('storage/' . $hall->image) : null,
            'is_active' => $this->hasColumn('halls', 'is_active') ? (bool) $hall->is_active : true,
            'bookings_count' => $bookings ? $bookings->count() : 0,
            'upcoming_bookings' => $bookings ? $bookings
                ->when($this->hasColumn('bookings', 'event_date'), fn ($query) => $query->where('event_date', '>=', Carbon::today()))
                ->when($this->hasColumn('bookings', 'status'), fn ($query) => $query->where('status', 'confirmed'))
                ->count() : 0,
            'total_revenue' => $bookings && $this->revenueColumn() ? (float) $bookings->where('status', 'confirmed')->sum($this->revenueColumn()) : 0,
            'created_at' => optional($hall->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($hall->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    private function mapPackage(Package $package, bool $detailed = false): array
    {
        $bookings = method_exists($package, 'bookings') ? $package->bookings() : null;
        $features = $package->features ?? [];
        if (is_string($features)) {
            $features = json_decode($features, true) ?: [];
        }

        $data = [
            'id' => $package->id,
            'name' => $package->name,
            'description' => $package->description ?? '',
            'price' => $package->price ?? 0,
            'image' => $package->image ? asset('storage/' . $package->image) : null,
            'is_active' => $this->hasColumn('packages', 'is_active') ? (bool) $package->is_active : true,
            'highlight' => $package->highlight ?? false,
            'features' => $features,
            'booking_count' => $bookings ? $bookings->count() : 0,
            'created_at' => optional($package->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($package->updated_at)->format('Y-m-d H:i:s'),
        ];

        if ($detailed) {
            $data['min_guests'] = $package->min_guests ?? null;
            $data['max_guests'] = $package->max_guests ?? null;
            $data['additional_guest_price'] = $package->additional_guest_price ?? 0;
            $data['total_revenue'] = $bookings && $this->revenueColumn() ? (float) $bookings->sum($this->revenueColumn()) : 0;
        }

        return $data;
    }

    private function packagePayload(Request $request): array
    {
        return $this->onlyExistingColumns('packages', [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'highlight' => $request->boolean('highlight', false),
            'is_active' => $request->boolean('is_active', true),
            'features' => $request->has('features') ? json_encode($request->features) : null,
        ]);
    }

    private function storeSimpleModel(Request $request, string $modelClass, string $table, string $responseKey, array $rules, ?string $imageFolder = null)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->onlyExistingColumns($table, $request->except(['_token', 'image']));

        if ($imageFolder && $request->hasFile('image') && $this->hasColumn($table, 'image')) {
            $data['image'] = $request->file('image')->store($imageFolder, 'public');
        }

        $model = $modelClass::create($data);

        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $responseKey)) . ' created successfully', $responseKey => $model]);
    }

    private function updateSimpleModel(Request $request, string $modelClass, string $table, string $responseKey, $id, array $rules, ?string $imageFolder = null)
    {
        $model = $modelClass::findOrFail($id);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $this->onlyExistingColumns($table, $request->except(['_token', '_method', 'image']));

        if ($imageFolder && $request->hasFile('image') && $this->hasColumn($table, 'image')) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            $data['image'] = $request->file('image')->store($imageFolder, 'public');
        }

        $model->update($data);

        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $responseKey)) . ' updated successfully', $responseKey => $model->fresh()]);
    }

    private function deleteSimpleModel(string $modelClass, string $label, $id)
    {
        $model = $modelClass::findOrFail($id);
        if (isset($model->image) && $model->image) {
            Storage::disk('public')->delete($model->image);
        }
        $model->delete();

        return response()->json(['success' => true, 'message' => ucfirst($label) . ' deleted successfully']);
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        return collect($data)
            ->filter(fn ($value, $column) => $this->hasColumn($table, $column))
            ->reject(fn ($value) => $value === null)
            ->all();
    }

    private function hasColumn(string $table, string $column): bool
    {
        try {
            return Schema::hasTable($table) && Schema::hasColumn($table, $column);
        } catch (\Throwable) {
            return false;
        }
    }

    private function safeRelation(string $modelClass, string $relation): bool
    {
        return method_exists($modelClass, $relation);
    }

    private function safeBookingRelations(array $relations): array
    {
        return collect($relations)->filter(fn ($relation) => method_exists(Booking::class, $relation))->values()->all();
    }
}
