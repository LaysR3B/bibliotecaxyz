<?php

namespace App\Models;

use App\Models\Libro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'estado',
        'libro_id',
        'fecha_prestamo',
        'fecha_devolucion',
    ];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    public function getDiasPrestamoAttribute(): ?int
    {
        if (! $this->fecha_prestamo) {
            return null;
        }

        $inicio = $this->fecha_prestamo instanceof Carbon ? $this->fecha_prestamo : Carbon::parse($this->fecha_prestamo);
        $fin = $this->fecha_devolucion ? ($this->fecha_devolucion instanceof Carbon ? $this->fecha_devolucion : Carbon::parse($this->fecha_devolucion)) : Carbon::now();

        return max($inicio->diffInDays($fin), 0);
    }
}
