<?php
header("Content-Type: text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *");

require_once('Table/bdProducto.php');
require_once('Table/bdFactura.php');
require_once('Table/bdUsuario.php');
/*require_once('Table/bdBoleta.php');*/
require_once('Table/bdPedido.php');
require_once('Table/bdStock.php');
require_once('Table/bdCliente.php');
require_once('Table/bdGrafico.php');
require_once('Table/bdTalonario.php');
require_once('Table/bdBoletaDispositivo.php');

define('IMAGE_FOLDER', '/home/aranibar/comprobantes/');

//Impresora termica
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta l铆nea

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\NativeEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

// Include PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date;


///


//por ZT
//Singleton para conexi贸n con Nubox
require_once('nubox.php');
require_once('nuboxCert.php');
require_once('nuboxTEST.php');
//require_once('dompdf/dompdf_config.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

require_once 'dompdf/autoload.inc.php';


//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR & ~E_DEPRECATED & ~E_NOTICE);
error_reporting(E_ALL);
global $Nubox; 
$Nubox = new NuboxConnect();
global $NuboxCert; 
$NuboxCert = new NuboxConnectCert();
global $NuboxTest; 
$NuboxTest = new NuboxConnectTest();


     $dataBaseServer="aranibar.cl";
 	 $dataBaseUsername="aranibar";
   	 $dataBaseUserPassword="eliasteamo007";
     $dataBaseName="aranibar_aranibar";







	$act = $_REQUEST['act'];

 


	switch($act) {
		//para los usuarios del sistema intranet
            
             case 'subirArchivoFolio': echo json_encode(subirArchivoFolio($_POST['idFolio'],
                                                                                  $_POST['imagen'])); break;    
            
            
            
        case 'generaDTEBoletaCert':  
        	echo generaDTEBoletaCert(  
                              $_REQUEST["FchEmision"], 
                              $_REQUEST["Afecto"],
                              $_REQUEST["DevID"],
                              json_decode($_REQUEST["Detalles"], true) 
                          ); 
                          
        break;     
            
            
                
        /**********FUNCION GENERAR COBRANZA************/    
            
        case 'listarClientes': echo json_encode(listarClientes($_REQUEST['rut'], 
                                                               $_REQUEST['nombre'],
                                                               $_REQUEST['tipo']                                                              
                                                              )); break;    
            
            
            
                    
        case 'listarNotaTotalFolio': echo json_encode(listarNotaTotalFolio( $_REQUEST['idCliente'],
                                                                         $_REQUEST['nFolio'])); break;      
            
            
            
            
            
            
                   
       case 'listarNotaTotalPedido': echo json_encode(listarNotaTotalPedido($_REQUEST['idCliente'],
                                                                            $_REQUEST['nPedido'])); break;      
            
            
            
            
       case 'insertarRecibo': echo json_encode(insertarRecibo($_REQUEST['idClie'], 
                                                                   $_REQUEST['fCobro'],
                                                                   $_REQUEST['nRecibo'],
                                                                   $_REQUEST['tipPago'],
                                                                   $_REQUEST['totalRecibo'],
                                                                   $_REQUEST['totalAbono'],
                                                                   $_REQUEST['observacion'],  
                                                                  $_REQUEST['deviceId'], 
                                                                   json_decode($_REQUEST["objDeta"])
                                                                  )); break;
            
            
            
            
            
            
       case 'listarRecibosAplicacion': echo json_encode(listarRecibosAplicacion(
                                                                 $_REQUEST['fRecibo'],
                                                                 $_REQUEST['andrID']
                                                                  )); break;           
            
            
            
            
            
       case 'eliminarReciboAplicacion': echo json_encode(eliminarReciboAplicacion($_REQUEST['idRicibo'])); break;   
            
            
            
           
       case 'subirImagenRecibo': echo json_encode(subirImagenRecibo($_REQUEST['idRecibos'],
                                                                                  $_REQUEST['imagen'])); break;  
            
                    
       case 'subirImagenReciboFirma': echo json_encode(subirImagenReciboFirma($_REQUEST['idRecibos'],
                                                                                  $_REQUEST['imagen'])); break;        
            
                   
            
            
            /******************/
            
            
             case 'consultarUsuario': echo json_encode(consultarUsuario($_REQUEST['usua'],
                                                               $_REQUEST['pass'])); break;       
            
            
        case 'excelTest': excelTest(); break;
            
            
        case 'excelCobranzaPedidos': excelCobranzaPedidos(); break;    
            
            
    }

function subirArchivoFolio($idFolio, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoFolio($idFolio, $imagen);
}

function consultarUsuario($usua, $pass){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarUsuario($usua, $pass);
} 


