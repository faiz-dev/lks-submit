<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSubmission extends ViewRecord
{
    protected static string $resource = SubmissionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('modul.judul'),
                TextEntry::make('peserta.nama'),
                TextEntry::make('created_at')
                    ->label('Diunggah Pada'),
                TextEntry::make('filepath'),
                TextEntry::make('filesize')
                    ->formatStateUsing(fn(string $state) => formatBytes($state)),
            ]);
    }
}
