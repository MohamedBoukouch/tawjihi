<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id_user', 'id_publication', 'text'
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'id_publication');
    }
}
