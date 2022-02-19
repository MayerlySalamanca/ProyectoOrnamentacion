<?php
namespace App\Models;
class Producto extends AbstractDBConnection implements Model
{
Private ? int $IdProducto;
private String $tipo;
private String $nombre;
private int $cantidad;
private double $valor;
private string $material;
private string $tamano;
private string $diseno;
private string $tipoServicio;

    /**
     * @param int|null $IdProducto
     * @param String $tipo
     * @param String $nombre
     * @param int $cantidad
     * @param float $valor
     * @param string $material
     * @param string $tamano
     * @param string $diseno
     * @param string $tipoServicio
     */
    public function __construct(?int $IdProducto, string $tipo, string $nombre, int $cantidad, float $valor, string $material, string $tamano, string $diseno, string $tipoServicio)
    {
        $this->IdProducto = $IdProducto;
        $this->tipo = $tipo;
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->valor = $valor;
        $this->material = $material;
        $this->tamano = $tamano;
        $this->diseno = $diseno;
        $this->tipoServicio = $tipoServicio;
    }

    /**
     * @return int|null
     */
    public function getIdProducto(): ?int
    {
        return $this->IdProducto;
    }

    /**
     * @param int|null $IdProducto
     */
    public function setIdProducto(?int $IdProducto): void
    {
        $this->IdProducto = $IdProducto;
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
     * @return String
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param String $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
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
     * @return float
     */
    public function getValor(): float
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return string
     */
    public function getMaterial(): string
    {
        return $this->material;
    }

    /**
     * @param string $material
     */
    public function setMaterial(string $material): void
    {
        $this->material = $material;
    }

    /**
     * @return string
     */
    public function getTamano(): string
    {
        return $this->tamano;
    }

    /**
     * @param string $tamano
     */
    public function setTamano(string $tamano): void
    {
        $this->tamano = $tamano;
    }

    /**
     * @return string
     */
    public function getDiseno(): string
    {
        return $this->diseno;
    }

    /**
     * @param string $diseno
     */
    public function setDiseno(string $diseno): void
    {
        $this->diseno = $diseno;
    }

    /**
     * @return string
     */
    public function getTipoServicio(): string
    {
        return $this->tipoServicio;
    }

    /**
     * @param string $tipoServicio
     */
    public function setTipoServicio(string $tipoServicio): void
    {
        $this->tipoServicio = $tipoServicio;
    }

    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdProducto' =>    $this->getIdProducto(),
            ':tipo' =>   $this->gettipo(),
            ':nombre' =>   $this->getnombre(),
            ':cantidad' =>   $this->getcantidad(),
            ':valor' =>   $this->getvalor(),
            ':material' =>   $this->getmaterial(),
            ':tamano' =>   $this->gettamano(),
            ':diseno' =>   $this->getdiseno(),
            ':tipoServicio' =>   $this->gettipoServicio(),



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