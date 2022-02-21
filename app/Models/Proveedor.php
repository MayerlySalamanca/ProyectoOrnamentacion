<?php

namespace App\Models;

use JetBrains\PhpStorm\Internal\TentativeType;

class Proveedor extends AbstractDBConnection implements \App\Interfaces\Model
{
    private ?int  $IdProveedor;
    private int $Docuemnto;
    private string $nombre;
    private string $ciudad;
    private \Estado $estado;

    /**
     * @param int|null $IdProveedor
     * @param int $Docuemnto
     * @param string $nombre
     * @param string $ciudad
     * @param \Estado $estado
     */
    public function __construct(array $proveedor = [])
    {
        parent::__construct();
        $this->setIdProveedor( $proveedor['IdProveedor'] ?? null) ;
        $this->setDocuemnto($proveedor['documento'] ?? 0);
        $this->setNombre($proveedor['nombre'] ?? '') ;
        $this->setCiudad($proveedor['ciudad']) ;
         $this->setEstado($proveedor['estado'] ?? \Estado::INACTIVO);
    }


    protected function save(string $query): ?bool

    {
        // TODO: Implement save() method.
    }

    function insert(): ?bool
    {
        // TODO: Implement insert() method.
    }

    function update(): ?bool
    {
        // TODO: Implement update() method.
    }

    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
    }

    static function search($query): ?array
    {
        // TODO: Implement search() method.
    }

    static function searchForId(int $id): ?object
    {
        // TODO: Implement searchForId() method.
    }

    static function getAll(): ?array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        // TODO: Implement jsonSerialize() method.
    }
}