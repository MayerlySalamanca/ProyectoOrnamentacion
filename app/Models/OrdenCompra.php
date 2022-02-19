<?php
namespace App\Models;
class OrdenCompra extends AbstractDBConnection implements Model
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
            $arrOrdenCompras = array();
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
    /**
     * @param $id
     * @return Categorias
     * @throws Exception
     */
    public static function searchForId($id) : ?Categorias
    {
        try {
            if ($id > 0) {
                $Categoria = new Categorias();
                $Categoria->Connect();
                $getrow = $Categoria->getRow("SELECT * FROM weber.categorias WHERE id =?", array($id));
                $Categoria->Disconnect();
                return ($getrow) ? new Categorias($getrow) : null;
            }else{
                throw new Exception('Id de categoria Invalido');
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
        return Categorias::search("SELECT * FROM weber.categorias");
    }

    /**
     * @param $nombre
     * @return bool
     * @throws Exception
     */
    public static function categoriaRegistrada($nombre): bool
    {
        $nombre = trim(strtolower($nombre));
        $result = Categorias::search("SELECT id FROM weber.categorias where nombre = '" . $nombre. "'");
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
        return "Nombre: $this->nombre, Descripción: $this->descripcion, Estado: $this->estado";
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
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }

}