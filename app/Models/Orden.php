<?php

namespace App\Models;

use App\Enums\Estado;
use Carbon\Carbon;
use JetBrains\PhpStorm\Internal\TentativeType;

class Orden extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int  $idOrdenCompra;
    private int $fabricacionId;
    private int $Factura_IdFactura;
    private int $Producto_IdProducto;
    private int $cantidad;

    private ?Factura $factura;
    private ?Producto $producto;

    public function __construct(array $Orden = [])
    {
        parent::__construct();
        $this->setIdOrdenCompra($Orden['idOrdenCompra'] ?? null);
        $this->setCantidad($Orden['cantidad'] ?? 0);
        $this->setFabricacionId($Orden['fabricacionId'] ?? 0);
        $this->setFacturaIdFactura($Orden['Factura_IdFactura'] ?? 0);
        $this->setProductoIdProducto($Orden['Producto_IdProducto'] ?? 0);




    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }




    /**
     * @return int|null
     */
    public function getIdOrdenCompra(): ?int
    {
        return $this->idOrdenCompra;
    }

    /**
     * @param int|null $idOrdenCompra
     */
    public function setIdOrdenCompra(?int $idOrdenCompra): void
    {
        $this->idOrdenCompra = $idOrdenCompra;
    }

    /**
     * @return int
     */
    public function getFabricacionId(): int
    {
        return $this->fabricacionId;
    }

    /**
     * @param int $fabricacionId
     */
    public function setFabricacionId(int $fabricacionId): void
    {
        $this->fabricacionId = $fabricacionId;
    }

    /**
     * @return int
     */
    public function getFacturaIdFactura(): int
    {
        return $this->Factura_IdFactura;
    }

    /**
     * @param int $Factura_IdFactura
     */
    public function setFacturaIdFactura(int $Factura_IdFactura): void
    {
        $this->Factura_IdFactura = $Factura_IdFactura;
    }

    /**
     * @return int
     */
    public function getProductoIdProducto(): int
    {
        return $this->Producto_IdProducto;
    }

    /**
     * @param int $Producto_IdProducto
     */
    public function setProductoIdProducto(int $Producto_IdProducto): void
    {
        $this->Producto_IdProducto = $Producto_IdProducto;
    }
    /* Relaciones */
    /**
     * Retorna el objeto venta correspondiente al detalle venta
     * @return Ventas|null
     */
    public function getVenta(): ?Factura
    {
        if(!empty($this->Factura_IdFactura)){
            $this->factura = factura::searchForId($this->Factura_IdFactura) ?? new Factura();
            return $this->factura;
        }
        return NULL;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Producto|null
     */
    public function getProducto(): ?Producto
    {
        if(!empty($this->producto_id)){
            $this->producto = Producto::searchForId($this->Producto_IdProducto) ?? new Producto();
            return $this->producto;
        }
        return NULL;
    }

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else {
            $arrData = [
                ':idOrdenCompra' => $this->getIdOrdenCompra(),
                ':cantidad' => $this->getCantidad(),
                ':fabricacionId' => $this->getFabricacionId(),
                ':Factura_IdFacturae' => $this->getFacturaIdFactura(),
                ':Producto_IdProducto' => $this->getProductoIdProducto(),


            ];
        }
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert() : ?bool
    {
        $query = "INSERT INTO ornamentacion.ordencompra VALUES (:idOrdenCompra,:cantidad,:fabricacionId,:Factura_IdFactura,:Producto_IdProducto,)";
        if($this->save($query)){
            return $this->getProducto()->susaddStock($this->getCantidad());
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE ornamentacion.ordencompra SET 
            cantidad = :cantidad, 
            fabricacionId = :fabricacionId, Factura_IdFactura = :Factura_IdFactura,
            Producto_IdProducto = : Producto_IdProducto WHERE idOrdenCompra = :idOrdenCompra";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $query = "DELETE FROM ornamentacion.ordencompra WHERE idOrdenCompra = :idOrdenCompra";
        return $this->save($query, 'deleted');                 //Guarda los cambios.
    }

    static function search($query): ?array
    {
        try {
            $arrOrden = array();
            $tmp = new Orden();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Orden = new Orden($valor);
                    array_push($arrOrden, $Orden);
                    unset($Orden);
                }
                return $arrOrden;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmporden = new Orden();
                $tmporden->Connect();
                $getrow = $tmporden->getRow("SELECT * FROM ornamentacion.ordencompra WHERE idOrdenCompra =?", array($id));
                $tmporden->Disconnect();
                return ($getrow) ? new Orden($getrow) : null;
            } else {
                throw new Exception('Id de Orden Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $idOrdenCompra
     * @return bool
     */
    public static function ordenRegistrado($idOrdenCompra): bool
    {
        //$result = producto::search("SELECT * FROM ornamentacion.producto where nombre = " . $nombre);
        $result = orden::search("SELECT * FROM ornamentacion.ordencompra where idOrdenCompra = '" . $idOrdenCompra."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }
    public static function productoEnFactura($Factura_IdFactura,$Producto_IdProducto): bool
    {
        $result = Orden::search("SELECT idOrdenCompra FROM ornamentacion.ordencompra where  Factura_IdFactura = '" . $Factura_IdFactura. "' and Producto_IdProducto = '" . $Producto_IdProducto. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return DetalleVentas::search("SELECT * FROM ornamentacion.ordencompra");
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Factura: ".$this->factura->getNumeroFactura().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";

    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            ':idOrdenCompra' =>    $this->getIdOrdenCompra(),
            ':fabricacionId' =>    $this-> getFabricacionId(),
            ':Factura_IdFacturae' =>   $this->getFacturaIdFactura(),
            ':Producto_IdProducto' =>  $this->getProductoIdProducto(),
            ':estado' =>   $this->getEstado(),


        ];
    }
}