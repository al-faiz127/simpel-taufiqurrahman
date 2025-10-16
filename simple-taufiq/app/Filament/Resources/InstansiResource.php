<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstansiResource\Pages;
use App\Models\Instansi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstansiResource extends Resource
{
    protected static ?string $model = Instansi::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Data';
    protected static ?string $navigationLabel = 'Instansi';
    protected static ?string $pluralLabel = 'Instansi';
    protected static ?string $slug = 'Instansi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Nama
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                // 2. Alamat
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(3)
                    ->required(),

                // 3. Bagian Kontak - Menggunakan Fieldset agar mirip tampilan modal yang ringkas
                Forms\Components\Fieldset::make('Kontak')
                    ->schema([
                        // Input Telepon
                        Forms\Components\TextInput::make('telepon')
                            ->label('Telepon')
                            ->tel()
                            ->numeric(),

                        // Input Email
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true, table: 'instansi'),

                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->suffixIcon('heroicon-o-globe-alt'),
                    ])
                    ->columns(1),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->description(fn($record) => $record->alamat ? 'Alamat: ' . $record->alamat : 'Alamat: -')
                    ->searchable()
                    ->sortable()
                    ->grow(),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->getStateUsing(function ($record) {
                        $notlpn = $record->telepon ?: '-';
                        $email = $record->email ?: '-';
                        $web = $record->website ?: '-';
                        return
                            "<div>
                            <div>No. Telepon : <span class=\"text-xs text-gray-500\">{$notlpn}</span></div> 
                            <div>Email : <span class=\"text-xs text-gray-500\">{$email}</span></div>
                             <div>Website : <span class=\"text-xs text-gray-500\">{$web}</span></div>
                            </div>";



                        return implode('<br>', $kontak);
                    })
                    ->html()
                    ->wrap(false)
                    ->alignment('left')
                    ->width('300px'),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->modalWidth('lg'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalWidth('md')
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }




    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstansis::route('/'),
        ];
    }
}
