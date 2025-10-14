<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPelatihan extends Model
{
    use HasUlids;

    protected $table = 'jenis_pelatihan';

    protected $fillable = ['jenis', 'deskripsi'];
    
    public function bangkom(): HasMany {
        return $this->hasMany(Bangkom::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = Str::slug($post->nama);
        });
    }
}
