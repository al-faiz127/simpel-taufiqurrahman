<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum BangkomStatus: string implements HasLabel
{
    case Draft = 'draft';
    case MenungguVerifikasi = 'Menunggu Verifikasi';
    case TerbitSTTP = 'Terbit STTP';
    case Pengelolaan = 'Pengelolaan';
    case MenungguVerifikasiII = 'Menunggu Verifikasi II';



    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'draft',
            self::MenungguVerifikasi => 'Menunggu Verifikasi I',
            self::Pengelolaan => 'Pengelolaan',
            self::MenungguVerifikasiII => 'Menunggu Verifikasi II',
            self::TerbitSTTP => 'Terbit STTP',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::MenungguVerifikasi => 'info',
            self::Pengelolaan => 'primary',
            self::MenungguVerifikasiII => 'info',
            self::TerbitSTTP => 'success',
        };
    }
    public function getIcon(): ?string
    {
        return match ($this) {
            self::MenungguVerifikasi => 'heroicon-m-clock',
            self::MenungguVerifikasiII => 'heroicon-m-clock',
            self::TerbitSTTP => 'heroicon-m-check-badge',
            default => null,
        };
    }
}