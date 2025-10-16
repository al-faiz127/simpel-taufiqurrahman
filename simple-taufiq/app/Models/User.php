<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
// use Filament\Tables\Columns\Layout\Panel;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'instansi_id',
        'phone',
        'satuan',
        'username',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // protected function handleRegistration(array $data): Model
    // {
    //     $user = $this->getUserModel()::create($data);

    //     $data['role'] = 'pelaksana'; // make it static

    //     /** Make sure role exists */
    //     Role::firstOrCreate(['name' => $data['role']]);

    //     $user->assignRole($data['role']);

    //     return $user;
    // }


    public function instansi(): BelongsTo
    {
      return $this->belongsTo(Instansi::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return !is_null($this->verified_at);
    }
    public function histori(): HasMany
    {
        return $this->hasMany(Histori::class);
    }
    public function bangkom(): HasMany
    {
        return $this->hasMany(Bangkom::class);
    }
}
