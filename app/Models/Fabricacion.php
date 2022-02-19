<?php
namespace App\Models;
class Fabricacion extends AbstractDBConnection implements Models
{
private ? int $idFabricacion;
private int $cantidad;
private int $MateriaPrima;
private int $Usuario_IdUsuario;
private string $estado;

    /**
     * @param int|null $idFabricacion
     * @param int $cantidad
     * @param int $MateriaPrima
     * @param int $Usuario_IdUsuario
     * @param string $estado
     */
    public function __construct(?int $idFabricacion, int $cantidad, int $MateriaPrima, int $Usuario_IdUsuario, string $estado)
    {
        $this->idFabricacion = $idFabricacion;
        $this->cantidad = $cantidad;
        $this->MateriaPrima = $MateriaPrima;
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
        $this->estado = $estado;
    }


    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':idFabricacion' =>    $this->getidFabricacion(),
            ':cantidad' =>   $this->getcantidad(),
            ':MateriaPrima' =>   $this->getMateriaPrima(),
            ':Usuario_IdUsuario' =>   $this->getUsuario_IdUsuario(),


        ];

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return int|null
     */
    public function getIdFabricacion(): ?int
    {
        return $this->idFabricacion;
    }

    /**
     * @param int|null $idFabricacion
     */
    public function setIdFabricacion(?int $idFabricacion): void
    {
        $this->idFabricacion = $idFabricacion;
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
     * @return int
     */
    public function getMateriaPrima(): int
    {
        return $this->MateriaPrima;
    }

    /**
     * @param int $MateriaPrima
     */
    public function setMateriaPrima(int $MateriaPrima): void
    {
        $this->MateriaPrima = $MateriaPrima;
    }

    /**
     * @return int
     */
    public function getUsuarioIdUsuario(): int
    {
        return $this->Usuario_IdUsuario;
    }

    /**
     * @param int $Usuario_IdUsuario
     */
    public function setUsuarioIdUsuario(int $Usuario_IdUsuario): void
    {
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
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
     * @return bool|null
     */
    function insert(): ?bool
    {
        $query = "INSERT INTO proyecto.Fabricacion VALUES (:idFabricacion,:cantidad,:MateriaPrima,:Usuario_IdUsuario,:estado)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE proyecto.categorias SET 
             idFabricacion= :idFabricacion,  cantidad= :cantidad,
            MateriaPrima = :MateriaPrima, Usuario_IdUsuario = :Usuario_IdUsuario, 
             estado= :estado WHERE idFabricacion = :idFabricacion";
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
     * @return Fabricacion|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrFabricacion = array();
            $tmp = new Fabricacion();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Fabricacion = new Fabricacion($valor);
                array_push($arrFabricacion, $Fabricacion);
                unset($Fabricacion);
            }
            return $arrFabricacion;
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