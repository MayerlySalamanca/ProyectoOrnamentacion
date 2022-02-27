<?php

namespace App\Models;

use App\Enums\Tipo;
use App\Enums\Estado;
use App\Models\Producto;
use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{

    public function testInsert()
    {
        $Producto = new Producto(
            [   'IdProducto' => null,
                'tipo' =>Tipo::PRODUCTO,
                'nombre' => 'perra',
                'stock' => 2,
                'valor' => 12000,
                'estado' => Estado::ACTIVO]
        );

        $Producto->insert();
        $this->assertSame(true, $Producto->productoRegistrado('perra'));
    }

}
