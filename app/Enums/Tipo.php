<?php
namespace App\Enums;

enum Tipo: string
{
    case PRODUCTO = 'Producto';
    case SERVICIO= "Servicio";


    public function toString(): string
    {
        return match ($this) {
            self::PRODUCTO => 'Producto',
            self::SERVICIO=> "Servicio",

        };
    }
}