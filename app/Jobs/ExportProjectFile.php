<?php

namespace App\Jobs;

use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ExportProjectFile implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Submission $submission)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newFileName = 'Project-peserta-'.$this->submission->peserta->nomor.'.zip';
        Storage::disk('local')->copy($this->submission->filepath, 'ready/'.$newFileName);

        $this->submission->moved = Carbon::now();
        $this->submission->save();
    }
}
