<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BangkomResource\Pages;
use App\Enums\BangkomStatus;
use App\Models\Bangkom;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\TextInput;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class BangkomResource extends Resource
{
    protected static ?string $model = Bangkom::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Bangkom';
    protected static ?string $pluralLabel = 'Bangkom';
    protected static ?string $slug = 'Bangkom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('kegiatan')
                        ->schema([
                            Select::make('user_id')
                                ->label('Penngelola')
                                ->relationship(
                                    name: 'user',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn($query) => $query
                                        ->whereHas('roles', fn($q) => $q->where('name', 'pelaksana'))
                                        ->whereNotNull('verified_at')
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
                        ])
                        ->columnSpanFull()
                        ->inlineLabel(),
                    Wizard\Step::make('Waktu, Tempat dan Kuota')
                        ->schema([
                            DatePicker::make('mulai')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->displayFormat('d/m/y')
                                ->minDate(now())
                                ->suffixIcon('heroicon-o-calendar')
                                ->native(false),
                            DatePicker::make('selesai')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->displayFormat('d/m/y')
                                ->minDate(now())
                                ->suffixIcon('heroicon-o-calendar')
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
                        ])
                        ->columnSpanFull()
                        ->inlineLabel(),
                    Wizard\Step::make('Panitia')
                        ->schema([
                            TextInput::make('panitia')
                                ->label('Nama Panitia')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('tlpnpanitia')
                                ->label('Telepon Panitia')
                                ->required()
                                ->tel(),
                        ])
                        ->columnSpanFull()
                        ->inlineLabel(),
                    Wizard\Step::make('Kurikulum')
                        ->schema([
                            TableRepeater::make('kurikulum')
                                ->headers([
                                    Header::make('narasumber')->label('Narasumber'),
                                    Header::make('materi')->label('Materi'),
                                    Header::make('jam')->label('Jam Pelajaran'),
                                ])
                                ->schema([
                                    TextInput::make('narasumber')->required(),
                                    TextInput::make('materi')->required(),
                                    TextInput::make('jam')->numeric(),
                                ])
                                ->addActionLabel('Tambah Kurikulum'),
                                ]),
                        
                    Wizard\Step::make('Deskripsi dan Persyaratan')
                        ->schema([
                            TextInput::make('deskripsi')
                                ->label('Deskripsi Kegiatan')
                                ->required(),
                            TextInput::make('persyaratan')
                                ->label('Persyaratan Peserta')
                                ->required(),
                        ])
                        ->columnSpanFull()
                        ->inlineLabel(),
                ])
                    ->submitAction(new HtmlString('
                        <button 
                            type="submit" 
                            wire:click="create"
                            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-warning-600 hover:bg-warning-500 focus:bg-warning-700 focus:ring-offset-warning-700"
                        >
                            Submit
                        </button>
                    '))
                    ->columnSpanFull()
                    ->skippable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')->label('No')->rowIndex(),
                Tables\Columns\TextColumn::make('kegiatan')->label('Nama Kegiatan'),
                Tables\Columns\TextColumn::make('jenis_pelatihan.jenis')->label('Jenis Pelatihan'),
                Tables\Columns\TextColumn::make('mulai')
                    ->label('Tanggal Pelatihan')
                    ->formatStateUsing(function ($record) {
                        if (!$record->mulai || !$record->selesai) return '-';
                        $mulai = Carbon::parse($record->mulai)->translatedFormat('d M');
                        $selesai = Carbon::parse($record->selesai)->translatedFormat('d M');
                        return "$mulai <small><span class='text-gray-500 text-sm'>s/d</span></small> $selesai";
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('kuota')->label('Kuota'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state instanceof BangkomStatus ? $state->getColor() : BangkomStatus::from($state)->getColor())
                    ->icon(fn($state) => $state instanceof BangkomStatus ? $state->getIcon() : BangkomStatus::from($state)->getIcon())
                    ->searchable(),
            ])
             ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('CetakPermohonan')
                    ->label('Cetak Permohonan')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->url(fn($record) => route('bangkom.permohonan', $record))
                    ->openUrlInNewTab(false),

                    Tables\Actions\Action::make('ajukanPermohonan')
                        ->label('Ajukan Permohonan')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('gray')
                        ->modalHeading('Ajukan Permohonan')
                        ->form([
                            Forms\Components\FileUpload::make('file_permohonan')
                                ->label('File Permohonan')
                                ->required()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/msword',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->maxSize(102400)
                                ->helperText('*Ukuran file maksimum: 100MB. Format yang diijinkan: PDF, DOCX, XLSX, PPTX, JPEG.')
                                ->directory('permohonan')
                                ->visibility('private')
                                ->downloadable()
                                ->openable()
                                ->previewable(),

                            Forms\Components\Checkbox::make('persetujuan')
                                ->label('Dengan ini saya menyetujui bahwa data yang saya isi adalah benar dan dapat dipercaya.')
                                ->required()
                                ->accepted()
                                ->validationMessages([
                                    'accepted' => 'Anda harus menyetujui pernyataan ini untuk melanjutkan.',
                                ]),
                        ])
                        ->modalSubmitActionLabel('Submit')
                        ->modalCancelActionLabel('Tutup')
                        ->visible(fn(Bangkom $record): bool => $record->status === BangkomStatus::Draft)
                        ->action(function (Bangkom $record, array $data) {
                            $record->update([
                                'status' => BangkomStatus::MenungguVerifikasi,
                                'file_permohonan' => $data['file_permohonan'],
                            ]);
                        }),

                Tables\Actions\Action::make('DokumenPermohonan')
                    ->label('Upload Permohonan')
                    ->icon('heroicon-o-document')
                    ->color('gray')
                    ->modalHeading('Ajukan Permohonan')
                    ->form([
                        Forms\Components\FileUpload::make('file_permohonan')
                            ->label('File Permohonan')
                            ->required()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'image/jpeg',
                                'image/png'
                            ])
                            ->maxSize(102400)
                            ->helperText('*Maksimal 100MB. Format: PDF, DOCX, JPG, PNG.')
                            ->directory('permohonan')
                            ->visibility('private')
                            ->downloadable()
                            ->openable()
                            ->previewable(),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->action(function (\App\Models\Bangkom $record, array $data) {
                        $record->update([
                            'status' => \App\Enums\BangkomStatus::MenungguVerifikasi,
                            'file_permohonan' => $data['file_permohonan'],
                        ]);
                    }),

                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),

                // 🔹 Dimasukkan ke dalam ActionGroup
                Tables\Actions\Action::make('ubahStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->modalHeading('Ubah Status')
                    ->modalDescription('Apakah Anda yakin ingin mengubah status ini?')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                \App\Enums\BangkomStatus::Draft->value => 'Draft',
                                \App\Enums\BangkomStatus::MenungguVerifikasi->value => 'Menunggu Verifikasi I',
                                \App\Enums\BangkomStatus::Pengelolaan->value => 'Pengelolaan',
                                \App\Enums\BangkomStatus::MenungguVerifikasiII->value => 'Menunggu Verifikasi II',
                                \App\Enums\BangkomStatus::TerbitSTTP->value => 'Terbit STTP',
                            ])
                            ->default(fn(\App\Models\Bangkom $record): string => $record->status->value)
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->helperText('Contoh: Permintaan perbaikan usulan.'),
                    ])
                    ->requiresConfirmation()
                    ->action(function (\App\Models\Bangkom $record, array $data) {
                        $oldStatus = $record->status;
                        $newStatus = \App\Enums\BangkomStatus::from($data['status']);

                        $record->update([
                            'status' => $newStatus,
                            'catatan' => $data['catatan'],
                        ]);

                        $record->histori()->create([
                            'users_id' => \Illuminate\Support\Facades\Auth::id(),
                            'oleh' => \Illuminate\Support\Facades\Auth::user()->name,
                            'sebelum' => $oldStatus->value,
                            'sesudah' => $newStatus->value,
                            'catatan' => $data['catatan'] ?? '-',
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Status berhasil diubah')
                            ->body("Status diubah dari {$oldStatus->getLabel()} menjadi {$newStatus->getLabel()}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('historiStatus')
                    ->label('Histori Status')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading('Histori Status')
                    ->modalContent(fn(\Illuminate\Database\Eloquent\Model $record) => new \Illuminate\Support\HtmlString(
                        \Illuminate\Support\Facades\Blade::render(<<<'BLADE'
                            <livewire:modal.histori-table :bangkom="$bangkom" />
                        BLADE, ['bangkom' => $record])
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Tables\Actions\Action::make('forceDelete')
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Permanen')
                    ->modalDescription('Apakah Anda yakin ingin menghapus data ini secara permanen?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->action(function (\App\Models\Bangkom $record) {
                        $record->forceDelete();
                        \Filament\Notifications\Notification::make()
                            ->title('Data berhasil dihapus permanen')
                            ->success()
                            ->send();
                    }),
            ])
            ->label('Aksi')
            ->icon('heroicon-o-ellipsis-horizontal'),
        ]);
}


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBangkoms::route('/'),
            'create' => Pages\CreateBangkom::route('/create'),
            'edit' => Pages\EditBangkom::route('/{record}/edit'),
            'view' => Pages\ViewBangkom::route('/{record}'),
        ];
    }
}
