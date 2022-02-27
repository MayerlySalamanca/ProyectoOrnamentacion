<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Orden;
use Carbon\Carbon;

class OrdenCompra
{
    private array $ordenCompra;

    public function __construct(array $_FORM)
    {
        $this->ordenCompra = array();
        $this->ordenCompra['idOrdenCompra'] = $_FORM['idOrdenCompra'] ?? NULL;
        $this->ordenCompra['cantidad'] = $_FORM['cantidad'] ?? '';
        $this->ordenCompra['Factura_IdFactura'] = $_FORM['Factura_IdFactura'] ?? '';
        $this->ordenCompra['Producto_IdProducto'] = $_FORM['Producto_IdProducto'] ?? '';

    }

    public function create()
    {
        try {
            if (!empty($this->ordenCompra['Factura_IdFactura']) and !empty($this->ordenCompra['Producto_IdProducto'])) {
                if(Orden::productoEnFactura($this->ordenCompra['Factura_IdFactura'], $this->ordenCompra['Producto_IdProducto'])){
                    $this->edit();
                }else{
                    $ordenCompra = new Orden($this->ordenCompra);
                    if ($ordenCompra->insert()) {
                        unset($_SESSION['frmOrdenCompra']);
                        header("Location: ../../views/modules/facturacion/create.php?id=".$this->ordenCompra['venta_id']."&respuesta=success&mensaje=Producto Agregado");
                    }
                }
            } else {
                header("Location: ../../views/modules/facturacion/create.php?id=".$this->ordenCompra['venta_id']."&respuesta=error&mensaje=Faltan parametros");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $arrOrden = Orden::search("SELECT * FROM ordencompra WHERE Factura_IdFactura = ".$this->ordenCompra['Factura_IdFactura']." and Producto_IdProducto = ".$this->ordenCompra['Producto_IdProducto']);
            /* @var $arrOrden Orden[] */
            $Orden = $arrOrden[0];
            $OldCantidad = $Orden->getCantidad();
            $Orden->setCantidad($OldCantidad + $this->ordenCompra['cantidad']);
            if ($Orden->update()) {
                $Orden->getProducto()->substractStock($this->ordenCompra['cantidad']);
                unset($_SESSION['frmDetalleVentas']);
                header("Location: ../../views/modules/facturacion/create.php?id=".$this->ordenCompra['venta_id']."&respuesta=success&mensaje=Producto Actualizado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function deleted (int $id){
        try {
            $ObjOrden = Orden::searchForId($id);
            $objProducto = $ObjOrden->getProducto();
            if($ObjOrden->deleted()){
                $objProducto->addStock($ObjOrden->getCantidad());
                header("Location: ../../views/modules/facturacion/create.php?id=".$ObjOrden->getVentasId()."&respuesta=success&mensaje=Producto Eliminado");
            }else{
                header("Location: ../../views/modules/facturacion/create.php?id=".$ObjOrden->getVentasId()."&respuesta=error&mensaje=Error al eliminar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Orden::searchForId($data['id']);
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
            $result = Orden::getAll();
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