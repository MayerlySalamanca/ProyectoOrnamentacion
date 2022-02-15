<?php
namespace App\Models;
class Proveedor
{
private ? int $IdProveedor;
private int $documento;
private string $nombre;
private string $ciudad;

    /**
     * @param int|null $IdProveedor
     * @param int $documento
     * @param string $nombre
     * @param string $ciudad
     */
    public function __construct(?int $IdProveedor, int $documento, string $nombre, string $ciudad)
    {
        $this->IdProveedor = $IdProveedor;
        $this->documento = $documento;
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
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


}