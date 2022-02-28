<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Models\GeneralFunctions;

use App\Enums\TipoMateria;
use App\Enums\Estado;
use App\Models\MateriaPrima;


class MateriasController
{

    private array $dataMateria;

    public function __construct(array $_FORM)
    {
        $this->dataMateria = array();
        $this->dataMateria['idMateria'] = $_FORM['idMateria'] ?? NULL;
        $this->dataMateria['nombre'] = $_FORM['nombre'] ?? NULL;
        $this->dataMateria['tipo'] = $_FORM['tipo'] ?? 'Perfiles';
        $this->dataMateria['valor_venta'] = $_FORM['valor_venta'] ?? Null;
        $this->dataMateria['stock'] = $_FORM['stock'] ?? Null;
        $this->dataMateria['estado'] = $_FORM['estado'] ?? 'Inactivo';
    }

    public function create($withFiles = null) {
        try {
            if (!empty($this->dataMateria['nombre']) && !MateriaPrima::materiaRegistrado($this->dataMateria['nombre'])) {

            }
            $materia = new MateriaPrima($this->dataMateria);
            if ($materia->insert()) {
                unset($_SESSION['frmUMateria']);
                header("Location: ../../views/modules/materia/index.php?respuesta=success&mensaje=materia Registrado");


            } else {
                header("Location: ../../views/modules/materia/create.php?respuesta=error&mensaje=materia ya registrado");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    public function edit($withFiles = null)
    {
        try {
            $user = new MateriaPrima($this->dataMateria);
            if($user->update()){
                unset($_SESSION['frmMateria']);
            }
            header("Location: ../../views/modules/materia/show.php?id=" . $user->getIdMateria() . "&respuesta=success&mensaje=Usuario Actualizado");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function searchForID (array $data){
        try {
            $result = MateriaPrima::searchForId($data['id']);
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
            $result = MateriaPrima::getAll();

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
            $ObjUsuario = MateriaPrima::searchForId($id);
            $ObjUsuario->setEstado("Activo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/materia/index.php");
            } else {
                header("Location: ../../views/modules/materia/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function inactivate(int $id)
    {
        try {
            $ObjUsuario = MateriaPrima::searchForId($id);
            $ObjUsuario->setEstado("Inactivo");
            if ($ObjUsuario->update()) {
                header("Location: ../../views/modules/materia/index.php");
            } else {
                header("Location: ../../views/modules/materia/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
        }
    }

    static public function selectMateria(array $params = [])
    {

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "materia_id";
        $params['name'] = $params['name'] ?? "materia_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrMateria = array();
        if ($params['where'] != "") {
            $base = "SELECT * FROM ornamentacion.MateriaPrima WHERE ";
            $arrMateria = MateriaPrima::search($base . $params['where']);
        } else {
            $arrMateria = MateriaPrima::getAll();
        }

        $htmlSelect = "<select " . (($params['isMultiple']) ? "multiple" : "") . " " . (($params['isRequired']) ? "required" : "") . " id= '" . $params['id'] . "' name='" . $params['name'] . "' class='" . $params['class'] . "'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if (is_array($arrMateria) && count($arrMateria) > 0) {
            /* @var $arrMateria MateriaPrima[] */
            foreach ($arrMateria as $MateriaPrima)
                if (!MateriasController::materiaIsInArray($MateriaPrima->getIdMateria(), $params['arrExcluir']))
                    $htmlSelect .= "<option " . (($MateriaPrima != "") ? (($params['defaultValue'] == $MateriaPrima->getIdMateria()) ? "selected" : "") : "") . " value='" . $MateriaPrima->getIdMateria() . "'>" . $MateriaPrima->getNombre() . "</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function materiaIsInArray($IdMateria, $ArrMateria)
    {
        if (count($ArrMateria) > 0) {
            foreach ($ArrMateria as $Materia) {
                if ($Materia->getIdMateria() == $IdMateria) {
                    return true;
                }
            }
        }
        return false;
    }

}