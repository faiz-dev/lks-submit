<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionHistory extends Model
{
    protected $fillable = [
        'submission_id',
        'filesize',
        'filepath'
    ];
    
    public function modul() {
        return $this->belongsTo(Modul::class, 'modul_id', 'id');
    }

    public function peserta() {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}
