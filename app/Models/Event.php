<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'begins_at', 'ends_at', 'place'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
