<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\Booking;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        return \Illuminate\Support\Facades\Auth::check() && 
               $booking && 
               ($booking->user_id === \Illuminate\Support\Facades\Auth::id() || (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->isAdmin()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $booking = $this->route('booking');

        return [
            'hall_id' => ['required', 'exists:halls,id'],
            'package_id' => ['required', 'exists:packages,id'],
            'event_date' => ['required', 'date', 'after:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'customization_guest_count' => ['required', 'integer', 'min:10', 'max:1000'],
            'customization_wedding_type' => ['required', 'string', 'exists:wedding_types,id'],
            'customization_wedding_type_time_slot' => ['nullable', 'string'],
            'customization_catholic_day1_date' => ['nullable', 'date'],
            'customization_catholic_day2_date' => ['nullable', 'date'],
            'customization_decorations_additional' => ['nullable', 'json'],
            'customization_additional_services_selected' => ['nullable', 'json'],
            'customization_catering_selected_menu_id' => ['required', 'exists:catering_menus,id'],
            'customization_catering_custom' => ['nullable', 'json'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'hall_id' => 'Hall',
            'package_id' => 'Package',
            'event_date' => 'Event date',
            'start_time' => 'Start time',
            'end_time' => 'End time',
            'guest_count' => 'Number of guests',
            'wedding_type' => 'Wedding type',
            'special_requests' => 'Special requests',
            'decorations' => 'Decorations',
            'services' => 'Additional services',
            'catering_menu' => 'Catering menu',
            'catering_items' => 'Catering items',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_date.after' => 'The event date must be a future date.',
            'end_time.after' => 'The end time must be after the start time.',
            'guest_count.min' => 'The minimum number of guests is :min.',
            'guest_count.max' => 'The maximum number of guests is :max.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
   private function mapCateringMenuId($frontendId)
{
    if (!$frontendId) return null;
    $map = [
        'menu-01' => 1,
        'menu-02' => 2,
        'menu-03' => 3,
        'menu-04' => 4,
        'menu-05' => 5,
    ];
    return $map[$frontendId] ?? (is_numeric($frontendId) ? (int)$frontendId : null);
}

protected function prepareForValidation(): void
{
    $this->merge([
        'customization_catering_selected_menu_id' => $this->mapCateringMenuId($this->customization_catering_selected_menu_id),
    ]);

    if ($this->has('start_time') && $this->has('end_time') && $this->event_date) {
        try {
            $start = Carbon::createFromFormat('Y-m-d H:i', $this->event_date . ' ' . $this->start_time);
            $end = Carbon::createFromFormat('Y-m-d H:i', $this->event_date . ' ' . $this->end_time);
            if ($start && $end && $end->greaterThan($start)) {
                $this->merge([
                    'start_time' => $start->format('H:i'),
                    'end_time' => $end->format('H:i'),
                ]);
            } else {
                $this->merge(['start_time' => null, 'end_time' => null]);
            }
        } catch (\Exception $e) {
            $this->merge(['start_time' => null, 'end_time' => null]);
            \Illuminate\Support\Facades\Log::warning('Time parsing failed: ' . $e->getMessage(), $this->all());
        }
    }
}

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to update this booking.');
    }
}
