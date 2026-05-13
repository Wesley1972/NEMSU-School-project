<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    // Allow these columns to be written to
    protected $fillable = [
        'key',
        'value',
    ];
}
