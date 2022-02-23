<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\Roll;
use App\Enums\TipoServicioProduct;
use App\Enums\Tipo;
use JetBrains\PhpStorm\Internal\TentativeType;

class Producto extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int  $IdProducto;
    private Tipo $tipo;
    private string $nombre;
    private int $cantidad;
     private double $valor;
     private string $material;
     private string $tamano;
     private string $diseno;
    private  TipoServicioProduct $tipoServicio;
    private Estado $estado;

    public function __construct(array $Producto = [])
    {
        parent::__construct();
        $this->setIdProducto($Producto['idProducto'] ?? null);
        $this->setTipo($Producto['tipo'] ?? Tipo::PRODUCTO);
        $this->setNombre($Producto['nombre'] ?? '');
        $this->setCantidad($Producto['cantidad'] ?? '');
        $this->setValor($Producto['valor'] ?? '');
        $this->setMaterial($Producto['material'] ?? Roll::CLIENTE);
        $this->setTamano($Producto['tamano'] ?? '');
        $this->setDiseno($Producto['diseno'] ?? '');
        $this->setTipoServicio($Producto['tipoServicio'] ?? TipoServicioProduct::FABRICACION );
        $this->setEstado($Producto['estado'] ?? Estado::INACTIVO);

    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return Tipo
     */
    public function getTipo(): string
    {
        return $this->tipo->toString();
    }

    /**
     * @param Tipo $tipo
     */
    public function setTipo(null|string|Tipo $tipo): void
    {
        if(is_string($tipo)){
            $this->tipo = Estado::from($tipo);
        }else{
            $this->tipo = $tipo;
        }
    }

    /**
     * @return String
     */
    public function getTipoServicio(): String
    {
        return $this->tipoServicio->toString();
    }

    /**
     * @param string|TipoServicioProduct|null $tipoServicio
     */
    public function setTipoServicio(null|string|TipoServicioProduct $tipoServicio): void
    {
        if(is_string($tipoServicio)){
            $this->tipoServicio = Estado::from($tipoServicio);
        }else{
            $this->tipoServicio = $tipoServicio;
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



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':IdProducto' =>    $this->getIdProducto(),
            ':tipo' =>   $this->getTipo(),
            ':nombre' =>   $this->getNombre(),
            ':cantidad' =>   $this->getCantidad(),
            ':valor' =>   $this->getValor(),
            ':material' =>   $this->getMaterial(),
            ':tamano' =>   $this->getTamano(),
            ':diseno' =>   $this->getDiseno(),
            ':tipoServicio' =>   $this->getTipoServicio(),
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.producto VALUES (
            :IdProducto,:tipo,:nombre,
            :cantidad,:valor,:material,:tamano,:diseno,:tipoServicio,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.producto SET 
            tipo = :tipo,
            nombre = :nombre, cantidad= :cantidad, valor = :valor,material= : material,tamano = :tamano,diseno = :diseno,tipoServicio = :tipoServicio, estado = :estado WHERE IdProducto = :IdProducto";
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
            $arrProducto = array();
            $tmp = new Producto();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Producto = new Producto($valor);
                    array_push($arrProducto, $Producto);
                    unset($Producto);
                }
                return $arrProducto;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?Producto
    {
        try {
            if ($id > 0) {
                $tmpProducto = new Producto();
                $tmpProducto->Connect();
                $getrow = $tmpProducto->getRow("SELECT * FROM ornamentacion.producto WHERE IdProducto =?", array($id));
                $tmpProducto->Disconnect();
                return ($getrow) ? new Producto($getrow) : null;
            } else {
                throw new Exception('Id de Producto Invalido');
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
    public static function productoRegistrado($nombre): bool
    {
        //$result = producto::search("SELECT * FROM ornamentacion.producto where nombre = " . $nombre);
        $result = producto::search("SELECT * FROM ornamentacion.producto where nombre = '" . $nombre."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Producto::search("SELECT * FROM ornamentacion.producto");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            ':idProducto' =>    $this->getIdProducto(),
            ':tipo' =>   $this->getTipo(),
            ':nombre' =>   $this->getNombre(),
            ':cantidad' =>   $this->getCantidad(),
            ':valor' =>   $this->getValor(),
            ':material' =>   $this->getMaterial(),
            ':tamano' =>   $this->getTamano(),
            ':diseno' =>   $this->getDiseno(),
            ':tipoServicio' =>   $this->getTipoServicio(),
            ':estado' =>   $this->getEstado(),


        ];
    }
}