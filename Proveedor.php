<?php

namespace App\Models;
use App\Enums\Estado;
use JetBrains\PhpStorm\Internal\TentativeType;

class Proveedor extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int  $IdProveedor;
    private int $documento;
    private string $nombre;
    private string $ciudad;
    private Estado $estado;
//Realacion
    private ?array $pedidosProveedor;

    /**
     * @param array $proveedor
     */
    public function __construct(array $proveedor = [])
    {
        parent::__construct();
        $this->setIdProveedor( $proveedor['IdProveedor'] ?? null) ;
        $this->setDocumento($proveedor['documento'] ?? 0);
        $this->setNombre($proveedor['nombre'] ?? '') ;
        $this->setCiudad($proveedor['ciudad']?? '') ;
        $this->setEstado($proveedor['estado'] ?? Estado::INACTIVO);
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
     * @return string
     */
    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    /**
     * @param string $ciudad
     */
    public function setCiudad(string $ciudad): void
    {
        $this->ciudad = $ciudad;
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



    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdProveedor' =>    $this->getIdProveedor(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>   $this->getNombre(),
            ':ciudad' =>   $this->getCiudad(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.proveedor VALUES (
            :IdProveedor,:documento,:nombre,
            :ciudad,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.proveedor SET 
            nombre = :nombre,
            documento = :documento, ciudad= :ciudad, estado = :estado WHERE IdProveedor = :IdProveedor";
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
                    $Proveedor = new Usuario($valor);
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

    static function searchForId(int $id): ?Proveedor
    {
        try {
            if ($id > 0) {
                $tmpProveedor = new Proveedor();
                $tmpProveedor->Connect();
                $getrow = $tmpProveedor->getRow("SELECT * FROM ornamentacion.usuario WHERE idProveedor =?", array($id));
                $tmpProveedor->Disconnect();
                return ($getrow) ? new Usuario($getrow) : null;
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
        //$result = proveedor::search("SELECT * FROM ornamentacion.proveedor where documento = " . $documento);
        $result = proveedor::search("SELECT * FROM ornamentacion.proveedor where documento = '" . $documento."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Proveedor::search("SELECT * FROM ornamentacion.proveedor");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            ':IdProveedor' =>    $this->getIdProveedor(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>   $this->getNombre(),
            ':ciudad' =>   $this->getCiudad(),
            ':estado' =>   $this->getEstado(),

        ];
    }
}