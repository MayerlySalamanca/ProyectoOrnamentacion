<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Fabricacion;
use Carbon\Carbon;

class DetalleComprasController
{
    private array $dataDetalleCompra;

    public function __construct(array $_FORM)
    {
        $this->dataDetalleCompra = array();
        $this->dataDetalleCompra['id'] = $_FORM['id'] ?? NULL;
        $this->dataDetalleCompra['compra_id'] = $_FORM['compra_id'] ?? '';
        $this->dataDetalleCompra['producto_id'] = $_FORM['producto_id'] ?? '';
        $this->dataDetalleCompra['cantidad'] = $_FORM['cantidad'] ?? '';
        $this->dataDetalleCompra['precio_venta'] = $_FORM['precio_venta'] ?? '';
    }

    public function create()
    {
        try {
            if (!empty($this->dataDetalleCompra['compra_id']) and !empty($this->dataDetalleCompra['producto_id'])) {
                if(Fabricacion::productoEnFactura($this->dataDetalleCompra['compra_id'], $this->dataDetalleCompra['producto_id'])){
                    $this->edit();
                }else{
                    $DetalleCompra = new Fabricacion($this->dataDetalleCompra);
                    if ($DetalleCompra->insert()) {
                        unset($_SESSION['frmDetalleCompras']);
                        header("Location: ../../views/modules/compras/create.php?id=".$this->dataDetalleCompra['compra_id']."&respuesta=success&mensaje=Producto Agregado");
                    }
                }
            } else {
                header("Location: ../../views/modules/compras/create.php?id=".$this->dataDetalleCompra['compra_id']."&respuesta=error&mensaje=Faltan parametros");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $arrDetalleCompra = Fabricacion::search("SELECT * FROM ornamentacion.fabricacion WHERE compra_id = ".$this->dataDetalleCompra['compra_id']." and producto_id = ".$this->dataDetalleCompra['producto_id']);
            /* @var $arrDetalleCompra Fabricacion[] */
            $DetalleCompra = $arrDetalleCompra[0];
            $OldCantidad = $DetalleCompra->getCantidad();
            $DetalleCompra->setCantidad($OldCantidad + $this->dataDetalleCompra['cantidad']);
            if ($DetalleCompra->update()) {
                $DetalleCompra->getProducto()->addStock($this->dataDetalleCompra['cantidad']);
                unset($_SESSION['frmDetalleCompras']);
                header("Location: ../../views/modules/compras/create.php?id=".$this->dataDetalleCompra['compra_id']."&respuesta=success&mensaje=Producto Actualizado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function deleted (int $id){
        try {
            $ObjDetalleCompra = Fabricacion::searchForId($id);
            $objProducto = $ObjDetalleCompra->getProducto();
            if($ObjDetalleCompra->deleted()){
                $objProducto->susaddStock($ObjDetalleCompra->getCantidad());
                header("Location: ../../views/modules/compras/create.php?id=".$ObjDetalleCompra->getCompraId()."&respuesta=success&mensaje=Producto Eliminado");
            }else{
                header("Location: ../../views/modules/compras/create.php?id=".$ObjDetalleCompra->getCompraId()."&respuesta=error&mensaje=Error al eliminar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Fabricacion::searchForId($data['id']);
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
            $result = Fabricacion::getAll();
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