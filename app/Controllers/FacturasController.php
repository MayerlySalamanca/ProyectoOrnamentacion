<?php

namespace App\Controllers;

require (__DIR__.'/../../vendor/autoload.php');

use App\Enums\EstadoFactura;
use App\Models\Factura;
use App\Models\GeneralFunctions;

use Carbon\Carbon;

class FacturasController{

    private array $dataVenta;

    public function __construct(array $_FORM)
    {
        $this->dataVenta = array();
        $this->dataVenta['idFactura'] = $_FORM['idFactura'] ?? NULL;
        $this->dataVenta['numeroFactura'] = $_FORM['numeroFactura'] ?? '';
        $this->dataVenta['usuarioCliente'] = $_FORM['usuarioCliente'] ?? null;
        $this->dataVenta['usuarioVendedor'] = $_FORM['usuarioVendedor'] ?? null;
        $this->dataVenta['fecha'] = !empty($_FORM['fecha']) ? Carbon::parse($_FORM['fecha_venta']) : new Carbon();
        $this->dataVenta['monto'] = $_FORM['monto'] ?? 0;
        $this->dataVenta['estado'] = $_FORM['estado'] ?? EstadoFactura::PROCESO;
    }

    public function create() {
        try {
            $Venta = new Factura($this->dataVenta);
            if ($Venta->insert()) {
                unset($_SESSION['frmVentas']);
                $Venta->Connect();
                $id = $Venta->getLastId('idFactura','factura');
                $Venta->Disconnect();
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
            $Venta = new Factura($this->dataVenta);
            if($Venta->update()){
                unset($_SESSION['frmVentas']);
            }
            header("Location: ../../views/modules/facturacion/show.php?id=" . $Venta->getIdFactura() . "&respuesta=success&mensaje=Venta Actualizada");
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
            $ObjVenta->setEstado("Anulada");
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

    static public function selectFactura (array $params = [] ){

        $params['isMultiple'] = $params['isMultiple'] ?? false;
        $params['isRequired'] = $params['isRequired'] ?? true;
        $params['id'] = $params['id'] ?? "factura_id";
        $params['name'] = $params['name'] ?? "factura_id";
        $params['defaultValue'] = $params['defaultValue'] ?? "";
        $params['class'] = $params['class'] ?? "form-control";
        $params['where'] = $params['where'] ?? "";
        $params['arrExcluir'] = $params['arrExcluir'] ?? array();
        $params['request'] = $params['request'] ?? 'html';

        $arrVentas = array();
        if($params['where'] != ""){
            $base = "SELECT * FROM ornamentacion.factura WHERE ";
            $arrVentas = Factura::search($base.$params['where']);
        }else{
            $arrVentas = Factura::getAll();
        }

        $htmlSelect = "<select ".(($params['isMultiple']) ? "multiple" : "")." ".(($params['isRequired']) ? "required" : "")." id= '".$params['id']."' name='".$params['name']."' class='".$params['class']."'>";
        $htmlSelect .= "<option value='' >Seleccione</option>";
        if(is_array($arrVentas) && count($arrVentas) > 0){
            /* @var $arrVentas Factura[] */
            foreach ($arrVentas as $ventas)
                if (!FacturasController::ventaIsInArray($ventas->getIdFactura(),$params['arrExcluir']))
                    $htmlSelect .= "<option ".(($ventas != "") ? (($params['defaultValue'] == $ventas->getIdFactura()) ? "selected" : "" ) : "")." value='".$ventas->getIdFactura()."'>".$ventas->getNumeroFactura()."</option>";
        }
        $htmlSelect .= "</select>";
        return $htmlSelect;
    }

    public static function ventaIsInArray($idVenta, $ArrVentas){
        if(count($ArrVentas) > 0){
            foreach ($ArrVentas as $Venta){
                if($Venta->getId() == $idVenta){
                    return true;
                }
            }
        }
        return false;
    }

}