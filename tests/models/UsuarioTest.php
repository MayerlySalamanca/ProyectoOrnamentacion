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
                'documento' => 555,
                'nombre' => 'pepe',
                'telefono' => '3227247325',
                'direccion' => 'crr 4',
                'roll' => Roll::VENDEDOR,
                'usuario' => 'Dil7',
                'contrasena' => '123456',
                'estado' => 'Activo']
        );

        $Usuario->insert();
        $this->assertSame(true, $Usuario->usuarioRegistrado(555));
    }

}
