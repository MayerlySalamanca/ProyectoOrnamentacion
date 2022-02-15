<?php
namespace App\Models;
class Abono
{
private ? int $IdAbono;
private String $Descripcion;
private String $fecha;
private int $valor;
//relaciones
private int $factura_IdFactura;

    /**
     * @param int|null $IdAbono
     * @param String $Descripcion
     * @param String $fecha
     * @param int $valor
     * @param int $factura_IdFactura
     */
    public function __construct(?int $IdAbono, string $Descripcion, string $fecha, int $valor, int $factura_IdFactura)
    {
        $this->IdAbono = $IdAbono;
        $this->Descripcion = $Descripcion;
        $this->fecha = $fecha;
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
        return $this->Descripcion;
    }

    /**
     * @param String $Descripcion
     */
    public function setDescripcion(string $Descripcion): void
    {
        $this->Descripcion = $Descripcion;
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
            ':IdAbono' =>    $this->getIdAbono(),
            ':Descripcion' =>   $this->getDescripcion(),
            ':Fecha' =>   $this->getFecha()->toDateTimeString(),
            ':Valor' =>  $this->getValor(),
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
        $query = "INSERT INTO weber.categorias VALUES (:IdAbono,:nombre,:descripcion,:estado,:created_at,:updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE weber.categorias SET 
            nombre = :nombre, descripcion = :descripcion,
            estado = :estado, created_at = :created_at, 
            updated_at = :updated_at WHERE id = :id";
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
            $arrCategorias = array();
            $tmp = new Categorias();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Categoria = new Categorias($valor);
                array_push($arrCategorias, $Categoria);
                unset($Categoria);
            }
            return $arrCategorias;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }


}