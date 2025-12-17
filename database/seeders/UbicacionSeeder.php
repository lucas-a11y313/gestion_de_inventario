<?php

namespace Database\Seeders;
use App\Models\Ubicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ubicacion::insert([
            [
                'nombre' => 'Dirección Técnica (DT)',
            ],
            [
                'nombre' => 'Unidad de Proyectos Especiales',
            ],
            [
                'nombre' => 'Planificación y Control',
            ],
            [
                'nombre' => 'Centro de Innovación Empresarial',
            ],
            [
                'nombre' => 'Centro de Innovación en Educación',
            ],
            [
                'nombre' => 'Centro de Innovación en Seguridad de Presa',
            ],
            [
                'nombre' => 'Centro de Innovación en Ingeniería de Computación',
            ],
            [
                'nombre' => 'Centro de Innovación Social y Gestión Territorial',
            ],
            [
                'nombre' => 'Centro de Innovación en Energías Alternativas',
            ],
            [
                'nombre' => 'Centro de Innovación en Sistemas Eléctricos y Automatización: Lab.ICI',
            ],
            [
                'nombre' => 'Centro de Innovación en Sistemas Eléctricos y Automatización: Lab.ASE',
            ],
            [
                'nombre' => 'Centro de Innovación en Sistemas Eléctricos y Automatización: Depósito',
            ],
            [
                'nombre' => 'Centro de Innovación en Sistemas Eléctricos y Automatización: Tacuru Pucu',
            ],
        ]);
    }
}
