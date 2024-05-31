<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat';
    protected $fillable = [
        'message', 'date', 'expediteur', 'destinataire', 'user_to_admin', 'is_active'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'expediteur');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'destinataire');
    }
}
