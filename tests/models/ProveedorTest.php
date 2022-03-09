<?php

namespace App\Models;
use App\Enums\Estado;
use App\Models\Proveedor;
use PHPUnit\Framework\TestCase;

class ProveedorTest extends TestCase
{

    public function testInsert()
    {
        $Proveedor = new Proveedor(
            [   'IdUsuario' => null,
                'documento' => 221,
                'nombre' => 'jose',
                'ciudad' => 'ditaa',
                'estado' => 'Inactivo']
        );

        $Proveedor->insert();
        $this->assertSame(true, $Proveedor->proveedorRegistrado(221));
    }

}
