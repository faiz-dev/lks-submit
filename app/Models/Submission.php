<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'modul_id',
        'peserta_id',
        'filesize',
        'filepath',
        'moved'
    ];
    
    public function modul() {
        return $this->belongsTo(Modul::class, 'modul_id', 'id');
    }

    public function peserta() {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function histories() {
        return $this->hasMany(SubmissionHistory::class, 'submission_id', 'id');
    }
}
