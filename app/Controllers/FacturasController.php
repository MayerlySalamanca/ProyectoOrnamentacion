<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Models\Factura;
use App\Models\GeneralFunctions;
use App\Models\Factura;
use Carbon\Carbon;

class FacturasController{

    private array $dataVenta;

    public function __construct(array $_FORM)
    {
        $this->dataVenta = array();
        $this->dataVenta['IdFactura'] = $_FORM['IdFactura'] ?? NULL;
        $this->dataVenta['numeroFactura'] = $_FORM['numeroFactura'] ?? 0;
        $this->dataVenta['nombreCliente'] = $_FORM['nombreCliente'] ?? '';
        $this->dataVenta['cantidad'] = $_FORM['cantidad'] ?? 0;
        $this->dataVenta['fecha_venta'] = !empty($_FORM['fecha_venta']) ? Carbon::parse($_FORM['fecha_venta']) : new Carbon();
        $this->dataVenta['estado'] = $_FORM['estado'] ?? 'Proceso';
        $this->dataVenta['valor'] = $_FORM['valor'] ?? 0;
        $this->dataVenta['usuarioVendedor'] = $_FORM['usuarioVendedor'] ?? 0;
    }

    public function create() {
        try {
            $factura = new Factura($this->dataVenta);
            if ($factura->insert()) {
                unset($_SESSION['frmFactura']);
                $factura->Connect();
                $id = $factura->getLastId('ventas');
                $factura->Disconnect();
                header("Location: ../../views/modules/facturacion/create.php?id=" . $id . "");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/facturacion/create.php?respuesta=error");
        }
    }

    public function edit()
    {
        try {
            $Factura = new Factura($this->dataVenta);
            if($Factura->update()){
                unset($_SESSION['frmVentas']);
            }
            header("Location: ../../views/modules/facturacion/show.php?id=" . $Factura->getIdFactura() . "&respuesta=success&mensaje=Venta Actualizada");
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            //header("Location: ../../views/modules/facturacion/edit.php?respuesta=error");
        }
    }

    static public function searchForID (array $data){
        try {
            $result = Factura::searchForId($data['id']);
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
            $result = Factura::getAll();
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

    static public function cancel(){
        try {
            $ObjVenta = Factura::searchForId($_GET['Id']);
            $ObjVenta->setEstado("Cancelada");
            if($ObjVenta->update()){
                header("Location: ../../views/modules/facturacion/index.php");
            }else{
                header("Location: ../../views/modules/facturacion/index.php?respuesta=error&mensaje=Error al guardar");
            }
        } catch (\Exception $e) {
            GeneralFunctions::logFile('Exception',$e, 'error');
            header("Location: ../../views/modules/facturacion/index.php?respuesta=error");
        }
    }

    static public function selectVentas (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "factura_id";
        $params['name'] = $params['name'] ?? "factura_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrFactura = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM factura WHERE  ";
            $arrFactura = Factura::search($base.$params['where']);
        }else{
            $arrFactura = Factura::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrFactura) && count($arrFactura) > 0){
            /* @var $arrFactura Factura[] */
            foreach ($arrFactura as $ventas)
                if (!FacturasController::ventaIsInArray($ventas->getIdFactura(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($ventas != "") ? (($params['defaultValue'] == $ventas->getIdFactura()) ? "selected" : "" ) : "")." value='".$ventas->getId()."'>".$ventas->getNumeroSerie()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function ventaIsInArray($idVenta, $ArrFactura){
        if(count($ArrFactura) > 0){
            foreach ($ArrFactura as $Venta){
                if($Venta->getId() == $idVenta){
                    return true;
                }
            }
        }
        return false;
    }

}