<?php
namespace App\Models;
class Fabricacion extends AbstractDBConnection implements Models
{
private ? int $idFabricacion;
private int $cantidad;
private int $MateriaPrima;
private int $Usuario_IdUsuario;

    /**
     * @param int|null $idFabricacion
     * @param int $cantidad
     * @param int $MateriaPrima
     * @param int $Usuario_IdUsuario
     */
    public function __construct(?int $idFabricacion, int $cantidad, int $MateriaPrima, int $Usuario_IdUsuario)
    {
        $this->idFabricacion = $idFabricacion;
        $this->cantidad = $cantidad;
        $this->MateriaPrima = $MateriaPrima;
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
    }

    /**
     * @return int|null
     */
    public function getIdFabricacion(): ?int
    {
        return $this->idFabricacion;
    }

    /**
     * @param int|null $idFabricacion
     */
    public function setIdFabricacion(?int $idFabricacion): void
    {
        $this->idFabricacion = $idFabricacion;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return int
     */
    public function getMateriaPrima(): int
    {
        return $this->MateriaPrima;
    }

    /**
     * @param int $MateriaPrima
     */
    public function setMateriaPrima(int $MateriaPrima): void
    {
        $this->MateriaPrima = $MateriaPrima;
    }

    /**
     * @return int
     */
    public function getUsuarioIdUsuario(): int
    {
        return $this->Usuario_IdUsuario;
    }

    /**
     * @param int $Usuario_IdUsuario
     */
    public function setUsuarioIdUsuario(int $Usuario_IdUsuario): void
    {
        $this->Usuario_IdUsuario = $Usuario_IdUsuario;
    }
    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':idFabricacion' =>    $this->getidFabricacion(),
            ':cantidad' =>   $this->getcantidad(),
            ':MateriaPrima' =>   $this->getMateriaPrima(),
            ':Usuario_IdUsuario' =>   $this->getUsuario_IdUsuario(),


        ];

        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    /**
     * @return bool|null
     */
    function insert(): ?bool
    {
        $query = "INSERT INTO weber.categorias VALUES (:idFabricacion,:cantidad,:MateriaPrima,:Usuario_IdUsuario)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE proyecto.categorias SET 
            nombre = :nombre, descripcion = :descripcion,
            estado = :estado, created_at = :created_at, 
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
            $arrCategorias = array();
            $tmp = new Categorias();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            foreach ($getrows as $valor) {
                $Categoria = new Categorias($valor);
                array_push($arrCategorias, $Categoria);
                unset($Categoria);
            }
            return $arrCategorias;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }


}