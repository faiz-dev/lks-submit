<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'submission_open',
        'duration_in_minutes',
    ];

    public function files() {
        return $this->hasMany(ModulFiles::class, 'modul_id', 'id');
    }
}
