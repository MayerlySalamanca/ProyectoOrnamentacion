<?php
namespace App\Models;
class Factura
{
    private ? int $IdFactura;
    private String $tipo;
    private int $Cantidad;
    private String $fechaInicio;
    private ? String $fechaFin;
    private ? String $fechaEstimada;
    private String $estado;
    private String $fechaVenta;
    private int $usuarioComprador;
    private int $usuarioVendedor;

    /**
     * @param int|null $IdFactura
     * @param String $tipo
     * @param int $Cantidad
     * @param String $fechaInicio
     * @param String|null $fechaFin
     * @param String|null $fechaEstimada
     * @param String $estado
     * @param String $fechaVenta
     * @param int $usuarioComprador
     * @param int $usuarioVendedor
     */
    public function __construct(?int $IdFactura, string $tipo, int $Cantidad, string $fechaInicio, ?string $fechaFin, ?string $fechaEstimada, string $estado, string $fechaVenta, int $usuarioComprador, int $usuarioVendedor)
    {
        $this->IdFactura = $IdFactura;
        $this->tipo = $tipo;
        $this->Cantidad = $Cantidad;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->fechaEstimada = $fechaEstimada;
        $this->estado = $estado;
        $this->fechaVenta = $fechaVenta;
        $this->usuarioComprador = $usuarioComprador;
        $this->usuarioVendedor = $usuarioVendedor;
    }


    /**
     * @return int|null
     */
    public function getIdFactura(): ?int
    {
        return $this->IdFactura;
    }

    /**
     * @param int|null $IdFactura
     */
    public function setIdFactura(?int $IdFactura): void
    {
        $this->IdFactura = $IdFactura;
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
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->Cantidad;
    }

    /**
     * @param int $Cantidad
     */
    public function setCantidad(int $Cantidad): void
    {
        $this->Cantidad = $Cantidad;
    }

    /**
     * @return String
     */
    public function getFechaInicio(): string
    {
        return $this->fechaInicio;
    }

    /**
     * @param String $fechaInicio
     */
    public function setFechaInicio(string $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return String|null
     */
    public function getFechaFin(): ?string
    {
        return $this->fechaFin;
    }

    /**
     * @param String|null $fechaFin
     */
    public function setFechaFin(?string $fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * @return String|null
     */
    public function getFechaEstimada(): ?string
    {
        return $this->fechaEstimada;
    }

    /**
     * @param String|null $fechaEstimada
     */
    public function setFechaEstimada(?string $fechaEstimada): void
    {
        $this->fechaEstimada = $fechaEstimada;
    }

    /**
     * @return String
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param String $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return String
     */
    public function getFechaVenta(): string
    {
        return $this->fechaVenta;
    }

    /**
     * @param String $fechaVenta
     */
    public function setFechaVenta(string $fechaVenta): void
    {
        $this->fechaVenta = $fechaVenta;
    }

    /**
     * @return int
     */
    public function getUsuarioComprador(): int
    {
        return $this->usuarioComprador;
    }

    /**
     * @param int $usuarioComprador
     */
    public function setUsuarioComprador(int $usuarioComprador): void
    {
        $this->usuarioComprador = $usuarioComprador;
    }

    /**
     * @return int
     */
    public function getUsuarioVendedor(): int
    {
        return $this->usuarioVendedor;
    }

    /**
     * @param int $usuarioVendedor
     */
    public function setUsuarioVendedor(int $usuarioVendedor): void
    {
        $this->usuarioVendedor = $usuarioVendedor;
    }


}