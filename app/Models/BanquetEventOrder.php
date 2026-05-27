<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanquetEventOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'beo_number',
        'booking_id',
        'prepared_by',
        'event_date',
        'setup_time',
        'guest_arrival_time',
        'service_start_time',
        'event_end_time',
        'final_guest_count',
        'room_setup',
        'menu_notes',
        'decor_notes',
        'av_notes',
        'staffing_notes',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'approved_at' => 'datetime',
        'final_guest_count' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function timelineItems()
    {
        return $this->hasMany(EventTimelineItem::class, 'beo_id')->orderBy('sort_order');
    }
}
