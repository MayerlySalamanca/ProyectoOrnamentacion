<?php
enum Rol: string
{
    case ADMINISTRADOR = 'Administrador';
    case PROVEEDOR= 'Proveedor';
    case CLIENTE= 'Cliente';

    public function toString(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::PROVEEDOR => 'Proveedor',
            self::CLIENTE=>'Cliente',
        };
    }
}