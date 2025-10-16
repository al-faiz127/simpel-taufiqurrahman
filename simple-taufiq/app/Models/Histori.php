<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Histori extends Model
{
    use HasUlids;
    const UPDATED_AT = null;

    protected $table = 'histori';

    protected $fillable = ['user_id', 'bangkom_id', 'oleh', 'sebelum', 'sesudah', 'catatan', 'waktu'];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bangkom(): BelongsTo
    {
        return $this->belongsTo(Bangkom::class);
    }
    public function castStatus($value)
    {
        if ($value) {
            return \App\Enums\BangkomStatus::from($value);
        }
        return null;
    }

    public function getSebelumAttribute($value)
    {
        return $this->castStatus($value);
    }

    public function getSesudahAttribute($value)
    {
        return $this->castStatus($value);
    }
}
