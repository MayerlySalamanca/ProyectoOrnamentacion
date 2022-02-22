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
        // TODO: Implement save() method.
    }

    function insert(): ?bool
    {
        // TODO: Implement insert() method.
    }

    function update(): ?bool
    {
        // TODO: Implement update() method.
    }

    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
    }

    static function search($query): ?array
    {
        // TODO: Implement search() method.
    }

    static function searchForId(int $id): ?object
    {
        // TODO: Implement searchForId() method.
    }

    static function getAll(): ?array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        // TODO: Implement jsonSerialize() method.
    }
}