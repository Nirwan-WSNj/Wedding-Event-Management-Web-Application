<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    public function rules(): array
    {
        return [
            // Core booking fields - IMPROVED VALIDATION
            'hall_id' => ['required', 'integer', 'exists:halls,id'],
            'hall_name' => ['nullable', 'string', 'max:255'],
            'hall_booking_date' => ['required', 'date', 'after_or_equal:today'],
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'package_price' => ['nullable', 'numeric', 'min:0'],
            'event_date' => ['nullable', 'date', 'after:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'guest_count' => ['required', 'integer', 'min:10', 'max:1000'],
            
            // Wedding type and customization
            'wedding_type_id' => ['nullable'],
            'customization_wedding_type' => ['nullable', 'string'],
            'wedding_type_time_slot' => ['nullable', 'string'],
            'catholic_day1_date' => ['nullable', 'date'],
            'catholic_day2_date' => ['nullable', 'date', 'after:catholic_day1_date'],
            
            // Contact information - IMPROVED FLEXIBILITY
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'visit_purpose' => ['nullable', 'string'],
            'visit_purpose_other' => ['nullable', 'string'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'visit_date' => ['nullable', 'date', 'after_or_equal:today'],
            'visit_time' => ['nullable', 'date_format:H:i'],
            
            // Wedding details - IMPROVED FLEXIBILITY
            'wedding_groom_name' => ['nullable', 'string', 'max:255'],
            'wedding_bride_name' => ['nullable', 'string', 'max:255'],
            'wedding_groom_email' => ['nullable', 'email', 'max:255'],
            'wedding_bride_email' => ['nullable', 'email', 'max:255'],
            'wedding_groom_phone' => ['nullable', 'string', 'max:20'],
            'wedding_bride_phone' => ['nullable', 'string', 'max:20'],
            'wedding_date' => ['nullable', 'date'],
            'wedding_alternative_date1' => ['nullable', 'date'],
            'wedding_alternative_date2' => ['nullable', 'date'],
            'wedding_additional_notes' => ['nullable', 'string', 'max:1000'],
            
            // JSON fields for customizations
            'customization_decorations_additional' => ['nullable', 'json'],
            'customization_catering_custom' => ['nullable', 'json'],
            'customization_additional_services_selected' => ['nullable', 'json'],
            
            // Catering
            'selected_menu_id' => ['nullable', 'integer'],
            
            // Agreements - IMPROVED FLEXIBILITY
            'privacy_agreed' => ['nullable', 'accepted'],
            'terms_agreed' => ['nullable', 'accepted'],
            
            // Time slot validation
            'time_slot' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateTimeSlotAvailability($fail);
                }
            ],
        ];
    }

    private function validateTimeSlotAvailability($fail)
    {
        $hallId = $this->getValidatedHallId();
        $eventDate = $this->input('event_date');
        $startTime = $this->input('start_time');
        $endTime = $this->input('end_time');

        if (!$hallId || !$eventDate || !$startTime || !$endTime) {
            return; // Skip validation if required fields are missing
        }

        try {
            $start = Carbon::createFromFormat('Y-m-d H:i', $eventDate . ' ' . $startTime);
            $end = Carbon::createFromFormat('Y-m-d H:i', $eventDate . ' ' . $endTime);
            
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $overlapping = DB::table('bookings')
                ->where('hall_id', $hallId)
                ->where('event_date', $eventDate)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->exists();

            if ($overlapping) {
                $fail('The selected time slot is not available.');
            }
        } catch (\Exception $e) {
            Log::warning('Time slot validation failed', [
                'error' => $e->getMessage(),
                'event_date' => $eventDate,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
            $fail('Invalid time format provided.');
        }
    }

    public function attributes(): array
    {
        return [
            'hall_id' => 'Hall',
            'package_id' => 'Package',
            'event_date' => 'Event date',
            'start_time' => 'Start time',
            'end_time' => 'End time',
            'guest_count' => 'Number of guests',
            'wedding_type_id' => 'Wedding type',
            'contact_name' => 'Contact name',
            'contact_email' => 'Contact email',
            'contact_phone' => 'Contact phone',
            'wedding_groom_name' => 'Groom name',
            'wedding_bride_name' => 'Bride name',
            'wedding_groom_phone' => 'Groom phone',
            'special_requests' => 'Special requests',
            'selected_menu_id' => 'Catering menu',
            'privacy_agreed' => 'Privacy policy agreement',
            'terms_agreed' => 'Terms and conditions agreement',
            'time_slot' => 'Time slot',
        ];
    }

    public function messages(): array
    {
        return [
            'event_date.after' => 'The event date must be a future date.',
            'hall_booking_date.after_or_equal' => 'The booking date must be today or a future date.',
            'guest_count.min' => 'The minimum number of guests is :min.',
            'guest_count.max' => 'The maximum number of guests is :max.',
            'privacy_agreed.accepted' => 'You must accept the privacy policy to proceed.',
            'terms_agreed.accepted' => 'You must accept the terms and conditions to proceed.',
            'catholic_day2_date.after' => 'Day 2 date must be after Day 1 date.',
            'visit_date.after_or_equal' => 'Visit date must be today or a future date.',
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->all();
        
        // Map frontend IDs to database IDs
        $mappedData = [
            'hall_id' => $this->mapHallId($data['hall_id'] ?? null),
            'package_id' => $this->mapPackageId($data['package_id'] ?? null),
            'wedding_type_id' => $this->mapWeddingTypeId($data['customization_wedding_type'] ?? $data['wedding_type_id'] ?? null),
            'selected_menu_id' => $this->mapCateringMenuId($data['customization_catering_selected_menu_id'] ?? $data['selected_menu_id'] ?? null),
        ];

        // Handle date fields
        $eventDate = $data['event_date'] ?? ($data['wedding_date'] ?? $data['hall_booking_date'] ?? null);
        if ($eventDate && Carbon::hasFormat($eventDate, 'Y-m-d')) {
            $mappedData['event_date'] = $eventDate;
        }

        // Handle time fields
        $mappedData['start_time'] = $data['start_time'] ?? ($data['wedding_ceremony_time'] ?? null);
        $mappedData['end_time'] = $data['end_time'] ?? ($data['wedding_reception_time'] ?? null);

        // Handle guest count
        $mappedData['guest_count'] = $data['customization_guest_count'] ?? $data['guest_count'] ?? null;

        // Handle contact fields
        $mappedData['contact_name'] = $data['contact_name'] ?? null;
        $mappedData['contact_email'] = $data['contact_email'] ?? null;
        $mappedData['contact_phone'] = $data['contact_phone'] ?? null;
        $mappedData['visit_purpose'] = $data['contact_visit_purpose'] ?? $data['visit_purpose'] ?? null;
        $mappedData['visit_purpose_other'] = $data['contact_visit_purpose_other'] ?? $data['visit_purpose_other'] ?? null;
        $mappedData['special_requests'] = $data['contact_special_requests'] ?? $data['special_requests'] ?? null;

        // Handle wedding details
        $mappedData['wedding_groom_name'] = $data['wedding_groom_name'] ?? null;
        $mappedData['wedding_bride_name'] = $data['wedding_bride_name'] ?? null;
        $mappedData['wedding_groom_email'] = $data['wedding_groom_email'] ?? null;
        $mappedData['wedding_bride_email'] = $data['wedding_bride_email'] ?? null;
        $mappedData['wedding_groom_phone'] = $data['wedding_groom_phone'] ?? null;
        $mappedData['wedding_bride_phone'] = $data['wedding_bride_phone'] ?? null;
        $mappedData['wedding_date'] = $data['wedding_date'] ?? null;
        $mappedData['wedding_alternative_date1'] = $data['wedding_alternative_date1'] ?? null;
        $mappedData['wedding_alternative_date2'] = $data['wedding_alternative_date2'] ?? null;
        $mappedData['wedding_additional_notes'] = $data['wedding_additional_notes'] ?? null;

        // Handle wedding type specific fields
        $mappedData['wedding_type_time_slot'] = $data['customization_wedding_type_time_slot'] ?? null;
        $mappedData['catholic_day1_date'] = $data['customization_catholic_day1_date'] ?? null;
        $mappedData['catholic_day2_date'] = $data['customization_catholic_day2_date'] ?? null;

        // Handle agreements
        $mappedData['privacy_agreed'] = $data['privacy_agreed'] ?? ($data['wedding_privacy_agreed'] ?? null);
        $mappedData['terms_agreed'] = $data['terms_agreed'] ?? ($data['wedding_terms_agreed'] ?? null);

        // Handle JSON fields
        $mappedData['customization_decorations_additional'] = $this->processJsonField($data['customization_decorations_additional'] ?? null);
        $mappedData['customization_catering_custom'] = $this->processJsonField($data['customization_catering_custom'] ?? null);
        $mappedData['customization_additional_services_selected'] = $this->processJsonField($data['customization_additional_services_selected'] ?? null);

        // Add time slot placeholder for validation
        $mappedData['time_slot'] = 'placeholder';

        // Merge all mapped data
        $this->merge(array_filter($mappedData, function($value) {
            return $value !== null;
        }));

        Log::info('Prepared validation data', $this->all());
    }

    private function processJsonField($value): ?string
    {
        if (is_null($value)) return null;
        if (is_string($value)) {
            // Validate JSON
            json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }
        }
        if (is_array($value)) {
            return json_encode($value);
        }
        return null;
    }

    private function getValidatedHallId(): ?int
    {
        $hallId = $this->input('hall_id');
        return is_numeric($hallId) ? (int)$hallId : null;
    }

    // Mapping methods
    private function mapHallId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        $map = [
            'jubilee-ballroom' => 1,
            'grand-ballroom' => 2,
            'garden-pavilion' => 3,
            'royal-heritage-hall' => 4,
            'riverside-garden' => 5,
        ];
        
        return $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
    }

    private function mapPackageId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        $map = [
            'package-basic' => 2,
            'package-golden' => 3,
            'package-infinity' => 1,
        ];
        
        $allowedIds = [1, 2, 3];
        $id = $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
        
        return in_array($id, $allowedIds, true) ? $id : null;
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

    private function mapCateringMenuId($frontendId): ?int
    {
        if (!$frontendId) return null;
        
        $map = [
            'menu-01' => 1,
            'menu-02' => 2,
            'menu-03' => 3,
            'wedding-package-04' => 4,
            'wedding-package-05' => 5,
        ];
        
        return $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
    }
}