function generaDTEBoletaCert($FchEmision,                           
                               $Afecto,
                               $DevID,
                               $Detalles){

	
	global $NuboxCert;
	
	//Creamos el objecto que seria el JSON
	$JSON = array(
	    "afecto"            =>  $Afecto === "SI",
	    "productos"         =>  array()
	);

    $total = 0;

	//Partimos en 1 ya que el valor de SECUENCIA, parte en 1
	for ($i=1; $i <= count($Detalles); $i++) { 

		//Creamos una referencia al detalle actual
		$d = $Detalles[$i-1];

		//El folio lo dejamos siempre en 1, configuraremos que NUBOX se encarge de darnos los folios
		
		$row = array(
    	    "fechaEmision"      =>  date('Y-m-d', strtotime($FchEmision)),
    	    "folio"             =>  1,
    	    "rutContraparte"    =>  "66666666-6",   //Boleta An贸nima
    	    "comunaContraparte" =>  "Arica",        //Igual que el emisor
    	    "razonSocialContraparte"    =>  ".",
            "giroContraparte"   =>  ".",
            "direccionContraparte"  =>  "Arica",
    	    "codigoSucursal"    =>  "00001",
    	    "afecto"            =>  $Afecto,
    	    "producto"          =>  $d["Nombre"],
    	    "valor"             =>  $d["Valor"],
    	    "cantidad"          =>  $d['Cantidad'],
    	    "fechaVencimiento"  =>  date('Y-m-d', strtotime($FchEmision)),
    	    "fechaPeriodoDesde" =>  date('Y-m-d', strtotime($FchEmision)),
    	    "fechaPeriodoHasta" =>  date('Y-m-d', strtotime($FchEmision)),
    	    "codigoSIITipoDeServicio"   => 3,
    	    "secuencia"         =>  $i
    	);
    	
    	$total += intval($d["Valor"]);
    	
    	$JSON["productos"][] = $row;

	}

	//*** PROCESO DE AUTENTICACION ***//

	$resp = $NuboxCert->autenticar();

	if( $resp == "OK"){
        

        $arr = $NuboxCert->enviaBoleta($JSON);
        $ret = json_encode($arr);
        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia = new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);

		$instancia->insertarBoleta($total, date('Y-m-d', strtotime($FchEmision)), $DevID, $arr["folio"], $Afecto === "SI");
        
        return $ret;
        
	} else {

		return $resp;

	}

}

   

function listarClientes($rut, $nombre, $tipo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientes($rut, $nombre, $tipo);
}


function listarNotaTotalFolio($idCliente, $nFolio){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarNotaTotalFolio($idCliente, $nFolio);
}



function listarNotaTotalPedido($idCliente, $nPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarNotaTotalPedido($idCliente, $nPedido);
}


function insertarRecibo($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono,$observacion,$objDeta, $deviceId){
global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
return $instancia->insertarRecibo($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono, $observacion, $objDeta, $deviceId);
}


function listarRecibosAplicacion($fRecibo, $andrID){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarRecibosAplicacion($fRecibo, $andrID);
}

function eliminarReciboAplicacion($idRicibo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarReciboAplicacion($idRicibo);
} 



function subirImagenRecibo($idRecibos, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirImagenRecibo($idRecibos, $imagen);
}

function subirImagenReciboFirma($idRecibos, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirImagenReciboFirma($idRecibos, $imagen);
}


function excelCobranzaPedidos(){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
	$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
	$rows = $instancia->queryCobranza();
	
	generateAndSendExcel($rows, 'CobranzaPedido', 'TEST');
}


function excelTest(){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
	$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
	$rows = $instancia->queryDemo();
	
	generateAndSendExcel($rows, 'ArchivoTest', 'TEST');
}

function generateAndSendExcel($rows, $filename, $sheetname) {
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set document properties
    $spreadsheet->getProperties()->setCreator("Sistema Intranet Aranibar")
                ->setTitle("Generado vía aranibar.cl");

    $sheet = $spreadsheet->getActiveSheet();

    // Extract headers from the first object
    if (!empty($rows)) {
        $headers = array_keys($rows[0]);
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '1', $header);
            $columnIndex++;
        }
    }

    // Add data rows
    $rowNumber = 2;
    foreach ($rows as $row) {
        $columnIndex = 'A';
        foreach ($row as $value) {
            $cellCoordinate = $columnIndex . $rowNumber;

            if (strtotime($value) !== false && date('d-m-Y', strtotime($value)) == $value) {
                // Explicitly convert to date format and set value
                $dateTimeValue = Date::PHPToExcel(new DateTime($value));
                $sheet->setCellValue($cellCoordinate, $dateTimeValue);
                $sheet->getStyle($cellCoordinate)->getNumberFormat()
                      ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            } elseif (is_numeric($value)) {
                // Explicitly set numeric value
                //$sheet->setCellValueExplicit($cellCoordinate, $value, NumberFormat::FORMAT_NUMBER);
                $sheet->getStyle($cellCoordinate)->getNumberFormat()
                      ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $sheet->setCellValue($cellCoordinate, floatval($value));
            } else {
                // Treat as text
                $value = $value === NULL ? "." : $value;
                $sheet->setCellValue($cellCoordinate, $value);
            }

            $columnIndex++;
        }
        $rowNumber++;
    }

    // Rename worksheet
    $spreadsheet->getActiveSheet()->setTitle($sheetname);

    // Redirect output to a client’s web browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}




?>