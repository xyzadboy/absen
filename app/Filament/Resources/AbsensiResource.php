<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function getPluralLabel(): string
    {
        return 'Absensi'; // Ubah label jamak di sini
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('anggota_id')
                    ->relationship('anggota', 'nama')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nim} - {$record->nama}")
                    ->searchable(['nim', 'nama'])
                    ->preload()
                    ->required()
                    ->label('Anggota (NIM - Nama)'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Hadir' => 'Hadir',])
                    ->default('Hadir')
                    ->required()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('anggota.nim')->searchable()->label('NIM'),
                Tables\Columns\TextColumn::make('anggota.email')->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
            ])
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
