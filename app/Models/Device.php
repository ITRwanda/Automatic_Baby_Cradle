<?php

namespace App\Models;

use App\Models\Family;
use Illuminate\Database\Eloquent\Model;

class Device extends Model {
    protected $fillable = ['device_name', 'device_token', 'family_id', 'user_id'];


    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function user() {
        // caregiver assigned to this device (stored in devices.user_id)
        return $this->belongsTo(User::class, 'user_id');
    }
}





