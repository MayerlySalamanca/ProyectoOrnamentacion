<?php

namespace App\Models;

use App\Interfaces\Model;
use App\Models\Producto;
use App\Models\Compras;
use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Fabricacion extends AbstractDBConnection implements Model
{
    private ?int $id;
    private int $producto_id;
    private int $compra_id;
    private int $cantidad;
    private float $precio_venta;


    /* Relaciones */
    private ?Producto $producto;
    private ?Compras $compra;

    /**
     * Detalle Compra constructor. Recibe un array asociativo
     * @param array $detalle_compra
     */
    public function __construct(array $detalle_compra = [])
    {
        parent::__construct();
        $this->setId($detalle_compra['id'] ?? NULL);
        $this->setCompraId($detalle_compra['compra_id'] ?? 0);
        $this->setProductoId($detalle_compra['producto_id'] ?? 0);
        $this->setCantidad($detalle_compra['cantidad'] ?? 0);
        $this->setPrecioVenta($detalle_compra['precio_venta'] ?? 0.0);
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
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getProductoId(): int
    {
        return $this->producto_id;
    }

    /**
     * @param int $producto_id
     */
    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    /**
     * @return int|mixed
     */
    public function getCompraId() : int
    {
        return $this->compra_id;
    }

    /**
     * @param int|mixed $compra_id
     */
    public function setCompraId(int $compra_id): void
    {
        $this->compra_id = $compra_id;
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
    public function getPrecioVenta() : float
    {
        return $this->precio_venta;
    }

    /**
     * @param float|mixed $precio_venta
     */
    public function setPrecioVenta(float $precio_venta): void
    {
        $this->precio_venta = $precio_venta;
    }

    public function getTotalProducto() : float
    {
        return $this->getPrecioVenta() ;
    }


    /* Relaciones */
    /**
     * Retorna el objeto venta correspondiente al detalle venta
     * @return Compras|null
     */
    public function getCompra(): ?Compras
    {
        if(!empty($this->compra_id)){
            $this->compra = Compras::searchForId($this->compra_id) ?? new Compras();
            return $this->compra;
        }
        return NULL;
    }

    /**
     * Retorna el objeto producto correspondiente al detalle venta
     * @return Productos|null
     */
    public function getProducto(): ?Producto
    {
        if(!empty($this->producto_id)){
            $this->producto = Producto::searchForId($this->producto_id) ?? new Producto();
            return $this->producto;
        }
        return NULL;
    }

    protected function save(string $query, string $type = 'insert'): ?bool
    {
        if($type == 'deleted'){
            $arrData = [ ':id' =>   $this->getId() ];
        }else{
            $arrData = [
                ':id' =>   $this->getId(),
                ':compra_id' =>   $this->getCompraId(),
                ':producto_id' =>  $this->getProductoId(),
                ':cantidad' =>   $this->getCantidad(),
                ':precio_venta' =>   $this->getPrecioVenta(),
            ];
        }

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert() : ?bool
    {
        $query = "INSERT INTO ornamentacion.fabricacion VALUES (:id,:compra_id,:producto_id,:cantidad,:precio_venta)";
        if($this->save($query)){
            return $this->getProducto()->addStock($this->getCantidad());
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function update() : bool
    {
        $query = "UPDATE ornamentacion.fabricacion SET 
            compra_id = :compra_id,producto_id = :producto_id,  cantidad = :cantidad, 
            precio_venta = :precio_venta  WHERE id = :id";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $query = "DELETE FROM ornamentacion.fabricacion WHERE id = :id";
        return $this->save($query, 'deleted');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetalleCompra = array();
            $tmp = new Fabricacion();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetalleCompra = new Fabricacion($valor);
                array_push($arrDetalleCompra, $DetalleCompra);
                unset($DetalleCompra);
            }
            return $arrDetalleCompra;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function searchForId($id) : ?Fabricacion
    {
        try {
            if ($id > 0) {
                $DetalleCompra = new Fabricacion();
                $DetalleCompra->Connect();
                $getrow = $DetalleCompra->getRow("SELECT * FROM ornamentacion.fabricacion WHERE id = ?", array($id));
                $DetalleCompra->Disconnect();
                return ($getrow) ? new Fabricacion($getrow) : null;
            }else{
                throw new Exception('Id de detalle compra Invalido');
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
        return Fabricacion::search("SELECT * FROM ornamentacion.fabricacion");
    }

    /**
     * @param $compra_id
     * @param $producto_id
     * @return bool
     */

    public static function productoEnFactura($compra_id,$producto_id): bool
    {
        $result = Fabricacion::search("SELECT id FROM ornamentacion.fabricacion where compra_id = '" . $compra_id. "' and producto_id = '" . $producto_id. "'");
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
        return "Venta: ".$this->compra->getNumeroSerie().", Producto: ".$this->producto->getNombre().", Cantidad: $this->cantidad, Precio Venta: $this->precio_venta";
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
            'producto_id' => $this->getProducto()->jsonSerialize(),
            'compra_id' => $this->getCompra()->jsonSerialize(),
            'cantidad' => $this->getCantidad(),
            'precio_venta' => $this->getPrecioVenta(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
        ];
    }
}