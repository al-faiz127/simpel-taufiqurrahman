<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPelatihanResource\Pages;
use App\Filament\Resources\JenisPelatihanResource\RelationManagers;
use App\Models\jenis_pelatihan;
use App\Models\JenisPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisPelatihanResource extends Resource
{
    protected static ?string $model = jenis_pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationLabel = 'Jenis pelatihan';

    protected static ?string $pluralLabel = 'Jenis pelatihan';

    protected static ?string $slug = 'Jenis-pelatihan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis')
                    ->label('Jenis Pelatihan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3),
            ])->columns(1); ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap()
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
            'index' => Pages\ListJenisPelatihans::route('/'),
            // 'create' => Pages\CreateJenisPelatihan::route('/create'),
            // 'edit' => Pages\EditJenisPelatihan::route('/{record}/edit'),
        ];
    }
}
