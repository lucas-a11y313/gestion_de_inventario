<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * Solicitudes realizadas por este usuario
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    /**
     * BPs que actualmente tiene asignados este usuario
     */
    public function inventarioBPs()
    {
        return $this->belongsToMany(InventarioBP::class, 'inventariobp_user', 'user_id', 'inventariobp_id')
                    ->withPivot('asignado_por', 'fecha_desasignacion')
                    ->withTimestamps();
    }

    /**
     * BPs activos (sin fecha de desasignación)
     */
    public function inventarioBPsActivos()
    {
        return $this->belongsToMany(InventarioBP::class, 'inventariobp_user', 'user_id', 'inventariobp_id')
                    ->whereNull('inventariobp_user.fecha_desasignacion')
                    ->withPivot('asignado_por', 'fecha_desasignacion')
                    ->withTimestamps();
    }
}
