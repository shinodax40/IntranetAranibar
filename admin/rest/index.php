<?php

define("RUTA_LOGS", __DIR__ . "/logs");

ini_set('display_errors', 0);
ini_set("log_errors", 0);
ini_set("error_log", RUTA_LOGS . "/" . date("Y-m-d") . ".log");

header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
header("Content-Type: text/html;charset=utf-8");

require_once ('config.php');

try {
    initApi();
} catch (TypeError $ex) {
    error_log($ex);
    $e = AppCode::COMPONENT_ERROR;
    $result = new AppResponse($e['code'], null, $e['message']);
    $result->imprimir();
} catch (AppException $ex) {
    error_log($ex);
    $result = new AppResponse($ex->getCode(), null, $ex->getMessage());
    $result->imprimir();
} catch (Exception $ex) {
    error_log($ex);
    $e = AppCode::UNKNOWN_ERROR;
    $result = new AppResponse($e['code'], null, $e['message']);
    $result->imprimir();
}

function initApi() {
    $api = new ApiRest();
    $mapping = $api->getMapeo();
    $response = '';

    if (!empty($mapping)) {
        switch ($mapping[0]) {
            case MAPPING_BOLETACABE:
                $service = new BoletacabeImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->boletacabeLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_BOLETADETA:
                $service = new BoletadetaImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->boletadetaLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_BOLETATOTAL:
                $service = new BoletatotalImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->boletatotalLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_BOLETADISPOSITIVO:
                $service = new BoletaDispositivoImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->boletaDispositivoLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_CATEGORIA:
                $service = new CategoriaImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->categoriaLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_FACTURACABE:
                $service = new FacturacabeImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->facturacabeLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_FACTURADETA:
                $service = new FacturadetaImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->facturadetaLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_FACTURATOTAL:
                $service = new FacturatotalImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->facturatotalLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_MARCA:
                $service = new MarcaImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->marcaLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_PRODUCTOS:
                $service = new ProductosImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->productosLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLATENCIONCLIENTE:
                $service = new TblAtencionClienteImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblAtencionClienteLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCAJA:
                $service = new TblCajaImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblCajaLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCANJEAR:
                $service = new TblCanjearImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblCanjearLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCLASIFICACIONPRODUCTOS:
                $service = new TblClasificacionProductosImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblClasificacionProductosLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCLIENTE:
                $service = new TblClienteImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblClienteLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCODIGOBARRAEXTRA:
                $service = new TblCodigoBarraExtraImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblCodigoBarraExtraLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
            case MAPPING_TBLCONCURSO:
                $service = new TblConcursoImpl();
                $variable = (isset($mapping[1]) ? (int) $mapping[1] : null);
                $response = $service->tblConcursoLogic($api->getMetodo(), $variable, $api->getParamatro(), $api->getDatos());
                break;
        }
    }
    $result = new AppResponse(AppCode::OK['code'], $response);
    $result->imprimir();
}

?>