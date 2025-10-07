<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SasaranResource\Pages;
use App\Filament\Resources\SasaranResource\RelationManagers;
use App\Models\Sasaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SasaranResource extends Resource
{
    protected static ?string $model = Sasaran::class;

    protected static ?string $navigationIcon = 'phosphor-crosshair';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationLabel = 'Sasaran';

    protected static ?string $pluralLabel = 'Sasaran';

    protected static ?string $slug = 'Sasaran';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sasaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->maxLength(65535),
            ])->columns(1); ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('sasaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->sortable()
                    ->searchable()
                    ->grow(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalWidth('md')
                    ->requiresConfirmation(),
            ])
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
            'index' => Pages\ListSasarans::route('/'),
            // 'create' => Pages\CreateSasaran::route('/create'),
            // 'edit' => Pages\EditSasaran::route('/{record}/edit'),
        ];
    }
}
