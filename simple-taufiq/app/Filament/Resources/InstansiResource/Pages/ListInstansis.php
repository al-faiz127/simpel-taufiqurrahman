<?php

namespace App\Filament\Resources\InstansiResource\Pages;

use App\Filament\Resources\InstansiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstansis extends ListRecords
{
    protected static string $resource = InstansiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Kunci utama: Menggunakan CreateAction
            Actions\CreateAction::make()
                ->label('Tambah Instansi')
                ->modalWidth('lg'), 
        ];
    }
}