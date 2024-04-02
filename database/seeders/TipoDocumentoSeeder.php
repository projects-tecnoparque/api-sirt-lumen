<?php

namespace Database\Seeders;

use App\Models\V1\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoDocumento::create([
            "abreviatura"=> "CC",
            "nombre"=> "Cédula de Ciudadanía",
        ]);
        TipoDocumento::create([
            "abreviatura"=> "TI",
            "nombre"=> "Tarjeta de Identidad",
        ]);
        TipoDocumento::create([
            "abreviatura"=> "CE",
            "nombre"=> "Cédula de Extranjería",
        ]);
        TipoDocumento::create([
            "abreviatura"=> "RC",
            "nombre"=> "Registro Civil",
        ]);
        TipoDocumento::create([
            "abreviatura"=> "PP",
            "nombre"=> "Pasaporte",
        ]);
        TipoDocumento::create([
            "abreviatura"=> "PEP",
            "nombre"=> "Permiso Especial de Permanencia",
        ]);
    }
}
