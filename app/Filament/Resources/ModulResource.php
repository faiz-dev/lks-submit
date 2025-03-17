<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModulResource\Pages;
use App\Filament\Resources\ModulResource\RelationManagers;
use App\Models\Modul;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModulResource extends Resource
{
    protected static ?string $model = Modul::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Modul')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul Modul')
                            ->required(),
                        TextInput::make('deskripsi'),
                        TextInput::make('duration_in_minutes')
                            ->label('Durasi')
                            ->suffix('menit'),
                        Toggle::make('submission_open')
                            ->inline(false),
                    ])
                    ->columnSpan(1),
                Section::make('File Soal, Bahan, atau tambahan lainnya')
                    ->schema([
                        Repeater::make('files')
                            ->relationship('files')
                            ->schema([
                                FileUpload::make('filepath')
                                    ->downloadable()
                                    ->disk('public')
                                    ->preserveFilenames()
                                    ->required()
                            ])
                    ])
                    ->columnSpan(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul'),
                TextColumn::make('deskripsi'),
                TextColumn::make('duration_in_minutes')
                    ->label('Durasi'),
                IconColumn::make('submission_open')
                    ->label('Pengumpulan dibuka')
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-c-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModuls::route('/'),
            'create' => Pages\CreateModul::route('/create'),
            'view' => Pages\ViewModul::route('/{record}'),
            'edit' => Pages\EditModul::route('/{record}/edit'),
        ];
    }
}
