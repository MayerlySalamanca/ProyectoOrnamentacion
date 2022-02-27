<?php

namespace App\Enums;

enum Roll: string
{
    case ADMINISTRADOR = 'administrador';
    case VENDEDOR= "vendedor";
    case CLIENTE= "cliente";

    public function toString(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'administrador',
            self::VENDEDOR=>'vendedor',
            self::CLIENTE=> "cliente",
        };
    }
}