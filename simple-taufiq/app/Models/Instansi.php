<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    use HasUlids;

    protected $table = 'instansi';

    protected $fillable = ['nama', 'alamat', 'telepon', 'email','website'];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
