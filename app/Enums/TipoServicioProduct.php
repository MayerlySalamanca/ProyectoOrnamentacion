<?php
namespace App\Enums;
enum TipoServicioProduct: string
{
    case FABRICACION = 'Fabricacion';
    case INSTALACION_= "Instalacion";


    public function toString(): string
    {
        return match ($this) {
            self::FABRICACION => 'Fabricacion',
            self::INSTALACION_=> "Instalacion",

        };
    }
}