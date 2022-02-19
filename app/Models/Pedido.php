<?php
namespace App\Models;
class Pedido extends AbstractDBConnection implements Model
{
private ? int $idPedido;
private string $nombre;
private string $fechaPedido;
private string $fechaEntrega;
private String $estado;
private int $proveedor_IdProveedor;

    /**
     * @param int|null $idPedido
     * @param string $nombre
     * @param string $fechaPedido
     * @param string $fechaEntrega
     * @param String $estado
     * @param int $proveedor_IdProveedor
     */
    public function __construct(?int $idPedido, string $nombre, string $fechaPedido, string $fechaEntrega, string $estado, int $proveedor_IdProveedor)
    {
        $this->idPedido = $idPedido;
        $this->nombre = $nombre;
        $this->fechaPedido = $fechaPedido;
        $this->fechaEntrega = $fechaEntrega;
        $this->estado = $estado;
        $this->proveedor_IdProveedor = $proveedor_IdProveedor;
    }

    /**
     * @return int|null
     */
    public function getIdPedidos(): ?int
    {
        return $this->idPedido;
    }

    /**
     * @param int|null $idPedido
     */
    public function setIdPedidos(?int $idPedido): void
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
     * @return string
     */
    public function getFechaPedido(): string
    {
        return $this->fechaPedido;
    }

    /**
     * @param string $fechaPedido
     */
    public function setFechaPedido(string $fechaPedido): void
    {
        $this->fechaPedido = $fechaPedido;
    }

    /**
     * @return string
     */
    public function getFechaEntrega(): string
    {
        return $this->fechaEntrega;
    }

    /**
     * @param string $fechaEntrega
     */
    public function setFechaentrega(string $fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return String
     */
    public function getestado(): string
    {
        return $this->estado;
    }

    /**
     * @param String $Estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return int
     */
    public function getProveedorIdProveedor(): int
    {
        return $this->proveedor_IdProveedor;
    }

    /**
     * @param int $proveedor_IdProveedor
     */
    public function setProveedorIdProveedor(int $proveedor_IdProveedor): void
    {
        $this->proveedor_IdProveedor = $proveedor_IdProveedor;
    }




    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdPedidos' =>    $this->getIdPedidos(),
            ':nombre' =>   $this->getnombre(),
            ':fechaPedido' =>   $this->getfechaPedido()->toDateTimeString(),
            ':fechaEntrega' =>   $this->getfechaEntrega()->toDateTimeString(),
            ':estado' =>   $this->getnombre(),
            ':proveedor_IdProveedor' =>   $this->getproveedor_IdProveedor(),
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
        $query = "INSERT INTO proyecto.pedidos VALUES (:IdPedido,:nombre,:fechaPedido,:fechaEntrega,:proveedor_IdProveedor)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE proyecto.pedidos SET 
            nombre = :nombre, fechaPedido = :fechaPedido,
            estado = :estado, fechaEntrega = :fechaEntrega, 
            proveedor_IdProveedor = :proveedor_IdProveedor WHERE idPedido = :idPedido";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo");              //Cambia el estado del Usuario
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
            $arrPedido = array();
            $tmp = new Pedido();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Pedido= new    Pedidos($valor);
                array_push($arrPedido, $Pedido);
                unset($Pedido);
            }
            return $arrPedido;
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