<?php

namespace App\Models;

use App\Enums\Estado;
use App\Interfaces\Model;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class Municipio extends AbstractDBConnection implements Model
{
    private ?int $idMunicipio;
    private string $nombre;
    private Estado $estado;
    private int $departamentosId;

    /* Objeto de la relacion */
    private Departamentos $departamento;

    /**
     * Municipio constructor. Recibe un array asociativo
     * @param array $municipio
     * @throws Exception
     */
    public function __construct(array $municipio = [])
    {
        parent::__construct();
        $this->setIdMunicipio($municipio['idMunicipio'] ?? null);
        $this->setNombre($municipio['nombre'] ?? '');
        $this->setEstado($municipio['estado'] ?? Estado::INACTIVO);
        $this->setDepartamentosId($municipio['departamentosId'] ?? 0);
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
    public function getIdMunicipio(): ?int
    {
        return $this->idMunicipio;
    }

    /**
     * @param int|null $idMunicipio
     * @return Municipio
     */
    public function setIdMunicipio(?int $idMunicipio): Municipio
    {
        $this->idMunicipio = $idMunicipio;
        return $this;
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
     * @return Municipio
     */
    public function setNombre(string $nombre): Municipio
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamentosId(): int
    {
        return $this->departamentosId;
    }

    /**
     * @param int $departamentosId
     */
    public function setDepartamentosId(int $departamentosId): void
    {
        $this->departamentosId = $departamentosId;
    }

    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param Estado|null $estado
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
     * Relacion con departamento
     *
     * @return null|Departamentos
     */
    public function getDepartamento(): ?Departamentos
    {
        if (!empty($this->departamentosId)) {
            $this->departamento = Departamentos::searchForId($this->departamentosId) ?? new Departamentos();
        }
        return $this->departamento;
    }

    public static function search($query): ?array
    {
        try {
            $arrMunicipio = array();
            $tmp = new Municipio();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Municipio = new Municipio($valor);
                array_push($arrMunicipio, $Municipio);
                unset($Municipio);
            }
            return $arrMunicipio;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function searchForId(int $id): ?Municipio
    {
        try {
            if ($id > 0) {
                $tmpMun = new Municipio();
                $tmpMun->Connect();
                $getrow = $tmpMun->getRow("SELECT * FROM ornamentacion.Municipios WHERE idMunicipio =?", array($id));
                $tmpMun->Disconnect();
                return ($getrow) ? new Municipio($getrow) : null;
            } else {
                throw new Exception('Id de municipio Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function getAll(): array
    {
        return Municipio::search("SELECT * FROM ornamentacion.Municipios");
    }

    public function __toString() : string
    {
        return "Nombre: $this->nombre, Estado:" .  $this->estado->toString() ;
    }

    #[ArrayShape([
        'idMunicipio' => "int|null",
        'nombre' => "string",
        'estado' => "string",
        'departamentosId' => "array"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'idMunicipio' => $this->getIdMunicipio(),
            'nombre' => $this->getNombre(),
            'estado' => $this->getEstado(),
            'departamentosId' => $this->getDepartamento()->jsonSerialize(),
        ];
    }

    protected function save(string $query): ?bool
    {
        return null;
    }

    public function insert(): ?bool
    {
        return false;
    }

    public function update(): ?bool
    {
        return false;
    }

    public function deleted(): ?bool
    {
        return false;
    }
}