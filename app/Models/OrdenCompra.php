<?php

class OrdenCompra
{
    private ? int $idOrdenCompra;
    private int $fabricacionId;
    private int $factura_IdFactura;
    private int $factura_IdProductos;

    /**
     * @param int|null $idOrdenCompra
     * @param int $fabricacionId
     * @param int $factura_IdFactura
     * @param int $factura_IdProductos
     */
    public function __construct(?int $idOrdenCompra, int $fabricacionId, int $factura_IdFactura, int $factura_IdProductos)
    {
        $this->idOrdenCompra = $idOrdenCompra;
        $this->fabricacionId = $fabricacionId;
        $this->factura_IdFactura = $factura_IdFactura;
        $this->factura_IdProductos = $factura_IdProductos;
    }

    /**
     * @return int|null
     */
    public function getIdOrdenCompra(): ?int
    {
        return $this->idOrdenCompra;
    }

    /**
     * @param int|null $idOrdenCompra
     */
    public function setIdOrdenCompra(?int $idOrdenCompra): void
    {
        $this->idOrdenCompra = $idOrdenCompra;
    }

    /**
     * @return int
     */
    public function getFabricacionId(): int
    {
        return $this->fabricacionId;
    }

    /**
     * @param int $fabricacionId
     */
    public function setFabricacionId(int $fabricacionId): void
    {
        $this->fabricacionId = $fabricacionId;
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

    /**
     * @return int
     */
    public function getFacturaIdProductos(): int
    {
        return $this->factura_IdProductos;
    }

    /**
     * @param int $factura_IdProductos
     */
    public function setFacturaIdProductos(int $factura_IdProductos): void
    {
        $this->factura_IdProductos = $factura_IdProductos;
    }



}