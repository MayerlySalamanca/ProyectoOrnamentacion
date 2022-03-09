<?php

namespace App\Models;
use App\Enums\Estado;
use App\Interfaces\Model;

class Proveedor extends AbstractDBConnection implements Model
{
    private ?int  $IdProveedor;
    private int $documento;
    private string $nombre;
    private Estado $estado;
    private ? int $municipiosId;
//Realacion
    private ? Municipio $municipios;
    /**
     * @param array $proveedor
     */
    public function __construct(array $proveedor = [])
    {
        parent::__construct();
        $this->setIdProveedor( $proveedor['idProveedor'] ?? null) ;
        $this->setDocumento($proveedor['documento'] ?? 0);
        $this->setNombre($proveedor['nombre'] ?? '') ;
        $this->setEstado($proveedor['estado'] ?? Estado::INACTIVO);
        $this->setMunicipiosId($proveedor['municipiosId']??null);
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
    public function getIdProveedor(): ?int
    {
        return $this->IdProveedor;
    }

    /**
     * @param int|null $IdProveedor
     */
    public function setIdProveedor(?int $IdProveedor): void
    {
        $this->IdProveedor = $IdProveedor;
    }

    /**
     * @return int
     */
    public function getDocumento(): int
    {
        return $this->documento;
    }

    /**
     * @param int $documento
     */
    public function setDocumento(int $documento): void
    {
        $this->documento = $documento;
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
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param string|Estado|null $estado
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
    public function getMunicipiosId(): ?int
    {
        return $this->municipiosId;
    }

    /**
     * @param int|null $municipiosId
     */
    public function setMunicipiosId(?int $municipiosId): void
    {
        $this->municipiosId = $municipiosId;
    }

    /**
     * @return Municipio|null
     */
    public function getMunicipio(): ?Municipio
    {
        if(!empty($this->municipiosId)){
            $this->municipios = Municipio::searchForId( $this->municipiosId) ?? new Municipio();
            return $this->municipios;
        }
        return null;
    }

    protected function save(string $query): ?bool

    {
        $arrData = [
            ':idProveedor' =>    $this->getIdProveedor(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>   $this->getNombre(),
            ':estado' =>   $this->getEstado(),
            ':municipiosId' =>$this->getMunicipiosId(),
        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.proveedor VALUES (
            :idProveedor,:documento,:nombre,
            :estado,:municipiosId
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.proveedor SET 
            nombre = :nombre,documento = :documento,
            estado = :estado, municipiosId = :municipiosId    WHERE idProveedor = :idProveedor";
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
            $arrProveedor = array();
            $tmp = new Proveedor();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Proveedor = new Proveedor($valor);
                    array_push($arrProveedor, $Proveedor);
                    unset($Proveedor);
                }
                return $arrProveedor;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $idProveedor): ?Proveedor
    {
        try {
            if ($idProveedor > 0) {
                $tmpProveedor = new Proveedor();
                $tmpProveedor->Connect();
                $getrow = $tmpProveedor->getRow("SELECT * FROM ornamentacion.proveedor WHERE idProveedor =?", array($idProveedor));
                $tmpProveedor->Disconnect();
                return ($getrow) ? new Proveedor($getrow) : null;
            } else {
                throw new Exception('Id de Proveedor Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }
    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function proveedorRegistrado($documento): bool
    {

        $result = proveedor::search("SELECT * FROM ornamentacion.proveedor where documento = '" . $documento."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAll(): ?array
    {
        return Proveedor::search("SELECT * FROM ornamentacion.proveedor");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'documento' =>   $this->getDocumento(),
            'nombre' =>   $this->getNombre(),
            'estado' =>   $this->getEstado(),
            'municipios' => $this->getMunicipio()->getNombre(),
        ];
    }

}