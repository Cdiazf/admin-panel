<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\Gasto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventosYGastosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = [
            'Evento Corporativo',
            'Boda de Muestra',
            'Concierto Demo'
        ];

        foreach ($eventos as $nombre) {
            $evento = Evento::create(['nombre' => $nombre]);

            for ($i = 1; $i <= 5; $i++) {
                Gasto::create([
                    'evento_id' => $evento->id,
                    'concepto'  => "Gasto $i de $nombre",
                    'monto'     => rand(50, 800),
                    'fecha'     => now()->subDays(rand(1, 20)),
                ]);
            }
        }
    }
}
