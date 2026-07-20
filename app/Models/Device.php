<?php

namespace App\Models;

use App\Models\Family;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_name', 'device_token', 'family_id', 'user_id'];

    // ── Family this device belongs to ───────────────────────
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * All caregivers assigned to this device (many-to-many via device_user pivot).
     */
    public function caregivers()
    {
        return $this->belongsToMany(User::class, 'device_user')
                    ->withTimestamps();
    }

    /**
     * Legacy single-caregiver accessor kept for backward compatibility.
     * Returns the first assigned caregiver, or null.
     * Use caregivers() for the full list.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
