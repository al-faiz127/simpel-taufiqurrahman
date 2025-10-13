<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BangkomResource\Pages;
use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource\RelationManagers;
use App\Models\Bangkom;
use Doctrine\DBAL\Schema\Schema;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use GuzzleHttp\Psr7\FnStream;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Carbon;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Bangkom';

    protected static ?string $pluralLabel = 'Bangkom';

    protected static ?string $slug = "Bangkom";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('kegiatan')
                        ->schema([
                            Select::make('user_id')
                                ->label('pelaksana')
                                ->relationship(
                                    name: 'user',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', 'pelaksana'))
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('instansi_id')
                                ->label('Instansi Pelaksana')
                                ->relationship('instansi', 'nama')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('unit')
                                ->required()
                                ->label('Unit Kerja / Perangkat Daerah Pelaksana*'),
                            TextInput::make('kegiatan')
                                ->required()
                                ->label('Nama Kegiatan*')
                                ->maxLength(255),
                            Select::make('jenis_pelatihan_id')
                                ->label('Jenis Pelatihan*')
                                ->relationship('jenis_pelatihan', 'jenis')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('bentuk_pelatihan_id')
                                ->label('Bentuk Pelatihan')
                                ->relationship('bentuk_pelatihan', 'bentuk')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('sasaran_id')
                                ->label('Sasaran')
                                ->relationship('Sasaran', 'sasaran')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                    Wizard\Step::make('Waktu,Tempat dan Kuota')
                        ->schema([
                            DatePicker::make('mulai')
                                ->label('tanggal mulai')
                                ->required()
                                ->displayFormat('d/m/y')
                                ->minDate(now())
                                ->suffixicon('heroicon-o-calendar')
                                ->native(false),
                            DatePicker::make('selesai')
                                ->label('tanggal selesai')
                                ->required()
                                ->displayFormat('d/m/y')
                                ->minDate(now())
                                ->suffixicon('heroicon-o-calendar')
                                ->native(false),
                            TextInput::make('tempat')
                                ->label('Tempat')
                                ->required()
                                ->suffixIcon('heroicon-o-map-pin'),
                            Textarea::make('alamat')
                                ->label('Alamat')
                                ->required(),
                            TextInput::make('kuota')
                                ->label('Kuota')
                                ->numeric()
                                ->required()
                                ->suffixIcon('heroicon-o-users'),
                        ]),
                    Wizard\Step::make('panitia')
                        ->schema([
                            TextInput::make('panitia')
                                ->label('Nama Panitia')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('tlpnpanitia')
                                ->label('Telepon Panitia')
                                ->required()
                                ->tel(),
                        ]),
                    Wizard\Step::make('kurikulum')
                        ->schema([
                            TableRepeater::make('kurikulum')
                                ->headers([
                                    Header::make('narasumber')->label('Nararumber'),
                                    Header::make('materi')->label('Materi'),
                                    Header::make('jam')->label('Jam Pelajaran'),

                                ])
                                ->schema([
                                    TextInput::make('narasumber')
                                        ->placeholder('isi narasumber')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('materi')
                                        ->placeholder('isi materi')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('jam')
                                        ->placeholder('isi jam pelajaran')
                                        ->numeric(),
                                ])

                                ->addActionLabel('tambah kurikulum'),
                        ]),
                    Wizard\Step::make('Deskripsi, Kegiatan Dan Persyaratan')
                        ->schema([
                            TextInput::make('deskripsi')
                                ->label('Deskripsi Kegiatan')
                                ->required(),
                            TextInput::make('persyaratan')
                                ->label('Persyaratan Peserta')
                                ->required(),
                        ]),

                ])
                ->submitAction(new \Illuminate\Support\HtmlString('
                        <button 
                            type="submit" 
                            wire:click="create"
                            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-warning-600 hover:bg-warning-500 focus:bg-warning-700 focus:ring-offset-warning-700"
                        >
                            Submit
                        </button>
                    '))
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                tables\Columns\TextColumn::make('kegiatan')
                    ->label('Nama Kegiatan'),
                tables\Columns\TextColumn::make('jenis_pelatihan.jenis')
                    ->label('Jenis Pelatihan'),
                tables\Columns\TextColumn::make('mulai')
                    ->label('tanggal pelatihan')
                    ->formatStateUsing(function ($record) {
                        if (!$record->mulai || !$record->selesai) {
                            return "-";
                        }

                        $mulai = Carbon::parse($record->mulai)->translatedFormat("d M");
                        $akhir = Carbon::parse($record->selesai)->translatedFormat("d M");

                        return "$mulai<small> <span class='text-gray-500 text-sm'>s/d</span> </small> $akhir";
                    })
                    ->html(),
                tables\Columns\TextColumn::make('kuota')
                    ->label('Kuota'),
                tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state instanceof BangkomStatus ? $state->getColor() : BangkomStatus::from($state)->getColor())
                    ->icon(fn($state) => $state instanceof BangkomStatus ? $state->getIcon() : BangkomStatus::from($state)->getIcon())
                    ->searchable(),
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
            'index' => Pages\ListBangkoms::route('/'),
            'create' => Pages\CreateBangkom::route('/create'),
            'edit' => Pages\EditBangkom::route('/{record}/edit'),
        ];
    }
}
