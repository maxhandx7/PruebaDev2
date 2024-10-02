<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'user_id',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array', // Para que se trate las etiquetas como JSON
    ];

    /* para obtener todas las etiquetas vinculadas a esta nota. */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
