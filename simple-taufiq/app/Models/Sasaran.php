<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

class Sasaran extends Model
{
    use HasUlids;

    protected $table = 'sasaran';

    protected $fillable = ['sasaran', 'deskripsi'];
}
