<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Bangkom extends Model
{
    use HasUlids;

    protected $table = 'bangkom';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'unit',
        'kegiatan',
        'jenis_pelatihan_id',
        'bentuk_pelatihan_id',
        'sasaran_id',
        'mulai',
        'selesai',
        'alamat',
        'tempat',
        'kuota',
        'panitia',
        'tlpnpanitia',
        'narasumber',
        'materi',
        'jam',
        'deskripsi',
        'persyaratan',
        'status',

    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }
    public function jenis_pelatihan(): BelongsTo
    {
        return $this->belongsTo(JenisPelatihan::class);
    }
    public function bentuk_pelatihan(): BelongsTo
    {
        return $this->belongsTo(BentukPelatihan::class);
    }
    public function sasaran(): BelongsTo
    {
        return $this->belongsTo(Sasaran::class);
    }
    protected $casts = [
        'kurikulum' => 'array',
        'mulai' => 'date',
        'selesai' => 'date',
        'status' => \App\Enums\BangkomStatus::class,
    ];
}
