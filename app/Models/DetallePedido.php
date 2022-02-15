<?php
namespace App\Models;

class DetallePedido
{
private ? INT $idDetallePedido;
private int $valor;
private int $cantidad;
private int $pedidosId;
private int $materiaPrimaId;

    /**
     * @param INT|null $idDetallePedido
     * @param int $valor
     * @param int $cantidad
     * @param int $pedidosId
     * @param int $materiaPrimaId
     */
    public function __construct(?int $idDetallePedido, int $valor, int $cantidad, int $pedidosId, int $materiaPrimaId)
    {
        $this->idDetallePedido = $idDetallePedido;
        $this->valor = $valor;
        $this->cantidad = $cantidad;
        $this->pedidosId = $pedidosId;
        $this->materiaPrimaId = $materiaPrimaId;
    }

    /**
     * @return INT|null
     */
    public function getIdDetallePedido(): ?int
    {
        return $this->idDetallePedido;
    }

    /**
     * @param INT|null $idDetallePedido
     */
    public function setIdDetallePedido(?int $idDetallePedido): void
    {
        $this->idDetallePedido = $idDetallePedido;
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
     * @return int
     */
    public function getPedidosId(): int
    {
        return $this->pedidosId;
    }

    /**
     * @param int $pedidosId
     */
    public function setPedidosId(int $pedidosId): void
    {
        $this->pedidosId = $pedidosId;
    }

    /**
     * @return int
     */
    public function getMateriaPrimaId(): int
    {
        return $this->materiaPrimaId;
    }

    /**
     * @param int $materiaPrimaId
     */
    public function setMateriaPrimaId(int $materiaPrimaId): void
    {
        $this->materiaPrimaId = $materiaPrimaId;
    }

}