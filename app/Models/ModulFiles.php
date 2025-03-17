<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulFiles extends Model
{
    protected $fillable = [
        'modul_id',
        'filepath',
    ];


    public function modul() {
        return $this->belongsTo(Modul::class, 'modul_id', 'id');
    }
}
