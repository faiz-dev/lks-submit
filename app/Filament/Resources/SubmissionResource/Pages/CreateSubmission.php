<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateSubmission extends CreateRecord
{
    protected static string $resource = SubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = Auth::user();
        if (!$user->hasRole('peserta')) {
            Notification::make()
                ->warning()
                ->title('Gagal!')
                ->body('hanya peserta yang dapat melakukan submission')
                ->persistent()
                ->send();
            $this->halt();
        }

        $pesertaId = $user->peserta->id;

        // delete old one if any
        $submission = Submission::where('peserta_id', $pesertaId)->where('modul_id', $data['modul_id'])->first();
        if ($submission) {
            $submission->filepath = $data['file_project'];
            $submission->filesize = Storage::disk('local')->size($data['file_project']);
            $submission->save();
        } else {
            $data['peserta_id'] = $user->peserta->id;
            $data['filepath'] = $data['file_project'];
            $data['filesize'] = Storage::disk('local')->size($data['file_project']);
            $submission = static::getModel()::create($data);
        }

        // record history

        SubmissionHistory::create([
            "submission_id" => $submission->id,
            "filepath"  => $submission->filepath,
            "filesize" => $submission->filesize,
        ]);

        return $submission;
    }
}
