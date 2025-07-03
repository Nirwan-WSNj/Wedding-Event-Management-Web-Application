<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\VisitSchedule;

class Hall extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'price',
        'image',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * App\Models\Hall
     *
     * @property float $price
     * @property int $id
     * @property string $name
     * @property string|null $description
     * @property int $capacity
     * @property float $price
     * @property string|null $image
     * @property bool $is_active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Booking[] $bookings
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HallAvailability[] $availability
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VisitSchedule[] $visitSchedules
     */

    // Relationships
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function availability(): HasMany
    {
        return $this->hasMany(HallAvailability::class);
    }

    public function visitSchedules(): HasMany
    {
        return $this->hasMany(VisitSchedule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableOn($query, $date)
    {
        return $query->whereDoesntHave('bookings', function ($query) use ($date) {
            $query->where('event_date', $date)
                  ->where('status', '!=', Booking::STATUS_CANCELLED);
        });
    }

    public function scopeWithinCapacity($query, $guestCount)
    {
        return $query->where('capacity', '>=', $guestCount);
    }

    // Methods
    public function isAvailable($date, $startTime, $endTime): bool
    {
        return !$this->bookings()
            ->where('event_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->exists();
    }

    public static function checkAvailability($hallId, $date, $startTime, $endTime)
    {
        // Convert date and times to Carbon instances for comparison
        $date = Carbon::parse($date);
        $startDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime);
        $endDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $endTime);

        // Check if there are any overlapping bookings
        $existingBookings = Booking::where('hall_id', $hallId)
            ->where('event_date', $date->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($startDateTime, $endDateTime) {
                $query->where(function($q) use ($startDateTime, $endDateTime) {
                    // Check if new booking starts during an existing booking
                    $q->where('start_time', '<=', $startDateTime->format('H:i'))
                      ->where('end_time', '>', $startDateTime->format('H:i'));
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    // Check if new booking ends during an existing booking
                    $q->where('start_time', '<', $endDateTime->format('H:i'))
                      ->where('end_time', '>=', $endDateTime->format('H:i'));
                })->orWhere(function($q) use ($startDateTime, $endDateTime) {
                    // Check if new booking completely encompasses an existing booking
                    $q->where('start_time', '>=', $startDateTime->format('H:i'))
                      ->where('end_time', '<=', $endDateTime->format('H:i'));
                });
            })
            ->exists();

        // Also check visit schedules for the same day
        $existingVisits = VisitSchedule::where('hall_id', $hallId)
            ->where('visit_date', $date->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->exists();

        // Return true if no overlapping bookings or visits found
        return !$existingBookings && !$existingVisits;
    }

    public function getAvailableDates($startDate, $endDate): array
    {
        $bookedDates = $this->bookings()
            ->whereBetween('event_date', [$startDate, $endDate])
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->pluck('event_date')
            ->toArray();

        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            if (!in_array($current->format('Y-m-d'), $bookedDates)) {
                $dates[] = $current->format('Y-m-d');
            }
            $current->addDay();
        }

        return $dates;
    }

    public function getUpcomingBookings($limit = 10)
    {
        return $this->bookings()
            ->where('event_date', '>=', Carbon::today())
            ->where('status', Booking::STATUS_CONFIRMED)
            ->orderBy('event_date')
            ->limit($limit)
            ->get();
    }

    public function canAccommodate($guestCount): bool
    {
        return $this->capacity >= $guestCount;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($hall) {
            // Prevent deletion if there are upcoming confirmed bookings
            if ($hall->bookings()
                ->where('event_date', '>=', Carbon::today())
                ->where('status', Booking::STATUS_CONFIRMED)
                ->exists()) {
                return false;
            }
        });
    }
}