<?php

class Usuario
{
   private  ? int $IdUsuario;
   private int $documento;
   private String  $nombre;
   private String $telefono;
   private String  $direccion;
   private String  $roll;
   private String  $contraseña;

    /**
     * @param int|null $IdUsuario
     * @param int $documento
     * @param String $nombre
     * @param String $telefono
     * @param String $direccion
     * @param String $roll
     * @param String $contraseña
     */
    public function __Usuario(){

     }
    public function __construct(?int $IdUsuario, int $documento, string $nombre, string $telefono, string $direccion, string $roll, string $contraseña)
    {
        $this->IdUsuario = $IdUsuario;
        $this->documento = $documento;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->roll = $roll;
        $this->contraseña = $contraseña;
    }

    /**
     * @return int|null
     */
    public function getIdUsuario(): ?int
    {
        return $this->IdUsuario;
    }

    /**
     * @param int|null $IdUsuario
     */
    public function setIdUsuario(?int $IdUsuario): void
    {
        $this->IdUsuario = $IdUsuario;
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
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getRoll(): string
    {
        return $this->roll;
    }

    /**
     * @param string $roll
     */
    public function setRoll(string $roll): void
    {
        $this->roll = $roll;
    }

    /**
     * @return string
     */
    public function getContraseña(): string
    {
        return $this->contraseña;
    }

    /**
     * @param string $contraseña
     */
    public function setContraseña(string $contraseña): void
    {
        $this->contraseña = $contraseña;
    }



}