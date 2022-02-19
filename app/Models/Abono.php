<?php
namespace App\Models;

class Abono extends AbstractDBConnection implements Model
{
private ? int $IdAbono;
private String $descripcion;
private String $fecha;
private String $estado;
private int $valor;
//relaciones
private int $factura_IdFactura;

    /**
     * @param int|null $IdAbono
     * @param String $descripcion
     * @param String $fecha
     * @param String $estado
     * @param int $valor
     * @param int $factura_IdFactura
     */
    public function __construct(?int $IdAbono, string $descripcion, string $fecha, string $estado, int $valor, int $factura_IdFactura)
    {
        $this->IdAbono = $IdAbono;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->valor = $valor;
        $this->factura_IdFactura = $factura_IdFactura;
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
     * @return String
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param String $Descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return String
     */
    public function getFecha(): string
    {
        return $this->fecha;
    }

    /**
     * @param String $fecha
     */
    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return String
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param String $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
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
        return $this->factura_IdFactura;
    }

    /**
     * @param int $factura_IdFactura
     */
    public function setFacturaIdFactura(int $factura_IdFactura): void
    {
        $this->factura_IdFactura = $factura_IdFactura;
    }



    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':idAbono' =>    $this->getIdAbono(),
            ':descripcion' =>   $this->getDescripcion(),
            ':fecha' =>   $this->getFecha()->toDateTimeString(),
            ':estado' =>   $this->getDescripcion(),
            ':valor' =>  $this->getValor(),
            ':factura_IdFactura' =>   $this->getFacturaIdFactura(),

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
        $query = "INSERT INTO proyecto.Abono VALUES (:IdAbono,:Descripcion,:Fecha,:estado,:Valor,:factura_IdFactura)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE proyecto.Abono SET 
            nombre = :nombre, descripcion = :descripcion,
            estado = :estado, fecha = :fecha, 
            valor = :valor, factura_IdFactura = :factura_IdFactura WHERE idAbono = :idAbono";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Categorias|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrAbono = array();
            $tmp = new Abono();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Abono = new Abono($valor);
                array_push($arrAbono, $Abono);
                unset($Abono);
            }
            return $arrAbono;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
    /**
     * @param $id
     * @return Abono
     * @throws Exception
     */
    public static function searchForId($id) : ?Abono
    {
        try {
            if ($id > 0) {
                $Abono= new Abono();
                $Abono->Connect();
                $getrow = $Abono->getRow("SELECT * FROM proyecto.Abono WHERE id =?", array($id));
                $Abono->Disconnect();
                return ($getrow) ? new Abono($getrow) : null;
            }else{
                throw new Exception('Id de Abono Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll() : ?array
    {
        return Abono::search("SELECT * FROM proyecto.Abono");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function categoriaRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Abono::search("SELECT id FROM proyecto.Abono where nombre = '" . $nombre. "'");
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
        return "IdAbono: $this->IdAbono, Descripción: $this->descripcion, fecha: $this->fecha ,Estado: $this->estado,valor:$this->valor, factura_IdFactura: $this->factura_IdFactura ";
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): array
    {
        return [
            'IdAbono' => $this->getIdAbono(),
            'descripcion' => $this->getDescripcion(),
            'fecha' => $this->getfecha(),
            'estado' => $this->getEstado(),
            'valor' => $this->getvalor(),
            'factura_IdFactura' => $this->getfactura_IdFactura(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }



}