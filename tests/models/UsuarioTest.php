<?php

namespace App\Models;

use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{

    public function testInsert()
    {
        $objUsuario  = new Usuario(
            [
                'idUsuario' => null,
                'documento' => 5555,
                'nombre' => 'dadsad',
                'telefono' => 'dsadasd',
                'direccion' => 'DSADSAD',
                'roll' => 'cliente',
                'contrasena' => 'dadsdasdasd',
                'estado' => 'Activo'
            ]
    );
        if($objUsuario->insert()){
            echo "BIEN";
        }
    }
}
