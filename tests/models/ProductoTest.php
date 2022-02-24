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
                'cantidad' => 2,
                'valor' => 12000,
                'material' => 'hierro',
                'tamano' => '18cm x 30cm',
                'diseno' => 'flores rejadas',
                'descripcion' => 'que hacen',
                'estado' => Estado::ACTIVO]
        );

        $Producto->insert();
        $this->assertSame(true, $Producto->productoRegistrado('Puerta'));
    }

}
