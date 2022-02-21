<?php


use App\Models\Usuario;
use PHPUnit\Framework\TestCase;
use App\Enums\Estado;

class UsuarioTest extends TestCase
{

    public function testInsert()
    {
        $Usuario = new Usuario(
            ['Id' => null,
                'nombres' => 'Bebidas',
                'orden' => 1,
                'estado' => 'Activo']
        );
        $Usuario->insert();
        $this->assertSame(true, $Usuario->usuarioRegistrado('Bebidas'));
    }
}
