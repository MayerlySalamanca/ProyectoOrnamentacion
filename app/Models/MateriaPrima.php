<?php
namespace App\Models;
class MateriaPrima extends AbstractDBConnection implements Model
{
private ? int $idMateria;
private string $nombre;
private string $tipo;
private int $stock;

    /**
     * @param int|null $idMateria
     * @param string $nombre
     * @param string $tipo
     * @param int $stock
     */
    public function __construct(?int $idMateria, string $nombre, string $tipo, int $stock)
    {
        $this->idMateria = $idMateria;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->stock = $stock;
    }

    /**
     * @return int|null
     */
    public function getIdMateria(): ?int
    {
        return $this->idMateria;
    }

    /**
     * @param int|null $idMateria
     */
    public function setIdMateria(?int $idMateria): void
    {
        $this->idMateria = $idMateria;
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
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdMateria' =>    $this->getIdMateria(),
            ':nombre' =>    $this->getnombre(),
            ':tipo' =>    $this->gettipo(),
            ':stock' =>    $this->getstock(),
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
        $query = "UPDATE Usuario.categorias SET 
            nombr = :nombre, descripcion = :descripcion,
            estados = :estado, created_at = :created_at, 
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