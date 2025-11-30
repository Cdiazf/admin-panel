<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagos extends Model
{
    protected $fillable = [
        'comprador_id',
        'monto',
        'fecha',
        'metodo_pago',
        'referencia',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Compradores::class, 'comprador_id');
    }
}
