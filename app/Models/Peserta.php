<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $fillable = [
        'nama', 'nomor', 'asal_sekolah', 'verified'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
}
