<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\Tipo;
use App\Interfaces\Model;


class Producto extends AbstractDBConnection implements Model
{
     private ?int  $idProducto;
     private Tipo $tipo;
     private string $nombre;
     private int $valor;
     private Estado $estado;
     private int $stock;

    public function __construct(array $Producto = [])
    {
        parent::__construct();
        $this->setIdProducto($Producto['idProducto'] ?? null);
        $this->setTipo($Producto['tipo'] ?? Tipo::PRODUCTO);
        $this->setNombre($Producto['nombre'] ?? '');
        $this->setStock($Producto['stock'] ?? 0);
        $this->setValor($Producto['valor'] ?? 0);
        $this->setEstado($Producto['estado'] ?? Estado::INACTIVO);


    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }


    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }


    /**
     * @return Tipo
     */
    public function getTipo(): string
    {
        return $this->tipo->toString();
    }

    /**
     * @param Tipo $tipo
     */
    public function setTipo(null|string|Tipo $tipo): void
    {
        if(is_string($tipo)){
            $this->tipo = Tipo::from($tipo);
        }else{
            $this->tipo = $tipo;
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
    public function getIdProducto(): ?int
    {
        return $this->idProducto;
    }

    /**
     * @param int|null $idProducto
     */
    public function setIdProducto(?int $idProducto): void
    {
        $this->idProducto = $idProducto;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return ucwords($this->nombre);
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim(mb_strtolower($nombre));
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



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':idProducto' => $this->getIdProducto(),
            ':tipo' => $this->getTipo(),
            ':nombre' => $this->getNombre(),
            ':stock' => $this->getStock(),
            ':valor' => $this->getValor(),
            ':estado' => $this->getEstado(),


        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.producto VALUES (
            :idProducto,:tipo,:nombre,
            :stock,:valor,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.producto SET 
            tipo= :tipo, nombre= :nombre,stock= :stock, valor = :valor,
            estado = :estado WHERE idProducto= :idProducto";
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
            $arrProducto = array();
            $tmp = new Producto();
            $tmp->Connect();
            $getrows = $tmp->getRows($query);
            $tmp->Disconnect();

            if (!empty($getrows)) {
                foreach ($getrows as $valor) {
                    $Producto = new Producto($valor);
                    array_push($arrProducto, $Producto);
                    unset($Producto);
                }
                return $arrProducto;
            }
            return null;
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    static function searchForId(int $Id): ?object
    {
        try {
            if ($Id > 0) {
                $tmProducto = new Producto();
                $tmProducto->Connect();
                $getrow = $tmProducto->getRow("SELECT * FROM ornamentacion.producto WHERE idProducto =?", array($Id));
                $tmProducto->Disconnect();
                return ($getrow) ? new Producto($getrow) : null;
            } else {
                throw new Exception('Id de Producto invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }

    /**
     * @param $nombre
     */
    public static function productoRegistrado($nombre): bool
    {
        $result = Producto::search("SELECT * FROM ornamentacion.producto where nombre = " . "'$nombre'" );
        if (!empty($result) && count($result)>0) {
            return true;
        } else {
            return false;
        }
    }

    static function getAll(): ?array
    {
        return Producto::search("SELECT * FROM ornamentacion.producto");
    }

    public function susaddStock(int $quantity)
    {
        $this->setStock( $this->getStock() - $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }
    public function addStock(int $quantity)
    {
        $this->setStock( $this->getStock() + $quantity);
        $result = $this->update();
        if($result == false){
            GeneralFunctions::console('Stock no actualizado!');
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [

            'tipo' => $this->getTipo(),
            'nombre' => $this->getNombre(),
            'Stock' => $this->getStock(),
            'valor' => $this->getValor(),
            'estado' => $this->getEstado(),
        ];
    }
}