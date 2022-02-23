<?php

namespace App\Models;

use App\Enums\Estado;
use JetBrains\PhpStorm\Internal\TentativeType;

class Fabricacion extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int $idFabricacion;
    private int $numeroFabricacion;
    private int $cantidad;
    private Estado $estado;
    private int $MateriaPrima_idMateria;
    private int $Usuario_IdUsuario;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $fabricacion
     */
    public function __construct(array $fabricacion = [])
    {
        parent::__construct();
        $this->setIdFabricacion($fabricacion['idFabricacion'] ?? null);
        $this->setNumeroFabricacion($fabricacion['numeroFabricacion'] ?? 0);
        $this->setCantidad($fabricacion['cantidad'] ?? 0);
        $this->setEstado($fabricacion['estado'] ?? Estado::INACTIVO);
        $this->setMateriaPrimaIdMateria($fabricacion['MateriaPrima_idMateria'] ?? 0);
        $this->setUsuarioIdUsuario($fabricacion['Usuario_IdUsuario'] ?? 0);

    }


    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }
    /**
     * @return Estado
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
    public function getNumeroFabricacion(): int
    {
        return $this->numeroFabricacion;
    }

    /**
     * @param int $numeroFabricacion
     */
    public function setNumeroFabricacion(int $numeroFabricacion): void
    {
        $this->numeroFabricacion = $numeroFabricacion;
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
    public function getMateriaPrimaIdMateria(): int
    {
        return $this->MateriaPrima_idMateria;
    }

    /**
     * @param int $MateriaPrima_idMateria
     */
    public function setMateriaPrimaIdMateria(int $MateriaPrima_idMateria): void
    {
        $this->MateriaPrima_idMateria = $MateriaPrima_idMateria;
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






    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idFabricacion' =>    $this->getIdFabricacion(),
            ':numeroFabricacion' =>   $this->getNumeroFabricacion(),
            ':cantidad' =>   $this->getCantidad(),
            ':estado' =>   $this->getEstado(),
            ':MateriaPrima_idMateria' =>   $this->getMateriaPrimaIdMateria(),
            ':Usuario_IdUsuario' =>   $this->getUsuarioIdUsuario(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.fabricacion  VALUES (
            :idFabricacion,:numeroFabricacion,:cantidad,
            :estado,:MateriaPrima_idMateria,:Usuario_IdUsuario
        
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.fabricacion SET 
            numeroFabricacion = :numeroFabricacion,
            cantidad = :cantidad, estado = :estado, MateriaPrima_idMateria = :MateriaPrima_idMateria, 
            Usuario_IdUsuario = :Usuario_IdUsuario WHERE IdUsuario = :IdUsuario";
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
            $arrFabricacion = array();
            $tmp = new Fabricacion();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Fabricacion = new Fabricacion($valor);
                    array_push($arrFabricacion, $Fabricacion);
                    unset($Fabricacion);
                }
                return $arrFabricacion;
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
                $tmpFabricacion = new Fabricacion();
                $tmpFabricacion->Connect();
                $getrow = $tmpFabricacion->getRow("SELECT * FROM ornamentacion.fabricacion WHERE idFabricacion =?", array($id));
                $tmpFabricacion->Disconnect();
                return ($getrow) ? new Fabricacion($getrow) : null;
            } else {
                throw new Exception('Id de fabricacion Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $numeroPedido
     * @return bool
     */
    public static function pedidoRegistrado($numeroFabricacion): bool
    {
        $result = fabricacion::search("SELECT * FROM ornamentacion.fabricacion where numeroFabricacion = '" . $numeroFabricacion."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return fabricacion::search("SELECT * FROM ornamentacion.fabricacion");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [



        ];
    }
}