<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use App\Enums\BangkomStatus;
use App\Models\Bangkom;
use App\Models\Histori;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class HistoriTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?Bangkom $bangkom = null;

    public function mount(Bangkom $bangkom): void
    {
        $this->bangkom = $bangkom;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Histori::query()
                    ->where('bangkom_id', $this->bangkom->id)
            )
            ->pluralModelLabel('Histori Status')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->label('Waktu')
                    ->sortable()
                    ->size('sm'),
                Tables\Columns\TextColumn::make('oleh')
                    ->label('Oleh')
                    ->wrap(),
                Tables\Columns\TextColumn::make('sebelum')
                    ->label('Status Sebelum')
                    ->badge()
                    // PERBAIKAN: Hapus BangkomStatus::from() karena $state sudah berupa objek Enum
                    ->color(fn(BangkomStatus $state) => $state->getColor())
                    ->icon(fn(BangkomStatus $state) => $state->getIcon()),
                Tables\Columns\TextColumn::make('sesudah')
                    ->label('Status Menjadi')
                    ->badge()
                    // PERBAIKAN: Hapus BangkomStatus::from() karena $state sudah berupa objek Enum
                    ->color(fn(BangkomStatus $state) => $state->getColor())
                    ->icon(fn(BangkomStatus $state) => $state->getIcon()),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->wrap()
                    ->placeholder('-')
                    ->size('xl')
                    ->color('gray')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada Histori Status');
    }

    public function render()
    {
        return view('livewire.modal.histori-table');
    }
}
