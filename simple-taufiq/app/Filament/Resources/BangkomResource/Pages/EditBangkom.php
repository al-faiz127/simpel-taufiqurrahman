<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;

class EditBangkom extends EditRecord
{
    protected static string $resource = BangkomResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kegiatan')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengelola')
                            ->relationship('user', 'name')
                            ->dehydrated(),

                        Select::make('instansi_id')
                            ->label('Instansi Pelaksana')
                            ->relationship('instansi', 'nama')
                            ->dehydrated(),

                        TextInput::make('unit')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->dehydrated(),

                        TextInput::make('kegiatan')
                            ->label('Nama Kegiatan')
                            ->dehydrated(),

                        Select::make('jenis_pelatihan_id')
                            ->label('Jenis Pelatihan')
                            ->relationship('jenis_pelatihan', 'jenis')
                            ->dehydrated(),

                        Select::make('bentuk_pelatihan_id')
                            ->label('Bentuk Pelatihan')
                            ->relationship('bentuk_pelatihan', 'bentuk')
                            ->dehydrated(),

                        Select::make('sasaran_id')
                            ->label('Sasaran')
                            ->relationship('sasaran', 'sasaran')
                            ->dehydrated(),
                    ])
                    ->collapsible()
                    ->inlineLabel(),


                Section::make('Waktu, Tempat dan Kuota')
                    ->schema([
                        DatePicker::make('mulai')
                            ->label('Tanggal Mulai')
                            ->suffixIcon('heroicon-o-calendar')
                            ->dehydrated(),

                        DatePicker::make('selesai')
                            ->label('Tanggal Berakhir')
                            ->suffixIcon('heroicon-o-calendar')
                            ->dehydrated(),

                        TextInput::make('tempat')
                            ->label('Tempat')
                            ->suffixIcon('heroicon-o-map-pin')
                            ->dehydrated(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->dehydrated()
                            ->rows(3),

                        TextInput::make('kuota')
                            ->label('Kuota')
                            ->dehydrated()
                            ->numeric()
                            ->suffixIcon('heroicon-o-users'),
                    ])
                    ->collapsible()
                    ->inlineLabel(),


                Section::make('Panitia')
                    ->schema([
                        TextInput::make('panitia')
                            ->label('Nama Panitia')
                            ->dehydrated(),

                        TextInput::make('tlpnpanitia')
                            ->label('Telepon Panitia')
                            ->dehydrated()
                            ->tel(),
                    ])
                    ->collapsible()
                    ->inlineLabel(),

                Section::make('kurikulum')
                    ->schema([

                        Repeater::make('kurikulum')
                            ->schema([
                                TextInput::make('narasumber'),
                                TextInput::make('materi'),
                                TextInput::make('jam'),
                            ])
                            ->dehydrated()
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->columns(3)
                            ->addActionLabel('tambah kurikulum'),
                    ])
                    ->collapsible(),

                Section::make('Deskripsi Kegiatan & Persyaratan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->dehydrated()
                            ->rows(2)
                            ->columnSpanFull(),

                        Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->dehydrated()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->inlineLabel(),
            ])
            ->columns(1);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Dokumentasi berhasil disimpan')
            ->body('Dokumentasi kegiatan telah berhasil diupload.');
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
