<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Filament\Resources\PesertaResource\RelationManagers;
use App\Models\Peserta;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Rawilk\FilamentPasswordInput\Password;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2
                    ])
                    ->schema([
                        Section::make('Data Peserta')
                            ->schema([
                                TextInput::make('nomor')
                                    ->label('Nomor Peserta')
                                    ->unique(ignoreRecord: true)
                                    ->numeric()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set) {
                                        $set('user.name', 'PESERTA ' . $state);
                                        $set('user.email', 'peserta_' . $state . '@itssb.id');
                                        $set('user.password', Str::password(8));
                                    })
                                    ->required(),
                                TextInput::make('nama')
                                    ->label('Nama Peserta')
                                    ->maxLength(40)
                                    ->required(),
                                TextInput::make('asal_sekolah')
                                    ->label('Asal Sekolah')
                                    ->maxLength(40)
                                    ->required(),
                                Toggle::make('verified')
                                    ->inline(false)
                                    ->default(true),
                            ])
                            ->columnSpan(1),
                        Section::make('Data Login Peserta')
                            ->relationship('user')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->readonly(),
                                TextInput::make('email')
                                    ->label('username')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->autocomplete(false),
                                Password::make('password')
                                    ->label('Password')
                                    ->length(8)
                                    ->copyable()
                                    ->regeneratePassword(fn() => Str::password(8)),
                                Select::make('role')
                                    ->relationship('roles', 'name')
                                    ->default('2')
                            ])
                            ->columnSpan(1)
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor'),
                TextColumn::make('nama'),
                TextColumn::make('asal_sekolah'),
                TextColumn::make('user.email')
            ])
            ->defaultSort('nomor')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}
