<?php
namespace App\Models;

class DetallePedido extends AbstractDBConnection implements Model
{
private ? INT $idDetallePedido;
private int $valor;
private int $cantidad;
private string $estado;
//Relaciones
private int $pedidosId;
private int $materiaPrimaId;

    /**
     * @param INT|null $idDetallePedido
     * @param int $valor
     * @param int $cantidad
     * @param string $estado
     * @param int $pedidosId
     * @param int $materiaPrimaId
     */
    public function __construct(?int $idDetallePedido, int $valor, int $cantidad, string $estado, int $pedidosId, int $materiaPrimaId)
    {
        $this->idDetallePedido = $idDetallePedido;
        $this->valor = $valor;
        $this->cantidad = $cantidad;
        $this->estado = $estado;
        $this->pedidosId = $pedidosId;
        $this->materiaPrimaId = $materiaPrimaId;
    }

    /**
     * @return int|null
     */
    public function getIdDetallePedido(): ?int
    {
        return $this->idDetallePedido;
    }

    /**
     * @param int|null $idDetallePedido
     */
    public function setIdDetallePedido(?int $idDetallePedido): void
    {
        $this->idDetallePedido = $idDetallePedido;
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
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return int
     */
    public function getPedidosId(): int
    {
        return $this->pedidosId;
    }

    /**
     * @param int $pedidosId
     */
    public function setPedidosId(int $pedidosId): void
    {
        $this->pedidosId = $pedidosId;
    }

    /**
     * @return int
     */
    public function getMateriaPrimaId(): int
    {
        return $this->materiaPrimaId;
    }

    /**
     * @param int $materiaPrimaId
     */
    public function setMateriaPrimaId(int $materiaPrimaId): void
    {
        $this->materiaPrimaId = $materiaPrimaId;
    }


    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdDetallePedido' =>    $this->getIdDetallePedido(),
            ':valor ' =>   $this->getvalor (),
            ':cantidad' =>  $this->getcantidad(),
            ':estado' =>  $this->getestado(),
            ':pedidosId' =>  $this->getpedidosId(),
            ':materiaPrimaId' =>   $this->getmateriaPrimaId(),
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
        $query = "INSERT INTO weber.categorias VALUES (:idDetallePedido,:valor,:cantidad,:estado,:pedidosId,:materiaPrimaId)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE proyecto.DetallePedido SET 
           IdDetallePedido = :IdDetallePedido, valor = :valor,
         cantidad = :cantidad, estado = :estado, 
            pedidosId = :pedidosId,materiaPrimaId= :materiaPrimaId WHERE idDetallePedido = :idDetallePedido";
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
     * @return DetallePedido|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrDetallePedido = array();
            $tmp = new DetallePedido();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $DetallePedido = new DetallePedido($valor);
                array_push($arrDetallePedido, $DetallePedido);
                unset($DetallePedido);
            }
            return $arrDetallePedido;
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