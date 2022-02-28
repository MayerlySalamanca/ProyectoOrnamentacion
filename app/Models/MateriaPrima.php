<?php


namespace App\Models;
use App\Interfaces\Model;
use App\Enums\Estado;
use App\Enums\TipoMateria;
use JetBrains\PhpStorm\Internal\TentativeType;

require_once "AbstractDBConnection.php";
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

class MateriaPrima extends AbstractDBConnection implements Model
{

    private ?int $idMateria;
    private String $nombre;
    private TipoMateria $tipo;
    private int $stock;
    private int $valor_venta;
    private Estado $estado;

    public function __construct(array $materia = [])
    {
        parent::__construct();
        $this->setIdMateria($materia['idMateria'] ?? null);
        $this->setNombre($materia['nombre'] ?? '');
        $this->setTipo($materia['tipo'] ?? TipoMateria::PERFILES);
        $this->setValorVenta($materia['valor_venta'] ?? 0);
        $this->setStock($materia['stock'] ?? 0);
        $this->setEstado($materia['estado'] ?? Estado::INACTIVO);

    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int
     */
    public function getValorVenta(): int
    {
        return $this->valor_venta;
    }

    /**
     * @param int $valor_venta
     */
    public function setValorVenta(int $valor_venta): void
    {
        $this->valor_venta = $valor_venta;
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
            ':valor_venta' =>  $this->getValorVenta(),
            ':stock' =>  $this->getStock(),
            ':estado' =>   $this->getEstado()
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.materiaprima VALUES (
            :idMateria,:nombre,:tipo,:valor_venta,
            :stock,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.materiaprima SET 
            nombre = :nombre , tipo = :tipo,
            stock= :stock ,valor_venta= :valor_venta,estado = :estado
            WHERE  idMateria = :idMateria";
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

        $result = MateriaPrima::search("SELECT * FROM ornamentacion.materiaprima where nombre= '" . $nombre."' ");
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
        return MateriaPrima::search("SELECT * FROM ornamentacion.materiaprima");
    }

    public function jsonSerialize() : array
    {
        return [
            'idMateria' => $this->getIdMateria(),
            'nombre' => $this->getNombre(),
            'tipo' => $this->getTipo(),
            'valor_venta' => $this->getValorVenta(),
            'stock' => $this->getStock(),
            'estado' => $this->getEstado(),
        ];
    }


}