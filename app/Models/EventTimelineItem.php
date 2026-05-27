<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTimelineItem extends Model
{
    protected $fillable = [
        'booking_id',
        'beo_id',
        'item_time',
        'title',
        'description',
        'responsible_team',
        'sort_order',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function beo()
    {
        return $this->belongsTo(BanquetEventOrder::class, 'beo_id');
    }
}
