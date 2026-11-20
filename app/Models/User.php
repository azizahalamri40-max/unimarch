<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'tipe_pengirim', 'no_telp'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    // Surat yang dikirim oleh user ini
    public function suratDikirim(): HasMany
    {
        return $this->hasMany(Surat::class, 'pengirim_id');
    }

    // Surat yang diterima oleh user ini (staff)
    public function suratDiterima(): HasMany
    {
        return $this->hasMany(Surat::class, 'penerima_id');
    }

    // Disposisi yang diberikan oleh user ini (pimpinan)
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'pimpinan_id');
    }
}