<?php

namespace App\Models;

use App\Interfaces\Model;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class DetalleVentas extends AbstractDBConnection implements Model
{
    private ?int $idOrdenCompra;
    private int $ventas_id;
    private int $Producto_IdProducto;
    private int $cantidad;
    private int $precio;


    /* Relaciones */
    private ?Factura $venta;
    private ?Producto $producto;

    /**
     * Detalle Venta constructor. Recibe un array asociativo
     * @param array $detalle_venta
     */
    public function __construct(array $detalle_venta = [])
    {
        parent::__construct();
        $this->setId($detalle_venta['idOrdenCompra'] ?? NULL);
        $this->setVentasId($detalle_venta['ventas_id'] ?? 0);
        $this->setProductoIdProducto($detalle_venta['Producto_IdProducto'] ?? 0);
        $this->setCantidad($detalle_venta['cantidad'] ?? 0);
        $this->setPrecio($detalle_venta['precio'] ?? 0.0);

    }

    /**
     *
     */
    function __destruct()
    {
        $this->Disconnect();
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
     * @return int|mixed
     */
    public function getVentasId() : int
    {
        return $this->ventas_id;
    }

    /**
     * @param int|mixed $ventas_id
     */
    public function setVentasId(int $ventas_id): void
    {
        $this->ventas_id = $ventas_id;
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


    /**
     * @return int|mixed
     */
    public function getCantidad() : int
    {
        return $this->cantidad;
    }

    /**
     * @param int|mixed $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float|mixed
     */
    public function getPrecio() : float
    {
        return $this->precio;
    }

    /**
     * @param float|mixed $precio_venta
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getTotalProducto() : float
    {
        return $this->getPrecio() * $this->getCantidad();
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @param Carbon $created_at
     */
    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    /* Relaciones */
    /**
     * Retorna el objeto venta correspondiente al detalle venta
     * @return Factura|null
     */
    public function getVenta(): ?Factura
    {
        if(!empty($this->ventas_id)){
            $this->venta = Factura::searchForId($this->ventas_id) ?? new Factura();
            return $this->venta;
        }
        return NULL;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Producto|null
     */
    public function getProducto(): ?Producto
    {
        if(!empty($this->Producto_IdProducto)){
            $this->producto = Producto::searchForId($this->Producto_IdProducto) ?? new Producto();
            return $this->producto;
        }
        return NULL;
    }

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':idOrdenCompra' =>   $this->getIdOrdenCompra() ];
        }else{
            $arrData = [
                ':idOrdenCompra' =>   $this->getIdOrdenCompra(),
                ':ventas_id' =>   $this->getVentasId(),
                ':Producto_IdProducto' =>  $this->getProductoIdProducto(),
                ':cantidad' =>   $this->getCantidad(),
                ':precio_venta' =>   $this->getPrecio(),

            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.detalle_ventas VALUES (:idOrdenCompra,:ventas_id,:Producto_IdProducto,:cantidad,:precio)";
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
        $query = "UPDATE ornamentacion.detalle_ventas SET 
            ventas_id = :ventas_id, Producto_IdProducto = :Producto_IdProducto, cantidad = :cantidad, 
            precio = :precio WHERE idOrdenCompra = :idOrdenCompra";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM ornamentacion.detalle_ventas WHERE idOrdenCompra = :idOrdenCompra";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetalleVenta = array();
            $tmp = new DetalleVentas();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleVenta = new DetalleVentas($valor);
                array_push($arrDetalleVenta, $DetalleVenta);
                unset($DetalleVenta);
            }
            return $arrDetalleVenta;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?DetalleVentas
    {
        try {
            if ($id > 0) {
                $DetalleVenta = new DetalleVentas();
                $DetalleVenta->Connect();
                $getrow = $DetalleVenta->getRow("SELECT * FROM ornamentacion.detalle_ventas WHERE idOrdenCompra = ?", array($id));
                $DetalleVenta->Disconnect();
                return ($getrow) ? new DetalleVentas($getrow) : null;
            }else{
                throw new Exception('Id de detalle venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public static function getAll() : array
    {
        return DetalleVentas::search("SELECT * FROM ornamentacion.detalle_ventas");
    }

    /**
     * @param $venta_id
     * @param $producto_id
     * @return bool
     */
    public static function productoEnFactura($ventas_id,$Producto_IdProducto): bool
    {
        $result = DetalleVentas::search("SELECT idOrdenCompra FROM ornamentacion.detalle_ventas where ventas_id = '" . $ventas_id. "' and Producto_IdProducto = '" . $Producto_IdProducto. "'");
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Factura: ".$this->venta->getNumeroSerie().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio";
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize() : array
    {
        return [
            'venta_id' => $this->getVenta()->jsonSerialize(),
            'Producto_IdProducto' => $this->getProductoIdProducto()->jsonSerialize(),
            'cantidad' => $this->getCantidad(),
            'precio' => $this->getPrecioVenta(),

        ];
    }
}