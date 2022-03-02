<?php

namespace App\Models;

use App\Enums\EstadoFactura;
use App\Models\Usuario;
use App\Interfaces\Model;

use Carbon\Carbon;
use Exception;
use JsonSerializable;

class Factura extends AbstractDBConnection implements Model
{
    private ?int $idFactura;
    private string $numeroFactura;
    private int $usuarioCliente;
    private int $usuarioVendedor;// Id numerico (1,2,3,4) almacena en BD
    private Carbon $fecha;
    private float $monto;
    private EstadoFactura $estado;


    /* Relaciones */

    private ?Usuario $empleado;
    private ?Usuario $cliente;
    private ?array $detalleVentas;

    /**
     * Venta constructor. Recibe un array asociativo
     * @param array $venta
     */
    public function __construct(array $venta = [])
    {
        parent::__construct();
        $this->setIdFactura($venta['idFactura'] ?? NULL);
        $this->setNumeroFactura($venta['numeroFactura'] ?? '');
        $this->setUsuarioVendedor($venta['usuarioVendedor'] ?? 0);
        $this->setUsuarioCliente($venta['usuarioCliente'] ?? 0);
        $this->setFecha(!empty($venta['fecha']) ? Carbon::parse($venta['fecha']) : new Carbon());
        $this->setEstado($venta['estado'] ?? EstadoFactura::PROCESO );
        $this->setMonto();
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
    public function getIdFactura(): ?int
    {
        return $this->idFactura;
    }

    /**
     * @param int|null $idFactura
     */
    public function setIdFactura(?int $idFactura): void
    {
        $this->idFactura = $idFactura;
    }

    /**
     * @return int
     */
    public function getUsuarioCliente(): int
    {
        return $this->usuarioCliente;
    }

    /**
     * @param int $usuarioCliente
     */
    public function setUsuarioCliente(int $usuarioCliente): void
    {
        $this->usuarioCliente = $usuarioCliente;
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
     * @return float|mixed
     */
    public function getMonto() : float
    {
        return $this->monto;
    }

    /**
     * @param float|mixed $monto
     */
    public function setMonto(): void
    {
        $total = 0;
        if($this->getIdFactura() != null){
            $arrDetallesVenta = $this->getDetalleVentas();
            if(!empty($arrDetallesVenta)){
                /* @var $arrDetallesVenta DetalleVentas[] */
                foreach ($arrDetallesVenta as $DetalleVenta){
                    $total += $DetalleVenta->getTotalProducto();
                }
            }
        }

        $this->monto = $total;
    }

    /**
     * @return EstadoFactura
     */
    public function getEstado(): string
    {
        return ucwords($this->estado->toString());
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
     * @return mixed|string
     */
    public function getNumeroFactura() : string
    {
        return $this->numeroFactura;
    }

    /**
     * @param
     * @throws Exception
     */
    public function setNumeroFactura(string $numeroFactura = null): void
    {
        if(empty($numeroFactura)){
            $this->Connect();
            $this->numeroFactura = 'FV-'.($this->countRowsTable('factura')+1).'-'.date('Y-m-d');
            $this->Disconnect();
        }else{
            $this->numeroFactura = $numeroFactura;
        }
    }


    /**
     * @return Carbon|mixed
     */
    public function getFecha() : Carbon
    {
        return $this->fecha_venta->locale('es');
    }

    /**
     * @param Carbon|mixed $fecha_venta
     */
    public function setFecha(Carbon $fecha_venta): void
    {
        $this->fecha_venta = $fecha_venta;
    }



    /* Relaciones */
    /**
     * Retorna el objeto usuario del empleado correspondiente a la venta
     * @return Usuario|null
     */
    public function getEmpleado(): ?Usuario
    {
        if(!empty($this->usuarioVendedor)){
            $this->empleado = Usuario::searchForId($this->usuarioVendedor) ?? new Usuario();
            return $this->empleado;
        }
        return NULL;
    }
    public function getCliente(): ?Usuario
    {
        if(!empty($this->usuarioCliente)){
            $this->cliente = Usuario::searchForId($this->usuarioCliente) ?? new Usuario();
            return $this->cliente;
        }
        return NULL;
    }

    /**
     * retorna un array de detalles venta que perteneces a una venta
     * @return array
     */
    public function getDetalleVentas(): ?array
    {

        $this->detalleVentas = DetalleVentas::search('SELECT * FROM ornamentacion.detalle_ventas where ventas_id = '.$this->idFactura);
        return $this->detalleVentas;
    }

    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idFactura' =>    $this->getIdFactura(),
            ':numeroFactura' =>   $this->getNumeroFactura(),
            ':usuarioVendedor' =>   $this->getUsuarioVendedor(),
            ':usuarioCliente' =>   $this->getUsuarioCliente(),
            ':fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':monto' =>   $this->getMonto(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.factura VALUES (:idFactura,:numeroFactura,:usuarioCliente,:usuarioVendedor,:fecha,:monto,:estado)";
        return $this->save($query);
    }


    public function update() : ?bool
    {
        $query = "UPDATE ornamentacion.factura SET 
            numeroFactura = :numeroFactura, usuarioCliente = :usuarioCliente,
            usuarioVendedor = :usuarioVendedor, fecha = :fecha,
            monto = :monto, estado = :estado
             WHERE idFactura = :idFactura";
        return $this->save($query);
    }

    /**
     * @return mixed
     */
    public function deleted() : bool
    {
        $this->setEstado("Anulada"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function search($query) : ?array
    {
        try {
            $arrVentas = array();
            $tmp = new Factura();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Venta = new Factura($valor);
                array_push($arrVentas, $Venta);
                unset($Venta);
            }
            return $arrVentas;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @param $id
     * @return Factura
     * @throws Exception
     */
    public static function searchForId($id) : ?Factura
    {
        try {
            if ($id > 0) {
                $Venta = new Factura();
                $Venta->Connect();
                $getrow = $Venta->getRow("SELECT * FROM ornamentacion.factura WHERE idFactura =?", array($id));
                $Venta->Disconnect();
                return ($getrow) ? new Factura($getrow) : null;
            }else{
                throw new Exception('Id de venta Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return NULL;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll() : array
    {
        return Factura::search("SELECT * FROM ornamentacion.factura");
    }

    /**
     * @param $numeroSerie
     * @return bool
     * @throws Exception
     */
    public static function facturaRegistrada($numeroSerie): bool
    {
        $numeroSerie = trim(strtolower($numeroSerie));
        $result = Factura::search("SELECT idFactura FROM ornamentacion.factura where numeroFactura = '" . $numeroSerie. "'");
        if ( !empty($result) && count ($result) > 0 ) {
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
        return "Numero Serie: $this->numeroFactura, Cliente: ".$this->getCliente()->nombresCompletos().", Empleado: ".$this->getEmpleado()->nombresCompletos().", Fecha Venta: $this->fecha->toDateTimeString(), monto: $this->monto, Estado: $this->estado";
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

            'numeroFactura' =>   $this->getNumeroFactura(),
            'empleado' =>   $this->getEmpleado()->jsonSerialize(),
            'cliente' =>   $this->getCliente()->jsonSerialize(),
            'fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            'monto' =>   $this->getMonto(),
            'estado' =>   $this->getEstado(),
        ];
    }
}