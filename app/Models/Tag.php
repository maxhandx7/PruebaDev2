<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /* para obtener todas las notas vinculadas a este tag. */
    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

}
