<?php
namespace App\Models;
class Pedidos extends AbstractDBConnection implements Model
{
private ? int $idPedidos;
private string $nombre;
private string $fechaPedido;
private string $fechaEntrega;
private String $Estado;
private int $proveedor_IdProveedor;

    /**
     * @param int|null $idPedidos
     * @param string $nombre
     * @param string $fechaPedido
     * @param string $fechaEntrega
     * @param int $proveedor_IdProveedor
     */
    public function __construct(?int $idPedidos, string $nombre, string $fechaPedido, string $fechaEntrega, int $proveedor_IdProveedor)
    {
        $this->idPedidos = $idPedidos;
        $this->nombre = $nombre;
        $this->fechaPedido = $fechaPedido;
        $this->fechaEntrega = $fechaEntrega;
        $this->proveedor_IdProveedor = $proveedor_IdProveedor;
    }

    /**
     * @return int|null
     */
    public function getIdPedidos(): ?int
    {
        return $this->idPedidos;
    }

    /**
     * @param int|null $idPedidos
     */
    public function setIdPedidos(?int $idPedidos): void
    {
        $this->idPedidos = $idPedidos;
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
    public function setFechaEntrega(string $fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
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
        $query = "UPDATE weber.categorias SET 
            nombre = :nombre, fechaPedido = :descripcion,
            estado = :estado, fechaEntrega = :created_at, 
            proveedor_IdProveedor = :updated_at WHERE idPedido = :id";
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
            $arrPedidos = array();
            $tmp = new Pedidos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Pedidos= new    Pedidos($valor);
                array_push($arrPedidos, $Pedidos);
                unset($Pedidos);
            }
            return $arrPedidos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }
}