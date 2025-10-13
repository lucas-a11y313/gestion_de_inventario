<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioBP extends Model
{
    protected $table = 'inventariobps';

    protected $fillable = [
        'bp',
        'user_id',
        'producto_id',
        'origen'
    ];

    /**
     * Relación con Producto
     * Un BP pertenece a un Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con User (Responsable actual) - LEGACY
     * Mantener para compatibilidad con código existente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a muchos con User (Historial de responsables)
     * Un BP puede tener muchos usuarios a través del tiempo
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'inventariobp_user', 'inventariobp_id', 'user_id')
                    ->withPivot('asignado_por', 'fecha_desasignacion')
                    ->withTimestamps()
                    ->orderBy('inventariobp_user.created_at', 'desc');
    }

    /**
     * Usuario actual (el que NO tiene fecha_desasignacion)
     */
    public function usuarioActual()
    {
        return $this->belongsToMany(User::class, 'inventariobp_user', 'inventariobp_id', 'user_id')
                    ->whereNull('inventariobp_user.fecha_desasignacion')
                    ->withPivot('asignado_por', 'fecha_desasignacion')
                    ->withTimestamps()
                    ->latest('inventariobp_user.created_at')
                    ->limit(1);
    }

    /**
     * Historial de usuarios (ordenado por fecha de asignación)
     */
    public function historialUsuarios()
    {
        return $this->belongsToMany(User::class, 'inventariobp_user', 'inventariobp_id', 'user_id')
                    ->withPivot('asignado_por', 'fecha_desasignacion')
                    ->withTimestamps()
                    ->orderBy('inventariobp_user.created_at', 'desc');
    }
}
