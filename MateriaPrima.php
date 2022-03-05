<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\TipoMateria;
use JetBrains\PhpStorm\Internal\TentativeType;

class MateriaPrima extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $idMateria;
    private String $nombre;
    private TipoMateria $tipo;
    private int $stock;
    private Estado $estado;

    public function __construct(array $Orden = [])
    {
        parent::__construct();
        $this->setIdMateria($Orden['idMateria'] ?? null);
        $this->setNombre($Orden['nombre'] ?? '');
        $this->setTipo($Orden['tipo'] ?? TipoMateria::PINTURA);
        $this->setStock($Orden['stock'] ?? 0);
        $this->setEstado($Orden['estado'] ?? Estado::INACTIVO);



    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
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
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo->toString();
    }

    /**
     * @param string|TipoMateria|null $tipo
     */
    public function setTipo(null|string|TipoMateria $tipo): void
    {
        if(is_string($tipo)){
            $this->tipo = TipoMateria::from($tipo);
        }else{
            $this->tipo = $tipo;
        }
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param EstadoCategorias|null $estado
     */
    public function setEstado(null|string|Estado $estado): void
    {
        if(is_string($estado)){
            $this->estado = Estado::from($estado);
        }else{
            $this->estado = $estado;
        }
    }



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idMateria' =>    $this->getIdMateria(),
            ':nombre' =>    $this->getNombre(),
            ':tipo' =>   $this->getTipo(),
            ':stock' =>  $this->getStock(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.materiaprima VALUES (
            :idMateria,:nombre,:tipo,
            :stock,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.materiaprima SET 
           nombre = :nombre, tipo= :tipo,
            stock= :stock,estado = :estado
            WHERE  idMateria = : idMateria";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    static function search($query): ?array
    {
        try {
            $arrMateriaPrima = array();
            $tmp = new MateriaPrima();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Materia = new MateriaPrima($valor);
                    array_push($arrMateriaPrima, $Materia);
                    unset($Materia);
                }
                return $arrMateriaPrima;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpmateria = new MateriaPrima();
                $tmpmateria->Connect();
                $getrow = $tmpmateria->getRow("SELECT * FROM ornamentacion.materiaprima WHERE idMateria =?", array($id));
                $tmpmateria->Disconnect();
                return ($getrow) ? new MateriaPrima($getrow) : null;
            } else {
                throw new Exception('Id de Materia Prima Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }


    /**
     * @param $idMateria
     * @return bool
     */
    public static function materiaRegistrado($nombre): bool
    {
        //$result = producto::search("SELECT * FROM ornamentacion.producto where nombre = " . $nombre);
        $result = materiaprima::search("SELECT * FROM ornamentacion.materiaprima where nombre= '" . $nombre."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }
    public function addStock(int $quantity)
    {
        $this->setStock( $this->getStock() + $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }
    public function substractStock(int $quantity)
    {
        $this->setStock( $this->getStock() - $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    static function getAll(): ?array
    {
        return orden::search("SELECT * FROM ornamentacion.materiaprima");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            ':idMateria' =>    $this->getIdMateria(),
            ':nombre' =>    $this->getNombreo(),
            ':tipo' =>   $this->getTipo(),
            ':stock' =>  $this->getStock(),
            ':estado' =>   $this->getEstado(),

        ];
    }
}