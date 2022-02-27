<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Enums\Estado;
use App\Enums\Tipo;
use App\Enums\TipoMateria;
use App\Models\GeneralFunctions;
use App\Models\Producto;


class ProductosController{

    private array $dataProducto;

    public function __construct(array $_FORM)
    {
        $this->dataProducto = array();
        $this->dataProducto['idProducto'] = $_FORM['idProducto'] ?? NULL;
        $this->dataProducto['tipo'] = $_FORM['tipo'] ?? Tipo::PRODUCTO;
        $this->dataProducto['nombre'] = $_FORM['nombre'] ?? NULL;
        $this->dataProducto['stock'] = $_FORM['stock'] ?? 0;
        $this->dataProducto['valor'] = $_FORM['valor'] ?? 0;
        $this->dataProducto['estado'] = $_FORM['estado'] ?? Estado::INACTIVO;
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataProducto['nombre']) && !Producto::productoRegistrado($this->dataProducto['nombre'])) {
                $Producto = new Producto($this->dataProducto);
                if ($Producto->insert()) {
                    unset($_SESSION['frmCategorias']);
                    header("Location: ../../views/modules/productos/index.php?respuesta=success&mensaje=Producto Registrada");
                }
            } else {
                header("Location: ../../views/modules/productos/create.php?respuesta=error&mensaje=Producto ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit()
    {
        try {
            $producto = new Producto($this->dataProducto);
            if($producto->update()){
                unset($_SESSION['frmProductos']);
            }

            header("Location: ../../views/modules/productos/show.php?id=" . $producto->getIdProducto() . "&respuesta=success&mensaje=Producto Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {

        try {
            $result = Producto::searchForId($data['id']);
            if (!empty($data['request']) and $data['request'] === 'ajax' and !empty($result)) {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result->jsonSerialize());
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function getAll (array $data = null){
        try {
            $result = Producto::getAll();
            if (!empty($data['request']) and $data['request'] === 'ajax') {
                header('Content-type: application/json; charset=utf-8');
                $result = json_encode($result);
            }
            return $result;
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
        return null;
    }

    static public function activate (int $Id){
        try {
            $ObjProducto = Producto::searchForId($Id);
            $ObjProducto->setEstado("Activo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate (int $idProducto){
        try {
            $ObjProducto = Producto::searchForId($idProducto);
            $ObjProducto->setEstado("Inactivo");
            if($ObjProducto->update()){
                header("Location: ../../views/modules/productos/index.php");
            }else{
                header("Location: ../../views/modules/productos/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectProducto (array $params = []){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "producto_Id";
        $params['name'] = $params['name'] ?? "producto_Id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrProducto = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM ornamentacion.producto WHERE";
            $arrProducto = Producto::search($base.$params['where']);
        }else{
            $arrProducto = Producto::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrProducto) && count($arrProducto) > 0){
            /* @var $arrProducto Producto[] */
            foreach ($arrProducto as $producto)
                if (!ProductosController::productoIsInArray($producto->getIdProducto(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($producto != "") ? (($params['defaultValue'] == $producto->getIdProducto()) ? "selected" : "" ) : "")." value='".$producto->getIdProducto()."'>".$producto->getNombre()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function productoIsInArray($idProducto, $ArrProducto){
        if(count($ArrProducto) > 0){
            foreach ($ArrProducto as $Producto){
                if($Producto->getIdProducto() == $idProducto){
                    return true;
                }
            }
        }
        return false;
    }

}