<?php
namespace App\Enums;

enum TipoMateria: string
{
    case VARILLA = 'Varilla';
    case PERFILES = "Perfiles";
    case MARCO = 'Marco';
    case DIVICIONES = "Divisiones";
    case ANGULO = 'Angulo';
    case PINTURA = "Pintura";
    case ANTICORROSICO = 'Anticorrosivo';
    case LIJA = "Lija";


    public function toString(): string
    {
        return match ($this) {
            self::VARILLA => 'Varilla',
            self::PERFILES => "Perfiles",
            self::MARCO => 'Marco',
            self::DIVICIONES => "Divisiones",
            self::ANGULO => 'Angulo',
            self::PINTURA => "Pintura",
            self::ANTICORROSICO => 'Anticorrosivo',
            self::LIJA => "Lija",

        };
    }
}