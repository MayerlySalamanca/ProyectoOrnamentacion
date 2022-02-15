<?php
namespace App\Models;
class Proveedor
{
private ? int $IdProveedor;
private int $documento;
private string $nombre;
private string $ciudad;

    /**
     * @param int|null $IdProveedor
     * @param int $documento
     * @param string $nombre
     * @param string $ciudad
     */
    public function __construct(?int $IdProveedor, int $documento, string $nombre, string $ciudad)
    {
        $this->IdProveedor = $IdProveedor;
        $this->documento = $documento;
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
    }

    /**
     * @return int|null
     */
    public function getIdProveedor(): ?int
    {
        return $this->IdProveedor;
    }

    /**
     * @param int|null $IdProveedor
     */
    public function setIdProveedor(?int $IdProveedor): void
    {
        $this->IdProveedor = $IdProveedor;
    }

    /**
     * @return int
     */
    public function getDocumento(): int
    {
        return $this->documento;
    }

    /**
     * @param int $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
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
     * @return string
     */
    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    /**
     * @param string $ciudad
     */
    public function setCiudad(string $ciudad): void
    {
        $this->ciudad = $ciudad;
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