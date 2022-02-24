<?php

namespace App\Models;
use App\Enums\Estado;
use App\Enums\Roll;
use App\Models\Usuario;

use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{

    public function testInsert()
    {
        $Usuario = new Usuario(
            ['IdUsuario' => null,
                'documento' => 1555,
                'nombre' => 'Dilan Galeano',
                'telefono' => '3227247325',
                'direccion' => 'crr 4',
                'roll' => Roll::VENDEDOR,
                'usuario' => 'admin1',
                'contrasena' => 'dadsdasdasd',
                'estado' => 'Activo']
        );

        $Usuario->insert();
        $this->assertSame(true, $Usuario->usuarioRegistrado(1555));
    }

}
