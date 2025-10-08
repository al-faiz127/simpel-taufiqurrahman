<?php

namespace App\Models;

use App\Enums\JalurPelatihan;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BentukPelatihan extends Model
{
    use HasUlids;

    protected $table = 'bentuk_pelatihan';

    protected $fillable = ['jalur', 'bentuk', 'deskripsi'];
    
    protected $casts = [
        'jalur' => JalurPelatihan::class,
    ];
    public function bangkom():HasMany{
        return $this->hasMany(Bangkom::class);
    }
}
