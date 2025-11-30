<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compradores extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'total_a_pagar',
        'monto_pagado',
    ];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pagos::class, 'comprador_id');
    }

    public function recalcularMontos(): void
    {
        $totalPagado = $this->pagos()->sum('monto');

        $this->monto_pagado = $totalPagado;
        $this->save();
    }

    public function getMontoPendienteAttribute(): float
    {
        return max(
            0,
            (float)$this->total_a_pagar - (float)$this->monto_pagado
        );
    }
}
