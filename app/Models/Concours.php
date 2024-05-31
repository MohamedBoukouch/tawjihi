<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concours extends Model
{
    use HasFactory;

    protected $fillable = [
        'annee_scolaire',
        'pdf',
        'niveau',
        'ecole_id',
        'ville_id',
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function ville()
    {
        return $this->belongsTo(EcoleVille::class, 'ville_id');
    }
}
