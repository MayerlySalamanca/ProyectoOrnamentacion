<?php
namespace App\Models;
use App\Interfaces\Models;

class Usuario extends AbstractDBConnection implements Models
{
    private ?int $IdUsuario;
    private int $documento;
    private string $nombre;
    private string $telefono;
    private string $direccion;
    private string $roll;
    private string $contraseña;

    /**
     * @param int|null $IdUsuario
     * @param int $documento
     * @param String $nombre
     * @param String $telefono
     * @param String $direccion
     * @param String $roll
     * @param String $contraseña
     */
    public function __Usuario()
    {

    }

    public function __construct(?int $IdUsuario, int $documento, string $nombre, string $telefono, string $direccion, string $roll, string $contraseña)
    {
        $this->IdUsuario = $IdUsuario;
        $this->documento = $documento;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->roll = $roll;
        $this->contraseña = $contraseña;
    }

    /**
     * @return int|null
     */
    public function getIdUsuario(): ?int
    {
        return $this->IdUsuario;
    }

    /**
     * @param int|null $IdUsuario
     */
    public function setIdUsuario(?int $IdUsuario): void
    {
        $this->IdUsuario = $IdUsuario;
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
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
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
     * @return string
     */
    public function getRoll(): string
    {
        return $this->roll;
    }

    /**
     * @param string $roll
     */
    public function setRoll(string $roll): void
    {
        $this->roll = $roll;
    }

    /**
     * @return string
     */
    public function getContraseña(): string
    {
        return $this->contraseña;
    }

    /**
     * @param string $contraseña
     */
    public function setContraseña(string $contraseña): void
    {
        $this->contraseña = $contraseña;
    }


    /**
     * @param string $query
     * @return bool|null
     */
    protected function save(string $query): ?bool
    {
        $hashPassword = password_hash($this->password, self::HASH, ['cost' => self::COST]);

        $arrData = [
            ':id' =>    $this->getId(),
            ':nombres' =>   $this->getNombres(),
            ':apellidos' =>   $this->getApellidos(),
            ':tipo_documento' =>  $this->getTipoDocumento(),
            ':documento' =>   $this->getDocumento(),
            ':telefono' =>   $this->getTelefono(),
            ':direccion' =>   $this->getDireccion(),
            ':municipio_id' =>   $this->getMunicipioId(),
            ':fecha_nacimiento' =>  $this->getFechaNacimiento()->toDateString(), //YYYY-MM-DD
            ':user' =>  $this->getUser(),
            ':password' =>   $hashPassword,
            ':foto' =>   $this->getFoto(),
            ':rol' =>   $this->getRol(),
            ':estado' =>   $this->getEstado(),
            ':created_at' =>  $this->getCreatedAt()->toDateTimeString(), //YYYY-MM-DD HH:MM:SS
            ':updated_at' =>  $this->getUpdatedAt()->toDateTimeString()
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
        $query = "INSERT INTO weber.usuarios VALUES (
            :id,:nombres,:apellidos,:tipo_documento,:documento,
            :telefono,:direccion,:municipio_id,:fecha_nacimiento,:user,
            :password,:foto,:rol,:estado,:created_at,:updated_at
        )";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE weber.usuarios SET 
            nombres = :nombres, apellidos = :apellidos, tipo_documento = :tipo_documento, 
            documento = :documento, telefono = :telefono, direccion = :direccion, 
            municipio_id = :municipio_id, fecha_nacimiento = :fecha_nacimiento, user = :user,  
            password = :password, foto = :foto, rol = :rol, estado = :estado, created_at = :created_at, 
            updated_at = :updated_at WHERE id = :id";
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
     * @return Usuarios|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrUsuarios = array();
            $tmp = new Usuarios();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Usuario = new Usuarios($valor);
                    array_push($arrUsuarios, $Usuario);
                    unset($Usuario);
                }
                return $arrUsuarios;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param int $id
     * @return Usuarios|null
     */
    public static function searchForId(int $id): ?Usuarios
    {
        try {
            if ($id > 0) {
                $tmpUsuario = new Usuarios();
                $tmpUsuario->Connect();
                $getrow = $tmpUsuario->getRow("SELECT * FROM weber.usuarios WHERE id =?", array($id));
                $tmpUsuario->Disconnect();
                return ($getrow) ? new Usuarios($getrow) : null;
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
        return Usuarios::search("SELECT * FROM weber.usuarios");
    }

    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function usuarioRegistrado($documento): bool
    {
        $result = Usuarios::search("SELECT * FROM weber.usuarios where documento = " . $documento);
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
        return $this->nombres . " " . $this->apellidos;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Nombres: $this->nombres, 
                Apellidos: $this->nombres, 
                Tipo Documento: $this->tipo_documento, 
                Documento: $this->documento, 
                Telefono: $this->telefono, 
                Direccion: $this->direccion, 
                Direccion: $this->fecha_nacimiento->toDateTimeString()";
    }

    public function login($user, $password): Usuarios|String|null
    {

        try {
            $resultUsuarios = Usuarios::search("SELECT * FROM usuarios WHERE user = '$user'");
            /* @var $resultUsuarios Usuarios[] */
            if (!empty($resultUsuarios) && count($resultUsuarios) >= 1) {
                if (password_verify($password, $resultUsuarios[0]->getPassword())) {
                    if ($resultUsuarios[0]->getEstado() == 'Activo') {
                        return $resultUsuarios[0];
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
            'id' => $this->getId(),
            'nombres' => $this->getNombres(),
            'apellidos' => $this->getApellidos(),
            'tipo_documento' => $this->getTipoDocumento(),
            'documento' => $this->getDocumento(),
            'telefono' => $this->getTelefono(),
            'direccion' => $this->getDireccion(),
            'municipio_id' => $this->getMunicipioId(),
            'fecha_nacimiento' => $this->getFechaNacimiento()->toDateString(),
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'foto' => $this->getFoto(),
            'rol' => $this->getRol(),
            'estado' => $this->getEstado(),
            'created_at' => $this->getCreatedAt()->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()->toDateTimeString(),
        ];
    }
}