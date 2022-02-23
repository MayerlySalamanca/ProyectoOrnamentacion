<?php

namespace App\Models;

use App\Enums\Estado;
use JetBrains\PhpStorm\Internal\TentativeType;

class DetallePedido extends AbstractDBConnection implements \App\Interfaces\Model
{

    private ?int  $idDetallePedido;
    private int $numeroDetallePedido;
    private int $valor;
    private int $cantidad;
    private Estado $estado;
    private int $pedidosId;
    private int $materiaPrimaId;

    /**
     * Usuarios constructor. Recibe un array asociativo
     * @param array $detallepedido
     */
    public function __construct(array $detallepedido = [])
    {
        parent::__construct();
        $this->setIdDetallePedido($detallepedido['idDetallePedido'] ?? null);
        $this->setNumeroDetallePedido($detallepedido['numeroDetallePedido'] ?? 0);
        $this->setValor($detallepedido['valor'] ?? 0);
        $this->setCantidad($detallepedido['cantidad'] ?? 0);
        $this->setEstado($detallepedido['estado'] ?? Estado::INACTIVO);
        $this->setPedidosId($detallepedido['pedidosId'] ?? 0);
        $this->setMateriaPrimaId($detallepedido['materiaPrimaId'] ?? 0);

    }


    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
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
     * @return int|null
     */
    public function getIdDetallePedido(): ?int
    {
        return $this->idDetallePedido;
    }

    /**
     * @param int|null $idDetallePedido
     */
    public function setIdDetallePedido(?int $idDetallePedido): void
    {
        $this->idDetallePedido = $idDetallePedido;
    }

    /**
     * @return int
     */
    public function getNumeroDetallePedido(): int
    {
        return $this->numeroDetallePedido;
    }

    /**
     * @param int $numeroDetallePedido
     */
    public function setNumeroDetallePedido(int $numeroDetallePedido): void
    {
        $this->numeroDetallePedido = $numeroDetallePedido;
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

    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idDetallePedido' =>    $this->getIdDetallePedido(),
            ':numeroDetallePedido' =>   $this->getNumeroDetallePedido(),
            ':valor' =>   $this->getValor(),
            ':cantidad' =>   $this->getCantidad(),
            ':estado' =>   $this->getEstado(),
            ':pedidosId' =>   $this->getPedidosId(),
            ':materiaPrimaId' =>   $this->getMateriaPrimaId(),

        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.detallepedido  VALUES (
            :idDetallePedido,:numeroDetallePedido,:valor,:cantidad,
            :estado,:pedidosId,:materiaPrimaId
        
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.detallepedido SET 
            numeroDetallePedido = :numeroDetallePedido,valor = :valor,
            cantidad = :cantidad, estado = :estado, pedidosId = :pedidosId, 
            materiaPrimaId = :materiaPrimaId WHERE idDetallePedido = :idDetallePedido";
        return $this->save($query);
    }

    function deleted(): ?bool
    {
        $this->setEstado("Inactivo"); //Cambia el estado del Usuario
        return $this->update();                    //Guarda los cambios..
    }

    static function search($query): ?array
    {
        try {
            $arrDetallePedido = array();
            $tmp = new DetallePedido();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $detallePedido = new DetallePedido($valor);
                    array_push($arrDetallePedido, $detallePedido);
                    unset($detallePedido);
                }
                return $arrDetallePedido;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $id): ?object
    {
        try {
            if ($id > 0) {
                $tmpDetalle = new DetallePedido();
                $tmpDetalle->Connect();
                $getrow = $tmpDetalle->getRow("SELECT * FROM ornamentacion.detallepedido WHERE idDetallePedido =?", array($id));
                $tmpDetalle->Disconnect();
                return ($getrow) ? new DetallePedido($getrow) : null;
            } else {
                throw new Exception('Id de Detalle Pedido Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $numeroPedido
     * @return bool
     */
    public static function detalleRegistrado($numeroDetallePedido): bool
    {
        $result = detallepedido::search("SELECT * FROM ornamentacion.detallepedido where numeroDetallePedido = '" . $numeroDetallePedido."' ");
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return detallepedido::search("SELECT * FROM ornamentacion.detallepedido");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [



        ];
    }
}