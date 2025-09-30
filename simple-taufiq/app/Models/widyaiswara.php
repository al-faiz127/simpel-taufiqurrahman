<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

class widyaiswara extends Model
{
    use HasUlids;

    protected $table = 'widyaiswara';

    protected $fillable = ['nip','nama','satker','telpon','email','alamat'];
}
