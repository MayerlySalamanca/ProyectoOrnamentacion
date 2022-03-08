<?php
namespace App\Models;

use App\Enums\Estado;
use App\Interfaces\Model;

final class Departamentos extends AbstractDBConnection implements Model
{
    private ?int $idDepartamentos;
    private string $nombre;
    private string $region;
    private Estado $estado;

    /* Relaciones */
    private ?array $MunicipiosDepartamento;

    /**
     * Departamentos constructor. Recibe un array asociativo
     * @param array $departamento
     */
    public function __construct(array $departamento = [])
    {
        parent::__construct();
        $this->setIdDepartamentos($departamento['idDepartamentos'] ?? null);
        $this->setNombre($departamento['nombre'] ?? '');
        $this->setRegion($departamento['region'] ?? '');
        $this->setEstado($departamento['estado'] ?? Estado::INACTIVO);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * @return int|null
     */
    public function getIdDepartamentos(): ?int
    {
        return $this->idDepartamentos;
    }

    /**
     * @param int|null $idDepartamentos
     */
    public function setIdDepartamentos(?int $idDepartamentos): void
    {
        $this->idDepartamentos = $idDepartamentos;
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
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
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

    /* Relaciones */
    /**
     * retorna un array de municipios que perteneces a un departamento
     * @return array
     */
    public function getMunicipiosDepartamento(): ?array
    {
        $this-> MunicipiosDepartamento = Municipio::search(
            "SELECT * FROM ornamentacion.municipios WHERE departamentosId = ".$this->idDepartamentos
        );
        return $this-> MunicipiosDepartamento ?? null;
    }

    public static function search($query): ?array
    {
        try {
            $arrDepartamentos = array();
            $tmp = new Departamentos();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Departamento = new Departamentos($valor);
                array_push($arrDepartamentos, $Departamento);
                unset($Departamento);
            }
            return $arrDepartamentos;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function searchForId(int $id): ?Departamentos
    {
        try {
            if ($id > 0) {
                $tmpDepartamento = new Departamentos();
                $tmpDepartamento->Connect();
                $getrow = $tmpDepartamento->getRow("SELECT * FROM ornamentacion.departamentos WHERE idDepartamentos =?", array($id));
                $tmpDepartamento->Disconnect();
                return ($getrow) ? new Departamentos($getrow) : null;
            } else {
                throw new Exception('Id de departamento Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    public static function getAll(): array
    {
        return Departamentos::search("SELECT * FROM ornamentacion.departamentos");
    }

    public function __toString() : string
    {
        return "Nombre: $this->nombre, Region: $this->region, Estado: " . $this->estado->toString();
    }

    #[ArrayShape([
        'idDepartamentos' => "int|null",
        'nombre' => "string",
        'region' => "string",
        'estado' => "string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'idDepartamentos' => $this->getIdDepartamentos(),
            'nombre' => $this->getNombre(),
            'region' => $this->getRegion(),
            'estado' => $this->getEstado(),
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