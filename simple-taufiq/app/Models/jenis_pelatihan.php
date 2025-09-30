<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

class jenis_pelatihan extends Model
{
    use HasUlids;

    protected $table = 'jenis_pelatihan';

    protected $fillable = ['jenis', 'deskripsi'];
}
