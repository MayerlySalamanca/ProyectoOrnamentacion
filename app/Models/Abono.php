<?php
namespace App\Models;
class Abono
{
private ? int $IdAbono;
private String $Descripcion;
private String $fecha;
private int $valor;
private int $factura_IdFactura;

    /**
     * @param int|null $IdAbono
     * @param String $Descripcion
     * @param String $fecha
     * @param int $valor
     * @param int $factura_IdFactura
     */
    public function __construct(?int $IdAbono, string $Descripcion, string $fecha, int $valor, int $factura_IdFactura)
    {
        $this->IdAbono = $IdAbono;
        $this->Descripcion = $Descripcion;
        $this->fecha = $fecha;
        $this->valor = $valor;
        $this->factura_IdFactura = $factura_IdFactura;
    }

    /**
     * @return int|null
     */
    public function getIdAbono(): ?int
    {
        return $this->IdAbono;
    }

    /**
     * @param int|null $IdAbono
     */
    public function setIdAbono(?int $IdAbono): void
    {
        $this->IdAbono = $IdAbono;
    }

    /**
     * @return String
     */
    public function getDescripcion(): string
    {
        return $this->Descripcion;
    }

    /**
     * @param String $Descripcion
     */
    public function setDescripcion(string $Descripcion): void
    {
        $this->Descripcion = $Descripcion;
    }

    /**
     * @return String
     */
    public function getFecha(): string
    {
        return $this->fecha;
    }

    /**
     * @param String $fecha
     */
    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return int
     */
    public function getValor(): int
    {
        return $this->valor;
    }

    /**
     * @param int $valor
     */
    public function setValor(int $valor): void
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getFacturaIdFactura(): int
    {
        return $this->factura_IdFactura;
    }

    /**
     * @param int $factura_IdFactura
     */
    public function setFacturaIdFactura(int $factura_IdFactura): void
    {
        $this->factura_IdFactura = $factura_IdFactura;
    }


}