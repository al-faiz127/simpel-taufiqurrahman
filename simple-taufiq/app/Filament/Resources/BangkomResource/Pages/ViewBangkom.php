<?php

namespace App\Filament\Resources\BangkomResource\Pages;

use App\Enums\BangkomStatus;
use App\Filament\Resources\BangkomResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;

class ViewBangkom extends ViewRecord
{
    protected static string $resource = BangkomResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // ====== Bagian KEGIATAN ======
                Section::make('Kegiatan')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengelola')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('instansi_id')
                            ->label('Instansi Pelaksana')
                            ->relationship('instansi', 'nama')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('unit')
                            ->label('Unit Kerja / Perangkat Daerah Pelaksana')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('kegiatan')
                            ->label('Nama Kegiatan')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('jenis_pelatihan_id')
                            ->label('Jenis Pelatihan')
                            ->relationship('jenis_pelatihan', 'jenis')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('bentuk_pelatihan_id')
                            ->label('Bentuk Pelatihan')
                            ->relationship('bentuk_pelatihan', 'bentuk')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('sasaran_id')
                            ->label('Sasaran')
                            ->relationship('sasaran', 'sasaran')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->collapsible()
                    ->inlineLabel(),

                // ====== Bagian WAKTU & TEMPAT ======
                Section::make('Waktu, Tempat dan Kuota')
                    ->schema([
                        DatePicker::make('mulai')
                            ->label('Tanggal Mulai')
                            ->suffixIcon('heroicon-o-calendar')
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('selesai')
                            ->label('Tanggal Berakhir')
                            ->suffixIcon('heroicon-o-calendar')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('tempat')
                            ->label('Tempat')
                            ->suffixIcon('heroicon-o-map-pin')
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->disabled()
                            ->dehydrated()
                            ->rows(3),

                        TextInput::make('kuota')
                            ->label('Kuota')
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->suffixIcon('heroicon-o-users'),
                    ])
                    ->collapsible()
                    ->inlineLabel(),

                // ====== Bagian PANITIA ======
                Section::make('Panitia')
                    ->schema([
                        TextInput::make('panitia')
                            ->label('Nama Panitia')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('tlpnpanitia')
                            ->label('Telepon Panitia')
                            ->disabled()
                            ->dehydrated()
                            ->tel(),
                    ])
                    ->collapsible()
                    ->inlineLabel(),

                // ====== Bagian KURIKULUM ======
                Section::make('Kurikulum')
                    ->schema([
                        TableRepeater::make('kurikulum')
                            ->schema([
                                TextInput::make('narasumber')
                                    ->disabled(),
                                TextInput::make('materi')
                                    ->disabled(),
                                TextInput::make('jam')
                                    ->disabled(),
                            ])
                            ->disabled()
                            ->dehydrated()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // ====== Bagian DESKRIPSI ======
                Section::make('Deskripsi Kegiatan & Persyaratan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->disabled()
                            ->dehydrated()
                            ->rows(2)
                            ->columnSpanFull(),

                        Textarea::make('persyaratan')
                            ->label('Persyaratan')
                            ->disabled()
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
