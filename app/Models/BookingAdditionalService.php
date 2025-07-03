<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAdditionalService extends Model
{
    protected $table = 'booking_additional_services';

    protected $fillable = [
        'booking_id',
        'service_id',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AdditionalService::class, 'service_id');
    }

    public function additionalService(): BelongsTo
    {
        return $this->belongsTo(AdditionalService::class, 'service_id');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if ($model->service_id && !AdditionalService::where('id', $model->service_id)->exists()) {
                throw new \Exception('Invalid additional service selected.');
            }
        });
    }
}