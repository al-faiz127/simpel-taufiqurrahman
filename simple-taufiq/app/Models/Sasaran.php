<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Sasaran extends Model
{
    use HasUlids;

    protected $table = 'sasaran';

    protected $fillable = ['sasaran', 'deskripsi'];

    public function bangkom():HasMany{
        return $this->hasMany(Bangkom::class);
    }
}

