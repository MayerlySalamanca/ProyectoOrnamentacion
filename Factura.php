<?php

namespace App\Models;

use App\Enums\EstadoFactura;
use Carbon\Carbon;
use JetBrains\PhpStorm\Internal\TentativeType;

class Factura extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $IdFactura;
    private int $numeroFactura;
    private string $nombreCliente;
    private int $cantidad;
    private Carbon $fecha;
    private EstadoFactura $estado;
    private int $valor;
    private int  $usuarioVendedor;

    public function __construct(array $Factura = [])
    {
        parent::__construct();
        $this->setIdFactura($Factura['IdFactura'] ?? null);
        $this->setNumeroFactura($Factura['numeroFactura'] ?? 0);
        $this->setNombreCliente($Factura['nombreCliente'] ?? '');
        $this->setCantidad($Factura['cantidad'] ?? 0);
        $this->setFecha(!empty($Factura['fecha']) ? Carbon::parse($Factura['fecha']) : new Carbon());
        $this->setEstado($Factura['estado'] ?? EstadoFactura::PROCESO);
        $this->setValor($Factura['valor'] ?? 0);
        $this->setUsuarioVendedor($Factura['usuarioVendedor'] ?? 0);


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
public function getNumeroFactura(): int
{
    return $this->numeroFactura;
}/**
 * @param int $numeroFactura
 */
public function setNumeroFactura(int $numeroFactura): void
{
    $this->numeroFactura = $numeroFactura;
}

    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param string|EstadoFactura|null $estado
     */
    public function setEstado(null|string|EstadoFactura $estado): void
    {
        if(is_string($estado)){
            $this->estado = EstadoFactura::from($estado);
        }else{
            $this->estado = $estado;
        }
    }

    /**
     * @return int|null
     */
    public function getIdFactura(): ?int
    {
        return $this->IdFactura;
    }

    /**
     * @param int|null $IdFactura
     */
    public function setIdFactura(?int $IdFactura): void
    {
        $this->IdFactura = $IdFactura;
    }

    /**
     * @return string
     */
    public function getNombreCliente(): string
    {
        return $this->nombreCliente;
    }

    /**
     * @param string $nombreCliente
     */
    public function setNombreCliente(string $nombreCliente): void
    {
        $this->nombreCliente = $nombreCliente;
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
     * @return Carbon
     */
    public function getFecha(): Carbon
    {
        return $this->fecha->locale('es');
    }

    /**
     * @param Carbon $fecha
     */
    public function setFecha(Carbon $fecha):Carbon
    {
        return $this->fecha=$fecha;
    }

    /**
     * @return int
     */
    public function getValor(): int
    {
        return $this->valor;
    }

    /**
     * @param int $valor
     */
    public function setValor(int $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getUsuarioVendedor(): int
    {
        return $this->usuarioVendedor;
    }

    /**
     * @param int $usuarioVendedor
     */
    public function setUsuarioVendedor(int $usuarioVendedor): void
    {
        $this->usuarioVendedor = $usuarioVendedor;
    }


    /**
     * @param string $query
     * @return bool|null
     */

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':IdFactura' =>    $this->getIdFactura(),
            ':numeroFactura' =>    $this->getNumeroFactura(),
            ':nombreCliente' =>    $this->getNombreCliente(),
            ':cantidad' =>   $this->getCantidad(),
            ':fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':estado' =>   $this->getEstado(),
            ':valor' =>   $this->getValor(),
            ':usuarioVendedor' =>   $this->getUsuarioVendedor(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.factura VALUES (
            :IdFactura,:numeroFactura,:nombreCliente,:cantidad,
            :fecha,:estado,:valor,:usuarioVendedor
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.factura SET 
            numeroFactura = :numeroFactura,nombreCliente=: nombreCliente,cantidad = :cantidad, fecha= :fecha,
            estado = :estado,valor = :valor,usuarioVendedor = :usuarioVendedor,
            WHERE IdFactura = :IdFactura";
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
            $arrFactura = array();
            $tmp = new Factura();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $factura = new Factura($valor);
                    array_push($arrFactura, $factura);
                    unset($factura);
                }
                return $arrFactura;
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
                $tmpFactura = new Factura();
                $tmpFactura->Connect();
                $getrow = $tmpFactura->getRow("SELECT * FROM ornamentacion.factura WHERE IdFactura =?", array($id));
                $tmpFactura->Disconnect();
                return ($getrow) ? new Factura($getrow) : null;
            } else {
                throw new Exception('Id de Factura Invalido');
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
    public static function facturaRegistrado($numeroFactura): bool
    {
        $result = factura::search("SELECT * FROM ornamentacion.factura where numeroFactura = '" . $numeroFactura."' ");
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