<?php

namespace App\Models;
use App\Enums\Estado;
use App\Enums\TipoMateria;
use App\Models\MateriaPrima;
use PHPUnit\Framework\TestCase;

class MateriaPrimaTest extends TestCase
{

    public function testInsert()
    {
        $Materia = new MateriaPrima(
            [   'idMateria' => null,
                'nombre' => 'Varilla1',
                'tipo' => TipoMateria::PERFILES,
                'stock' => 40,
                'estado' => 'Inactivo']
        );

        $Materia->insert();
        $this->assertSame(true, $Materia-> materiaRegistrado('Varilla 18'));
    }

}
