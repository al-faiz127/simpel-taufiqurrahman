<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'User';

    protected static ?string $pluralLabel = 'User';

    protected static ?string $slug = 'User';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('No Telepon/ Wa aktif')
                    ->required()
                    ->maxLength(255),
                Forms\components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('username')->label('Username')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('No Telepon/ Wa aktif')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->sortable()
                    ->badge()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('verified_at')
                    ->formatStateUsing(fn(User $record) => $record->verified_at ? 'Terverifkasi' : "Belum Terverifikasi")
                    ->description(fn(User $record) => $record->verified_at)
                    ->badge()
                    ->default('Belum Terverifikasi')
                    ->color(function (string $state) {
                        $status = $state === 'Belum Terverifikasi';

                        return match ($status) {
                            false => 'success',
                            true => 'danger'
                        };
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('verified_at')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->hidden(fn(User $record) => $record->verified_at !== null)
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi user ini?')
                        ->modalSubmitActionLabel('Ya, Verifikasi')
                        ->action(function (User $record) {
                            $record->update([
                                'verified_at' => now(),
                            ]);
                        }),
                    Tables\Actions\Action::make('verified_at_cancel')
                        ->label('Batalkan Verifikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->hidden(fn(User $record) => $record->verified_at === null)
                        ->requiresConfirmation()
                        ->modalHeading('Batalkan Verifikasi')
                        ->modalDescription('Apakah Anda yakin ingin membatalkan verifikasi user ini?')
                        ->modalSubmitActionLabel('Ya, Batalkan')
                        ->action(function (User $record) {
                            $record->update([
                                'verified_at' => null,
                            ]);
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('gray'),
                    Tables\Actions\DeleteAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
