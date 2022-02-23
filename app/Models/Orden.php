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
    private Estado $estado;

    public function __construct(array $Orden = [])
    {
        parent::__construct();
        $this->setIdOrdenCompra($Orden['idOrdenCompra'] ?? null);
        $this->setFabricacionId($Orden['fabricacionId'] ?? 0);
        $this->setFacturaIdFactura($Orden['Factura_IdFactura'] ?? 0);
        $this->setProductoIdProducto($Orden['Producto_IdProducto'] ?? 0);
        $this->setEstado($Orden['estado'] ?? Estado::INACTIVO);



    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }


    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param EstadoCategorias|null $estado
     */
    public function setEstado(null|string|Estado $estado): void
    {
        if(is_string($estado)){
            $this->estado = Estado::from($estado);
        }else{
            $this->estado = $estado;
        }
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


    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idOrdenCompra' =>    $this->getIdOrdenCompra(),
            ':fabricacionId' =>    $this-> getFabricacionId(),
            ':Factura_IdFacturae' =>   $this->getFacturaIdFactura(),
            ':Producto_IdProducto' =>  $this->getProductoIdProducto(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.ordencompra VALUES (
            :idOrdenCompra,:estado,:fabricacionId,:Factura_IdFactura,
            :Producto_IdProducto
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.ordencompra SET 
           fabricacionId = :fabricacionId, Factura_IdFactura= :Factura_IdFactura,
            Producto_IdProducto= :Producto_IdProducto,estado = :estado
            WHERE  idOrdenCompra = : idOrdenCompra";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
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

    /**
     * @return array|null
     */
    static function getAll(): ?array
    {
        return orden::search("SELECT * FROM ornamentacion.orden");
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