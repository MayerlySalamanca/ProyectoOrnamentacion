<?php

namespace App\Enums;

enum Roll: string
{
    case ADMINISTRADOR = 'administrador';
    case PROVEEDOR= "proveedor";
    case CLIENTE= "cliente";

    public function toString(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'administrador',
            self::PROVEEDOR => 'proveedor',
            self::CLIENTE=>'cliente',
        };
    }
}