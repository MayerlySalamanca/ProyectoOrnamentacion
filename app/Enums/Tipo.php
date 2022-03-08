<?php
namespace App\Enums;

enum Tipo: string
{
    case FABRICACION = 'Fabricacion';
    case INSTALACION= "Instalacion";
    case PRODUCTO = 'Producto';


    public function toString(): string
    {
        return match ($this) {
            self::FABRICACION => 'Fabricacion',
            self::INSTALACION=> "Instalacion",
            self::PRODUCTO=> "Producto",

        };
    }
}