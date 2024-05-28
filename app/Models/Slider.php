<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders'; // If your table name differs from plural of model name

    protected $primaryKey = 'id'; // If your primary key is not 'id'

    public $timestamps = true; // Set to false if you don't want to use timestamps

    protected $fillable = [
        'id',
        'titel1',
        'titel2',
        'link',
        'titel_link',
        'image',
    ];

    // Optionally define any relationships here if needed
}
