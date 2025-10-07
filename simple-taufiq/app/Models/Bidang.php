<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasUlids;

    protected $table = 'bidang';

    protected $fillable = ['bidang'];
}
