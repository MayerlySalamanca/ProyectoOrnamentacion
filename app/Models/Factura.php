<?php
namespace App\Models;
class Factura
{
    private ? int $IdFactura;
    private String $tipo;
    private int $Cantidad;
    private String $fechaInicio;
    private ? String $fechaFin;
    private ? String $fechaEstimada;
    private String $estado;
    private String $fechaVenta;
    // realacion
    private int $usuarioComprador;
    private int $usuarioVendedor;

    /**
     * @param int|null $IdFactura
     * @param String $tipo
     * @param int $Cantidad
     * @param String $fechaInicio
     * @param String|null $fechaFin
     * @param String|null $fechaEstimada
     * @param String $estado
     * @param String $fechaVenta
     * @param int $usuarioComprador
     * @param int $usuarioVendedor
     */
    public function __construct(?int $IdFactura, string $tipo, int $Cantidad, string $fechaInicio, ?string $fechaFin, ?string $fechaEstimada, string $estado, string $fechaVenta, int $usuarioComprador, int $usuarioVendedor)
    {
        $this->IdFactura = $IdFactura;
        $this->tipo = $tipo;
        $this->Cantidad = $Cantidad;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->fechaEstimada = $fechaEstimada;
        $this->estado = $estado;
        $this->fechaVenta = $fechaVenta;
        $this->usuarioComprador = $usuarioComprador;
        $this->usuarioVendedor = $usuarioVendedor;
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
     * @return String
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param String $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->Cantidad;
    }

    /**
     * @param int $Cantidad
     */
    public function setCantidad(int $Cantidad): void
    {
        $this->Cantidad = $Cantidad;
    }

    /**
     * @return String
     */
    public function getFechaInicio(): string
    {
        return $this->fechaInicio;
    }

    /**
     * @param String $fechaInicio
     */
    public function setFechaInicio(string $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return String|null
     */
    public function getFechaFin(): ?string
    {
        return $this->fechaFin;
    }

    /**
     * @param String|null $fechaFin
     */
    public function setFechaFin(?string $fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * @return String|null
     */
    public function getFechaEstimada(): ?string
    {
        return $this->fechaEstimada;
    }

    /**
     * @param String|null $fechaEstimada
     */
    public function setFechaEstimada(?string $fechaEstimada): void
    {
        $this->fechaEstimada = $fechaEstimada;
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
     * @return String
     */
    public function getFechaVenta(): string
    {
        return $this->fechaVenta;
    }

    /**
     * @param String $fechaVenta
     */
    public function setFechaVenta(string $fechaVenta): void
    {
        $this->fechaVenta = $fechaVenta;
    }

    /**
     * @return int
     */
    public function getUsuarioComprador(): int
    {
        return $this->usuarioComprador;
    }

    /**
     * @param int $usuarioComprador
     */
    public function setUsuarioComprador(int $usuarioComprador): void
    {
        $this->usuarioComprador = $usuarioComprador;
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
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdFactura' =>    $this->getIdFactura(),
            ':tipo ' =>   $this->gettipo(),
            ':Cantidad' =>   $this->getCantidad(),
            ':fechaInicio' =>   $this->getfechaInicio()->toDateTimeString(),
            ':fechaFin' =>   $this->getfechaFin()->toDateTimeString(),
            ':fechaEstimada' =>   $this->getfechaEstimada()->toDateTimeString(),
            ':estado ' =>  $this->getestado (),
            ':fechaVenta ' =>   $this->getfechaVenta ()->toDateTimeString(),
            ':usuarioComprador' =>  $this->getusuarioComprador(),
            ':usuarioVendedor' =>  $this->getusuarioVendedor(),

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