<?php

class Pedidos
{
private ? int $idPedidos;
private string $nombre;
private string $fechaPedido;
private string $fechaEntrega;
private int $proveedor_IdProveedor;

    /**
     * @param int|null $idPedidos
     * @param string $nombre
     * @param string $fechaPedido
     * @param string $fechaEntrega
     * @param int $proveedor_IdProveedor
     */
    public function __construct(?int $idPedidos, string $nombre, string $fechaPedido, string $fechaEntrega, int $proveedor_IdProveedor)
    {
        $this->idPedidos = $idPedidos;
        $this->nombre = $nombre;
        $this->fechaPedido = $fechaPedido;
        $this->fechaEntrega = $fechaEntrega;
        $this->proveedor_IdProveedor = $proveedor_IdProveedor;
    }

    /**
     * @return int|null
     */
    public function getIdPedidos(): ?int
    {
        return $this->idPedidos;
    }

    /**
     * @param int|null $idPedidos
     */
    public function setIdPedidos(?int $idPedidos): void
    {
        $this->idPedidos = $idPedidos;
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
    public function getFechaPedido(): string
    {
        return $this->fechaPedido;
    }

    /**
     * @param string $fechaPedido
     */
    public function setFechaPedido(string $fechaPedido): void
    {
        $this->fechaPedido = $fechaPedido;
    }

    /**
     * @return string
     */
    public function getFechaEntrega(): string
    {
        return $this->fechaEntrega;
    }

    /**
     * @param string $fechaEntrega
     */
    public function setFechaEntrega(string $fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return int
     */
    public function getProveedorIdProveedor(): int
    {
        return $this->proveedor_IdProveedor;
    }

    /**
     * @param int $proveedor_IdProveedor
     */
    public function setProveedorIdProveedor(int $proveedor_IdProveedor): void
    {
        $this->proveedor_IdProveedor = $proveedor_IdProveedor;
    }


}