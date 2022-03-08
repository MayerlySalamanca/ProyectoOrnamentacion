<?php

namespace App\Models;

use App\Enums\Estado;
use App\Models\Pedidos;
use App\Models\Proveedor;
use PHPUnit\Framework\TestCase;

class PedidosTest extends TestCase
{

    public function testInsert()
    {
        $Pedidos = new Pedidos(
            [   'idPedidos' => null,
                'numeroPedido' => 1,
                'nombre' => 'Fer',
                'fechaPedido' => 2022-02-22,
                'fechaEntrega' => 2022-03-12,
                'estado' => 'Inactivo',
                'Proveedor_IdProveedor' => 1,
            ]
        );

        $Pedidos->ins.ert();
        $this->assertSame(true, $Pedidos->pedidoRegistrado(1));
    }
}
