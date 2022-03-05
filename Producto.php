<?php

namespace App\Models;

use App\Enums\Estado;
use App\Enums\Tipo;
use JetBrains\PhpStorm\Internal\TentativeType;

class Producto extends AbstractDBConnection implements \App\Interfaces\Model
{
     private ?int  $IdProducto;
     private Tipo $tipo;
     private string $nombre;
     private int $cantidad;
     private int $valor;
     private string $material;
     private string $tamano;
     private string $diseno;
     private string $descripcion;
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
        $this->setMaterial($Producto['material'] ?? '');
        $this->setTamano($Producto['tamano'] ?? '');
        $this->setDiseno($Producto['diseno'] ?? '');
        $this->setDescripcion($Producto['descripcion'] ?? '');
        $this->setEstado($Producto['estado'] ?? Estado::INACTIVO);


    }

    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->Disconnect();
        }
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return int
     */
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
        return $this->IdProducto;
    }

    /**
     * @param int|null $IdProducto
     */
    public function setIdProducto(?int $IdProducto): void
    {
        $this->IdProducto = $IdProducto;
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
     * @return string
     */
    public function getMaterial(): string
    {
        return $this->material;
    }

    /**
     * @param string $material
     */
    public function setMaterial(string $material): void
    {
        $this->material = $material;
    }

    /**
     * @return string
     */
    public function getTamano(): string
    {
        return $this->tamano;
    }

    /**
     * @param string $tamano
     */
    public function setTamano(string $tamano): void
    {
        $this->tamano = $tamano;
    }

    /**
     * @return string
     */
    public function getDiseno(): string
    {
        return $this->diseno;
    }

    /**
     * @param string $diseno
     */
    public function setDiseno(string $diseno): void
    {
        $this->diseno = $diseno;
    }



    protected function save(string $query): ?bool
    {
        $arrData = [
            ':IdProducto' =>    $this->getIdProducto(),
            ':tipo' =>   $this->getTipo(),
            ':nombre' =>   $this->getNombre(),
            ':stock' =>   $this->getStock(),
            ':valor' =>   $this->getValor(),
            ':material' =>   $this->getMaterial(),
            ':tamano' =>   $this->getTamano(),
            ':diseno' =>   $this->getDiseno(),
            ':descripcion' =>   $this->getDescripcion(),
            ':estado' =>   $this->getEstado(),


        ];
        $this->Connect();
        $result = $this->insertRow($query, $arrData);
        $this->Disconnect();
        return $result;
    }

    function insert(): ?bool
    {
        $query = "INSERT INTO ornamentacion.producto VALUES (
            :IdProducto,:tipo,:nombre,
            :stock,:valor,:material,:tamano,:diseno,:descripcion,:estado
        )";
        return $this->save($query);
    }

    function update(): ?bool
    {
        $query = "UPDATE ornamentacion.producto SET 
            tipo = :tipo,
            nombre = :nombre,stock= :stock, valor = :valor,
            material= : material,tamano = :tamano,diseno = :diseno,
           ,descripcion = :descripcion,
            estado = :estado WHERE IdProducto = :IdProducto";
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

    static function searchForId(int $id): ?Producto
    {
        try {
            if ($id > 0) {
                $tmpProducto = new Producto();
                $tmpProducto->Connect();
                $getrow = $tmpProducto->getRow("SELECT * FROM ornamentacion.producto WHERE IdProducto =?", array($id));
                $tmpProducto->Disconnect();
                return ($getrow) ? new Producto($getrow) : null;
            } else {
                throw new Exception('Id de Producto Invalido');
            }
        } catch (Exception $e) {
            GeneralFunctions::logFile('Exception', $e);
        }
        return null;
    }
    /**
     * @param $documento
     * @return bool
     * @throws Exception
     */
    public static function productoRegistrado($nombre): bool
    {
        //$result = producto::search("SELECT * FROM ornamentacion.producto where nombre = " . $nombre);
        $result = producto::search("SELECT * FROM ornamentacion.producto where nombre = '" . $nombre."' ");
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
    public function jsonSerialize(): mixed
    {
        return [
            ':idProducto' =>    $this->getIdProducto(),
            ':tipo' =>   $this->getTipo(),
            ':nombre' =>   $this->getNombre(),
            ':cantidad' =>   $this->getCantidad(),
            ':valor' =>   $this->getValor(),
            ':material' =>   $this->getMaterial(),
            ':tamano' =>   $this->getTamano(),
            ':diseno' =>   $this->getDiseno(),
            ':tipoServicio' =>   $this->getTipoServicio(),
            ':estado' =>   $this->getEstado(),


        ];
    }
}