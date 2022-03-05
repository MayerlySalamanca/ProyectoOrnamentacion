<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\EstadoFactura;
use Carbon\Carbon;
use JetBrains\PhpStorm\Internal\TentativeType;

class Abono extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int $IdAbono;
    private int $numoerAbono;
    private string $descripcion;
    private carbon $fecha;
    private int $valor;
    private Estado $estado;
    private int $Factura_IdFactura;

     public function __construct(array $abono = [])
     {
         parent::__construct();
         $this->setIdAbono($abono['IdAbono'] ?? null);
         $this-> setNumoerAbono($abono['numoerAbono'] ?? 0);
         $this->setDescripcion($abono['descripcion'] ?? '');
         $this->setFecha(!empty($abono['fecha']) ? Carbon::parse($abono['fecha']) : new Carbon());
         $this->setValor($abono['valor'] ?? 0);
         $this->setEstado($abono['estado'] ?? Estado::INACTIVO);
         $this->setFacturaIdFactura($abono['Factura_IdFactura'] ?? 0);


     }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }


    /**
     * @return string
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
     * @return int|null
     */
    public function getIdAbono(): ?int
    {
        return $this->IdAbono;
    }

    /**
     * @param int|null $IdAbono
     */
    public function setIdAbono(?int $IdAbono): void
    {
        $this->IdAbono = $IdAbono;
    }

    /**
     * @return int
     */
    public function getNumoerAbono(): int
    {
        return $this->numoerAbono;
    }

    /**
     * @param int $numoerAbono
     */
    public function setNumoerAbono(int $numoerAbono): void
    {
        $this->numoerAbono = $numoerAbono;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
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
     * @param string $query
     * @return bool|null
     */

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':IdAbono' =>    $this->getIdAbono(),
            ':numoerAbono' =>    $this->getNumoerAbono(),
            ':descripcion' =>    $this->getDescripcion(),
            ':fecha' =>  $this->getFecha()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':valor' =>   $this->getValor(),
            ':estado' =>   $this->getEstado(),
            ':Factura_IdFactura' =>   $this->getFacturaIdFactura(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.abono VALUES (
            :IdAbono,:numoerAbono,:descripcion,
            :fecha,:valor,:estado,:Factura_IdFactura,
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.abono SET 
            numoerAbono = :numoerAbono,descripcion=: descripcion, fecha= :fecha,
            valor = :valor, estado = :estado, Factura_IdFactura = :Factura_IdFactura,
            WHERE IdAbono = :IdAbono";
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
            $arrAbono = array();
            $tmp = new Abono();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Abono = new Abono($valor);
                    array_push($arrAbono, $Abono);
                    unset($Abono);
                }
                return $arrAbono;
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
                $tmpAbono = new Abono();
                $tmpAbono->Connect();
                $getrow = $tmpAbono->getRow("SELECT * FROM ornamentacion.abono WHERE IdAbono =?", array($id));
                $tmpAbono->Disconnect();
                return ($getrow) ? new Abono($getrow) : null;
            } else {
                throw new Exception('Id de Abono Invalido');
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
    public static function facturaRegistrado($IdAbono): bool
    {
        $result = abono::search("SELECT * FROM ornamentacion.abono where IdAbono = '" . $IdAbono."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return abono::search("SELECT * FROM ornamentacion.abono");
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