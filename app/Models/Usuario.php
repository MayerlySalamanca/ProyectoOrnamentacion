<?php
namespace App\Models;
class Usuario
{
   private  ? int $IdUsuario;
   private int $documento;
   private String  $nombre;
   private String $telefono;
   private String  $direccion;
   private String  $roll;
   private String  $contraseña;

    /**
     * @param int|null $IdUsuario
     * @param int $documento
     * @param String $nombre
     * @param String $telefono
     * @param String $direccion
     * @param String $roll
     * @param String $contraseña
     */
    public function __Usuario(){

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
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdUsuario' =>    $this->getIdUsuario(),
            ':documento' =>   $this->getdocumento(),
            ':nombre' =>   $this->getnombre(),
            ':telefono' =>   $this->gettelefono(),
            ':direccion' =>   $this->getdireccion(),
            ':roll' =>   $this->getroll(),
            ':contraseña' =>   $this->getcontraseña(),
        ];


        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }



    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    public function insert(): ?bool
    {
        $query = "INSERT INTO proyecto.usuarios VALUES (
            :IdUsuario,:Documento,:nombre,:tipo_documento,:documento,
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
            nombres = :nombres, documento = :apellidos, tipo_documento = :tipo_documento, 
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
     * @return Categorias|array
     * @throws Exception
     */
    public static function search($query) : ?array
    {
        try {
            $arrUsuarios = array();
            $tmp = new Usuario();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Usuario= new Categorias($valor);
                array_push($arrUsuarios, $Usuario);
                unset($Usuario);
            }
            return $arrUsuarios;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }


}