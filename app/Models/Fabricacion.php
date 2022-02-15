<?php
namespace App\Models;
class Fabricacion
{
private ? int $idFabricacion;
private int $cantidad;
private int $MateriaPrima;
private int $Usuario_IdUsuario;

    /**
     * @param int|null $idFabricacion
     * @param int $cantidad
     * @param int $MateriaPrima
     * @param int $Usuario_IdUsuario
     */
    public function __construct(?int $idFabricacion, int $cantidad, int $MateriaPrima, int $Usuario_IdUsuario)
    {
        $this->idFabricacion = $idFabricacion;
        $this->cantidad = $cantidad;
        $this->MateriaPrima = $MateriaPrima;
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
    }

    /**
     * @return int|null
     */
    public function getIdFabricacion(): ?int
    {
        return $this->idFabricacion;
    }

    /**
     * @param int|null $idFabricacion
     */
    public function setIdFabricacion(?int $idFabricacion): void
    {
        $this->idFabricacion = $idFabricacion;
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
    public function getMateriaPrima(): int
    {
        return $this->MateriaPrima;
    }

    /**
     * @param int $MateriaPrima
     */
    public function setMateriaPrima(int $MateriaPrima): void
    {
        $this->MateriaPrima = $MateriaPrima;
    }

    /**
     * @return int
     */
    public function getUsuarioIdUsuario(): int
    {
        return $this->Usuario_IdUsuario;
    }

    /**
     * @param int $Usuario_IdUsuario
     */
    public function setUsuarioIdUsuario(int $Usuario_IdUsuario): void
    {
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
    }

}