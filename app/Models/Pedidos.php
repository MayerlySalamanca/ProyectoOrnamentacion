<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\Roll;
use App\Enums\Tipo;
use App\Enums\TipoServicioProduct;
use App\Models\Proveedor;
use Carbon\Carbon;
use JetBrains\PhpStorm\Internal\TentativeType;

class Pedidos extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $idPedido;
    private int $numeroPedido;
    private string $nombre;
    private carbon $fechaPedido;
    private carbon $fechaEntrega;
    private Estado $estado;
    private int  $Proveedor_IdProveedor;

    public function __construct(array $Pedidos = [])
    {
        parent::__construct();
        $this->setIdPedido($Pedidos['idPedido'] ?? null);
        $this->setNumeroPedido($Pedidos['numeroPedido'] ?? 0);
        $this->setNombre($Pedidos['nombre'] ?? '');
        $this->setFechaPedido(!empty($Pedidos['fechaPedido']) ? Carbon::parse($Pedidos['fechaPedido']) : new Carbon());
        $this->setFechaEntrega(!empty($Pedidos['fechaEntrega']) ? Carbon::parse($Pedidos['fechaEntrega']) : new Carbon());
        $this->setEstado($Pedidos['estado'] ?? Estado::INACTIVO);
        $this->setProveedorIdProveedor($Pedidos['Proveedor_IdProveedor'] ?? 0);


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
    public function getNumeroPedido(): int
    {
        return $this->numeroPedido;
    }

    /**
     * @param int $numeroPedido
     */
    public function setNumeroPedido(int $numeroPedido): void
    {
        $this->numeroPedido = $numeroPedido;
    }



    /**
     * @return Carbon
     */
    public function getFechaPedido(): Carbon
    {
        return $this->fechaPedido->locale('es');
        //return $this->fechaPedido;
    }

    /**
     * @param Carbon $fechaPedido
     */
    public function setFechaPedido(Carbon $fechaPedido): void
    {
        $this->fechaPedido = $fechaPedido;
    }

    /**
     * @return Carbon
     */
    public function getFechaEntrega(): Carbon
    {
        return $this->fechaEntrega->locale('es');
        //return $this->fechaEntrega;
    }

    /**
     * @param Carbon $fechaEntrega
     */
    public function setFechaEntrega(Carbon $fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
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
    public function getIdPedido(): ?int
    {
        return $this->idPedido;
    }

    /**
     * @param int|null $idPedido
     */
    public function setIdPedido(?int $idPedido): void
    {
        $this->idPedido = $idPedido;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int
     */
    public function getProveedorIdProveedor(): int
    {
        return $this->Proveedor_IdProveedor;
    }

    /**
     * @param int $Proveedor_IdProveedor
     */
    public function setProveedorIdProveedor(int $Proveedor_IdProveedor): void
    {
        $this->Proveedor_IdProveedor = $Proveedor_IdProveedor;
    }





    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idPedidos' =>    $this->getIdPedido(),
            ':numeroPedido' =>    $this->getNumeroPedido(),
            ':nombre' =>   $this->getNombre(),
            ':fechaPedido' =>  $this->getFechaPedido()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':fechaEntrega' =>  $this->getFechaEntrega()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':estado' =>   $this->getEstado(),
            ':Proveedor_IdProveedor' =>   $this->getProveedorIdProveedor(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.pedidos VALUES (
            :idPedidos,:numeroPedido,:nombre,
            :fechaPedido,:fechaEntrega,:estado,:Proveedor_IdProveedor
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.pedidos SET 
            numeroPedido=: numeroPedido,nombre = :nombre, fechaPedido= :fechaPedido, fechaEntrega = :fechaEntrega,
            estado = :estado,Proveedor_IdProveedor = :Proveedor_IdProveedor,
            WHERE idPedidos = :IdidPedidos";
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
            $arrPedidos = array();
            $tmp = new Pedidos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Pedidos = new Pedidos($valor);
                    array_push($arrPedidos, $Pedidos);
                    unset($Pedidos);
                }
                return $arrPedidos;
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
                $tmpPedidos = new Pedidos();
                $tmpPedidos->Connect();
                $getrow = $tmpPedidos->getRow("SELECT * FROM ornamentacion.pedidos WHERE idPedidos =?", array($id));
                $tmpPedidos->Disconnect();
                return ($getrow) ? new Pedidos($getrow) : null;
            } else {
                throw new Exception('Id de Pedidos Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $numeroPedido
     * @return bool
     */
    public static function pedidoRegistrado($numeroPedido): bool
    {
        //$result = producto::search("SELECT * FROM ornamentacion.producto where nombre = " . $nombre);

        $result = pedidos::search("SELECT * FROM ornamentacion.pedidos where numeroPedido = '" . $numeroPedido."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return pedidos::search("SELECT * FROM ornamentacion.pedidos");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [



        ];
    }
}