<?php
namespace App\Models;

class DetallePedido extends AbstractDBConnection implements Model
{
private ? INT $idDetallePedido;
private int $valor;
private int $cantidad;
private int $pedidosId;
private int $materiaPrimaId;

    /**
     * @param INT|null $idDetallePedido
     * @param int $valor
     * @param int $cantidad
     * @param int $pedidosId
     * @param int $materiaPrimaId
     */
    public function __construct(?int $idDetallePedido, int $valor, int $cantidad, int $pedidosId, int $materiaPrimaId)
    {
        $this->idDetallePedido = $idDetallePedido;
        $this->valor = $valor;
        $this->cantidad = $cantidad;
        $this->pedidosId = $pedidosId;
        $this->materiaPrimaId = $materiaPrimaId;
    }

    /**
     * @return INT|null
     */
    public function getIdDetallePedido(): ?int
    {
        return $this->idDetallePedido;
    }

    /**
     * @param INT|null $idDetallePedido
     */
    public function setIdDetallePedido(?int $idDetallePedido): void
    {
        $this->idDetallePedido = $idDetallePedido;
    }

    /**
     * @return int
     */
    public function getValor(): int
    {
        return $this->valor;
    }

    /**
     * @param int $valor
     */
    public function setValor(int $valor): void
    {
        $this->valor = $valor;
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
    public function getPedidosId(): int
    {
        return $this->pedidosId;
    }

    /**
     * @param int $pedidosId
     */
    public function setPedidosId(int $pedidosId): void
    {
        $this->pedidosId = $pedidosId;
    }

    /**
     * @return int
     */
    public function getMateriaPrimaId(): int
    {
        return $this->materiaPrimaId;
    }

    /**
     * @param int $materiaPrimaId
     */
    public function setMateriaPrimaId(int $materiaPrimaId): void
    {
        $this->materiaPrimaId = $materiaPrimaId;
    }

    /**
     * @param string $query
     * @return bool|null
     * metodo para guardar un abono
     */
    protected function save(string $query): ?bool

    {
        $arrData = [
            ':IdDetallePedido' =>    $this->getIdDetallePedido(),
            ':valor ' =>   $this->getvalor (),
            ':cantidad' =>  $this->getcantidad(),
            ':pedidosId' =>  $this->getpedidosId(),
            ':materiaPrimaId' =>   $this->getmateriaPrimaId(),
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
        $query = "INSERT INTO weber.categorias VALUES (:IdAbono,:nombre,:descripcion,:estado,:created_at,:updated_at)";
        return $this->save($query);
    }

    /**
     * @return bool|null
     */
    public function update(): ?bool
    {
        $query = "UPDATE weber.categorias SET 
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