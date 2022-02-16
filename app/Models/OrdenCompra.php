<?php
namespace App\Models;
class OrdenCompra
{
    private ? int $idOrdenCompra;
    private int $fabricacionId;
    private int $factura_IdFactura;
    private int $factura_IdProductos;

    /**
     * @param int|null $idOrdenCompra
     * @param int $fabricacionId
     * @param int $factura_IdFactura
     * @param int $factura_IdProductos
     */
    public function __construct(?int $idOrdenCompra, int $fabricacionId, int $factura_IdFactura, int $factura_IdProductos)
    {
        $this->idOrdenCompra = $idOrdenCompra;
        $this->fabricacionId = $fabricacionId;
        $this->factura_IdFactura = $factura_IdFactura;
        $this->factura_IdProductos = $factura_IdProductos;
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
     * @return int
     */
    public function getFacturaIdProductos(): int
    {
        return $this->factura_IdProductos;
    }

    /**
     * @param int $factura_IdProductos
     */
    public function setFacturaIdProductos(int $factura_IdProductos): void
    {
        $this->factura_IdProductos = $factura_IdProductos;
    }

    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdOrdenCompra' =>    $this->getIdOrdenCompra(),
            ':fabricacionId' =>   $this->getfabricacionId(),
            ':factura_IdFactura ' =>   $this->getfactura_IdFactura (),
            ':factura_IdProductos ' =>   $this->getfactura_IdProductos (),
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