<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecoleville extends Model
{
    use HasFactory;

    protected $fillable = [
        'ville',
        'type',
        'logo',
        'ecole_id',
    ];

    // Define the relationship with the Ecole model
    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'ecole_id');
    }

    // Add accessor to include ecole name in the serialized data
    protected $appends = ['ecole_name'];

    public function getEcoleNameAttribute()
    {
        return $this->ecole ? $this->ecole->name : null;
    }
}
