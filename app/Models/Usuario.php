<?php

namespace App\Models;
use App\Enums\Estado;

use App\Enums\Roll;
use App\Interfaces\Model;
use Exception;


require_once ("AbstractDBConnection.php");
require_once (__DIR__."\..\Interfaces\Model.php");
require_once (__DIR__.'/../../vendor/autoload.php');

use JetBrains\PhpStorm\Internal\TentativeType;

class Usuario extends AbstractDBConnection implements Model
{


    private ?int $idUsuario;
    private int $documento;
    private string $nombres;
    private String $telefono;
    private string $direccion;
    private Roll $roll;
    private ?string $contrasena;
    private Estado $estado;

    //Realaciones
    private ?array $FabricacionUsuario;
    private ?array $FacturaUsurio;

    /* Seguridad de Contraseña */
    const HASH = PASSWORD_DEFAULT;
    const COST = 10;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $usuario
     */
    public function __construct(array $usuario = [])
    {
        parent::__construct();
        $this->setIdUsuario($usuario['idUsuario'] ?? null);
        $this->setDocumento($usuario['documento'] ?? 0);
        $this->setNombres($usuario['nombre'] ?? '');
        $this->setTelefono($usuario['telefono'] ?? '');
        $this->setDireccion($usuario['direccion'] ?? '');
        $this->setRoll($usuario['roll'] ?? Roll::PROVEEDOR);
        $this->setContrasena($usuario['contrasena'] ?? '');
        $this->setEstado($usuario['estado'] ?? Estado::INACTIVO);

    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return int|null
     */
    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    /**
     * @param int|null $idUsuario
     */
    public function setIdUsuario(?int $idUsuario): void
    {
        $this->idUsuario = $idUsuario;
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
    public function getNombres(): string
    {
        return $this->nombres;
    }

    /**
     * @param string $nombres
     */
    public function setNombres(string $nombres): void
    {
        $this->nombres = $nombres;
    }

    /**
     * @return String
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param String $telefono
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
     * @return Roll
     */
    public function getRoll(): string
    {
        return $this->roll->toString();
    }

    /**
     * @param string|Roll|null $roll
     */
    public function setRoll(null|string|Roll $roll): void
    {
        if(is_string($roll)){
            $this->roll = Roll::from($roll);

        }else{
            $this->roll = $roll;
        }
    }

    /**
     * @return string|null
     */
    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    /**
     * @param string|null $contrasena
     */
    public function setContrasena(?string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }

    /**
     * @return Estado
     */
    public function getEstado(): string
    {
        return $this->estado->toString();
    }

    /**
     * @param EstadoCategorias|null $estado
     */
    public function setEstado(null|string|Estado $estado): void
    {
        if(is_string($estado)){
            $this->estado = Estado::from($estado);
        }else{
            $this->estado = $estado;
        }
    }


    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $hashPassword = password_hash($this->contrasena, self::HASH, ['cost' => self::COST]);

        $arrData = [
            ':IdUsuario' =>    $this->getIdUsuario(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>   $this->getNombres(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':roll' =>   $this->getRoll(),
            ':contrasena' =>   $hashPassword,
            ':estado' =>   $this->getEstado(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    public function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.usuario VALUES (
            :IdUsuario,:documento,:nombre,
            :telefono,:direccion,:roll,
            :contrasena,:estado
        )";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE ornamentacion.usuario SET 
            nombres = :nombres,
            documento = :documento, telefono = :telefono, direccion = :direccion, 
            contrasena = :contrasena, rol = :rol, estado = :estado WHERE IdUsuario = :IdUsuario";
        return $this->save($query);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deleted(): bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    /**
     * @param $query
     * @return Usuario|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrUsuario = array();
            $tmp = new Usuario();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuario($valor);
                    array_push($arrUsuario, $Usuario);
                    unset($Usuario);
                }
                return $arrUsuario;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param int $id
     * @return Usuario|null
     */
    public static function searchForId(int $id): ?Usuario
    {
        try {
            if ($id > 0) {
                $tmpUsuario = new Usuario();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM ornamentacion.usuario WHERE idUsuario =?", array($id));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Usuario($getrow) : null;
            } else {
                throw new Exception('Id de usuario Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getAll(): array
    {
        return Usuario::search("SELECT * FROM ornamentacion.usuario");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function usuarioRegistrado($documento): bool
    {
        $result = usuario::search("SELECT * FROM ornamentacion.usuario where documento = " . $documento);
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function nombresCompletos(): string
    {
        return $this->nombres . " " ;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Nombres: $this->nombres, 
                Documento: $this->documento, 
                Telefono: $this->telefono, 
                Direccion: $this->direccion, 
                ";
    }

    public function login($user, $password): Usuario|String|null
    {

        try {
            $resultUsuario = Usuario::search("SELECT * FROM usuario WHERE user = '$user'");
            /* @var $resultUsuario Usuario[] */
            if (!empty($resultUsuario) && count($resultUsuario) >= 1) {
                if (password_verify($password, $resultUsuario[0]->getPassword())) {
                    if ($resultUsuario[0]->getEstado() == 'Activo') {
                        return $resultUsuario[0];
                    } else {
                        return "Usuario Inactivo";
                    }
                } else {
                    return "Contraseña Incorrecta";
                }
            } else {
                return "Usuario Incorrecto";
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
            return "Error en Servidor";
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize(): array
    {
        return [
            ':IdUsuario' =>    $this->getIdUsuario(),
            ':documento' =>   $this->getDocumento(),
            ':nombre' =>   $this->getNombres(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':roll' =>   $this->getRoll(),
            ':contrasena'=> $this-> getContrasena() ,
            ':estado' =>   $this->getEstado(),

        ];
    }


}