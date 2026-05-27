<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\AdditionalService;
use App\Models\Booking;
use App\Models\CateringMenu;
use App\Models\Decoration;
use App\Models\Hall;
use App\Models\Package;
use App\Models\WeddingType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            $booking = $this->createBookingRecord($data);
            Log::info('Booking record created', ['booking_id' => $booking->id]);

            $this->handleBookingCatering($booking, $data);
            $this->handleBookingDecorations($booking, $data);
            $this->handleBookingAdditionalServices($booking, $data);
            $this->handleCustomCateringItems($booking, $data);
            $this->handleVisitSchedule($booking, $data);

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
        $hallId = $this->mapAndValidateHallId($data['hall_id'] ?? null);
        $packageId = $this->mapAndValidatePackageId($data['package_id'] ?? null);
        $weddingTypeId = $this->mapAndValidateWeddingTypeId($data['wedding_type_id'] ?? null);

        $hallName = null;
        if ($hallId) {
            $hall = Hall::find($hallId);
            $hallName = $hall ? $hall->name : null;
        }

        $packagePrice = 0;
        if ($packageId) {
            $package = Package::find($packageId);
            $packagePrice = $package ? $package->price : 0;
        }

        $booking = new Booking();
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
                                'item_id' => $item['id'] ?? null,
                                'custom_name' => $item['name'],
                                'price' => $item['price'] ?? 0,
                                'quantity' => $item['quantity'] ?? 1,
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
        if (!empty($data['visit_date']) && !empty($data['visit_time'])) {
            $booking->submitVisitRequest();
        }
    }

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
            return null;
        }
        return $weddingTypeId;
    }

    private function mapHallId($frontendId): ?int
    {
        if (!$frontendId) return null;
        if (is_numeric($frontendId)) {
            $id = (int)$frontendId;
            return Hall::where('id', $id)->where('is_active', true)->exists() ? $id : null;
        }
        $hallName = ucwords(str_replace('-', ' ', $frontendId));
        $hall = Hall::where('name', $hallName)->where('is_active', true)->first();
        if ($hall) return $hall->id;
        $hall = Hall::where('name', $frontendId)->where('is_active', true)->first();
        return $hall ? $hall->id : null;
    }

    private function mapPackageId($frontendId): ?int
    {
        if (!$frontendId) return null;
        if (is_numeric($frontendId)) {
            $id = (int)$frontendId;
            return Package::where('id', $id)->where('is_active', true)->exists() ? $id : null;
        }
        if (str_starts_with($frontendId, 'package-')) {
            $packageType = str_replace('package-', '', $frontendId);
            $packageName = ucfirst($packageType) . ' Package';
            $package = Package::where('name', $packageName)->where('is_active', true)->first();
            return $package ? $package->id : null;
        }
        $package = Package::where('name', $frontendId)->where('is_active', true)->first();
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
        $map = [
            'guest-parking' => 1,
            'basic-sound-system' => 2,
            'basic-photography-locs' => 3,
            'live-band' => 4,
            'cultural-dancers' => 5,
            'photo-video-package' => 6,
            'multimedia' => 7,
            'fireworks' => 8,
        ];
        return $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
    }

    private function sanitizeJsonField($value): ?string
    {
        if (is_null($value) || $value === '') return null;
        if (is_string($value)) {
            $value = stripslashes($value);
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded);
            }
        }
        if (is_array($value)) return json_encode($value);
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
        $errorDetails = config('app.debug') ? ' Error: ' . $e->getMessage() : ' Please contact support if this issue persists.';
        return $defaultMessages[$action] . $errorDetails;
    }

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
            return redirect()->route('bookings.my')->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $this->getErrorMessage('update', $e));
        }
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        try {
            $this->validateBookingCanBeCancelled($booking);
            DB::beginTransaction();
            $booking->delete();
            DB::commit();
            return redirect()->route('bookings.my')->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return back()->with('error', $this->getErrorMessage('cancel', $e));
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
        $halls = Hall::where('is_active', true)->get();
        $packages = Package::where('is_active', true)->get();
        $weddingTypes = WeddingType::where('is_active', true)->get();
        $decorations = Decoration::all();
        $cateringMenus = CateringMenu::all();
        $additionalServices = AdditionalService::all()->groupBy('type');

        $hallsData = $halls->map(function ($hall) {
            $frontendId = strtolower(str_replace(' ', '-', $hall->name));
            return [
                'id' => $frontendId,
                'database_id' => $hall->id,
                'name' => $hall->name,
                'description' => $hall->description ?? 'Beautiful wedding venue with excellent facilities.',
                'capacity' => $hall->capacity ?? 100,
                'price' => (float) $hall->price,
                'image' => $hall->image ? asset('storage/' . ltrim($hall->image, '/')) : asset('storage/halls/default-hall.jpg'),
                'features' => is_string($hall->features) ? json_decode($hall->features, true) : ($hall->features ?? [
                    'Air Conditioning',
                    'Sound System',
                    'Lighting',
                    'Parking Available',
                    'Catering Facilities'
                ]),
                'is_active' => $hall->is_active,
            ];
        })->values();

        $packagesData = $packages->map(function ($package) {
            $frontendId = 'package-' . strtolower(str_replace(' ', '-', str_replace(' Package', '', $package->name)));
            return [
                'id' => $frontendId,
                'database_id' => $package->id,
                'name' => str_replace(' Package', '', $package->name),
                'desc' => $package->description ?? 'Premium wedding package with excellent features.',
                'price' => (float) $package->price,
                'features' => is_string($package->features) ? json_decode($package->features, true) : ($package->features ?? []),
                'image' => $package->image ? asset('storage/' . ltrim($package->image, '/')) : asset('storage/halls/default-package.jpg'),
                'highlight' => $package->highlight ?? false,
                'is_active' => $package->is_active,
            ];
        })->values();

        $bookingOptions = [
            'halls' => $hallsData,
            'packages' => $packagesData,
            'weddingTypes' => $weddingTypes,
            'decorations' => $decorations,
            'cateringMenus' => $cateringMenus,
            'additionalServices' => $additionalServices,
        ];

        return view('booking', compact(
            'bookingOptions',
            'hallsData',
            'packagesData',
            'weddingTypes',
            'decorations',
            'cateringMenus',
            'additionalServices'
        ));
    }

    private function validateBookingCanBeUpdated(Booking $booking): void
    {
        if ($booking->status !== Booking::STATUS_PENDING) {
            throw new \Exception('Only pending bookings can be updated.');
        }
    }

    private function validateBookingCanBeCancelled(Booking $booking): void
    {
        if ($booking->status === Booking::STATUS_COMPLETED) {
            throw new \Exception('Completed bookings cannot be cancelled.');
        }
    }
}
