<?php

namespace App\Models;

use App\Models\User;
use App\Models\Device;
use Illuminate\Database\Eloquent\Model;

class Family extends Model {
    protected $fillable = ['family_name', 'parent_id'];

    public function parent() {


        return $this->belongsTo(User::class, 'parent_id');
    }
    public function members() {
        return $this->hasMany(User::class);
    }
    public function devices() {
        return $this->hasMany(Device::class);
    }
}

