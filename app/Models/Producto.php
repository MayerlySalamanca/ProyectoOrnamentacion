<?php

class Producto
{
Private ? int $IdProducto;
private String $tipo;
private String $nombre;
private int $cantidad;
private double $valor;
private string $material;
private string $tamano;
private string $diseno;
private string $tipoServicio;

    /**
     * @param int|null $IdProducto
     * @param String $tipo
     * @param String $nombre
     * @param int $cantidad
     * @param float $valor
     * @param string $material
     * @param string $tamano
     * @param string $diseno
     * @param string $tipoServicio
     */
    public function __construct(?int $IdProducto, string $tipo, string $nombre, int $cantidad, float $valor, string $material, string $tamano, string $diseno, string $tipoServicio)
    {
        $this->IdProducto = $IdProducto;
        $this->tipo = $tipo;
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->valor = $valor;
        $this->material = $material;
        $this->tamano = $tamano;
        $this->diseno = $diseno;
        $this->tipoServicio = $tipoServicio;
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
     * @return String
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param String $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
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

    /**
     * @return string
     */
    public function getTipoServicio(): string
    {
        return $this->tipoServicio;
    }

    /**
     * @param string $tipoServicio
     */
    public function setTipoServicio(string $tipoServicio): void
    {
        $this->tipoServicio = $tipoServicio;
    }



}