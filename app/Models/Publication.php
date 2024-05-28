<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'localisation', 
        'type',
        'date',
        'titel', 
        'description', 
        'link',
        'numberlike',
        'numbercomment', 
        'link_titel', 
        'file'
    ];

    // Assuming 'file' attribute is casted as an array
    protected $casts = [
        'file' => 'array',
    ];
}
