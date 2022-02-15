<?php
namespace App\Models;
class MateriaPrima
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