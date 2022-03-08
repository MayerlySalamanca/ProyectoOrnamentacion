<?php

namespace App\Models;
use App\Enums\EstadoFactura;
use App\Models\Factura;
use PHPUnit\Framework\TestCase;

class FacturaTest extends TestCase
{

    public function testInsert()
    {
        $factura = new Factura(
            [   'IdFactura' => null,
                'numeroFactura' => 12,
                'nombreCliente' =>'di',
                'cantidad' => 2,
                'fecha' => 2022-03-12,
                'estado' => EstadoFactura::PROCESO,
                'valor' => 10000,
                'usuarioVendedor' => 1]
        );

        $factura->insert();
        $this->assertSame(true, $factura-> facturaRegistrado(12));
    }

}
