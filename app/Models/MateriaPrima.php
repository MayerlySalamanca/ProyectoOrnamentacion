<?php

class MateriaPrima
{
private ? int $idMateria;
private string $nombre;
private string $tipo;
private int $stock;

    /**
     * @param int|null $idMateria
     * @param string $nombre
     * @param string $tipo
     * @param int $stock
     */
    public function __construct(?int $idMateria, string $nombre, string $tipo, int $stock)
    {
        $this->idMateria = $idMateria;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->stock = $stock;
    }

    /**
     * @return int|null
     */
    public function getIdMateria(): ?int
    {
        return $this->idMateria;
    }

    /**
     * @param int|null $idMateria
     */
    public function setIdMateria(?int $idMateria): void
    {
        $this->idMateria = $idMateria;
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
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }


}