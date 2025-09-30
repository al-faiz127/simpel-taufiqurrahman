<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class instansi extends Model
{
    use HasUlids;

    protected $table = 'instansi';

    protected $fillable = ['nama', 'alamat', 'telepon', 'email','website'];
}
