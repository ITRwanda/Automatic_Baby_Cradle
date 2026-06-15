<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceActivity extends Model
{
    protected $fillable = [
        'device_id',
        'event_type',
        'payload',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}

