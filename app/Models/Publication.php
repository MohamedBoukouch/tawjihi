<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'localisation',
        'type',
        'titel',
        'description',
        'link',
        'link_titel',
        'file',
        'numberlike',
        'numbercomment',
        'date',
    ];

    protected $casts = [
        'file' => 'array',
    ];

    public function likes()
    {
        return $this->hasMany(Like::class, 'publication_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'publication_id');
    }
}
