<?php

namespace App\Controllers;
require (__DIR__.'/../../vendor/autoload.php');
use App\Models\GeneralFunctions;
use App\Models\Proveedor;

class ProveedoresController
{
    private array $dataProveedor;

    public function __construct(array $_FORM)
    {
        $this->dataProveedor = array();
        $this->dataProveedor['IdProveedor'] = $_FORM['IdProveedor'] ?? NULL;
        $this->dataProveedor['documento'] = $_FORM['documento'] ?? NULL;
        $this->dataProveedor['nombre'] = $_FORM['nombre'] ?? '';
        $this->dataProveedor['ciudad'] = $_FORM['ciudad'] ?? '';
        $this->dataProveedor['estado'] = $_FORM['estado'] ?? 'Activo';
        $this->dataProveedor['municipiosId'] = $_FORM['municipiosId'] ?? NULL;
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataProveedor['documento']) && !Proveedor::proveedorRegistrado($this->dataProveedor['documento'])) {
                $proveedores = new Proveedor($this->dataProveedor);
                if ($proveedores->insert()) {
                    unset($_SESSION['frmProveedores']);
                    header("Location: ../../views/modules/proveedores/index.php?respuesta=success&mensaje=proveedores Registrado");
                }
            } else {
                header("Location: ../../views/modules/proveedores/create.php?respuesta=error&mensaje=proveedores ya registrada");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            $proveedores = new Proveedor($this->dataProveedor);
            if($proveedores->update()){
                unset($_SESSION['frmProveedores']);
            }
            header("Location: ../../views/modules/proveedores/show.php?id=" . $proveedores->getIdProveedor() . "&respuesta=success&mensaje=Categoria Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID(array $data)
    {
        try {
            $result = Proveedor::searchForId($data['id']);
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

    static public function getAll(array $data = null)
    {
        try {
            $result = Proveedor::getAll();
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

    static public function activate(int $id)
    {
        try {
            $objProveedores = Proveedor::searchForId($id);
            $objProveedores->setEstado("Activo");
            if ($objProveedores->update()) {
                header("Location: ../../views/modules/proveedores/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/proveedores/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjProveedores = Proveedor::searchForId($id);
            $ObjProveedores->setEstado("Inactivo");
            if ($ObjProveedores->update()) {
                header("Location: ../../views/modules/proveedores/index.php?respuesta=success&mensaje=Registro actualizado");
            } else {
                header("Location: ../../views/modules/proveedores/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectProveedor(array $params = []) {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "provedor_id";
        $params['name'] = $params['name'] ?? "provedor_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrProveedor = array();
        if ($params['where'] != "") { //Si hay filtro
            $base = "SELECT * FROM ornamentacion.proveedor WHERE";
            $arrProveedor = Proveedor::search($base . ' ' . $params['where']);
        } else {
            $arrProveedor = Proveedor::getAll();
        }
        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "' style='width: 100%;'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrProveedor) && count($arrProveedor) > 0) {
            /* @var $arrProveedor Proveedor[] */
            foreach ($arrProveedor as $proveedor)
                if (!ProveedoresController::categoriaIsInArray($proveedor->getIdProveedor(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($proveedor != "") ? (($params['defaultValue'] == $proveedor->getIdProveedor()) ? "selected" : "") : "") . " value='" . $proveedor->getIdProveedor() . "'>" . $proveedor->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    private static function categoriaIsInArray($idCategoria, $ArrCategorias)
    {
        if (count($ArrCategorias) > 0) {
            foreach ($ArrCategorias as $Categoria) {
                if ($Categoria->getId() == $idCategoria) {
                    return true;
                }
            }
        }
        return false;
    }


}