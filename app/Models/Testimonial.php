<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'booking_id',
        'groom_name',
        'bride_name',
        'groom_email',
        'bride_email',
        'groom_phone',
        'bride_phone',
        'groom_photo',
        'bride_photo',
        'couple_photo',
        'wedding_date',
        'venue_used',
        'package_used',
        'rating',
        'title',
        'review_text',
        'service_ratings',
        'would_recommend',
        'favorite_aspect',
        'improvement_suggestions',
        'is_featured',
        'is_approved',
        'approved_by',
        'approved_at',
        'display_on_website',
        'social_media_consent',
        'metadata'
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'service_ratings' => 'array',
        'would_recommend' => 'boolean',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'display_on_website' => 'boolean',
        'social_media_consent' => 'boolean',
        'approved_at' => 'datetime',
        'metadata' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForWebsite($query)
    {
        return $query->where('display_on_website', true)
                    ->where('is_approved', true);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getCoupleNameAttribute()
    {
        return $this->groom_name . ' & ' . $this->bride_name;
    }

    public function getGroomPhotoUrlAttribute()
    {
        if ($this->groom_photo) {
            return asset('storage/testimonials/photos/' . $this->groom_photo);
        }
        return asset('images/default-groom.jpg');
    }

    public function getBridePhotoUrlAttribute()
    {
        if ($this->bride_photo) {
            return asset('storage/testimonials/photos/' . $this->bride_photo);
        }
        return asset('images/default-bride.jpg');
    }

    public function getCouplePhotoUrlAttribute()
    {
        if ($this->couple_photo) {
            return asset('storage/testimonials/photos/' . $this->couple_photo);
        }
        return asset('images/default-couple.jpg');
    }

    public function getStarRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getAverageServiceRatingAttribute()
    {
        if (!$this->service_ratings || !is_array($this->service_ratings)) {
            return 0;
        }

        $ratings = array_values($this->service_ratings);
        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }

    public function getFormattedWeddingDateAttribute()
    {
        return $this->wedding_date ? $this->wedding_date->format('F j, Y') : null;
    }

    // Methods
    public function approve($approvedBy = null)
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $approvedBy,
            'approved_at' => now()
        ]);
    }

    public function feature()
    {
        $this->update(['is_featured' => true]);
    }

    public function unfeature()
    {
        $this->update(['is_featured' => false]);
    }

    public function enableWebsiteDisplay()
    {
        $this->update(['display_on_website' => true]);
    }

    public function disableWebsiteDisplay()
    {
        $this->update(['display_on_website' => false]);
    }

    public static function getServiceRatingCategories()
    {
        return [
            'venue_quality' => 'Venue Quality',
            'food_quality' => 'Food Quality',
            'service_quality' => 'Service Quality',
            'decoration' => 'Decoration',
            'coordination' => 'Event Coordination',
            'value_for_money' => 'Value for Money',
            'cleanliness' => 'Cleanliness',
            'staff_friendliness' => 'Staff Friendliness'
        ];
    }

    public static function createFromBooking($bookingId, $data)
    {
        $booking = Booking::with(['user', 'hall', 'package'])->find($bookingId);
        
        if (!$booking) {
            throw new \Exception('Booking not found');
        }

        return self::create([
            'user_id' => $booking->user_id,
            'booking_id' => $bookingId,
            'groom_name' => $data['groom_name'],
            'bride_name' => $data['bride_name'],
            'groom_email' => $data['groom_email'] ?? null,
            'bride_email' => $data['bride_email'] ?? null,
            'groom_phone' => $data['groom_phone'] ?? null,
            'bride_phone' => $data['bride_phone'] ?? null,
            'wedding_date' => $booking->event_date,
            'venue_used' => $booking->hall->name ?? $booking->hall_name,
            'package_used' => $booking->package->name ?? 'Custom Package',
            'rating' => $data['rating'],
            'title' => $data['title'],
            'review_text' => $data['review_text'],
            'service_ratings' => $data['service_ratings'] ?? [],
            'would_recommend' => $data['would_recommend'] ?? true,
            'favorite_aspect' => $data['favorite_aspect'] ?? null,
            'improvement_suggestions' => $data['improvement_suggestions'] ?? null,
            'display_on_website' => $data['display_on_website'] ?? true,
            'social_media_consent' => $data['social_media_consent'] ?? false,
            'is_approved' => false, // Requires admin approval
            'metadata' => [
                'booking_total' => $booking->total_amount,
                'guest_count' => $booking->guest_count,
                'submission_ip' => request()->ip(),
                'submission_date' => now()->toISOString()
            ]
        ]);
    }

    public function getServiceRatingForCategory($category)
    {
        return $this->service_ratings[$category] ?? 0;
    }

    public function hasPhoto($type = 'couple')
    {
        switch ($type) {
            case 'groom':
                return !empty($this->groom_photo);
            case 'bride':
                return !empty($this->bride_photo);
            case 'couple':
                return !empty($this->couple_photo);
            default:
                return false;
        }
    }

    public static function getFeaturedTestimonials($limit = 6)
    {
        return self::approved()
                   ->forWebsite()
                   ->featured()
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public static function getRecentTestimonials($limit = 10)
    {
        return self::approved()
                   ->forWebsite()
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public static function getHighRatedTestimonials($minRating = 4, $limit = 8)
    {
        return self::approved()
                   ->forWebsite()
                   ->highRated($minRating)
                   ->orderBy('rating', 'desc')
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    public static function getAverageRating()
    {
        return self::approved()->avg('rating') ?: 0;
    }

    public static function getTotalReviews()
    {
        return self::approved()->count();
    }

    public static function getRatingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = self::approved()->where('rating', $i)->count();
        }
        return $distribution;
    }
}