<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\Pages\RelationSubmissionHistory;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Filament\Resources\SubmissionResource\RelationManagers\HistoriesRelationManager;
use App\Jobs\ExportProjectFile;
use App\Models\Modul;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->schema([
                        Select::make('modul_id')
                            ->hint('Jika tidak muncul berarti modul submission belum dibuka')
                            ->options(Modul::where('submission_open', true)->pluck('judul', 'id'))
                            ->label('Modul')
                            ->columnSpan(2),
                        FileUpload::make('file_project')
                            ->directory('raw')
                            ->disk('local')
                            ->required()
                            ->maxSize(500000)
                            ->acceptedFileTypes([
                                'application/zip',
                                'application/x-compress',
                                'application/x-compressed',
                                'application/x-gzip',
                                'application/x-stuffit',
                                'application/x-tar',
                                'application/x-winzip',
                                'application/x-zip',
                                'application/x-zip-compressed',
                                'multipart/x-zip',
                            ])
                            ->columnSpan(2)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user->hasRole('peserta')) {
                    return $query
                        ->select(DB::raw('*'))
                        ->orderBy('created_at', 'desc')
                        ->where('peserta_id', $user->peserta->id);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('peserta.nama'),
                TextColumn::make('modul.judul'),
                TextColumn::make('created_at'),
                TextColumn::make('filepath'),
                TextColumn::make('filesize')
                    ->formatStateUsing(fn(string $state) => formatBytes($state)),
                TextColumn::make('moved')
                    ->label('Ready'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')
                        ->action(function (Collection $records) {
                            foreach ($records as $submission) {
                                ExportProjectFile::dispatch($submission);
                            }
                            Notification::make()
                                ->warning()
                                ->title('On Process')
                                ->body($records->count() . ' Submission sedang disiapkan')
                                ->persistent()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'view' => Pages\ViewSubmission::route('/{record}'),
        ];
    }
}
