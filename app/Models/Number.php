<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'phone',
        'status'
    ];

    protected $casts = [
        'number' => 'integer',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('status', 'disponivel');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reservado');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'pago');
    }
}
