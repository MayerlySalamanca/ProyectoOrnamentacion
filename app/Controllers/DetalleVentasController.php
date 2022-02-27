<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\DetalleVentas;
use Carbon\Carbon;

class DetalleVentasController
{
    private array $dataDetalleVenta;

    public function __construct(array $_FORM)
    {
        $this->dataDetalleVenta = array();
        $this->dataDetalleVenta['idOrdenCompra'] = $_FORM['idOrdenCompra'] ?? NULL;
        $this->dataDetalleVenta['ventas_id'] = $_FORM['ventas_id'] ?? '';
        $this->dataDetalleVenta['Producto_IdProducto'] = $_FORM['Producto_IdProducto'] ?? '';
        $this->dataDetalleVenta['cantidad'] = $_FORM['cantidad'] ?? '';
        $this->dataDetalleVenta['precio'] = $_FORM['precio'] ?? '';
    }

    public function create()
    {
        try {
            if (!empty($this->dataDetalleVenta['ventas_id']) and !empty($this->dataDetalleVenta['Producto_IdProducto'])) {
                if(DetalleVentas::productoEnFactura($this->dataDetalleVenta['ventas_id'], $this->dataDetalleVenta['Producto_IdProducto'])){
                    $this->edit();
                }else{
                    $DetalleVenta = new DetalleVentas($this->dataDetalleVenta);
                    if ($DetalleVenta->insert()) {
                        unset($_SESSION['frmDetalleVentas']);
                        header("Location: ../../views/modules/facturacion/create.php?id=".$this->dataDetalleVenta['ventas_id']."&respuesta=success&mensaje=Producto Agregado");
                    }
                }
            } else {
                header("Location: ../../views/modules/facturacion/create.php?id=".$this->dataDetalleVenta['ventas_id']."&respuesta=error&mensaje=Faltan parametros");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $arrDetalleVenta = DetalleVentas::search("SELECT * FROM ornamentacion.detalle_ventas WHERE ventas_id = ".$this->dataDetalleVenta['ventas_id']." and Producto_IdProducto = ".$this->dataDetalleVenta['Producto_IdProducto']);
            /* @var $arrDetalleVenta DetalleVentas[] */
            $DetalleVenta = $arrDetalleVenta[0];
            $OldCantidad = $DetalleVenta->getCantidad();
            $DetalleVenta->setCantidad($OldCantidad + $this->dataDetalleVenta['cantidad']);
            if ($DetalleVenta->update()) {
                $DetalleVenta->getProducto()->susaddStock($this->dataDetalleVenta['cantidad']);
                unset($_SESSION['frmDetalleVentas']);
                header("Location: ../../views/modules/facturacion/create.php?id=".$this->dataDetalleVenta['ventas_id']."&respuesta=success&mensaje=Producto Actualizado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function deleted (int $id){
        try {
            $ObjDetalleVenta = DetalleVentas::searchForId($id);
            $objProducto = $ObjDetalleVenta->getProducto();
            if($ObjDetalleVenta->deleted()){
                $objProducto->addStock($ObjDetalleVenta->getCantidad());
                header("Location: ../../views/modules/facturacion/create.php?id=".$ObjDetalleVenta->getIdOrdenCompra()."&respuesta=success&mensaje=Producto Eliminado");
            }else{
                header("Location: ../../views/modules/facturacion/create.php?id=".$ObjDetalleVenta->getIdOrdenCompra()."&respuesta=error&mensaje=Error al eliminar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = DetalleVentas::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll()
    {
        try {
            $result = DetalleVentas::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
}