<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentNotification extends Model
{
    protected $fillable = [
        'user_id',
        'device_activity_id',
        'device_id',
        'event_type',
        'title',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(DeviceActivity::class, 'device_activity_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    /** Unread notifications for a user */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /** Mark this notification as read */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}
