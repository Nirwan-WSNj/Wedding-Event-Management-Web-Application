<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Hall;
use App\Models\WeddingType;
use App\Models\CateringMenu;
use App\Models\Decoration;
use App\Models\AdditionalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index(): \Illuminate\Contracts\View\View
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['package', 'hall', 'weddingType'])
            ->orderByDesc('created_at')
            ->get();

        return view('bookings.my', compact('bookings'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            Log::info('Booking submission started', [
                'user_id' => Auth::id(),
                'request_data_keys' => array_keys($request->all())
            ]);
            DB::beginTransaction();

            $data = $request->validated();
            Log::info('Validated data', [
                'data_keys' => array_keys($data),
                'hall_id' => $data['hall_id'] ?? 'not_set',
                'hall_name' => $data['hall_name'] ?? 'not_set',
                'package_id' => $data['package_id'] ?? 'not_set'
            ]);

            // Create the main booking record
            $booking = $this->createBookingRecord($data);
            Log::info('Booking record created', ['booking_id' => $booking->id]);

            // Handle related data
            $this->handleBookingCatering($booking, $data);
            $this->handleBookingDecorations($booking, $data);
            $this->handleBookingAdditionalServices($booking, $data);
            $this->handleCustomCateringItems($booking, $data);
            $this->handleVisitSchedule($booking, $data);

            // Calculate and update total amount
            $booking->total_amount = $booking->calculateTotalAmount();
            $booking->save();

            DB::commit();
            Log::info('Booking created successfully', ['booking_id' => $booking->id]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'booking_id' => $booking->id,
                    'message' => 'Booking created successfully!',
                    'redirect' => route('bookings.my')
                ]);
            }

            return redirect()
                ->route('bookings.my')
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getErrorMessage('create', $e),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null,
                    'error_details' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : null
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $this->getErrorMessage('create', $e));
        }
    }

    private function createBookingRecord(array $data): Booking
    {
        // Map and validate IDs
        $hallId = $this->mapAndValidateHallId($data['hall_id'] ?? null);
        $packageId = $this->mapAndValidatePackageId($data['package_id'] ?? null);
        $weddingTypeId = $this->mapAndValidateWeddingTypeId($data['wedding_type_id'] ?? null);

        // Get hall name from database instead of relying on frontend data
        $hallName = null;
        if ($hallId) {
            $hall = Hall::find($hallId);
            $hallName = $hall ? $hall->name : null;
        }

        // Get package price from database instead of relying on frontend data
        $packagePrice = 0;
        if ($packageId) {
            $package = Package::find($packageId);
            $packagePrice = $package ? $package->price : 0;
        }

        $booking = new Booking();
        
        // Set attributes individually to avoid any escaping issues
        $booking->user_id = Auth::id();
        $booking->hall_id = $hallId;
        $booking->hall_name = $hallName;
        $booking->hall_booking_date = $data['hall_booking_date'] ?? $data['event_date'] ?? now()->format('Y-m-d');
        $booking->package_id = $packageId;
        $booking->package_price = $packagePrice;
        $booking->wedding_type_id = $weddingTypeId;
        $booking->status = Booking::STATUS_PENDING;
        $booking->event_date = $data['event_date'] ?? null;
        $booking->start_time = $data['start_time'] ?? null;
        $booking->end_time = $data['end_time'] ?? null;
        $booking->guest_count = $data['guest_count'] ?? null;
        $booking->wedding_type_time_slot = $data['wedding_type_time_slot'] ?? null;
        $booking->catholic_day1_date = $data['catholic_day1_date'] ?? null;
        $booking->catholic_day2_date = $data['catholic_day2_date'] ?? null;
        $booking->contact_name = $data['contact_name'] ?? null;
        $booking->contact_email = $data['contact_email'] ?? null;
        $booking->contact_phone = $data['contact_phone'] ?? null;
        $booking->visit_purpose = $data['visit_purpose'] ?? null;
        $booking->visit_purpose_other = $data['visit_purpose_other'] ?? null;
        $booking->special_requests = $data['special_requests'] ?? null;
        $booking->visit_date = $data['visit_date'] ?? null;
        $booking->visit_time = $data['visit_time'] ?? null;
        $booking->wedding_groom_name = $data['wedding_groom_name'] ?? null;
        $booking->wedding_bride_name = $data['wedding_bride_name'] ?? null;
        $booking->wedding_groom_email = $data['wedding_groom_email'] ?? null;
        $booking->wedding_bride_email = $data['wedding_bride_email'] ?? null;
        $booking->wedding_groom_phone = $data['wedding_groom_phone'] ?? null;
        $booking->wedding_bride_phone = $data['wedding_bride_phone'] ?? null;
        $booking->wedding_date = $data['wedding_date'] ?? null;
        $booking->wedding_alternative_date1 = $data['wedding_alternative_date1'] ?? null;
        $booking->wedding_alternative_date2 = $data['wedding_alternative_date2'] ?? null;
        $booking->wedding_ceremony_time = $data['start_time'] ?? null;
        $booking->wedding_reception_time = $data['end_time'] ?? null;
        $booking->wedding_additional_notes = $data['wedding_additional_notes'] ?? null;
        $booking->terms_agreed = !empty($data['terms_agreed']) ? 1 : 0;
        $booking->privacy_agreed = !empty($data['privacy_agreed']) ? 1 : 0;
        $booking->customization_guest_count = $data['guest_count'] ?? null;
        $booking->customization_wedding_type = $data['customization_wedding_type'] ?? null;
        
        // Handle JSON fields safely
        $booking->customization_decorations_additional = $this->sanitizeJsonField($data['customization_decorations_additional'] ?? null);
        $booking->customization_catering_custom = $this->sanitizeJsonField($data['customization_catering_custom'] ?? null);
        $booking->customization_additional_services_selected = $this->sanitizeJsonField($data['customization_additional_services_selected'] ?? null);

        $booking->save();
        return $booking;
    }

    private function handleBookingCatering(Booking $booking, array $data): void
    {
        $menuId = $data['selected_menu_id'] ?? null;
        $guestCount = $data['guest_count'] ?? 0;

        if ($menuId && CateringMenu::where('id', $menuId)->exists()) {
            $menu = CateringMenu::find($menuId);
            $pricePerPerson = $menu->price_per_person ?? 0;
            $totalPrice = $guestCount * $pricePerPerson;

            DB::table('booking_catering')->insert([
                'booking_id' => $booking->id,
                'menu_id' => $menuId,
                'guest_count' => $guestCount,
                'price_per_person' => $pricePerPerson,
                'total_price' => $totalPrice,
                'special_requests' => $data['catering_special_requests'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function handleBookingDecorations(Booking $booking, array $data): void
    {
        $decorationIds = $this->parseJsonArray($data['customization_decorations_additional'] ?? '[]');
        
        if (!empty($decorationIds)) {
            foreach ($decorationIds as $decorationId) {
                if (is_numeric($decorationId) && Decoration::where('id', $decorationId)->exists()) {
                    DB::table('booking_decorations')->insert([
                        'booking_id' => $booking->id,
                        'decoration_id' => $decorationId,
                        'quantity' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function handleBookingAdditionalServices(Booking $booking, array $data): void
    {
        $serviceIds = $this->parseJsonArray($data['customization_additional_services_selected'] ?? '[]');
        
        if (!empty($serviceIds)) {
            foreach ($serviceIds as $serviceId) {
                $numericId = $this->mapServiceId($serviceId);
                if ($numericId && AdditionalService::where('id', $numericId)->exists()) {
                    DB::table('booking_additional_services')->insert([
                        'booking_id' => $booking->id,
                        'service_id' => $numericId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function handleCustomCateringItems(Booking $booking, array $data): void
    {
        $customCatering = $this->parseJsonObject($data['customization_catering_custom'] ?? '{}');
        
        if (!empty($customCatering)) {
            foreach ($customCatering as $category => $items) {
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (is_array($item) && isset($item['name'])) {
                            DB::table('booking_catering_items')->insert([
                                'booking_id' => $booking->id,
                                'category' => $category,
                                'item_name' => $item['name'],
                                'price' => $item['price'] ?? 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function handleVisitSchedule(Booking $booking, array $data): void
    {
        // Mark visit as submitted if visit details are provided
        if (!empty($data['visit_date']) && !empty($data['visit_time'])) {
            $booking->submitVisitRequest();
        }
    }

    // Mapping and validation methods
    private function mapAndValidateHallId($frontendId): int
    {
        $hallId = $this->mapHallId($frontendId);
        if (!$hallId || !Hall::where('id', $hallId)->where('is_active', true)->exists()) {
            Log::error('Hall validation failed', [
                'frontend_id' => $frontendId,
                'mapped_id' => $hallId,
                'available_halls' => Hall::where('is_active', true)->pluck('id', 'name')->toArray()
            ]);
            throw new \Exception('Invalid hall selected.');
        }
        return $hallId;
    }

    private function mapAndValidatePackageId($frontendId): int
    {
        $packageId = $this->mapPackageId($frontendId);
        if (!$packageId || !Package::where('id', $packageId)->where('is_active', true)->exists()) {
            Log::error('Package validation failed', [
                'frontend_id' => $frontendId,
                'mapped_id' => $packageId,
                'available_packages' => Package::where('is_active', true)->pluck('id', 'name')->toArray()
            ]);
            throw new \Exception('Invalid package selected.');
        }
        return $packageId;
    }

    private function mapAndValidateWeddingTypeId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        $weddingTypeId = $this->mapWeddingTypeId($frontendId);
        if ($weddingTypeId && !WeddingType::where('id', $weddingTypeId)->exists()) {
            Log::warning('Invalid wedding type ID', [
                'frontend_id' => $frontendId,
                'mapped_id' => $weddingTypeId
            ]);
            // Don't throw exception, just return null to allow booking to proceed
            return null;
        }
        return $weddingTypeId;
    }

    private function mapHallId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        // If it's already a numeric ID, validate it exists in database
        if (is_numeric($frontendId)) {
            $id = (int)$frontendId;
            return Hall::where('id', $id)->where('is_active', true)->exists() ? $id : null;
        }
        
        // Handle frontend hall IDs (e.g., 'jubilee-ballroom', 'grand-ballroom')
        // Convert kebab-case to Title Case
        $hallName = str_replace('-', ' ', $frontendId);
        $hallName = ucwords($hallName);
        
        $hall = Hall::where('name', $hallName)
            ->where('is_active', true)
            ->first();
            
        if ($hall) {
            return $hall->id;
        }
        
        // Try to find hall by exact name match
        $hall = Hall::where('name', $frontendId)
            ->where('is_active', true)
            ->first();
            
        return $hall ? $hall->id : null;
    }

    private function mapPackageId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        // If it's already a numeric ID, validate it exists in database
        if (is_numeric($frontendId)) {
            $id = (int)$frontendId;
            return Package::where('id', $id)->where('is_active', true)->exists() ? $id : null;
        }
        
        // Handle frontend package IDs (e.g., 'package-basic', 'package-golden')
        if (str_starts_with($frontendId, 'package-')) {
            $packageType = str_replace('package-', '', $frontendId);
            $packageName = ucfirst($packageType) . ' Package';
            
            $package = Package::where('name', $packageName)
                ->where('is_active', true)
                ->first();
            return $package ? $package->id : null;
        }
        
        // Try to find package by exact name match
        $package = Package::where('name', $frontendId)
            ->where('is_active', true)
            ->first();
            
        return $package ? $package->id : null;
    }

    private function mapWeddingTypeId($frontendVal): ?int
    {
        if (!$frontendVal) return null;
        
        $map = [
            'Kandyan Wedding' => 1,
            'Low-Country Wedding' => 2,
            'European Wedding' => 3,
            'Indian Wedding' => 4,
            'Catholic Wedding' => 5,
        ];
        
        return $map[$frontendVal] ?? (is_numeric($frontendVal) ? (int)$frontendVal : null);
    }

    private function mapServiceId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        // Map frontend service IDs to database IDs
        $map = [
            'multimedia' => 6,
            'live-band' => 7,
            'cultural-dancers' => 8,
            'fireworks' => 9,
            'photo-video-package' => 10,
            'basic-photography-locs' => 3,
            'guest-parking' => 4,
            'basic-sound-system' => 5,
        ];
        
        return $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
    }

    // Utility methods
    private function sanitizeJsonField($value): ?string
    {
        if (is_null($value) || $value === '') {
            return null;
        }
        
        if (is_string($value)) {
            // Remove any potential escape characters that might cause issues
            $value = stripslashes($value);
            
            // Validate JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded); // Re-encode to ensure clean JSON
            }
        }
        
        if (is_array($value)) {
            return json_encode($value);
        }
        
        Log::warning('Invalid JSON field value', ['value' => $value]);
        return null;
    }

    private function parseJsonArray($json): array
    {
        if (is_array($json)) return $json;
        if (!$json) return [];
        
        try {
            $parsed = json_decode($json, true);
            return is_array($parsed) ? $parsed : [];
        } catch (\Exception $e) {
            Log::warning('Failed to parse JSON array', ['json' => $json, 'error' => $e->getMessage()]);
            return [];
        }
    }

    private function parseJsonObject($json): array
    {
        if (is_array($json)) return $json;
        if (!$json) return [];
        
        try {
            $parsed = json_decode($json, true);
            return is_array($parsed) ? $parsed : [];
        } catch (\Exception $e) {
            Log::warning('Failed to parse JSON object', ['json' => $json, 'error' => $e->getMessage()]);
            return [];
        }
    }

    private function getErrorMessage(string $action, \Exception $e): string
    {
        $defaultMessages = [
            'create' => 'Failed to create booking. Please try again.',
            'update' => 'Failed to update booking. Please try again.',
            'cancel' => 'Failed to cancel booking. Please try again.',
        ];

        $errorDetails = '';
        if (config('app.debug')) {
            $errorDetails = ' Error: ' . $e->getMessage();
        } else {
            $errorDetails = ' Please contact support if this issue persists.';
        }

        return $defaultMessages[$action] . $errorDetails;
    }

    // Other controller methods (keeping existing functionality)
    public function edit(Booking $booking): \Illuminate\Contracts\View\View
    {
        $this->authorize('update', $booking);
        $packages = Package::where('is_active', true)->get();
        return view('bookings.edit', compact('booking', 'packages'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        try {
            $this->validateBookingCanBeUpdated($booking);
            DB::beginTransaction();

            $data = $request->validated();
            $booking->fill($data);
            $booking->save();

            DB::commit();
            return redirect()
                ->route('bookings.my')
                ->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', $this->getErrorMessage('update', $e));
        }
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        try {
            $this->validateBookingCanBeCancelled($booking);
            DB::beginTransaction();
            $booking->delete();
            DB::commit();
            
            return redirect()
                ->route('bookings.my')
                ->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking deletion failed: ' . $e->getMessage());
            
            return back()
                ->with('error', $this->getErrorMessage('cancel', $e));
        }
    }

    public function submit(StoreBookingRequest $request)
    {
        Log::info('Booking submit method called', [
            'request_path' => $request->path(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
        ]);
        return $this->store($request);
    }

    public function show(Booking $booking): \Illuminate\Contracts\View\View
    {
        $this->authorize('view', $booking);
        
        $booking->load([
            'hall',
            'package',
            'weddingType',
            'bookingDecorations.decoration',
            'bookingAdditionalServices.service',
            'bookingCatering.menu'
        ]);

        return view('bookings.show', compact('booking'));
    }

    public function showBookingForm(Request $request)
    {
        // Get all active halls from database
        $halls = Hall::where('is_active', true)->get();
        $packages = Package::where('is_active', true)->get();
        $weddingTypes = WeddingType::where('is_active', true)->get();
        $decorations = Decoration::all();
        $cateringMenus = CateringMenu::all();
        $additionalServices = AdditionalService::all()->groupBy('type');

        // Transform halls for frontend compatibility - DYNAMIC from database
        $hallsData = $halls->map(function ($hall) {
            // Create frontend-compatible ID for JavaScript
            $frontendId = strtolower(str_replace(' ', '-', $hall->name));
            
            return [
                'id' => $frontendId, // Frontend uses this for selection
                'database_id' => $hall->id, // Backend uses this for validation
                'name' => $hall->name,
                'description' => $hall->description ?? 'Beautiful wedding venue with excellent facilities.',
                'capacity' => $hall->capacity ?? 100,
                'price' => (float) $hall->price,
                'image' => $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg'),
                'features' => is_string($hall->features) ? json_decode($hall->features, true) : ($hall->features ?? [
                    'Air Conditioning',
                    'Sound System', 
                    'Lighting',
                    'Parking Available',
                    'Catering Facilities'
                ]),
                'is_active' => $hall->is_active,
            ];
        });

        // Transform packages for frontend compatibility - DYNAMIC from database
        $packagesData = $packages->map(function ($package) {
            // Create frontend-compatible ID for JavaScript
            $frontendId = 'package-' . strtolower(str_replace(' ', '-', str_replace(' Package', '', $package->name)));
            
            return [
                'id' => $frontendId, // Frontend uses this for selection
                'database_id' => $package->id, // Backend uses this for validation
                'name' => str_replace(' Package', '', $package->name), // Clean name for display
                'desc' => $package->description ?? 'Premium wedding package with excellent features.',
                'price' => (float) $package->price,
                'features' => is_string($package->features) ? json_decode($package->features, true) : ($package->features ?? []),
                'image' => $package->image ? asset('storage/packages/' . $package->image) : asset('storage/halls/default-package.jpg'),
                'highlight' => $package->highlight ?? false,
                'is_active' => $package->is_active,
            ];
        });

        $bookingOptions = [
            'halls' => $hallsData,
            'packages' => $packagesData,
            'wedding_types' => $weddingTypes,
            'decorations' => $decorations,
            'catering_menus' => $cateringMenus,
        ];

        $inProgressBooking = null;
        if (Auth::check()) {
            $inProgressBooking = Booking::where('user_id', Auth::id())
                ->where('status', Booking::STATUS_PENDING)
                ->latest('updated_at')
                ->first();
        }

        return view('booking', compact('bookingOptions', 'inProgressBooking', 'additionalServices', 'packagesData', 'hallsData'));
    }

    public function saveProgress(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            
            $booking = null;
            if (!empty($data['booking_id'])) {
                $booking = Booking::find($data['booking_id']);
            }
            
            if (!$booking) {
                $booking = new Booking();
                $booking->user_id = Auth::id();
                $booking->status = Booking::STATUS_PENDING;
            }

            // Update only provided fields
            $allowedFields = [
                'hall_id', 'hall_name', 'hall_booking_date', 'package_id', 'package_price',
                'customization_guest_count', 'customization_wedding_type', 'customization_wedding_type_time_slot',
                'customization_catholic_day1_date', 'customization_catholic_day2_date',
                'customization_decorations_additional', 'customization_catering_selected_menu_id',
                'customization_catering_custom', 'customization_additional_services_selected',
                'contact_name', 'contact_email', 'contact_phone', 'visit_purpose',
                'visit_purpose_other', 'special_requests', 'visit_date', 'visit_time',
                'wedding_groom_name', 'wedding_bride_name', 'wedding_groom_email', 'wedding_bride_email',
                'wedding_groom_phone', 'wedding_bride_phone', 'wedding_date', 'wedding_alternative_date1',
                'wedding_alternative_date2', 'wedding_ceremony_time', 'wedding_reception_time',
                'wedding_additional_notes', 'terms_agreed', 'privacy_agreed',
            ];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $booking->$field = $data[$field];
                }
            }

            $booking->event_date = $data['wedding_date'] ?? $data['hall_booking_date'] ?? $booking->event_date;
            $booking->start_time = $data['wedding_ceremony_time'] ?? $booking->start_time;
            $booking->end_time = $data['wedding_reception_time'] ?? $booking->end_time;
            
            $booking->save();
            DB::commit();
            
            return response()->json(['success' => true, 'booking_id' => $booking->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function getAvailableTimeSlots(Request $request)
    {
        return response()->json(['slots' => []]);
    }

    protected function validateBookingCanBeUpdated(Booking $booking): void
    {
        $this->authorize('update', $booking);

        if (!$booking->isPending()) {
            throw new \Exception('Only pending bookings can be updated.');
        }
    }

    protected function validateBookingCanBeCancelled(Booking $booking): void
    {
        $this->authorize('delete', $booking);

        if (!$booking->isPending()) {
            throw new \Exception('Only pending bookings can be cancelled.');
        }
    }

    /**
     * Submit visit request - creates a booking with visit details only
     */
    public function submitVisitRequest(Request $request)
    {
        try {
            Log::info('Visit request submission started', [
                'user_id' => Auth::id(),
                'request_data_keys' => array_keys($request->all())
            ]);

            DB::beginTransaction();

            // Validate required fields for visit request
            $request->validate([
                'hall_id' => 'required',
                'hall_name' => 'required|string',
                'hall_booking_date' => 'required|date',
                'package_id' => 'required',
                'contact_name' => 'required|string',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string',
                'visit_date' => 'required|date|after:today',
                'visit_time' => 'required|string',
                'visit_purpose' => 'required|string',
                'customization_guest_count' => 'required|integer|min:10'
            ]);

            $data = $request->all();

            Log::info('Visit request data received', [
                'hall_id' => $data['hall_id'] ?? 'missing',
                'package_id' => $data['package_id'] ?? 'missing',
                'user_id' => Auth::id()
            ]);

            // Create booking record with visit details
            $booking = $this->createVisitBookingRecord($data);
            
            // Submit the visit request
            $booking->submitVisitRequest();
            
            // Calculate advance payment amount (20% of estimated total)
            $estimatedTotal = $this->calculateEstimatedTotal($booking, $data);
            $booking->advance_payment_amount = round($estimatedTotal * 0.20, 2);
            $booking->advance_payment_required = true;
            $booking->save();

            DB::commit();

            Log::info('Visit request submitted successfully', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'message' => 'Visit request submitted successfully! Waiting for manager approval.',
                'advance_payment_amount' => $booking->advance_payment_amount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Visit request submission failed', [
                'error_message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit visit request: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get booking status for real-time updates
     */
    public function getBookingStatus($id)
    {
        try {
            $booking = Booking::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'visit_confirmed' => $booking->visit_confirmed,
                'advance_payment_paid' => $booking->advance_payment_paid,
                'visit_confirmation_notes' => $booking->visit_confirmation_notes,
                'advance_payment_amount' => $booking->advance_payment_amount,
                'status' => $booking->status,
                'visit_confirmed_at' => $booking->visit_confirmed_at,
                'advance_payment_paid_at' => $booking->advance_payment_paid_at
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting booking status', [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving booking status'
            ], 500);
        }
    }

    /**
     * Create a booking record specifically for visit requests
     */
    private function createVisitBookingRecord(array $data): Booking
    {
        // Map and validate IDs
        $hallId = $this->mapAndValidateHallId($data['hall_id'] ?? null);
        $packageId = $this->mapAndValidatePackageId($data['package_id'] ?? null);

        // Get hall name from database
        $hallName = null;
        if ($hallId) {
            $hall = Hall::find($hallId);
            $hallName = $hall ? $hall->name : null;
        }

        // Get package price from database
        $packagePrice = 0;
        if ($packageId) {
            $package = Package::find($packageId);
            $packagePrice = $package ? $package->price : 0;
        }

        $booking = new Booking();
        
        // Basic booking info
        $booking->user_id = Auth::id();
        $booking->hall_id = $hallId;
        $booking->hall_name = $hallName;
        $booking->hall_booking_date = $data['hall_booking_date'];
        $booking->package_id = $packageId;
        $booking->package_price = $packagePrice;
        $booking->status = Booking::STATUS_PENDING;
        
        // Contact information
        $booking->contact_name = $data['contact_name'];
        $booking->contact_email = $data['contact_email'];
        $booking->contact_phone = $data['contact_phone'];
        
        // Visit details
        $booking->visit_date = $data['visit_date'];
        $booking->visit_time = $data['visit_time'];
        $booking->visit_purpose = $data['visit_purpose'];
        $booking->visit_purpose_other = $data['visit_purpose_other'] ?? null;
        $booking->special_requests = $data['special_requests'] ?? null;
        
        // Customization details
        $booking->customization_guest_count = $data['customization_guest_count'];
        $booking->customization_wedding_type = $data['customization_wedding_type'] ?? null;
        $booking->wedding_type_time_slot = $data['wedding_type_time_slot'] ?? null;
        $booking->catholic_day1_date = $data['catholic_day1_date'] ?? null;
        $booking->catholic_day2_date = $data['catholic_day2_date'] ?? null;
        
        // Handle JSON fields
        $booking->customization_decorations_additional = $this->sanitizeJsonField($data['customization_decorations_additional'] ?? null);
        $booking->customization_catering_custom = $this->sanitizeJsonField($data['customization_catering_custom'] ?? null);
        $booking->customization_additional_services_selected = $this->sanitizeJsonField($data['customization_additional_services_selected'] ?? null);
        
        // Catering
        $booking->customization_catering_selected_menu_id = $data['customization_catering_selected_menu_id'] ?? null;
        
        // Set event date and guest count for compatibility
        $booking->event_date = $data['hall_booking_date'];
        $booking->guest_count = $data['customization_guest_count'];
        $booking->selected_menu_id = $data['customization_catering_selected_menu_id'] ?? null;

        $booking->save();
        return $booking;
    }

    /**
     * Calculate estimated total cost for advance payment calculation
     */
    private function calculateEstimatedTotal(Booking $booking, array $data): float
    {
        $total = 0;

        // Add package price
        $total += $booking->package_price;

        // Add decoration costs
        $decorationIds = $this->parseJsonArray($data['customization_decorations_additional'] ?? '[]');
        if (!empty($decorationIds)) {
            $decorationCost = Decoration::whereIn('id', $decorationIds)->sum('price');
            $total += $decorationCost;
        }

        // Add additional services costs
        $serviceIds = $this->parseJsonArray($data['customization_additional_services_selected'] ?? '[]');
        if (!empty($serviceIds)) {
            $mappedServiceIds = array_filter(array_map([$this, 'mapServiceId'], $serviceIds));
            $serviceCost = AdditionalService::whereIn('id', $mappedServiceIds)->sum('price');
            $total += $serviceCost;
        }

        // Add catering costs
        $menuId = $data['customization_catering_selected_menu_id'] ?? null;
        $guestCount = $data['customization_guest_count'] ?? 0;
        if ($menuId && $guestCount > 0) {
            $menu = CateringMenu::find($menuId);
            if ($menu) {
                $total += $menu->price_per_person * $guestCount;
            }
        }

        // Add custom catering costs
        $customCatering = $this->parseJsonObject($data['customization_catering_custom'] ?? '{}');
        if (!empty($customCatering)) {
            foreach ($customCatering as $category => $items) {
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (is_array($item) && isset($item['price'])) {
                            $total += $item['price'];
                        }
                    }
                }
            }
        }

        return $total;
    }

    /**
     * Get visit status for the current user's latest booking
     */
    public function getVisitStatus(Request $request)
    {
        try {
            // Get the user's latest booking with visit request
            $booking = Booking::where('user_id', Auth::id())
                ->where('visit_submitted', true)
                ->latest('created_at')
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'No visit request found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'booking' => [
                    'id' => $booking->id,
                    'visit_submitted' => $booking->visit_submitted,
                    'visit_confirmed' => $booking->visit_confirmed,
                    'advance_payment_paid' => $booking->advance_payment_paid,
                    'visit_date' => $booking->visit_date,
                    'visit_time' => $booking->visit_time,
                    'visit_confirmed_at' => $booking->visit_confirmed_at,
                    'advance_payment_amount' => $booking->advance_payment_amount,
                    'hall_name' => $booking->hall_name,
                    'status' => $booking->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting visit status', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving visit status'
            ], 500);
        }
    }

    /**
     * Manager confirms visit after calling customer
     */
    public function confirmVisitByCall(Request $request, $id)
    {
        try {
            $request->validate([
                'call_status' => 'required|in:successful,no_answer,busy,invalid_number',
                'call_notes' => 'nullable|string|max:1000',
                'visit_confirmed' => 'required|boolean',
                'new_visit_date' => 'nullable|date|after:today',
                'new_visit_time' => 'nullable|string',
                'manager_notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $booking = Booking::where('id', $id)
                ->where('visit_submitted', true)
                ->where('visit_confirmed', false)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visit request not found or already processed'
                ], 404);
            }

            // Record the call attempt
            $callData = [
                'booking_id' => $booking->id,
                'manager_id' => Auth::id(),
                'call_status' => $request->call_status,
                'call_notes' => $request->call_notes,
                'call_attempted_at' => now(),
                'customer_phone' => $booking->contact_phone,
                'customer_name' => $booking->contact_name,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('manager_call_logs')->insert($callData);

            // If call was successful and visit is confirmed
            if ($request->call_status === 'successful' && $request->visit_confirmed) {
                // Update visit date/time if provided
                if ($request->new_visit_date) {
                    $booking->visit_date = $request->new_visit_date;
                }
                if ($request->new_visit_time) {
                    $booking->visit_time = $request->new_visit_time;
                }

                // Use the new workflow method to confirm visit
                $callData = [
                    'notes' => $request->manager_notes,
                    'call_status' => $request->call_status,
                    'call_duration' => $request->call_duration ?? null
                ];

                $booking->confirmVisitAfterCall(Auth::user(), $callData);

                // Log the confirmation
                Log::info('Visit confirmed by manager call', [
                    'booking_id' => $booking->id,
                    'manager_id' => Auth::id(),
                    'customer_phone' => $booking->contact_phone,
                    'call_status' => $request->call_status
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Visit confirmed successfully after customer call',
                    'booking' => [
                        'id' => $booking->id,
                        'visit_confirmed' => true,
                        'visit_date' => $booking->visit_date,
                        'visit_time' => $booking->visit_time,
                        'advance_payment_amount' => $booking->advance_payment_amount
                    ]
                ]);
            } 
            // If call was unsuccessful or visit was not confirmed
            else {
                $booking->visit_call_attempts = ($booking->visit_call_attempts ?? 0) + 1;
                $booking->last_call_attempt_at = now();
                $booking->last_call_status = $request->call_status;
                $booking->last_call_notes = $request->call_notes;

                // If visit was explicitly rejected
                if ($request->call_status === 'successful' && !$request->visit_confirmed) {
                    $booking->visit_rejected = true;
                    $booking->visit_rejected_at = now();
                    $booking->visit_rejection_reason = $request->manager_notes ?? 'Customer declined visit';
                }

                $booking->save();
                DB::commit();

                $message = $request->call_status === 'successful' 
                    ? 'Customer call completed - visit not confirmed'
                    : 'Call attempt recorded - will retry later';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'call_status' => $request->call_status,
                    'retry_needed' => in_array($request->call_status, ['no_answer', 'busy'])
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming visit by call', [
                'booking_id' => $id,
                'manager_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing call confirmation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get call history for a booking
     */
    public function getCallHistory($id)
    {
        try {
            $booking = Booking::find($id);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $callLogs = DB::table('manager_call_logs')
                ->where('booking_id', $id)
                ->orderBy('call_attempted_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'call_history' => $callLogs,
                'booking' => [
                    'id' => $booking->id,
                    'customer_name' => $booking->contact_name,
                    'customer_phone' => $booking->contact_phone,
                    'visit_call_attempts' => $booking->visit_call_attempts ?? 0,
                    'last_call_attempt_at' => $booking->last_call_attempt_at,
                    'last_call_status' => $booking->last_call_status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting call history', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving call history'
            ], 500);
        }
    }

    /**
     * Mark a visit as requiring callback
     */
    public function scheduleCallback(Request $request, $id)
    {
        try {
            $request->validate([
                'callback_date' => 'required|date|after_or_equal:today',
                'callback_time' => 'required|string',
                'callback_notes' => 'nullable|string|max:500'
            ]);

            $booking = Booking::find($id);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $booking->callback_scheduled = true;
            $booking->callback_date = $request->callback_date;
            $booking->callback_time = $request->callback_time;
            $booking->callback_notes = $request->callback_notes;
            $booking->callback_scheduled_by = Auth::id();
            $booking->callback_scheduled_at = now();
            $booking->save();

            Log::info('Callback scheduled', [
                'booking_id' => $booking->id,
                'callback_date' => $request->callback_date,
                'callback_time' => $request->callback_time,
                'manager_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Callback scheduled successfully',
                'callback_info' => [
                    'date' => $request->callback_date,
                    'time' => $request->callback_time,
                    'notes' => $request->callback_notes
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error scheduling callback', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error scheduling callback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin booking index page
     */
    public function adminIndex(Request $request)
    {
        try {
            // Get all bookings with relationships
            $query = Booking::with(['user', 'hall', 'package']);
            
            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('hall_id')) {
                $query->where('hall_id', $request->hall_id);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('event_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('event_date', '<=', $request->date_to);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('contact_name', 'like', "%{$search}%")
                      ->orWhere('contact_email', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                });
            }
            
            // Get paginated results
            $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
            
            // Get halls for filter dropdown
            $halls = Hall::where('is_active', true)->get(['id', 'name']);
            
            // Calculate stats
            $stats = [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'total_revenue' => Booking::where('advance_payment_paid', true)->sum('advance_payment_amount')
            ];
            
            // If it's an AJAX request, return JSON
            if ($request->ajax() || $request->wantsJson()) {
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
            }
            
            // Return view for regular requests
            return view('admin.bookings.index', compact('bookings', 'halls', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error loading admin bookings: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load bookings',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to load bookings: ' . $e->getMessage());
        }
    }

    /**
     * Admin booking details page
     */
    public function adminShow($id)
    {
        try {
            $booking = Booking::with([
                'user', 
                'hall', 
                'package', 
                'weddingType',
                'bookingDecorations.decoration',
                'bookingAdditionalServices.service',
                'bookingCatering.menu'
            ])->findOrFail($id);
            
            return view('admin.bookings.show', compact('booking'));
            
        } catch (\Exception $e) {
            Log::error('Error loading booking details: ' . $e->getMessage());
            return back()->with('error', 'Booking not found');
        }
    }
}