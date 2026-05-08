<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'waste_type',
        'weight',
        'pickup_address',
        'location_type',
        'status',
        'points_earned',
    ];

    // A Schedule belongs to a single User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}