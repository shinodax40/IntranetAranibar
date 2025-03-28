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
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\NativeEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

   

///


//por ZT
//Singleton para conexión con Nubox
require_once('nubox.php');
require_once('nuboxCert.php');
require_once('nuboxTEST.php');
//require_once('dompdf/dompdf_config.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

require_once 'dompdf/autoload.inc.php';


error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
global $Nubox; 
$Nubox = new NuboxConnect();
global $NuboxCert; 
$NuboxCert = new NuboxConnectCert();
global $NuboxTest; 
$NuboxTest = new NuboxConnectTest();
/*
    $dataBaseServer="localhost";
	$dataBaseUsername="root";
	$dataBaseUserPassword="";
	$dataBaseName="aranibar_aranibar8";*/

     /*$dataBaseServer="https://www.aranibar.cl/";
	 $dataBaseUsername="aranibar";
	 $dataBaseUserPassword="bOb54:VO:aM02y";
	 $dataBaseName="aranibar_aranibar";*/



   /*  $dataBaseServer="localhost";
	 $dataBaseUsername="aranibar";
	 $dataBaseUserPassword="bOb54:VO:aM02y";
	 $dataBaseName="aranibar_santa_maria";*/

    /*$dataBaseServer="localhost";
	 $dataBaseUsername="aranibar";
	 $dataBaseUserPassword="&*d[{H35cpGj";
	 $dataBaseName="aranibar_aranibar";*/

/*
    $dataBaseServer="localhost";
	 $dataBaseUsername="aranibar";
	 $dataBaseUserPassword="zi2Rs6L[#Vl82M";
	 $dataBaseName="aranibar_aranibar";
	 
*/

     /***Local ***/
     /*$dataBaseServer="localhost";
     $dataBaseUsername="root";
     $dataBaseUserPassword="";*/

     /***Produccion***/
     $dataBaseServer="aranibar.cl";
 	 $dataBaseUsername="aranibar";
   	 $dataBaseUserPassword="eliasteamo007";


    /***Produccion***/
     $dataBaseServerGPS="dev.magnussoluciones.cl/phpmyadmin";
 	 $dataBaseUsernameGPS="aranibar_vendedor";
   	 $dataBaseUserPasswordGPS="qDChknOVeDbvNRK8";
     $dataBaseNameGPS="aranibar_vendedor";
  
    
     /***DEV MAGNUS ***/
     /*$dataBaseServer="dev.magnussoluciones.cl";
     $dataBaseUsername="aranibar_vendedor";
     $dataBaseUserPassword="qDChknOVeDbvNRK8";
     $dataBaseName="aranibar_aranibar";*/
/*
     $dataBaseMasterServer="dev.magnussoluciones.cl";
     $dataBaseMasterUsername="aranibar_vendedor";
     $dataBaseMasterUserPassword="qDChknOVeDbvNRK8";
     $dataBaseMasterName="aranibar_aranibar";
     $nombreSucursal="Test";
*/


	$act    = $_REQUEST['act'];



     $ambiente = $_REQUEST['tienda'];

    $tokens_admin_tienda = array(
        'a0530d563e20942be204f321f18c63bb06943ff14d98ca2678f22b48e2e45eaf',
        '83bfcb7deab372d664c0b48fee4b2e59023820d1589f4614e339fe2e516cc759',
        '5d7b4d013a6cd96e7ee715c3c50d24b2c53c59f67bfc9015bc8494ebd8d48b9b',
        '698b856cb47c719af656e6f572cafaae3aad984277c40c29a931280d5c619eb6',
        '831702da7f72b2eb14656457d49971efc80af10213b59d2c780b4d15167cf971'
    );

     $token_origen = $tokens_admin_tienda[(isset($ambiente) ? (int) $ambiente : 0)];

   $url_services = "https://aranibar.cl/admin/rest";
 //   $url_services = "http://localhost/tienda-aranibar/admin/rest";

    if($ambiente == "1"){                
    /*     $nombreRazonSocial ="JUAN CARLOS ARANIBAR CASTRO";
         $rutRazonSocial    ="16.466.904-1";
         $nombreCompartidoImpresora = "";        
         $dataBaseName="aranibar_aranibar";
         $nombreSucursal="SUCURSAL BARROS ARANA";*/
        
        
         $nombreRazonSocial ="SOCIEDAD HNOS. ARANIBAR SPA";
         $rutRazonSocial    ="77.839.652-1";        
         $nombreCompartidoImpresora = "";        
         $dataBaseName="aranibar_aranibar";
		 //$dataBaseName="aranibar";
         $nombreSucursal="SUCURSAL BARROS ARANA";
         $nombreDireccion="Barros Arana 3033";
        
                
    }else if($ambiente == "2"){
         $nombreRazonSocial ="SOCIEDAD HNOS. ARANIBAR SPA";
         $rutRazonSocial    ="77.839.652-1";        
         $nombreCompartidoImpresora = "impimirVentasLocal";        
         $dataBaseName="aranibar_santa_maria";
         $nombreSucursal="SUCURSAL SANTA MARIA";
         $nombreDireccion="Santa Maria 2141, Lcl 126";

    }else if($ambiente == "3"){
         $nombreRazonSocial ="SOCIEDAD HNOS. ARANIBAR SPA";
         $rutRazonSocial    ="77.839.652-1";        
         $nombreCompartidoImpresora = "impimirVentasLocal";        
         $dataBaseName="aranibar_tucapel";
         $nombreSucursal="SUCURSAL TUCAPEL";
         $nombreDireccion="Tucapel 2462";


    }else if($ambiente == "4"){
         $nombreRazonSocial ="SOCIEDAD HNOS. ARANIBAR SPA";
         $rutRazonSocial    ="77.839.652-1";        
         $nombreCompartidoImpresora = "impimirVentasLocal";        
         $dataBaseName="aranibar_asocapec";
         $nombreSucursal="SUCURSAL ASOCAPEC";
         $nombreDireccion="M.Castillo Ibaceta 3305 Lcl 78";


    }

    


    /* 
     DATOS DE LA BASE DE DATOS DE CASA MATRIZ
    */
    
    $dataBaseMasterServer="aranibar.cl";
    $dataBaseMasterUsername="aranibar";
    $dataBaseMasterUserPassword="eliasteamo007";
    $dataBaseMasterName="aranibar_aranibar";



	switch($act) {
        case 'consultarPuntos': echo obtenerPuntos($_REQUEST['rut']); break;
        case 'registrarPuntos': echo registrarPuntos(json_decode(file_get_contents("php://input"))); break;
        //para los usuarios del sistema intranet
            
        case 'listarSubProducto': echo json_encode(listarSubProducto($_REQUEST['nodo_prod'],
                                                                     $_REQUEST['tipBusq']
                                                                     )); break;  
            
            
       case 'listarDescuentosVentas': echo json_encode(listarDescuentosVentas($_REQUEST['id_prod'])); break;  
            
            
               case 'listarProductoHijo': echo json_encode(listarProductoHijo($_REQUEST['id_prod'])); break;      
    
            
            
       case 'consultarDescTiendaProd': echo json_encode(consultarDescTiendaProd()); break;      
      
            
              case 'listTiendaPedidoPagina': echo json_encode(listTiendaPedidoPagina()); break;   
            
            		case 'listTiendaDetallePagina': echo json_encode(listTiendaDetallePagina($_REQUEST['id_orden'])); break;

            
            
     
            
		case 'checkUser': echo loginUsua($_REQUEST['user'], 
                                         $_REQUEST['pass']); break;
            
		case 'tipoProducto': echo json_encode(getDataTipo()); break;
            
		case 'getDataMarca': echo json_encode(getDataMarca($_REQUEST['id'])); break;
            
            
    	case 'getDataTransporte': echo json_encode(getDataTransporte()); break;

            
            
    //    	case 'getDataMarca': echo getDataMarca(json_decode($_REQUEST['id'])); break;    
            
            
		case 'listarProductos': echo json_encode(listarProductos($_REQUEST['codProducto'],
                                                                     $_REQUEST['nombreProd'],
                                                                     $_REQUEST['marcaProd'],
                                                                     $_REQUEST['categoriaProd']
                                                                    )); break;
            
       	        	case 'listarCodigoBarra': echo json_encode(listarCodigoBarra($_REQUEST['id']
                                                                    )); break;     
            
            		case 'agregarCodigoBarra': echo json_encode(agregarCodigoBarra($_REQUEST['id'],
                                                                     $_REQUEST['cod_barra']
                                                                    )); break;     
             		case 'eliminarCodigoBarra': echo json_encode(eliminarCodigoBarra($_REQUEST['id']
                                                                    )); break;     
        
		case 'insertarProducto': echo json_encode(insertarProducto($_REQUEST['id'], 
                                                                   $_REQUEST['codProducto'],
                                                                   $_REQUEST['nombreProd'],
                                                                   $_REQUEST['marcaProd'],
                                                                   $_REQUEST['categoriaProd'],
                                                                   $_REQUEST['precioCosto'],
                                                                   $_REQUEST['stockProd'],
                                                                   $_REQUEST['accionProducto'],
                                                                   $_REQUEST['ObservacionProd'],
                                                                   $_REQUEST['precioVenta']
                                                                  )); break;
    
        case 'insertarPedido': echo insertarPedido(json_decode($_REQUEST['objCabe']),
                                                  json_decode($_REQUEST['objDeta']),
                                                  json_decode($_REQUEST['objCliente'])); break;  
            
            
        case 'listarPedidos': echo json_encode(listarPedidos($_REQUEST['tipoPagos'],
                                                                     $_REQUEST['nombre'],
                                                                     $_REQUEST['desde'],
                                                                     $_REQUEST['hasta'],
                                                                     $_REQUEST['idPedido'],
                                                                     $_REQUEST['tipBusq'],
                                                                     $_REQUEST['idTransp'],
                                                                     $_REQUEST['busqRap'],
                                                                     $_REQUEST['tipoUsuario'] ,
                                                                     $_REQUEST['idUsuario'] 
                                                            )); break;  
            
            
            
                    
        case 'listarPedidosGeneral': echo json_encode(listarPedidosGeneral($_REQUEST['tipoPagos'],
                                                                     $_REQUEST['nombre'],                                                                   
                                                                     $_REQUEST['idPedido'],
                                                                     $_REQUEST['folio'],                                                                             
                                                                     $_REQUEST['desde1'],
                                                                     $_REQUEST['hasta1'],
                                                                     $_REQUEST['desde2'],
                                                                     $_REQUEST['hasta2'],
                                                                     $_REQUEST['desde3'],
                                                                     $_REQUEST['hasta3'],                                                                                 
                                                                     $_REQUEST['desde4'],
                                                                     $_REQUEST['hasta4'],  
                                                                     $_REQUEST['tipBusq'],
                                                                     $_REQUEST['idTransp'],
                                                                     $_REQUEST['busqRap'],
                                                                     $_REQUEST['tipoUsuario'] ,
                                                                     $_REQUEST['idUsuario'] 
                                                            )); break;      
            
            
            
            
            
        case 'listarCobros': echo json_encode(listarCobros()); break;  
            
        case 'listarEstadoPedido': echo json_encode(listarEstadoPedido()); break;      
            
         
       	case 'listarDetallePedido': echo json_encode(listarDetallePedido($_REQUEST['idPedido'])); break;
            
            
             	case 'consultarInventarioTiendas': echo json_encode(consultarInventarioTiendas($_REQUEST['idProd'])); break;
            
                
            
            
            
            
       	case 'listarDetallePedidoProd': echo json_encode(listarDetallePedidoProd($_REQUEST['idPedido'],
                                                                                 $_REQUEST['idProd'])); break;
            
            
            
    
       	case 'anularPedido': echo json_encode(anularPedido($_REQUEST['idPedido'],
                                                           $_REQUEST['oberv'],
                                                           $_REQUEST['folio'],
                                                           $_REQUEST['idUsuario']
                                                          )); break; 
            
            
            
         	case 'anularPedidoListado': echo json_encode(anularPedidoListado($_REQUEST['idPedido'],
                                                           $_REQUEST['oberv']
                                                          )); break; 
                
            
            
        case 'anularInventario': echo json_encode(anularInventario($_REQUEST['id_asociado'],
                                                                   $_REQUEST['id_tipo_inv'],
                                                                   $_REQUEST['id_invent'])); break;     
            

        case 'modificarPedido': echo modificarPedido(json_decode($_REQUEST['arrCliente'])); break;      
            
    
            
            
            
        case 'modificarPedidoCobro': echo modificarPedidoCobro(json_decode($_REQUEST['arrCliente'])); break;
            
            
        case 'modificarPedidoEstado': echo modificarPedidoEstado(json_decode($_REQUEST['arrCliente'])); break;     
            
            case 'modificarTalonario': echo modificarTalonario(json_decode($_REQUEST['arrTalonario'])); break;     
            
      
         
            
        
         case 'modificarVentaTienda': echo json_encode(modificarVentaTienda($_REQUEST['idPedido'], 
                                                               $_REQUEST['idPago'],
                                                               $_REQUEST['tienda']                                                              
                                                              )); break;        
                
                
        
        case 'listarClientes': echo json_encode(listarClientes($_REQUEST['rut'], 
                                                               $_REQUEST['nombre'],
                                                               $_REQUEST['tipo']                                                              
                                                              )); break;
            
        case 'listarProveedores': echo json_encode(listarProveedores($_REQUEST['rut'],
                                                               $_REQUEST['nombre'])); break;        
            
            
      case 'listarProvSelection': echo json_encode(listarProvSelection()); break;          
            
   
        case 'consultarUsuario': echo json_encode(consultarUsuario($_REQUEST['usua'],
                                                               $_REQUEST['pass'])); break;
															   
		  /*   case 'consultarUsuario': echo consultarUsuario(json_decode($_REQUEST['usua']),
                                                              json_decode($_REQUEST['pass'])
                                                              ); break;  */
            													   
															   
            
		case 'estilo': echo json_encode(getEstilo()); break;
		case 'saveUsuario': echo saveUsuario(json_decode($_REQUEST['usuario'])); break;
		case 'savePregunta': echo saveMaterial(json_decode($_REQUEST['pregunta'])); break;
		case 'respuesta': echo json_encode(getRespuesta($_REQUEST['id'])); break;
		case 'saveRespuesta': echo saveRespuesta(json_decode($_REQUEST['respuesta'])); break;

		//eliminar despues
		case 'informe': echo json_encode(getInforme());
            
            
            
        case 'insertarStock': echo insertarStock(json_decode($_REQUEST['objCabe']),
                                                  json_decode($_REQUEST['objDeta']),
                                                  json_decode($_REQUEST['objCliente']),
												  ($_REQUEST['obserbIng'])
												); break;

        //TEST PARA DTE
        case 'testDTE':  
        	echo generaDTE(   $_REQUEST["TpoDTE"], 
                              $_REQUEST["FchEmision"], 
                              $_REQUEST["Rut"], 
                              $_REQUEST["RznSoc"], 
                              $_REQUEST["Giro"], 
                              $_REQUEST["Comuna"], 
                              $_REQUEST["Direccion"], 
                              $_REQUEST["Email"], 
                              $_REQUEST["IndTraslado"], 
                              $_REQUEST["ComunaDestino"], 
                              $_REQUEST["Vendedor"],  
                              $_REQUEST["FormaPago"], 
                              $_REQUEST["TerminosPagos"], 
                              json_decode($_REQUEST["Detalles"],true),
                              $_REQUEST["idPedido"]  ,
                             $_REQUEST["TipoServicio"] 
                          ); 
        	break;
            
            
          
             //TEST PARA DTE
        case 'generaDTEBoleta':  
        	echo generaDTEBoleta(  
                              $_REQUEST["FchEmision"],                              
                              $_REQUEST["RznSoc"], 
                              $_REQUEST["Giro"], 
                              $_REQUEST["Comuna"], 
                              $_REQUEST["Direccion"], 
                              json_decode($_REQUEST["Detalles"],true) 
                          ); 
        	break;    
            
            
   
            
            

       case 'generarPedidoAplicacion':
        echo generarPedidoAplicacion(
                $_REQUEST["FchEmision"],
                $_REQUEST["Nombres"],
                $_REQUEST["Email"],
                $_REQUEST["Telefono"],
                $_REQUEST["Direccion"],
                $_REQUEST["Total"],
                $_REQUEST["Detalle"],
                $_REQUEST["DevID"]
                          );
        break;
            
         case 'generarCanjearAplicacion':
        echo generarCanjearAplicacion(
                $_REQUEST["idProd"],
                $_REQUEST["DevID"]
                          );
        break;
            
  
            
            
            

     case 'listCanjeadosPendientesDispositivo': echo json_encode(listCanjeadosPendientesDispositivo($_REQUEST['idDiv'], 
                                                                                                   $_REQUEST['idProd'])); break;
            
            

     case 'listProductosAplicacion': echo json_encode(listProductosAplicacion($_REQUEST['listarOpcion'])); break;      
    
     case 'listComprasAplicacion': echo json_encode(listComprasAplicacion($_REQUEST['idAndroid'])); break;      
            
     case 'listProductosCompradosAplicacion': echo json_encode(listProductosCompradosAplicacion($_REQUEST['idDiv'])); break;      
          
            
     case 'getPDF': echo obtienePDF(json_decode($_REQUEST['identificador']),
                                    json_decode($_REQUEST['folio'])
                                    ); break;    
    
     case 'getPDFNC': echo obtienePDFNC($_REQUEST['folio']); break; 
            
            
     case 'insertarFacturaNubox': echo insertarFacturaNubox(json_decode($_REQUEST['arrayFac']),
                                                              json_decode($_REQUEST['numPedido'])
                                                              ); break;  
            
     case 'insertarFacturaNuboxJoin': echo insertarFacturaNuboxJoin(json_decode($_REQUEST['arrayFac']),
                                                                   json_decode($_REQUEST['arrayPedido'])
                                                                   ); break;  
       
            
     case 'joinPedidos': echo json_encode(joinPedidos($_REQUEST['pedido'])); break;
            

                        
        case 'listarDireccionesCliente': echo json_encode(listarDireccionesCliente($_REQUEST['id_cliente'])); break;
            
    
     case 'joinPedidosTiendas': echo json_encode(joinPedidosTiendas($_REQUEST['idUsuario'],
                                                               $_REQUEST['fInicio'],
                                                               $_REQUEST['fFin']

                                                            )); break;  	    
        
     case 'listarCreditosPendienteFactura': echo json_encode(listarCreditosPendienteFactura()); break;  
	
        case 'joinPedidosProductoSalidas': echo json_encode(joinPedidosProductoSalidas($_REQUEST['pedido'])); break;
			
					
		        case 'listarCreditosPorPedido': echo json_encode(listarCreditosPorPedido($_REQUEST['pedido'])); break;
	
	     
        case 'joinRuta': echo json_encode( joinRuta(($_REQUEST['pedido']),
                                         json_decode($_REQUEST['arrayRuta'])
												   )
										 ); break;	
			
			
	  case 'pesoProductos': echo json_encode( pesoProductos(($_REQUEST['pedido']) )); break;			
			
			
		
   /*    	case 'joinRuta': echo json_encode(joinRuta($_REQUEST['pedido'],
												   $_REQUEST['arrayRuta'])); break;*/
		
            
     //  case 'gerexpPDF': echo gerexpPDF(json_decode($_POST["detalle"])); break;
            
      case 'gerexpPDF':  
      echo gerexpPDF(json_decode($_POST["detalle"])); 
      break;   
            
    
          case 'gerexpPDFTienda':  
      echo gerexpPDFTienda(json_decode($_POST["detalle"])); 
      break;          
            
            
      case 'gerexpClientePDF':  
      echo gerexpClientePDF(json_decode($_POST["detalle"])); 
      break;   
			
	  case 'gerexpRutaPDF':  
      echo gerexpRutaPDF(json_decode($_POST["detalle"])); 
      break; 	
            
            
              case 'generarClientePDFRuta':  
      echo generarClientePDFRuta(json_decode($_POST["detalle"])); 
      break; 		
			
			
       
      case 'gerexpTalonarioPDF':  
      echo gerexpTalonarioPDF(json_decode($_POST["detalle"])); 
      break;   
               
            
      
            
      case 'generarInventarioValidarPDF':  
      echo generarInventarioValidarPDF(json_decode($_POST["detalle"])); 
      break;         
            
            
            
            

     case 'generarFacturaPedidoPDF':  
        echo generarFacturaPedidoPDF(json_decode($_POST["detalle"])); 
        break;   
            
      case 'generarCotizacionPedidoPDF':  
        echo generarCotizacionPedidoPDF(json_decode($_POST["detalle"])); 
        break;           
            
           case 'generarCotizacionOCPDF':  
        echo generarCotizacionOCPDF(json_decode($_POST["detalle"])); 
        break;           
            
            
            

     case 'listarIngresos': echo json_encode(listarIngresos($_REQUEST['fechDesde'],
                                                               $_REQUEST['fechHasta'],
                                                               $_REQUEST['idProv'],
                                                               $_REQUEST['tipBusq']

                                                            )); break;  	  
        
     case 'listarDetalleIngresos': echo json_encode(listarDetalleIngresos($_REQUEST['idIngresos']
                                                            )); break;  	
            
            
            
            
     case 'listarInventarioBodega': echo json_encode(listarInventarioBodega($_REQUEST['fechaListar'],
                                                                            $_REQUEST['idProd'])); break;         
    
    
     case 'listProductosTotal': echo json_encode(listProductosTotal(   $_REQUEST['codProducto'],
                                                                       $_REQUEST['nombreProd'],
                                                                       $_REQUEST['marcaProd'],
                                                                       $_REQUEST['categoriaProd'],
                                                                       $_REQUEST['bodega'],
                                                                       $_REQUEST['clasif'],
                                                                       $_REQUEST['clasiFiltro'],
                                                                       $_REQUEST['nombreProdBusq'],
                                                                        $_REQUEST['codigoProdBusq'],
                                                                   
                                                                    
                                                                    
                                                                       $_REQUEST['tipoBusqueda']
                                                                    
                                                             )); break;
      
    
     case 'listProductosTotalPrecio': echo json_encode(listProductosTotalPrecio($_REQUEST['codProducto'],
                                                                       $_REQUEST['nombreProd'],
                                                                       $_REQUEST['marcaProd'],
                                                                       $_REQUEST['categoriaProd']
                                                             )); break;        
            
     case 'listPreciosFactura': echo json_encode(listPreciosFactura($_REQUEST['idProducto']
                                                             )); break;
    
            
     case 'guardarPrecioFact': echo guardarPrecioFact(json_decode($_REQUEST['objPrcioFac'])); break;
            
            
     case 'guardarObserFact': 
            echo json_encode(guardarObserFact($_REQUEST['id'],
                                              $_REQUEST['obse'],
                                              $_REQUEST['estCobro'],
                                              $_REQUEST['estPedido'],
                                              $_REQUEST['fechCobro'],
                                              $_REQUEST['numDoc']

                                             )); break;  

    case 'listarProductosInventario': echo json_encode(listarProductosInventario($_REQUEST['idProd'])); break;
          
    case 'listarPedClientesCobro': echo json_encode(listarPedClientesCobro($_REQUEST['idCliente'],
                                                                        $_REQUEST['idTipoBusqueda'] ,
                                                                        $_REQUEST['desde'] ,
                                                                     $_REQUEST['hasta']            
                                                            )); break;
            
    case 'ingresarInventario': echo ingresarInventario(json_decode($_REQUEST['arrSal']),
                                                      json_decode($_REQUEST['arrIng']),
                                                      json_decode($_REQUEST['arrCua'])

                                                     ); break;         
          
          
          
          
    case 'listarClientesEmpresa': echo json_encode(listarClientesEmpresa($_REQUEST['nombreClie'],
                                                                        $_REQUEST['responsable'] ,
                                                                        $_REQUEST['tipoComp']           
                                                            )); break;
              
    case 'listarVendedores': echo json_encode(listarVendedores()); break;   
   
    case 'listarSector': echo json_encode(listarSector()); break;   
       
          
    case 'guardarCliente': echo guardarCliente(json_decode($_REQUEST['arrClie'])); break;            
          
          
    case 'saveProductoModArchivo': echo json_encode(saveProductoModArchivo($_POST['idProd'],
                                                                                  $_POST['imagen'])); break;    

    case 'saveMarcaArchivo': echo json_encode(saveMarcaArchivo($_POST['idMarca'],
                                                                                  $_POST['imagen'])); break;  

  
    case 'subirArchivoRuta': echo json_encode(subirArchivoRuta($_POST['idInforme'],
                                                                                  $_POST['imagen'])); break; 
            
    case 'subirArchivoCamion': echo json_encode(subirArchivoCamion($_POST['idInforme'],
                                                                                  $_POST['imagen'])); break; 
            
            
            
    case 'subirArchivoFactura': echo json_encode(subirArchivoFactura($_POST['idFolio'],
                                                                                  $_POST['imagen'])); break; 
            
            
    case 'subirArchivoTalonario': echo json_encode(subirArchivoTalonario($_POST['idTalonario'],
                                                                                  $_POST['imagen'])); break; 
   
            
    case 'subirArchivoCaja': echo json_encode(subirArchivoCaja($_POST['idCaja'],
                                                                                  $_POST['imagen'])); break;         
            
            
     /*   case 'saveProductoModArchivo':  
        echo saveProductoModArchivo(json_decode($_POST["idProd"],
                                                $_POST['imagen']));
        break;         
       */     
            
  case 'saveProductoMod': echo saveProductoMod(json_decode($_REQUEST['objProdutos'])); break;        
            
         
          case 'listarOfertasProductosDescuento': echo json_encode(listarOfertasProductosDescuento()); break;         
            
     
  case 'saveProductoModFoto': echo saveProductoModFoto(json_decode($_REQUEST['objProdutos'])); break;         
            
  case 'saveProductoModFotoTransf': echo saveProductoModFotoTransf(json_decode($_REQUEST['objProdutos'])); break;         
        
            
       
   case 'traspasoPedidoTienda': echo traspasoPedidoTienda(json_decode($_REQUEST['arrDeta'])); break;    
          
   case 'listarProductosCajas': echo json_encode(listarProductosCajas($_REQUEST['idProd'])); break;        
          
      case 'listarProductosCajasTienda': echo json_encode(listarProductosCajasTienda($_REQUEST['idProd'])); break;        
         
            
            
   case 'ingresarVenta': echo ingresarVenta(json_decode($_REQUEST['arrVenta'])); break;   
            

            
   case 'generarBoletaVentas':  echo generarBoletaVentas(json_decode($_REQUEST["arrVenta"]), $_REQUEST["descuentoPunto"], $_REQUEST['rutPunto']); break;
            
          
   case 'listarVentasGeneradas': echo json_encode(listarVentasGeneradas($_REQUEST['desde'],
                                                                        $_REQUEST['hasta'])); break;
            
          case 'listarVentasGeneradasTiendas': echo json_encode(listarVentasGeneradasTiendas($_REQUEST['desde'],
                                                                                             $_REQUEST['hasta'],
                                                                                             $_REQUEST['tienda'],
                                                                                             $_REQUEST['usuario']
                                                                                            )); break;
            
          
            
          
          
   case 'listaDetalleVentas': echo json_encode(listaDetalleVentas($_REQUEST['idVenta'])); break;
            
            
   case 'listaDetalleTiendas': echo json_encode(listaDetalleTiendas($_REQUEST['idVenta'],
                                                                        $_REQUEST['tienda'])); break;
            
            
      
   case 'modificarPedidoTransp': echo modificarPedidoTransp(json_decode($_REQUEST['arrEstado'])); break;              
          
   case 'sumaTotalVentas': echo json_encode(sumaTotalVentas($_REQUEST['fecha'])); break;
          
          
   case 'insertarTotalVentas': echo json_encode(insertarTotalVentas($_REQUEST['fecha'],
                                                                      $_REQUEST['tienda'],
                                                                      $_REQUEST['usuario'],
                                                                      $_REQUEST['valor'])); break;
    
   case 'listarSalidasProductos': echo json_encode(listarSalidasProductos($_REQUEST['idProd'])); break;                    
            
   case 'guardarClientePrecio': echo guardarClientePrecio(json_decode($_REQUEST['arrClie'])); break;            
            
   case 'tipoProductoPrecio': echo json_encode(tipoProductoPrecio()); break;
        
   case 'marcaProductoPrecio': echo json_encode(marcaProductoPrecio($_REQUEST['id'])); break;
            
   case 'modObsDesp': echo json_encode(modObsDesp($_REQUEST['obs'],
                                                 $_REQUEST['fecha'],
                                                 $_REQUEST['id'],
									             $_REQUEST['jornada'],
                                                 $_REQUEST['tipoEntrega'])
									  ); break;       
            
   case 'cobradoVendedor': echo json_encode(cobradoVendedor($_REQUEST['cobrado'],                                                                      
                                                            $_REQUEST['id'])); break;      
            
   case 'generarProductosPDF':  
                echo generarProductosPDF(json_decode($_POST["productos"]));break;               

   case 'generarCobroPDF':  
                 echo generarCobroPDF(json_decode($_POST["productos"])); break;         

   case 'generarInventarioPDF':  
                 echo generarInventarioPDF(json_decode($_POST["productos"]));break;   
            
       
   case 'sumaTotalPorMes': echo json_encode(sumaTotalPorMes()); break;   
   
   case 'sumaTotalPorMesFrancisca': echo json_encode(sumaTotalPorMesFrancisca()); break;            
   
   case 'sumaTotalPorMesBlanca': echo json_encode(sumaTotalPorMesBlanca()); break;            
   
   case 'sumaTotalPorMesJuan': echo json_encode(sumaTotalPorMesJuan()); break;   
			
   case 'sumaTotalPorMesDiego': echo json_encode(sumaTotalPorMesDiego()); break;   

   case 'sumaTotalPorMesAndres': echo json_encode(sumaTotalPorMesAndres()); break;   

   case 'sumaTotalPorMesJorge': echo json_encode(sumaTotalPorMesJorge()); break;   
            
   case 'transporteEstado': echo json_encode(transporteEstado()); break;   
			
	         
   case 'sumaTotalPorMesUsuario': echo json_encode(sumaTotalPorMesUsuario($_REQUEST['idUsuario'])); break;            

    
               case 'sumaTotalPorSemanasRango': echo json_encode(sumaTotalPorSemanasRango(($_REQUEST['idUsuario']),
                                                                                          ($_REQUEST['desde']),
                                                                                          ($_REQUEST['hasta']))); break; 
            

   case 'sumaTotalPorSemRangoGene': echo json_encode(sumaTotalPorSemRangoGene()); break;            

   case 'tiendas': echo json_encode(tiendas()); break;            
            
   case 'sumaTotalPorMesCobro': echo json_encode(sumaTotalPorMesCobro()); break;            

   case 'consultarProdEnStock': echo consultarProdEnStock(json_decode($_REQUEST['arrProd'])); break;         
            
   case 'consulEstadoConfig': echo consulEstadoConfig(); break;     
            
   case 'updateConf': echo json_encode(updateConf($_REQUEST['id'],                                                                      
                                                  $_REQUEST['activo'])); break;                
            
   case 'agregarProdListado': echo agregarProdListado(json_decode($_REQUEST['arrayClieDet']),
                                                                 ($_REQUEST['accionProd'])); break; 
            
   case 'eliminarProdList': echo json_encode(eliminarProdList($_REQUEST['pedido'],                                                                      
                                                              $_REQUEST['producto'])); break;            

   case 'updateEstadoDepacho': echo json_encode(updateEstadoDepacho($_REQUEST['pedido'])); break; 
         
   case 'consultarPedido': echo consultarPedido(json_decode($_REQUEST['pedido'])); break;            
      
   case 'listUsuarioSeguimiento': echo json_encode(listUsuarioSeguimiento(($_REQUEST['id_usua']),
                                                                          ($_REQUEST['desde']),
                                                                          ($_REQUEST['hasta']))); break; 
            
   case 'updateInforme': echo json_encode(updateInforme(($_REQUEST['cliente']),
                                                        ($_REQUEST['nuevo']),
                                                        ($_REQUEST['idConf']))); break; 
           
   case 'arrayUpdateInforme': echo arrayUpdateInforme(json_decode($_REQUEST['arrayInforme'])); break; 
          
   case 'listarClientesProd': echo json_encode(listarClientesProd(($_REQUEST['id']),
                                                                  ($_REQUEST['desde']),
                                                                  ($_REQUEST['hasta']))); break;         
            
   case 'listarTalonarios': echo json_encode(listarTalonarios($_REQUEST['id_talonario'],
                                                              $_REQUEST['desde'],
                                                              $_REQUEST['hasta'],
															  $_REQUEST['id_estado'],
															  $_REQUEST['numDoc'])); break; 
            
   case 'consultarFactura': echo json_encode(consultarFactura($_REQUEST['id_talonario'],
                                                              $_REQUEST['id_prove'])); break;        
            
   case 'consulFact': echo json_encode(consulFact($_REQUEST['id_talonario'],
                                                  $_REQUEST['id_prove'])); break;              
            
   case 'guardarTalonario': echo guardarTalonario(json_decode($_REQUEST['objFactCabe']),
                                                  json_decode($_REQUEST['objFactDet']),                                               json_decode($_REQUEST['objFactDetAux'])); break;          
            
   case 'listarTalonariosFact': echo json_encode(listarTalonariosFact($_REQUEST['id_talonario'])); break;   
      
   case 'registroTalonario': echo json_encode(registroTalonario()); break;          
   
   case 'consultarInventarioIng': echo json_encode(consultarInventarioIng($_REQUEST['id_prod'])); break; 
            
            
      case 'consultarInventarioIngCaja': echo json_encode(consultarInventarioIngCaja($_REQUEST['id_prod'])); break;         
            
   
   case 'updateObservacionClie': echo json_encode(updateObservacionClie($_REQUEST['idObs'], 
                                                                       $_REQUEST['idClie'])); break;
            			
	case 'cantidadParticular': echo json_encode(cantidadParticular($_REQUEST['idUsuario'])); break;            
	case 'cantidadMascotero': echo json_encode(cantidadMascotero($_REQUEST['idUsuario'])); break;            
	case 'cantidadAlmacen': echo json_encode(cantidadAlmacen($_REQUEST['idUsuario'])); break;            
	case 'cantidadVeterinaria': echo json_encode(cantidadVeterinaria($_REQUEST['idUsuario'])); break;    	

    case 'updatePrecioFactura': echo updatePrecioFactura(json_decode($_REQUEST['arrProd'])); break;  			
			
	case 'activarDesactivarFact': echo json_encode(activarDesactivarFact($_REQUEST['idIng'], 
                                                                       $_REQUEST['activo'])); break;
    case 'listarPorcentajeOferta': echo json_encode(listarPorcentajeOferta()); break;  
        
    case 'updatePorcentaje': echo json_encode(updatePorcentaje($_REQUEST['idProd'], 
                                                               $_REQUEST['idPorcentaje'])); break;  
    case 'listarProductosOfertas': echo json_encode(listarProductosOfertas()); break;
    case 'listarProductosSinStock': echo json_encode(listarProductosSinStock()); break;

    case 'guardarObsClie': echo json_encode(guardarObsClie($_REQUEST['idObs'], 
                                                           $_REQUEST['idClie'])); break;
            
            
      case 'guardarObsInvt': echo json_encode(guardarObsInvt($_REQUEST['idObs'], 
                                                             $_REQUEST['idClie'])); break;        
   
    
          case 'guardarObsCredito': echo json_encode(guardarObsCredito($_REQUEST['idCred'], 
                                                                       $_REQUEST['obsCredito'])); break;        
            			        
            
    
   case 'sumaTotalPorMesClienteAnterior': echo json_encode(sumaTotalPorMesClienteAnterior($_REQUEST['idCliente'])); break;            
   case 'sumaTotalPorMesClienteActual': echo json_encode(sumaTotalPorMesClienteActual($_REQUEST['idCliente'])); break;            

    
            
   case 'listarVentaPorTipo': echo json_encode(listarVentaPorTipo($_REQUEST['idUsua'])); break;   
         
   case 'listarVentaVendedores': echo json_encode(listarVentaVendedores($_REQUEST['mesSel'], 
                                                                   $_REQUEST['anoSel'])); break; 
            
    case 'sumaCantidadOferta': echo json_encode(sumaCantidadOferta($_REQUEST['idUsua'])); break;  
   case 'listarVentaActualUsua': echo json_encode(listarVentaActualUsua()); break;   

         
      case 'sumaCantidadOfertaTotal': echo json_encode(sumaCantidadOfertaTotal()); break;   
            
    case 'insertarProductoNuevo': echo json_encode(insertarProductoNuevo($_REQUEST['codProd'], 
                                                                   $_REQUEST['nombProd'],
                                                                   $_REQUEST['pesProd'],
                                                                   $_REQUEST['bodProd'],
                                                                   $_REQUEST['bodega'],
                                                                   $_REQUEST['pesosProd'],
                                                                   $_REQUEST['marcProd'],
                                                                   $_REQUEST['catProd'],
                                                                   $_REQUEST['claProd'],
                                                                    $_REQUEST['idProveedor'],
                                                                    
                                        $_REQUEST['foto']
                                                                  )); break;        
    
     case 'listarClasificacionProd': echo json_encode(listarClasificacionProd()); break;
            
            
            
     case 'insertarLog': echo json_encode(insertarLog($_REQUEST['id_pedido'], 
                                                             $_REQUEST['accion'],
                                                             $_REQUEST['idUsuario']
                                                  )); break;
     
     case 'listarAnteriorPorcentaje': echo json_encode(listarAnteriorPorcentaje()); break;
     case 'listarActualPorcentaje': echo json_encode(listarActualPorcentaje()); break;
      
     case 'listarPorcentajePorUsuario': echo json_encode(listarPorcentajePorUsuario($_REQUEST['idUsua'])); break;  
     
            
     case 'anualVentaUsuario': echo json_encode(anualVentaUsuario()); break;  
         
     case 'listProductosVentas': echo json_encode(listProductosVentas($_REQUEST['idUsuario'])); break;  
       
   	case 'listaInformeTransp': echo json_encode(listaInformeTransp($_REQUEST['desde'],
                                                                           $_REQUEST['hasta'],
                                                                           $_REQUEST['id_inf'])); break;       
            
     case 'verDetalleInformeTransp': echo json_encode(verDetalleInformeTransp($_REQUEST['idPedido'])); break;  
     
            
     case 'saveInformeTransp': echo saveInformeTransp(json_decode($_REQUEST['arrPed']
                                                                 
                                                                 )); break;       
     /*      	case 'saveInformeTransp': echo json_encode(saveInformeTransp($_REQUEST['arrPed'],
                                                                           $_REQUEST['arrInf'])); break;  */       
            
            
          
            
         case 'saveInformeTranspBodega': echo saveInformeTranspBodega(json_decode($_REQUEST['arrPed'])); break;         
        
            
            

             case 'guardarDetallePago': echo json_encode(guardarDetallePago( $_REQUEST['arrPed'],
                                                                                    $_REQUEST['idInf'])); break;   
            
            
            
         case 'eliminarPedidoTrans': echo json_encode(eliminarPedidoTrans($_REQUEST['idPedido'])); break;  
            
            
             case 'eliminarFolioPedido': echo json_encode(eliminarFolioPedido($_REQUEST['idPedido'],
                                                                              $_REQUEST['idFolio'],
                                                                              $_REQUEST['observacion'],
                                                                              $_REQUEST['id_usuario']
                                                                             )); break;  
        
   
   //  case 'consultarEstadoInforTrans': echo json_encode(consultarEstadoInforTrans($_REQUEST['idPedido'])); break;  
            
    case 'validarRutaInforme': echo json_encode(validarRutaInforme($_REQUEST['idInforme'])); break;  
            
    case 'validarReciboCobranza': echo json_encode(validarReciboCobranza($_REQUEST['idInforme'])); break;  
           
            
            
 	
    case 'insertarInformTransp': echo json_encode(insertarInformTransp($_REQUEST['idInforme'],
                                                                           $_REQUEST['idPedido'])); break;       
            
        
    case 'listProdListaVenta': echo json_encode(listProdListaVenta($_REQUEST['idVendedor'],
                                                                   $_REQUEST['idTipList'])); break;  
     
    case 'listProdListaVentaTotal': echo json_encode(listProdListaVentaTotal($_REQUEST['idTipList'])); break;  
            
            
     case 'modificarEstadoMasivoTransp': echo modificarEstadoMasivoTransp(json_decode($_REQUEST['arrEstado'])); break;         
            
           
    case 'anularPedidoInformeVenta': echo json_encode(anularPedidoInformeVenta($_REQUEST['idInforme'])); break;  
            
       case 'anularVentaTienda': echo json_encode(anularVentaTienda($_REQUEST['idInforme'],
                                                                    $_REQUEST['observacion'],
                                                                    $_REQUEST['tienda'])); break;  
         
            
            

            /*    case 'enviarCorreoPedido':  
        echo enviarCorreoPedido((json_decode($_POST["detalle"]),$_POST["detalle"])); 
        break;
            */

    case 'enviarCorreoPedido':         
    echo enviarCorreoPedido(              $_REQUEST["correoUsua"],                                            
                              json_decode($_REQUEST["detalle"]),
                                          $_REQUEST["transp"],   
                                          $_REQUEST["transpMsj"],
                                          $_REQUEST["transpEst"]
                              ); 
        	break;    
            
            
     case 'listarLogPedido': echo json_encode(listarLogPedido(($_REQUEST["idPedido"]) )); break;             
         
     case 'mostrarImagen': echo json_encode(mostrarImagen(($_REQUEST["idProd"]) )); break;    
            
         case 'mostrarImagenTransf': echo json_encode(mostrarImagenTransf(($_REQUEST["idPed"]) )); break;    
            
           case 'borrarImagenTransf': echo json_encode(borrarImagenTransf(($_REQUEST["idPed"]) )); break;    
          
            
            
     
        case 'listCantPedSemana': echo json_encode(listCantPedSemana( $_REQUEST['desde'],
                                                                                    $_REQUEST['hasta'])); break;   
        
          
      case 'eliminarClientesSemana': echo json_encode(eliminarClientesSemana($_REQUEST['nRuta'])); break;  
            
      case 'insertarClientesSemana': echo json_encode(insertarClientesSemana( $_REQUEST['idUsua'],
                                                                                    $_REQUEST['idClie'],
                                                                                    $_REQUEST['idDia'])); break;   
            
            
            
            
    case 'listarClieSemanaPdf': echo json_encode(listarClieSemanaPdf( $_REQUEST['idUsua'],
                                                                                            $_REQUEST['idDia'])); break;       
            
            
    case 'listarClientesRuta': echo json_encode(listarClientesRuta( $_REQUEST['idUsua'])); break;             

    case 'listarClientesSemana': echo json_encode(listarClientesSemana( $_REQUEST['idUsua'])); break;          
            
        case 'listarClientesGeo': echo json_encode(listarClientesGeo()); break;          
        
  
            
         
    case 'listCantPedMensual': echo json_encode(listCantPedMensual( $_REQUEST['idUsua'],
                                                                        $_REQUEST['idTipo'])); break;             
            
            
            
          
            
            
    case 'buscarProductoRapido': echo json_encode(buscarProductoRapido($_REQUEST['nombr'],
                                                                      $_REQUEST['tipBus'])); break;   
            
    case 'precioUsuaDesc': echo json_encode( precioUsuaDesc(($_REQUEST['idUsua']) )); break;	
            
            
            
            
    case 'imprimirTermica':  
        echo imprimirTermica(json_decode($_POST["detalle"])); 
        break;     
     
       
            
            
     case 'imprimirTermicaBoletaElect':  
      echo imprimirTermicaBoletaElect(json_decode($_POST["detalle"])); 
     break;  
            
            
         case 'imprimirTermicaObservacionInformeTransporte':  
      echo imprimirTermicaObservacionInformeTransporte(json_decode($_POST["detalle"])); 
     break;          
     
                   
      case 'imprimirTermicaFacturas': echo json_encode(imprimirTermicaFacturas()); break;           
            
     case 'imprimirTermicaBoletaVentas':  
        echo imprimirTermicaBoletaVentas(json_decode($_POST["detalle"])); 
     break;                
                   
            
            
     case 'verDetalleObsPed': echo json_encode(verDetalleObsPed( $_REQUEST['idEstado'],
                                                                 $_REQUEST['idProd'])); break; 
            
            
     case 'listarNotaTotalPedido': echo json_encode(listarNotaTotalPedido( $_REQUEST['idCliente'],
                                                                           $_REQUEST['nPedido'])); break;      

            
     case 'listarNotaTotalFolio': echo json_encode(listarNotaTotalFolio( $_REQUEST['idCliente'],
                                                                         $_REQUEST['nFolio'])); break;      
            
     case 'listarClientesRojo': echo json_encode(listarClientesRojo($_REQUEST['idUsua'])); break;            
          
    
        
            
       
      case 'listarInicioCaja': echo json_encode(listarInicioCaja($_REQUEST['idUsua'])); break;                
            
        case 'insertarInicioCaja': echo json_encode(insertarInicioCaja($_REQUEST['idUsua'])); break;        
            
 
        case 'imprimirTermicaInicioCaja':  echo imprimirTermicaInicioCaja(json_decode($_POST["idCaja"])); 
        break;                
                   
          case 'updateCajaFin': echo json_encode(updateCajaFin($_REQUEST['idUsua'], $_POST['data'], $_REQUEST['obserCaja'])); break;        
            
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
             
            
            	case 'insertarReciboUsuario': echo json_encode(insertarReciboUsuario($_REQUEST['idClie'], 
                                                                   $_REQUEST['fCobro'],
                                                                   $_REQUEST['nRecibo'],
                                                                   $_REQUEST['tipPago'],
                                                                   $_REQUEST['totalRecibo'],
                                                                   $_REQUEST['totalAbono'],
                                                                   $_REQUEST['observacion'],  
                                                                                     $_REQUEST['idUsuario'], 
                                                                   json_decode($_REQUEST["objDeta"])
                                                                  )); break;
                     
           
        
            
     case 'imprimirTermicaFinCaja': 
            echo json_encode(imprimirTermicaFinCaja($_REQUEST['idCaja'],
                                              $_REQUEST['efectivo'],
                                              $_REQUEST['debito'],
                                              $_REQUEST['transferencia'],
                                              $_REQUEST['descuento'],
                                              $_REQUEST['totalVenta'],
                                              $_REQUEST['gastoLocal'],
                                              json_decode($_REQUEST['detalle'])
                      

                                             )); break;  
     break;   
   
            
            
            
            
            
            
            
            
      case 'listarRecibos': echo json_encode(listarRecibos($_REQUEST['tipBusq'], 
                                                                 $_REQUEST['folio'],
                                                                 $_REQUEST['nombre'],
                                                                 $_REQUEST['fIngreso'],
                                                                 $_REQUEST['fEntrega'],
                                                                 $_REQUEST['fIngresoHasta'],
                                                                 $_REQUEST['fEntregaHasta'] ,
                                                                 $_REQUEST['tipoUsuario'],
                                                                 $_REQUEST['idUsuario']
                                                                  )); break;    
            
     case 'listarRecibosAplicacion': echo json_encode(listarRecibosAplicacion(
                                                                 $_REQUEST['fRecibo'],
                                                                 $_REQUEST['andrID']
                                                                  )); break;           
            
      
      case 'modificarEstadoClienteCobro': echo json_encode(modificarEstadoClienteCobro
                                                                ($_REQUEST['idPedido'], 
                                                                 $_REQUEST['documento'],
                                                                 $_REQUEST['estadoCobro']    
                                                                    )); break;  
            
            
            
          case 'actualizarDetalleCobranza': echo json_encode(actualizarDetalleCobranza
                                                                ($_REQUEST['idCli'], 
                                                                 $_REQUEST['actCobra'],
                                                                 $_REQUEST['actLimit'],
                                                                 $_REQUEST['obsCobra'],
                                                                 $_REQUEST['limtCompra']
                                                                    )); break;  
            
                
            
            
            
            
      case 'listarRecibosClientes': echo json_encode(listarRecibosClientes($_REQUEST['idPedido'])); break;    
      case 'listarDetalleRecibo': echo json_encode(listarDetalleRecibo($_REQUEST['idRecibo'])); break;  
            
            
      case 'consultarNuboxPedido': echo json_encode(consultarNuboxPedido($_REQUEST['idPedido'])); break;    
     

           case 'cambiaEstadoTipoDoc': echo json_encode(cambiaEstadoTipoDoc($_REQUEST['idPedido'],
                                                                            $_REQUEST['idTipoDoc'])); break;    
       
             case 'listarCobranzaDetaCliente': echo json_encode(listarCobranzaDetaCliente($_REQUEST['idCliente'])); break;    
     
            
            
            
      case 'updateEstadoProductoAct': echo json_encode(updateEstadoProductoAct( $_REQUEST['idProd'],
                                                                        $_REQUEST['activo'])); break;     
            
            
    case 'updateEstadoProductoActTienda': echo json_encode(updateEstadoProductoActTienda( $_REQUEST['idProd'],
                                                                        $_REQUEST['activo'])); break;              
      
      case 'traspasoProductosTienda': echo json_encode(traspasoProductosTienda( $_REQUEST['idPedido'],
                                                                                    $_REQUEST['id_tienda'])); break;         
      
      case 'insertarGrupoProd': echo json_encode(insertarGrupoProd($_REQUEST['idProd'],
                                                                         $_REQUEST['cantIng'],
                                                                         $_REQUEST['cantDesc'],
                                                                         $_REQUEST['idProdDes'])); break;         
            
           /********Transporte***********/ 
            
      case 'insertarSolicitudMantencion': echo json_encode(insertarSolicitudMantencion($_REQUEST['nombre'], 
                                                                   $_REQUEST['observacion'],
                                                                   $_REQUEST['tipo_solicitud'],
                                                                   $_REQUEST['f_entrega'],
                                                                   $_REQUEST['telefono'],
                                                                   $_REQUEST['idTransp'],
                                                                   $_REQUEST['kilometraje'],                   
                                                                     json_decode($_REQUEST["objDeta"])
                                                                  )); break;
            
     case 'modificarSolicitudMantencion': echo json_encode(modificarSolicitudMantencion($_REQUEST['nombre'], 
                                                                   $_REQUEST['observacion'],
                                                                   $_REQUEST['tipo_solicitud'],
                                                                   $_REQUEST['f_entrega'],
                                                                   $_REQUEST['telefono'],
                                                                   $_REQUEST['idTransp'],   
                                                                   $_REQUEST['idPed'],   
                                                                   $_REQUEST['kilometraje'],    
                                                                     json_decode($_REQUEST["objDeta"])
                                                                  )); break;
                  

            
     case 'listarSolicitudesMantencion': echo json_encode(listarSolicitudesMantencion($_REQUEST['tipBusq'], 
                                                                   $_REQUEST['folio'],
                                                                   $_REQUEST['nombre'],
                                                                   $_REQUEST['fIngreso'],
                                                                   $_REQUEST['fEntrega'],
                                                                   $_REQUEST['fIngresoHasta'],
                                                                   $_REQUEST['fEntregaHasta'],
                                                                   $_REQUEST['patente']                      
                                                                    )); break;
            
    case 'listarDetallePedidoMantencion': echo json_encode(listarDetallePedidoMantencion($_REQUEST['idPedido'])); break;
        
            
    case 'getDataTipoMantenciones': echo json_encode(getDataTipoMantenciones()); break;
       
    case 'getTipoSolicitudMantenciones': echo json_encode(getTipoSolicitudMantenciones()); break;
   
            
     
            
    case 'generarMantencionPDF':echo generarMantencionPDF(json_decode($_POST["detalle"])); break;      
            
            
    case 'getDataMantencionTransporte': echo json_encode(getDataMantencionTransporte()); break;

            
        /***********************************/
            
     case 'modificarEstadoTransporte': echo json_encode(modificarEstadoTransporte($_REQUEST['idEstado'],
                                                                                  $_REQUEST['idSol'])); break;     
                        
       case 'eliminarReciboAplicacion': echo json_encode(eliminarReciboAplicacion($_REQUEST['idRicibo'])); break;     
            
            
        case 'listadoInforTransporteAsig': echo json_encode(listadoInforTransporteAsig( $_REQUEST['id_transporte'],
                                                                                       $_REQUEST['desde'],
                                                                        $_REQUEST['hasta'])); break;      
            
            
        case 'imprimirTermicaCaja':  
        echo imprimirTermicaCaja(json_decode($_POST["detalle"])); 
        break;     
           
   
            
                   case 'imprimirTermicaCajaListadoProductos': echo json_encode(imprimirTermicaCajaListadoProductos( $_REQUEST['idUsuario']
                                                                                                                   )); break;      
            
            
           case 'imprimirTermicaReciboDinero':  
        echo imprimirTermicaReciboDinero(json_decode($_POST["detalle"])); 
        break;      
                 
            
            
            
                 case 'generaDTEBoletaCert':  
        	echo generaDTEBoletaCert(  
                              $_REQUEST["FchEmision"], 
                              $_REQUEST["Afecto"],
                              $_REQUEST["DevID"],
                              json_decode($_REQUEST["Detalles"], true) 
                          ); 
                          
        	break; 
            
            /*
  
            
                 case 'subirImagenRecibo':  
        	echo subirImagenRecibo(  
                              $_REQUEST["idRecibo"],
                              json_decode($_REQUEST["Detalles"], true) 
                          ); 
                          
        	break; 
            */
            
                     
     case 'subirImagenRecibo': echo json_encode(subirImagenRecibo($_REQUEST['idRecibos'],
                                                                                  $_REQUEST['imagen'])); break;  
            
            
            
                    
     case 'subirImagenReciboFirma': echo json_encode(subirImagenReciboFirma($_REQUEST['idRecibos'],
                                                                                  $_REQUEST['imagen'])); break;        
            
            
            
            
         case 'listarCierreCajas': echo json_encode(listarCierreCajas( $_REQUEST['fInicio'], 
                                                                       $_REQUEST['fFin']
                                                                                       
                                                                    )); break;        
            
             case 'listarCierreCajasMultiple': echo json_encode(listarCierreCajasMultiple( $_REQUEST['fInicio'], 
                                                                       $_REQUEST['fFin']
                                                                                       
                                                                    )); break;          
           
         case 'actualizarTotalInfoTransp': echo json_encode(actualizarTotalInfoTransp( $_REQUEST['modEfectivo'], 
                                                                                        $_REQUEST['modDebito'],
                                                                                      $_REQUEST['modTransferencia'],
                                                                                      $_REQUEST['modCajaVecina'],
                                                                                      $_REQUEST['idTransporte']
                                                                                       
                                                                    )); break;      
            
        /*
            
                 case 'testboleta':  
        	echo testboleta(  
                              $_REQUEST["FchEmision"], 
                              $_REQUEST["Afecto"],
                              $_REQUEST["DevID"],
                              json_decode($_REQUEST["Detalles"], true) 
                          ); 
                          
        	break;   */
            
            
        case 'boletaTiendaVentasPDF': echo json_encode(boletaTiendaVentasPDF( $_REQUEST['idPedido'], $_REQUEST['imprimir'] )); break;      
              
        case 'ticketsRetiroBodega': echo json_encode(ticketsRetiroBodega( $_REQUEST['idPedido'] )); break;      
              
        case 'generarBoletaDesdeListadoPedidos': echo json_encode(generarBoletaDesdeListadoPedidos( $_REQUEST['idPedido'] )); break;      

        case 'subirArchivoFolio': echo json_encode(subirArchivoFolio($_POST['idFolio'],
                                                                                  $_POST['imagen'])); break; 
        //Busqueda Estado Nubox
        case 'updateEstadoCompraSII': echo updateEstadoCompraSII($_REQUEST['folio'], $_REQUEST['rut'], $_REQUEST['id_factura']); break;
        
        case 'obtienePDFCompraSII': echo obtienePDFCompraSII($_REQUEST['folio'], $_REQUEST['rut']); break;
        
        case 'generarNotaCreditoSII': echo generarNotaCreditoSII($_POST['data']); break;

        case 'listarMarcasProductos': echo json_encode(listarMarcasProductos()); break;

        case 'listarDescuentos': echo json_encode(listarDescuentos()); break;

        case 'insertarMarcaProducto': echo json_encode(insertarMarcaProducto()); break;

        case 'eliminarMarcaProducto': echo json_encode(eliminarMarcaProducto( $_REQUEST['codMarca'])); break;
        
            
        case 'listEscalaProducto': echo json_encode(listEscalaProducto(
                                                                        $_REQUEST['idMarca'], 
                                                                        $_REQUEST['idCategoria']
                                                                        )); break;
  

        case 'consultarRelacionProductoMarca': echo json_encode(consultarRelacionProductoMarca(
                                                                            $_REQUEST['codMarca'], 
                                                                            $_REQUEST['codCategoria']
                                                                            )); break;

                                                                        


        case 'insertarEscalaProd': echo json_encode(insertarEscalaProd( $_REQUEST['idProd'], 
                                                                        $_REQUEST['idMarca'],
                                                                        $_REQUEST['idCategoria']
                                                                                       
                                                                    )); break;      
            
            
                case 'borrarProdEscala': echo json_encode(borrarProdEscala( $_REQUEST['idEscala']                                                                                       
                                                                    )); break;      
            
             
        case 'actualizarProdEscala': echo json_encode(actualizarProdEscala( $_REQUEST['idEscala'], 
                                                                        $_REQUEST['pescala'],
                                                                        $_REQUEST['descripEsc'],
                                                                           $_REQUEST['catMinima']
                                                                                       
                                                                    )); break;      
            
        case 'actualizarMarca': echo json_encode(actualizarMarca( $_REQUEST['codMarca'], 
                                                                    $_REQUEST['nombreMarca'],
                                                                    $_REQUEST['codCategoria'],
                                                                       $_REQUEST['activoMarca']
                                                                                   
                                                                )); break;      
        
                                                            

        case 'syncCierreCaja': echo syncCierreCaja($_POST['data']); break;
        case 'listarCierreCajaCentralizada': echo json_encode(listarCierreCajaCentralizada( $_REQUEST['fInicio'], 
                                                                       $_REQUEST['fFin']
                                                                                       
                                                                    )); break; 
            
                case 'listarMes': echo json_encode(listarMes()); break;

            
                            case 'listarAno': echo json_encode(listarAno()); break;
    }

function limpiarRut($input) {
    return preg_replace('/[^k0-9]/i', '', $input);
}

function validarRut($input) {
    $rut = limpiarRut($input);
    $dv  = substr($rut, -1);
    $numero = substr($rut, 0, strlen($rut)-1);
    $i = 2;
    $suma = 0;
    foreach(array_reverse(str_split($numero)) as $v)
    {
        if($i==8)
            $i = 2;

        $suma += $v * $i;
        ++$i;
    }

    $dvr = 11 - ($suma % 11);

    if($dvr == 11)
        $dvr = 0;
    if($dvr == 10)
        $dvr = 'K';

    if($dvr == strtoupper($dv)) {
        return true;
    } else {
        return false;
    }
}

function obtenerPuntos($rut) {
    global $token_origen, $url_services;
    $valido = validarRut($rut);
    if($valido) {
        $curl = curl_init();

        $rut = limpiarRut($rut);
        $numero = substr($rut, 0, strlen($rut)-1);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url_services/puntos/$numero/movimientos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token_origen"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    } else {
        return "{\"data\":0}";
    }
}


function registrarPuntos($datos) {
    global $token_origen, $url_services;
    $valido = validarRut($datos->rut);
    if($valido) {
        $response = "";
        $curl = curl_init();

        $rut = limpiarRut($datos->rut);
        $numero = substr($rut, 0, strlen($rut)-1);

        if ($datos->ocupaPuntos) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "$url_services/puntos/$numero/movimientos",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "{\"tipo\": \"OCUPA\",\"valor\": $datos->valor,\"idPedido\": $datos->idPedido}",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token_origen",
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
        }

        $total = $datos->total / 100;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url_services/puntos/$numero/movimientos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{\"tipo\": \"ACUMULA\",\"valor\": $total,\"idPedido\": $datos->idPedido}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token_origen",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    } else {
        return "{\"data\":0}";
    }
}

function eliminarPuntos($idPedido = 0) {
    global $token_origen, $url_services;
    if ($idPedido > 0) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url_services/puntos/$idPedido/movimientos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token_origen"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    } else {
        return "{\"data\":0}";
    }
}

function consultarDescTiendaProd(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarDescTiendaProd();
} 


function listTiendaPedidoPagina(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listTiendaPedidoPagina();
} 



function listTiendaDetallePagina($id_orden){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listTiendaDetallePagina($id_orden);
} 




function listarMes(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarMes();
} 


function listarAno(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarAno();
} 





function actualizarTotalInfoTransp($modEfectivo, $modDebito, $modTransferencia, $modCajaVecina, $idTransporte){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
return $instancia->actualizarTotalInfoTransp($modEfectivo, $modDebito, $modTransferencia, $modCajaVecina, $idTransporte);
}

function listarCierreCajas($fInicio,$fFin){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarCierreCajas($fInicio,$fFin);
} 
function listarCierreCajasMultiple($fInicio,$fFin){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarCierreCajasMultiple($fInicio,$fFin);
} 



function listarCierreCajaCentralizada($fInicio,$fFin){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->listarCierreCajaCentralizada($fInicio,$fFin);
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



function listadoInforTransporteAsig($id_transporte,$desde,$hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listadoInforTransporteAsig($id_transporte,$desde,$hasta);
} 


function eliminarReciboAplicacion($idRicibo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarReciboAplicacion($idRicibo);
} 


function modificarEstadoTransporte($idEstado, $idSol){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarEstadoTransporte($idEstado, $idSol);
} 

function gerexpPDF($detalle){   
   
$file = "pdf/NotaPedidoPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));

$grid5 =$detalle[0]->idInforme;         
$grid4 =$detalle[0]->pesoProd;     
$grid3 =$detalle[0]->pedidos; 
$grid2 =$detalle[0]->fecha; 
$grid1 = '';
	

			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.	
                              '<td align="center">'.$de->nombre.'</td>'.
 							  '<td align="center">'.$de->cantidad.'</td>'.
							  '<td>('.$de->codProducto.') - '.$de->nombreProd.'</td>'.
							  '<td align="center">'.$de->bodega.'</td>'.
						 '</tr>';
				
			}    
    
    
 	$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
   
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("certificado.pdf");
    
return $dompdf;

}






function gerexpPDFTienda($detalle){   
   
$file = "pdf/NotaPedidoTiendaPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
$grid2 =$detalle[0]->fecha; 
$grid1 = '';
	

			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.	
                              '<td align="center">'.$de->nombre.'</td>'.
 							  '<td align="center">'.$de->cantidad.'</td>'.
							  '<td>('.$de->codProducto.') - '.$de->nombreProd.'</td>'.
							  '<td align="center">'.$de->bodega.'</td>'.
						 '</tr>';
				
			}    
    
    
 	$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
   
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("certificado.pdf");
    
return $dompdf;

}



function gerexpPDFVentas($detalle){   
   
$file = "pdf/NotaPedidoTiendaVentasPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
$grid2 =$detalle[0]->fecha; 
$grid1 = '';
	

			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.	
                              '<td align="center">'.$de->nombre.'</td>'.
 							  '<td align="center">'.$de->cantidad.'</td>'.
							  '<td>('.$de->codProducto.') - '.$de->nombreProd.'</td>'.
							  '<td align="center">'.$de->bodega.'</td>'.
						 '</tr>';
				
			}    
    
    
 	$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
   
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("certificado.pdf");
    
return $dompdf;

}






function generarMantencionPDF($detalle){   
    
     
$file   = "pdf/MantencionTransportePDF.html";
$handle = fopen($file,"r");
$html   = fread($handle, filesize($file));
    
$grid2 =$detalle[0]->nombreMec;     
$grid3 =$detalle[0]->tipoSol; 
$grid4 =$detalle[0]->contacto; 
$grid5 =$detalle[0]->fechaEntrega; 
$grid6 =$detalle[0]->idMant;     
$grid1 = '';
	
					
			foreach ($detalle AS $de){	
					$grid1 .= '<tr  class="Estilo3">'.	
							  '<td>'.$de->detalleMant.'</td>'.
                              '<td align="center">'.$de->valor.'</td>'.
 							  '<td align="center">'.$de->nombreMant.'</td>'.							
						      '</tr>';			
			}    
    
    
  
    
 	$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
    $html = str_replace("#grid6#",$grid6,$html);

    
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter", "portrait");   
    
    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("mantencion.pdf");
    return $dompdf;

}



function getDataMantencionTransporte(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getDataMantencionTransporte();
} 
    
    
function modificarSolicitudMantencion($nombre, $observacion, $tipo_solicitud, $f_entrega, $telefono, $idTransp, $idPed,$kilometraje ,$objDeta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarSolicitudMantencion($nombre, $observacion, $tipo_solicitud, $f_entrega, $telefono, $idTransp, $idPed, $kilometraje,$objDeta);
}

function getTipoSolicitudMantenciones(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getTipoSolicitudMantenciones();
} 

function getDataTipoMantenciones(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getDataTipoMantenciones();
} 

function listarDetallePedidoMantencion($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDetallePedidoMantencion($idPedido);
} 


function listarSolicitudesMantencion($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $patente){
    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
return $instancia->listarSolicitudesMantencion($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $patente);
}



function insertarSolicitudMantencion($nombre, $observacion, $tipo_solicitud, $f_entrega, $telefono, $idTransp, $kilometraje, $objDeta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarSolicitudMantencion($nombre, $observacion, $tipo_solicitud, $f_entrega, $telefono, $idTransp, $kilometraje, $objDeta);
}


function insertarGrupoProd($idProd, $cantIng, $cantDesc, $idProdDes){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarGrupoProd($idProd, $cantIng, $cantDesc, $idProdDes);
}


function traspasoProductosTienda($idPedido, $id_tienda){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->traspasoProductosTienda($idPedido, $id_tienda);
}

function updateEstadoProductoAct($idProd, $activo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateEstadoProductoAct($idProd, $activo);
}

function updateEstadoProductoActTienda($idProd, $activo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateEstadoProductoActTienda($idProd, $activo);
}
    
    
function consultarNuboxPedido($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarNuboxPedido($idPedido);
}


function listarCobranzaDetaCliente($idCliente){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarCobranzaDetaCliente($idCliente);
}



function cambiaEstadoTipoDoc($idPedido, $idTipoDoc){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cambiaEstadoTipoDoc($idPedido, $idTipoDoc);
}




function listarRecibosClientes($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarRecibosClientes($idPedido);
}


function listarDetalleRecibo($idRecibo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDetalleRecibo($idRecibo);
}


function listarRecibos($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $tipoUsuario, $idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarRecibos($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $tipoUsuario, $idUsuario);
}


function listarRecibosAplicacion($fRecibo, $andrID){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarRecibosAplicacion($fRecibo, $andrID);
}














function updateCajaFin($idUsua, $data = null, $obserCaja = null){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateCajaFin($idUsua, $data, $obserCaja);
}


function listarInicioCaja($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarInicioCaja($idUsua);
}

function insertarInicioCaja($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarInicioCaja($idUsua);
}

function syncCierreCaja($data){
    global $dataBaseMasterServer,$dataBaseMasterUsername,$dataBaseMasterUserPassword,$dataBaseMasterName,$nombreSucursal;
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdPedido($dataBaseMasterServer,$dataBaseMasterUsername,$dataBaseMasterUserPassword,$dataBaseMasterName);
    return $instancia->syncCierreCaja($data, $nombreSucursal, $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
}

function listCanjeadosPendientesDispositivo($idDiv, $idProd){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listCanjeadosPendientesDispositivo($idDiv, $idProd);
}


function listProductosAplicacion($listarOpcion){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProductosAplicacion($listarOpcion);
}

function listProductosCompradosAplicacion($idDiv){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProductosCompradosAplicacion($idDiv);
}



function listComprasAplicacion($idAndroid){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listComprasAplicacion($idAndroid);
}



  function listarClientesRojo($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesRojo($idUsua);
	}



function verDetalleObsPed($idEstado, $idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->verDetalleObsPed($idEstado, $idProd);
}


function precioUsuaDesc($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->precioUsuaDesc($idUsua);
}

function buscarProductoRapido($nombr, $tipBus){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->buscarProductoRapido($nombr, $tipBus);
}

function listCantPedMensual($idUsua, $idTipo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listCantPedMensual($idUsua, $idTipo);
}


function listarClieSemanaPdf($idUsua, $idDia ){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClieSemanaPdf($idUsua, $idDia);
}



function insertarClientesSemana($idUsua, $idClie, $idDia ){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarClientesSemana($idUsua, $idClie, $idDia);
}


function eliminarClientesSemana($nRuta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarClientesSemana($nRuta);
}



function listarClientesRuta($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesRuta($idUsua);
}

function listarClientesSemana($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesSemana($idUsua);
}


function listarClientesGeo(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesGeo();
}

function listCantPedSemana($desde, $hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listCantPedSemana($desde, $hasta);
}


function mostrarImagen($idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->mostrarImagen($idProd);
}    

function mostrarImagenTransf($idPed){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->mostrarImagenTransf($idPed);
}    

function borrarImagenTransf($idPed){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->borrarImagenTransf($idPed);
}    



function listarLogPedido($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarLogPedido($idPedido);
}    


    
function modificarEstadoMasivoTransp($arrEstado){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarEstadoMasivoTransp($arrEstado);
}    
   





function enviarCorreoPedido($correo , $detalle, $transp, $transpMsj, $transpEst){
    
$file = "pdf/mensajeTransporte.html";
    $handle = fopen($file,"r");

    $html = fread($handle, filesize($file));
    $grid8  = $detalle[0]->neto;
    $grid7  = $detalle[0]->iva;
    $grid6  = $detalle[0]->totalPedido;
    $grid5  = $detalle[0]->direccion;
    $grid4  = $detalle[0]->razosocial;
    $grid3  = $transpMsj;     
    $grid2  = $detalle[0]->pedido; 
    $grid10 = $detalle[0]->vendedor; 
    $grid11 = "";
    $grid12 = $detalle[0]->tipPedGen; 


    
    if($detalle[0]->cobroDespacho == "1"){
            $grid11 = "<TR>
                    <TD align='right' class='Estilo8' style='border: hidden' width='18%'>Despacho: </TD>
                    <TD  class='Estilo6'  style='border: hidden'>	
                    2,000
                    </TD>
             </TR>";
    }
    
    

    
    
    $grid1 = '';
    $grid9 = '';
    
    $gridSalto = '';
    $gridSaltoMas = '';



    $contador = 0;
    $gridTable = '';
    
    $max = sizeof($detalle);
    
    $canti = 20 - $max;
    $cantiMas = 45 - $max;

    
    $gridTableSaltoLinea ="<table  width='70%' height: '100px';     style='font-size: 9px;  page-break-after:always;' align='center'>
                                <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA COMPRA</th>                                     
                                </tr>
                                <tr style='font-size: 9px'>
									 <th width='15%'>CODIGO</th>
										<th width='70%'>DESCRIPCION</th>		
                                        <th width='15%'>P.VENTA</th>	
										<th width='15%'>CANT.</th>
                						<th width='15%'>TOTAL</th>
								  </tr>";
    
    
    $gridTableSinSalto = "<table  width='70%' height: '100px';  style='font-size: 9px' align='center'>
                              <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA COMPRA</th>                                     
                                </tr>      
                              <tr style='font-size: 9px'>
                                                             <th width='15%'>CODIGO</th>
                                                                <th width='70%'>DESCRIPCION</th>		
                                                                <th width='15%'>P.VENTA</th>	
                                                                <th width='15%'>CANT.</th>
                                                                <th width='15%'>TOTAL</th>
                              </tr>";
    
    
				 foreach ($detalle AS $de){	
        
        
                       if($contador < 20){
                                $grid1 .= '<tr>'.							
                                              '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                              '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                              '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                          '</tr>';
                        }
                     
                     
                         if($contador >= 20){
                                    if($contador < 40){

                                         $grid9 .= '<tr>'.							
                                                      '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                                      '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                                      '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                                   '</tr>';

                                    }
                         }
                     
                        $contador = $contador  + 1;

                           
               }
                           
                           
                       //   $grid1 .= "</table>";  
                      //    $grid9 .= "</table>";  

    
    
    for ($x = 0; $x <= $canti; $x++) {
             $gridSalto .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.		
                             '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.	
                           '</tr>';
    } 
    
    
    for ($x = 0; $x <= $cantiMas; $x++) {
             $gridSaltoMas .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.		
                             '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.	
                           '</tr>';
    } 
    
    
    if($max > 20){
       $gridTableSaltoLinea = $gridTableSaltoLinea . $grid1 . '</table>' . $gridTableSinSalto . $grid9 . $gridSaltoMas . '</table>' . '<br>';        
    }else{
       $gridTableSaltoLinea = $gridTableSinSalto .  $grid1 . $gridSalto . '</table>'  . '<br>';        
    }
    
  $gridTable = $gridTableSaltoLinea;
 


    
    $html = str_replace("#gridTable#",$gridTable,$html);

    //$html = str_replace("#grid9#",$grid9,$html);
    //$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
    $html = str_replace("#grid6#",$grid6,$html);
    $html = str_replace("#grid7#",$grid7,$html);
    $html = str_replace("#grid8#",$grid8,$html);
    $html = str_replace("#grid10#",$grid10,$html);
    $html = str_replace("#grid11#",$grid11,$html);
    $html = str_replace("#grid12#",$grid12,$html);

$para = $correo;		
$titulo = $grid4." - ".$transp." - Estado: ". $transpEst; 
$mensaje = $html;	
$cabeceras = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";	
$cabeceras .= 'From: Transportes Aranibar<cuenta@aranibar.cl>';	
$enviado = mail($para, $titulo, $mensaje, $cabeceras);
 
if ($enviado){
  echo '1';
}else{
  echo '0';
}
    
}    


function modificarEstadoClienteCobro($idPedido, $documento, $estadoCobro){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarEstadoClienteCobro($idPedido, $documento, $estadoCobro);
}



 
function actualizarDetalleCobranza($idCli, $actCobra, $actLimit, $obsCobra, $limtCompra){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->actualizarDetalleCobranza($idCli, $actCobra, $actLimit, $obsCobra, $limtCompra);
}     


   
function listarNotaTotalPedido($idCliente, $nPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarNotaTotalPedido($idCliente, $nPedido);
}
    
function listarNotaTotalFolio($idCliente, $nFolio){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarNotaTotalFolio($idCliente, $nFolio);
}


     

function insertarRecibo($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono,$observacion,$objDeta, $deviceId){
global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
return $instancia->insertarRecibo($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono, $observacion, $objDeta, $deviceId);
}

function insertarReciboUsuario($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono,$observacion,$objDeta,$idUsuario){
global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
return $instancia->insertarReciboUsuario($idClie, $fCobro, $nRecibo, $tipPago, $totalRecibo, $totalAbono, $observacion, $objDeta, $idUsuario);
}



function anularPedidoInformeVenta($idInforme){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anularPedidoInformeVenta($idInforme);
}    

function anularVentaTienda($idInforme, $observacion, $tienda){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anularVentaTienda($idInforme,$observacion, $tienda);
}    








    
function listProdListaVentaTotal($idTipList){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProdListaVentaTotal($idTipList);
}

function listProdListaVenta($idVendedor, $idTipList){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProdListaVenta($idVendedor, $idTipList);
}

function insertarInformTransp($idInforme, $idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarInformTransp($idInforme, $idPedido);
}

function validarRutaInforme($idInforme){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->validarRutaInforme($idInforme);
}



function validarReciboCobranza($idInforme){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->validarReciboCobranza($idInforme);
}

/*
function consultarEstadoInforTrans($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarEstadoInforTrans($idPedido);
}*/

function eliminarPedidoTrans($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarPedidoTrans($idPedido);
}

function eliminarFolioPedido($idPedido, $idFolio,$observacion ,$id_usuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarFolioPedido($idPedido, $idFolio, $observacion ,$id_usuario );
}



function saveInformeTransp($arrProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveInformeTransp($arrProd);
}


function saveInformeTranspBodega($arrProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveInformeTranspBodega($arrProd);
}



function guardarDetallePago($arrPed, $idInf){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarDetallePago($arrPed,$idInf );
}




function verDetalleInformeTransp($idPedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->verDetalleInformeTransp($idPedido);
}


function listaInformeTransp($desde, $hasta, $id_inf){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listaInformeTransp($desde, $hasta, $id_inf);
}
    
    
function listProductosVentas($idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProductosVentas($idUsuario);
}



function productosFocos(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->productosFocos();
}


function anualVentaUsuario(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anualVentaUsuario();
}


function listarPorcentajePorUsuario($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarPorcentajePorUsuario($idUsua);
}

function listarAnteriorPorcentaje(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarAnteriorPorcentaje();
}


function listarActualPorcentaje(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarActualPorcentaje();
}



function listarClasificacionProd(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClasificacionProd();
}
	

function insertarProductoNuevo($codProd, $nombProd, $pesProd, $bodProd, $bodega, $pesosProd, $marcProd, $catProd, $claProd, $idProveedor, $foto ){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarProductoNuevo($codProd, $nombProd, $pesProd, $bodProd, $bodega, $pesosProd, $marcProd, $catProd, $claProd, $idProveedor, $foto);
}


function sumaCantidadOfertaTotal(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaCantidadOfertaTotal();
}



function listarVentaActualUsua(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVentaActualUsua();
}

function sumaCantidadOferta($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaCantidadOferta($idUsua);
}

       

function listarVentaVendedores($mesSel, $anoSel){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVentaVendedores($mesSel, $anoSel);
}

function listarVentaPorTipo($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVentaPorTipo($idUsua);
}

function sumaTotalPorMesClienteActual($idCliente){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesClienteActual($idCliente);
}

function sumaTotalPorMesClienteAnterior($idCliente){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesClienteAnterior($idCliente);
}

function listarProductosSinStock(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductosSinStock();
}


function guardarObsClie($idObs, $idClie){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarObsClie($idObs, $idClie);
}


function guardarObsInvt($idObs, $idClie){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarObsInvt($idObs, $idClie);
}


function guardarObsCredito($idCred, $obsCredito){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarObsCredito($idCred, $obsCredito);
}




function listarProductosOfertas(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductosOfertas();
}


function updatePorcentaje($idProd, $idPorcentaje){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updatePorcentaje($idProd, $idPorcentaje);
}



function listarPorcentajeOferta(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarPorcentajeOferta();
}


function activarDesactivarFact($idIng, $activo){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->activarDesactivarFact($idIng, $activo);
}

function updatePrecioFactura($arrProd){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updatePrecioFactura($arrProd);
}


function cantidadParticular($idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cantidadParticular($idUsuario);
}
function cantidadMascotero($idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cantidadMascotero($idUsuario);
}
function cantidadAlmacen($idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cantidadAlmacen($idUsuario);
}
function cantidadVeterinaria($idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cantidadVeterinaria($idUsuario);
}



function updateObservacionClie($idObs, $idClie){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateObservacionClie($idObs, $idClie);
}


function consultarInventarioIng($id_prod){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarInventarioIng($id_prod);
}    


function consultarInventarioIngCaja($id_prod){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarInventarioIngCaja($id_prod);
}  
    
function registroTalonario() {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->registroTalonario();
}

function listarTalonariosFact($id_talonario) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarTalonariosFact($id_talonario);
}

function guardarTalonario($objFactCabe, $objFactDet, $objFactDetAux){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarTalonario($objFactCabe, $objFactDet, $objFactDetAux);
}

function consulFact($id_talonario, $id_prove) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consulFact($id_talonario, $id_prove);
}


function listarTalonarios($id_talonario, $desde, $hasta, $id_estado, $numDoc) {
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarTalonarios($id_talonario, $desde, $hasta, $id_estado, $numDoc);
}


function consultarFactura($id_talonario, $id_prove) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
	 $instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
	 return $instancia->consultarFactura($id_talonario, $id_prove);
}


function listarClientesProd($id, $desde, $hasta) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesProd($id, $desde, $hasta);
}

function arrayUpdateInforme($arrayInforme) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->arrayUpdateInforme($arrayInforme);
}



function updateInforme($cliente,  $nuevo , $idConf) {
     global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateInforme($cliente,  $nuevo , $idConf);
}


function listUsuarioSeguimiento($id_usua, $desde, $hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listUsuarioSeguimiento($id_usua, $desde, $hasta);
}


function consultarPedido($pedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarPedido($pedido);
}

function updateEstadoDepacho($pedido){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateEstadoDepacho($pedido);
}


function eliminarProdList($pedido, $producto){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarProdList($pedido, $producto);
}

function updateConf($id, $activo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->updateConf($id, $activo);
}

function consulEstadoConfig(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consulEstadoConfig();
}


function consultarProdEnStock($arrProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarProdEnStock($arrProd);
}



function sumaTotalPorMesCobro(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesCobro();
}

function sumaTotalPorMes(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMes();
}

function sumaTotalPorMesFrancisca(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesFrancisca();
}

function sumaTotalPorMesBlanca(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesBlanca();
}
function sumaTotalPorMesJuan(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesJuan();
}

function sumaTotalPorMesDiego(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesDiego();
}

function sumaTotalPorMesAndres(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesAndres();
}

function sumaTotalPorMesJorge(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesJorge();
}



/*
function sumaTotalPorMesNico(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesNico();
}*/

function sumaTotalPorMesUsuario($idUsua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorMesUsuario($idUsua);
}
function sumaTotalPorSemanasRango($idUsua, $desde, $hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorSemanasRango($idUsua, $desde, $hasta);
}
function sumaTotalPorSemRangoGene(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdGrafico($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalPorSemRangoGene();
}
/*****************/

function tiendas(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->tiendas();
}


function cobradoVendedor($cobrado, $id){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->cobradoVendedor($cobrado, $id);
}


function modObsDesp($obs, $fecha, $id, $jornada, $tipoEntrega){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modObsDesp($obs, $fecha, $id, $jornada, $tipoEntrega);
}


function tipoProductoPrecio(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->tipoProductoPrecio();
}

    
function marcaProductoPrecio($idTipo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;        
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->marcaProductoPrecio($idTipo);
}


  /****USUARIO***/
  function listarClientesEmpresa($nombreClie, $responsable, $tipoComp){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientesEmpresa($nombreClie, $responsable, $tipoComp);
	}

  function guardarCliente($arrClie){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarCliente($arrClie);
	}


  function guardarClientePrecio($arrClie){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarClientePrecio($arrClie);
	}

  function listarVendedores(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVendedores();
	}


  function listarSector(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdCliente($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarSector();
	}


/*******************/





   //*****Funciones Usuario******//
function loginUsua($usua, $pass){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
        
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->loginUsua($usua, $pass);
}
	
	
	//*****Funciones Productos******//
	
function getDataTipo(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getDataTipos();
}
	
    
function getDataMarca($idTipo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;        
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getDataMarca($idTipo);
}

function getDataTransporte(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;        
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->getDataTransporte();
}

    
function listarProductos($codProducto,$nombreProd,$marcaProd,$categoriaProd/*, $tipoBusq*/){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductos($codProducto,$nombreProd,$marcaProd,$categoriaProd/*, $tipoBusq*/);
}


function listarCodigoBarra($id){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarCodigoBarra($id);
}

function agregarCodigoBarra($id, $cod_barra){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->agregarCodigoBarra($id, $cod_barra);
}

function eliminarCodigoBarra($id){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->eliminarCodigoBarra($id);
}


function listProductosTotal($codProducto,$nombreProd,$marcaProd,$categoriaProd, $bodega, $clasif, $clasiFiltro, $nombreProdBusq, $codigoProdBusq, $tipoBusqueda){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProductosTotal($codProducto,$nombreProd,$marcaProd,$categoriaProd, $bodega, $clasif, $clasiFiltro, $nombreProdBusq, $codigoProdBusq, $tipoBusqueda);
}


function listProductosTotalPrecio($codProducto,$nombreProd,$marcaProd,$categoriaProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listProductosTotalPrecio($codProducto,$nombreProd,$marcaProd,$categoriaProd);
}


function insertarProducto($id,$codProducto,$nombreProd,$marcaProd,$categoriaProd, $precioCosto,$stockProd,$accionProducto,$ObservacionProd,$precioVenta ){
		$instancia= new bdProducto($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);         
		return $instancia->insertarProducto($id,$codProducto,$nombreProd,$marcaProd,$categoriaProd,$precioCosto,         $stockProd,$accionProducto,$ObservacionProd,$precioVenta);
}

function listPreciosFactura($idProducto){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listPreciosFactura($idProducto);
}


function guardarPrecioFact($objPrcioFac){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarPrecioFact($objPrcioFac);
}



function listarProductosInventario($idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductosInventario($idProd);
}

function listarPedClientesCobro($idCliente, $idTipoBusqueda, $desde, $hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdTalonario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarPedClientesCobro($idCliente, $idTipoBusqueda, $desde, $hasta);
}




function saveProductoModArchivo($idProd, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveProductoModArchivo($idProd, $imagen);
}

function saveMarcaArchivo($idMarca, $imagen){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->saveMarcaArchivo($idMarca, $imagen);
}





function subirArchivoRuta($idInforme, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoRuta($idInforme, $imagen);
}

function subirArchivoCamion($idInforme, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoCamion($idInforme, $imagen);
}



function subirArchivoFactura($idFolio, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoFactura($idFolio, $imagen);
}










function subirArchivoTalonario($idTalonario, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoTalonario($idTalonario, $imagen);
}


function subirArchivoCaja($idCaja, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoCaja($idCaja, $imagen);
}

function saveProductoMod($objProdutos){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveProductoMod($objProdutos);
}


function listarOfertasProductosDescuento(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarOfertasProductosDescuento();
}




function saveProductoModFoto($objProdutos){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveProductoModFoto($objProdutos);
}

function saveProductoModFotoTransf($objProdutos){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->saveProductoModFotoTransf($objProdutos);
}





function listarProductosCajas($idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductosCajas($idProd);
}

function listarProductosCajasTienda($idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductosCajasTienda($idProd);
}



	
function insertarMarcaProducto(){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->insertarMarcaProducto();
}

function eliminarMarcaProducto($codMarca){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->eliminarMarcaProducto($codMarca);
}


    /*
	public function insertarMarca($tipo, $nombMarca ){
		$instancia= new bdProducto($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->insertarMarca($tipo, $nombMarca);
	}
	
	
	
*/
	
   //*****Funciones Factura******//
	
	/*public function insertarFactura($arrayCabe, $arrayDeta, $arrayTotal){
		$instancia= new bdFactura($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->insertarFactura($arrayCabe , $arrayDeta, $arrayTotal);
	}

	public function listarFacturas($numeroFactura,$nombre,$fecha){
		$instancia= new bdFactura($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->listarFacturas($numeroFactura,$nombre,$fecha);
	}
	
	public function listarFacturasDeta($numeroFactura){
		$instancia= new bdFactura($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->listarFacturasDeta($numeroFactura);
	}
	
	public function cancelarFactura($arrayDeta){
		$instancia= new bdFactura($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->cancelarFactura($arrayDeta);
	}	  
	
	public function modificarFactura($arrayDeta, $arrayTotal){
		$instancia= new bdFactura($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->modificarFactura($arrayDeta, $arrayTotal);
	}*/

	//*****Funciones Boletas******//
	
	/*public function insertarBoleta($arrayCabe, $arrayDeta, $arrayTotal){
		$instancia= new bdBoleta($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->insertarBoleta($arrayCabe , $arrayDeta, $arrayTotal);
	}
	
	
	public function listarBoletas($numeroBoleta,$nombre){
		$instancia= new bdBoleta($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->listarBoletas($numeroBoleta,$nombre);
	}
	
	public function listarBoletasDeta($numeroBoleta){
		$instancia= new bdBoleta($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->listarBoletasDeta($numeroBoleta);
	}
	
	
	public function expPDF($detalle){
		$instancia= new bdBoleta($this->dataBaseServer,$this->dataBaseUsername,$this->dataBaseUserPassword,$this->dataBaseName);
		return $instancia->expPDF($detalle);
      }*/

  /****USUARIO***/
function listarClientes($rut, $nombre, $tipo){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarClientes($rut, $nombre, $tipo);
}

function consultarUsuario($usua, $pass){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarUsuario($usua, $pass);
} 


function listarProveedores($rut, $nombre){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProveedores($rut, $nombre);
}

function listarProvSelection(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProvSelection();
}


/********************************/

/*****PEDIDOS*******/
function insertarPedido($objCabe, $objDeta, $objCliente){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarPedido($objCabe, $objDeta, $objCliente);
}




function insertarNotaPedidoSalida($objCabe){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarNotaPedidoSalida($objCabe);
}




 function listarPedidos($tipoPagos, $nombre, $desde, $hasta, $idPedido, $tipBusq, $idTransp, $busqRap, $tipoUsuario, $idUsuario){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarPedidos($tipoPagos, $nombre, $desde, $hasta, $idPedido, $tipBusq, $idTransp, $busqRap, $tipoUsuario, $idUsuario);
}




 function listarPedidosGeneral($tipoPagos, $nombre, $idPedido, $folio,  
                               
                               $desde1, 
                               $hasta1, 
                               
                                 $desde2, 
                               $hasta2, 
                               
                                 $desde3, 
                               $hasta3, 
                               
                                 $desde4, 
                               $hasta4, 
                               
                               $tipBusq, $idTransp, $busqRap, $tipoUsuario, $idUsuario){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarPedidosGeneral($tipoPagos, $nombre,  $idPedido, $folio, 
                                                    $desde1, 
                               $hasta1, 
                               
                                 $desde2, 
                               $hasta2, 
                               
                                 $desde3, 
                               $hasta3, 
                               
                                 $desde4, 
                               $hasta4, 
                                                
                                                $tipBusq, $idTransp, $busqRap, $tipoUsuario, $idUsuario);
}











 function listarDetallePedido($idPedido){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDetallePedido($idPedido);
}

 function listarDetallePedidoProd($idPedido, $idProd){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDetallePedidoProd($idPedido, $idProd);
}

 function consultarInventarioTiendas($idProd){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->consultarInventarioTiendas($idProd);
}



 function anularPedido($idPedido, $oberv, $folio, $idUsuario){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anularPedido($idPedido, $oberv, $folio, $idUsuario);
}


 function anularPedidoListado($idPedido, $oberv){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anularPedidoListado($idPedido, $oberv);
}


 function anularInventario($id_asociado, $id_tipo_inv, $id_invent){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->anularInventario($id_asociado, $id_tipo_inv, $id_invent);
}



 function modificarVentaTienda($idPedido, $idPago, $tienda){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarVentaTienda($idPedido, $idPago, $tienda);
}




 function modificarPedido($arrCliente, $arrayClieDet){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarPedido($arrCliente, $arrayClieDet);
}

 function agregarProdListado($arrayClieDet, $accionProd){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->agregarProdListado($arrayClieDet, $accionProd);
}



 function modificarPedidoCobro($arrCliente){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarPedidoCobro($arrCliente);
}




 function modificarPedidoEstado($arrCliente){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarPedidoEstado($arrCliente);
}

 function modificarTalonario($arrTalonario){        
      //  print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarTalonario($arrTalonario);
}



 function modificarPedidoTransp($arrEstado){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->modificarPedidoTransp($arrEstado);
}



function joinPedidos($pedido){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->joinPedidos($pedido);
}

function listarDireccionesCliente($id_cliente){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdUsuario($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->listarDireccionesCliente($id_cliente);
}






function joinPedidosTiendas($idUsuario, $fInicio, $fFin){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->joinPedidosTiendas($idUsuario, $fInicio, $fFin);
}

function listarCreditosPendienteFactura(){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->listarCreditosPendienteFactura();
}





function joinPedidosProductoSalidas($pedido){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->joinPedidosProductoSalidas($pedido);
}






function listarCreditosPorPedido($pedido){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->listarCreditosPorPedido($pedido);
}



function listarCobros(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarCobros();
}
function transporteEstado(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->transporteEstado();
}



function listarEstadoPedido(){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarEstadoPedido();
}


function listarSubProducto($nodo_prod, $tipBusq){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarSubProducto($nodo_prod, $tipBusq);
}


function listarDescuentosVentas($id_prod){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDescuentosVentas($id_prod);
}

function listarMarcasProductos(){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->listarMarcasProductos();
}




function listarProductoHijo($id_prod){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarProductoHijo($id_prod);
}





 function traspasoPedidoTienda($arrDeta){        
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->traspasoPedidoTienda($arrDeta);
}


/********************************/


/*****STOCK*******/

function insertarStock($objCabe, $objDeta, $objCliente, $obserbIng){        
       // print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarStock($objCabe, $objDeta, $objCliente, $obserbIng);
}


function listarIngresos($fechDesde, $fechHasta, $idProv, $tipBusq){        
       // print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarIngresos($fechDesde, $fechHasta, $idProv, $tipBusq);
}


function listarDetalleIngresos($idIngresos){        
       // print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarDetalleIngresos($idIngresos);
}


function guardarObserFact($id, $obse, $estCobro, $estPedido, $fechCobro, $numDoc){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->guardarObserFact($id, $obse, $estCobro, $estPedido, $fechCobro, $numDoc);
}




function listarInventarioBodega($fechaListar, $idProd){        
       // print_r($objDeta);     
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarInventarioBodega($fechaListar, $idProd);
}

 function ingresarInventario($arrSal, $arrIng, $arrCua){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->ingresarInventario($arrSal, $arrIng, $arrCua);
}





function listarVentasGeneradas($desde, $hasta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVentasGeneradas($desde, $hasta);
}

function listarVentasGeneradasTiendas($desde, $hasta, $tienda, $usuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarVentasGeneradasTiendas($desde, $hasta, $tienda, $usuario);
}

function sumaTotalVentas($fecha){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->sumaTotalVentas($fecha);
}

function insertarTotalVentas($fecha, $tienda, $usuario, $valor){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarTotalVentas($fecha, $tienda, $usuario, $valor);
}


 function listarSalidasProductos($idProd){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listarSalidasProductos($idProd);
}
    

function joinRuta($pedido, $arrayRuta){        
       
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
      
		return $instancia->joinRuta($pedido, $arrayRuta);
}


function pesoProductos($pedido){               
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);      
		return $instancia->pesoProductos($pedido);
}

function updateEstadoCompraSII($folio, $rut, $id_factura){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName,$NuboxCert;
    $resp = $NuboxCert->autenticar();

	if( $resp == "OK"){
	    $length = strlen($rut);

        // Check if the length of the string is more than 1
        if($length > 1) {
            $main_part = substr($rut, 0, $length - 1); // Get all characters except the last one
            $last_char = substr($rut, -1); // Get the last character
            $rut = $main_part . '-' . $last_char; // Concatenate with a '-'
        }
	    $estado = $NuboxCert->obtieneEstadoCompra($rut, $folio);
	    $updRes = ".";
	    
	    if($estado){
	        $instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		    $updRes = $instancia->updateEstadoCompraSII($id_factura, $folio, $estado);
	    }
	    return $estado;
	}
}

function obtienePDFCompraSII($folio, $rut){
    global $NuboxCert;
    $resp = $NuboxCert->autenticar();

	if( $resp == "OK"){
	    $length = strlen($rut);

        // Check if the length of the string is more than 1
        if($length > 1) {
            $main_part = substr($rut, 0, $length - 1);
            $rut = $main_part;
        }

	    $pdf = $NuboxCert->obtienePDFCompraSII($rut, $folio);
	    
	    return $pdf;
	}
}

function obtienePDFNC($folio){
    global $NuboxCert;
    $resp = $NuboxCert->autenticar();

	if( $resp == "OK"){

	    $pdf = $NuboxCert->obtienePDFNC($folio);
	    
	    return $pdf;
	}
}



/*
* Genera un DTE y lo envía a NUBOX.COM para su sincronización
*
* @param integer $TpoDTE corresponde al la identificación que le da el SII al tipo de documento.
* @param string  $FchEmision fecha de emisión del documento (dd-mm-yyyy)
* @param string  $Rut RUT del Receptor del DTE, sin puntos ni guiones (10123456-9)
* @param string  $RznSoc Razon Social del Receptor del DTE
* @param string  $Giro Giro del Receptor del DTE
* @param string  $Comuna Comuna del Receptor del DTE
* @param string  $Direccion Dirección postal del Receptor del DTE
* @param string  $Email Email del Receptor del DTE
* @param string  $IndTraslado Indicador de Traslado SII para Guías de Despacho 
*				 (1:Operacion constituye venta, 2:Ventas por efectuar, 3:Consignaciones, 
*				  4:Entrega gratuita,5:Traslados internos,6:Otros traslados no venta,7:Guia de devolucion)
* @param string  $ComunaDestino Nombre de Comuna de Destino para Guías de Despacho
* @param Array   $Detalles Arreglo con los Detalles para el DTE

* 		 	string $Afecto Indica si el detalle es afecto a IVA ("SI" o "NO")

*		 	string  $Nombre Nombre corto del producto (hasta 80 caracteres)
*		 	string  $Descripcion Descripción larga del producto (hasta 900 caracteres)
*		 	integer $Cantidad cantidad de productos
*		 	integer $Precio precio unitario del producto
*		 	string  $Codigo código del producto (hasta 35 caracteres)
*
* @returns XML xml de respuesta de nubox
*/


   function insertarNubox($folio, $identificador,$tipo, $idPedido){
               global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
               $instancia= new bdFactura($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
               return $instancia->insertarNubox($folio, $identificador,$tipo, $idPedido);
        }


function xml_attribute($object, $attribute)
{
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}



function obtienePDF($identificador, $folio){
	global $Nubox;   
    $cad1 . $cad2;
    $urlPdf="D:facturas/Factura_".$folio.".pdf";
    
	$resp = $Nubox->autenticar();

	if( $resp == "OK"){

		$pdf = $Nubox->obtienePDF($identificador);

		file_put_contents($urlPdf, $pdf);

		//header('Content-type: application/pdf');


		return $pdf;

	} else {

		return $resp;

	}

}


function insertarFacturaNubox($arrayFac, $numPedido){
       global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
       $instancia= new bdFactura($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
       return $instancia->insertarFacturaNubox($arrayFac, $numPedido);
}


function insertarFacturaNuboxJoin($arrayFac, $arrayPedido){
       global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
       $instancia= new bdFactura($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
       return $instancia->insertarFacturaNuboxJoin($arrayFac, $arrayPedido);
}

function insertarLog($id_pedido, $accion, $idUsuario){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->insertarLog($id_pedido, $accion, $idUsuario);
}


function generarFacturaPedidoPDF($detalle){
$file = "pdf/UnirPedidosPDF.html";
    
    
   // $file = "pdf/BoletaTiendaPDF.html";
    
    $handle = fopen($file,"r");

    $html = fread($handle, filesize($file));
    $grid8  = $detalle[0]->neto;
    $grid7  = $detalle[0]->iva;
    $grid6  = $detalle[0]->totalPedido;
    $grid5  = $detalle[0]->direccion;
    $grid4  = $detalle[0]->razosocial;
    $grid3  = $detalle[0]->pedido;     
    $grid2  = $detalle[0]->fecha; 
    $grid10 = $detalle[0]->vendedor; 
    $grid11 = "";
    $grid12 = $detalle[0]->tipPedGen; 

    $grid13 = $detalle[0]->formaPago;  
    $grid14 = $detalle[0]->credito; 
    
    if($detalle[0]->cobroDespacho == "1"){
            $grid11 = "<TR>
                    <TD align='right' class='Estilo8' style='border: hidden' width='18%'>Despacho: </TD>
                    <TD  class='Estilo6'  style='border: hidden'>	
                    2,000
                    </TD>
             </TR>";
    }
    
    

    
    
    $grid1 = '';
    $grid9 = '';
    
    $gridSalto = '';
    $gridSaltoMas = '';



    $contador = 0;
    $gridTable = '';
    
    $max = sizeof($detalle);
    
    $canti = 20 - $max;
    $cantiMas = 45 - $max;

    
    $gridTableSaltoLinea ="<table  width='70%' height: '100px';     style='font-size: 8px;  page-break-after:always;' align='center'>
                                <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA COMPRA</th>                                     
                                </tr>
                                <tr style='font-size: 12px'>
									 <th width='15%'>CODIGO</th>
										<th width='70%'>DESCRIPCION</th>		
                                        <th width='15%'>P.VENTA</th>	
										<th width='15%'>CANT.</th>
                						<th width='15%'>TOTAL</th>
								  </tr>";
    
    
    $gridTableSinSalto = "<table  width='70%' height: '100px';  style='font-size: 11px' align='center'>
                              <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA COMPRA</th>                                     
                                </tr>      
                              <tr style='font-size: 15px'>
                                                             <th width='15%'>CODIGO</th>
                                                                <th width='70%'>DESCRIPCION</th>		
                                                                <th width='15%'>P.VENTA</th>	
                                                                <th width='15%'>CANT.</th>
                                                                <th width='15%'>TOTAL</th>
                              </tr>";
    
    
				 foreach ($detalle AS $de){	
        
        
                       if($contador < 20){
                                $grid1 .= '<tr>'.							
                                              '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                              '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                              '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                          '</tr>';
                        }
                     
                     
                         if($contador >= 20){
                                    if($contador < 40){

                                         $grid9 .= '<tr>'.							
                                                      '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                                      '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                                      '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                                   '</tr>';

                                    }
                         }
                     
                        $contador = $contador  + 1;

                           
               }
                           
                           
                       //   $grid1 .= "</table>";  
                      //    $grid9 .= "</table>";  

    
    
    for ($x = 0; $x <= $canti; $x++) {
             $gridSalto .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.		
                             '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.	
                           '</tr>';
    } 
    
    
    for ($x = 0; $x <= $cantiMas; $x++) {
             $gridSaltoMas .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.		
                             '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.	
                           '</tr>';
    } 
    
    
    if($max > 20){
       $gridTableSaltoLinea = $gridTableSaltoLinea . $grid1 . '</table>' . $gridTableSinSalto . $grid9 . $gridSaltoMas . '</table>' . '';        
    }else{
       $gridTableSaltoLinea = $gridTableSinSalto .  $grid1 . $gridSalto . '</table>'. '';        
    }
    
  $gridTable = $gridTableSaltoLinea;
 


    
    $html = str_replace("#gridTable#",$gridTable,$html);

    //$html = str_replace("#grid9#",$grid9,$html);
    //$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
    $html = str_replace("#grid6#",$grid6,$html);
    $html = str_replace("#grid7#",$grid7,$html);
    $html = str_replace("#grid8#",$grid8,$html);
    $html = str_replace("#grid10#",$grid10,$html);
    $html = str_replace("#grid11#",$grid11,$html);
    $html = str_replace("#grid12#",$grid12,$html);

    $html = str_replace("#grid13#",$grid13,$html);
    $html = str_replace("#grid14#",$grid14,$html);

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
   // $dompdf->set_paper("letter", "portrait");   
  //  $dompdf->set_paper(array(0,0,227.6220472441,1000));

    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("certificado.pdf");
    
    return $dompdf;
}

function generarCotizacionPedidoPDF($detalle){

    $file = "pdf/CotizacionPDF.html";
    $handle = fopen($file,"r");

    $html = fread($handle, filesize($file));
    $grid8  = $detalle[0]->neto;
    $grid7  = $detalle[0]->iva;
    $grid6  = $detalle[0]->totalPedido;
    $grid5  = $detalle[0]->direccion;
    $grid4  = $detalle[0]->razosocial;
    $grid3  = $detalle[0]->pedido;     
    $grid2  = $detalle[0]->fecha; 
    $grid10 = $detalle[0]->vendedor; 
    $grid11 = "";
    $grid12 = $detalle[0]->tipPedGen; 


    
    if($detalle[0]->cobroDespacho == "1"){
            $grid11 = "<TR>
                    <TD align='right' class='Estilo8' style='border: hidden' width='18%'>Despacho: </TD>
                    <TD  class='Estilo6'  style='border: hidden'>	
                    2,000
                    </TD>
             </TR>";
    }
    
    

    
    
    $grid1 = '';
    $grid9 = '';
    
    $gridSalto = '';
    $gridSaltoMas = '';



    $contador = 0;
    $gridTable = '';
    
    $max = sizeof($detalle);
    
    $canti = 20 - $max;
    $cantiMas = 45 - $max;

    
    $gridTableSaltoLinea ="<table  width='70%' height: '100px';     style='font-size: 12px;  page-break-after:always;' align='center'>
                                <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA COMPRA</th>                                     
                                </tr>
                                <tr style='font-size: 12px'>
									 <th width='15%'>CODIGO</th>
										<th width='70%'>DESCRIPCION</th>		
                                        <th width='15%'>P.VENTA</th>	
										<th width='15%'>CANT.</th>
                						<th width='15%'>TOTAL</th>
								  </tr>";
    
    
    $gridTableSinSalto = "<table  width='70%' height: '100px';  style='font-size: 12px' align='center'>
                              <tr>
                                  <th class='Estilo8' COLSPAN='5'>Detalle de la cotización:</th>                                     
                                </tr>      
                              <tr style='font-size: 12px'>
                                                             <th width='15%'>CODIGO</th>
                                                                <th width='70%'>DESCRIPCION</th>		
                                                                <th width='15%'>P.VENTA</th>	
                                                                <th width='15%'>CANT.</th>
                                                                <th width='15%'>TOTAL</th>
                              </tr>";
    
    
				 foreach ($detalle AS $de){	
        
        
                       if($contador < 20){
                                $grid1 .= '<tr>'.							
                                              '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                              '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                              '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                              '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                          '</tr>';
                        }
                     
                     
                         if($contador >= 20){
                                    if($contador < 40){

                                         $grid9 .= '<tr>'.							
                                                      '<td><div  class="Estilo3">'.$de->codProducto.'</div></td>'.
                                                      '<td><div  class="Estilo3">'.$de->nombreProd.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->precioVenta.'</div></td>'.
                                                      '<td><div  class="Estilo4">'.$de->cantidad.'</div></td>'.		
                                                      '<td><div  class="Estilo4">'.$de->total.'</div></td>'.	
                                                   '</tr>';

                                    }
                         }
                     
                        $contador = $contador  + 1;

                           
               }
                           
                           
                       //   $grid1 .= "</table>";  
                      //    $grid9 .= "</table>";  

    
    
    for ($x = 0; $x <= $canti; $x++) {
             $gridSalto .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                           '</tr>';
    } 
    
    
    for ($x = 0; $x <= $cantiMas; $x++) {
             $gridSaltoMas .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                           '</tr>';
    } 
    
    
    if($max > 20){
       $gridTableSaltoLinea = $gridTableSaltoLinea . $grid1 . '</table>' . $gridTableSinSalto . $grid9 . $gridSaltoMas . '</table>' . '<br>';        
    }else{
       $gridTableSaltoLinea = $gridTableSinSalto .  $grid1 . $gridSalto . '</table>'  . '<br>';        
    }
    
  $gridTable = $gridTableSaltoLinea;
 


    
    $html = str_replace("#gridTable#",$gridTable,$html);

    //$html = str_replace("#grid9#",$grid9,$html);
    //$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
    $html = str_replace("#grid6#",$grid6,$html);
    $html = str_replace("#grid7#",$grid7,$html);
    $html = str_replace("#grid8#",$grid8,$html);
    $html = str_replace("#grid10#",$grid10,$html);
    $html = str_replace("#grid11#",$grid11,$html);
    $html = str_replace("#grid12#",$grid12,$html);


    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter", "portrait");   
    
    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("certificado.pdf");
    

    return $dompdf;
}






function generarProductosPDF($detalle){       
    
$file = "pdf/ProductosPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
    
    
    
$grid3 =$detalle[0]->nombreProv;     
$grid2 =$detalle[0]->fecha;   
$gridTr1="";
    
					
        foreach ($detalle AS $de){	
                        $gridTr1 .=   '<tr  class="Estilo3">'.	
                          '<td align="center">'.$de->counta.'</td>'.
                          '<td align="center">'.$de->id.'</td>'.
                          '<td>'.$de->nombreProd.'</td>'.	                            
                          '<td align="center"><img width="26" height="26" src="'.$de->urlImgBarros.'"/> '.$de->stockBarros.'</td>'.	
                          '<td align="center"><img width="26" height="26" src="'.$de->urlImgSantas.'"/> '.$de->stockSantas.'</td>'.	                            
                          '<td align="center"><img width="26" height="26" src="'.$de->urlImgTucape.'"/> '.$de->stockTucape.'</td>'.	                            
                          '</tr>';                
        }      
    
    
    $html = str_replace("#grid4#",$gridTr1,$html);
    $html = str_replace("#grid3#",$grid2,$html);

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("productos.pdf");
    
return $dompdf;    
    
}

function generarCobroPDF($detalle){		       
$file = "pdf/CobroPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
$contador = 0;
    
$grid2 =$detalle[0]->fecha;   
$gridTr1="";
$gridTr2="";
$gridTr3="";
$max = sizeof($detalle);    
$contador2 = 0;
    
$gridTableSalto = ' <table  width="90%" height="100px" style="font-size: 9px;  page-break-after:always;" align="center">
                                <tr>
                                  <th class="Estilo8" COLSPAN="8">PEDIDOS</th>                                     
                                </tr>
                                <tr style="font-size: 8px">
									    <th width="10%" align="center">#</th>                                    
									    <th width="10%" align="center">N°FACT</th>
										<th width="20%" align="center">N°PED</th>
                                        <th width="15%" align="center">DESPACHO</th>	                    
										<th width="20%" align="center">ESTADO</th>
										<th width="50%" align="center">CLIENTE</th>
                                        <th width="15%" align="center">COBRO</th>	
                                        <th width="15%" align="center">SALDO</th>	
								  </tr>
                            ';
    

					
        foreach ($detalle AS $de){	

              $contador   = $contador  + 1;
              $contador2  = $contador2 + 1;

                if($contador != 30){
            
                    
                    if($contador2 == $max){

                        $gridTr1 .=   '<tr  class="Estilo3">'.	
                          '<td align="center">'.$de->counta.'</td>'.    
                          '<td align="center">'.$de->folio.'</td>'.
                          '<td align="center">'.$de->id_pedido.'</td>'.
                          '<td align="center">'.$de->fecha_entrega.'</td>'.	                                                        
                          '<td align="center">'.$de->nombreEstado.'</td>'.	                                                        
                          '<td align="center">'.$de->nombre.'</td>'.	      
                          '<td align="center">'.$de->fecha_cobro.'</td>'.	      
                          '<td align="center">$ '.$de->totalPedido.'</td>'.	 
                          '</tr>';
                    
                         $gridTr2.= $gridTableSalto. $gridTr1 . '</table>';
                         $gridTr3  = $gridTr3 . $gridTr2;
                    }else{
                        
                             $gridTr1 .=   '<tr  class="Estilo3">'.	
                                      '<td align="center">'.$de->counta.'</td>'.
                                      '<td align="center">'.$de->folio.'</td>'.
                                      '<td align="center">'.$de->id_pedido.'</td>'.
                                      '<td align="center">'.$de->fecha_entrega.'</td>'.	                                             
                                      '<td align="center">'.$de->nombreEstado.'</td>'.	                                                        
                                      '<td align="center">'.$de->nombre.'</td>'.	  
                                      '<td align="center">'.$de->fecha_cobro.'</td>'.	                                       
                                      '<td align="center">$ '.$de->totalPedido.'</td>'.	 
                                      '</tr>';
                    }
                    
                }else{
                     $gridTr1 .=    '<tr  class="Estilo3">'.	
                                    '<td align="center">'.$de->counta.'</td>'.
                                    '<td align="center">'.$de->folio.'</td>'.
                                    '<td align="center">'.$de->id_pedido.'</td>'.
                                    '<td align="center">'.$de->fecha_entrega.'</td>'.	                                                 
                                    '<td align="center">'.$de->nombreEstado.'</td>'.	                                                        
                                    '<td align="center">'.$de->nombre.'</td>'.	      
                                    '<td align="center">'.$de->fecha_cobro.'</td>'.	     
                                    '<td align="center">$ '.$de->totalPedido.'</td>'.	 
                                    '</tr>';
                    
                    $gridTr2.= $gridTableSalto. $gridTr1 . ' </table> ';
                }           
            
            
                if($contador == 30){
                    $gridTr3  = $gridTr3 . $gridTr2;
                    $gridTr2  = "";
                    $gridTr1  = "";
                    $contador = 0;
                }
                
        }  
    
    
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid4#",$gridTr3,$html);

    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("clientes.pdf");
    
return $dompdf;

}

function generarInventarioPDF($detalle){		    
   
$file = "pdf/InventarioPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
    
$grid2 =$detalle[0]->fecha;   
$gridTr1="";
$gridTr2="";
$gridTr3="";
$max = sizeof($detalle); 
$contador = 0;    
$contador2 = 0;
    
$gridTableSalto = ' <table  width="90%" height="100px" style="font-size: 12px;  page-break-after:always;" align="center">
                                <tr>
                                  <th class="Estilo8" COLSPAN="5">PRODUCTOS</th>                                     
                                </tr>
                                <tr style="font-size: 8px">
									    <th width="10%" align="center">#</th>                                    
									    <th width="10%" align="center">CODIGO</th>
										<th width="20%" align="center">NOMBRE</th>
                                        <th width="15%" align="center">CANTIDAD</th>	                    
										<th width="20%" align="center">ESTADO</th>
								  </tr>
                            ';
    
        foreach ($detalle AS $de){	

              $contador   = $contador  + 1;
              $contador2  = $contador2 + 1;

                if($contador != 25){
            
                    
                    if($contador2 == $max){

                        $gridTr1 .=   
                          '<tr  class="Estilo3">'.
                          '<td align="center">'.$de->counta.'</td>'.                                
                          '<td align="center">'.$de->idProd.'</td>'.    
                          '<td align="center">'.$de->nombProd.'</td>'.
                          '<td align="center">'.$de->cantIng.'</td>'.
                          '<td align="center"><img src="'.$de->urlImg.'"/>  '.$de->diferencia.'</td>'.
                          '</tr>';
                    
                         $gridTr2.= $gridTableSalto. $gridTr1 . '</table>';
                         $gridTr3  = $gridTr3 . $gridTr2;
                    }else{
                        
                             $gridTr1 .=   
                                  '<tr  class="Estilo3">'.	
                                  '<td align="center">'.$de->counta.'</td>'.                                                              
                                  '<td align="center">'.$de->idProd.'</td>'.   
                                  '<td align="center">'.$de->nombProd.'</td>'.
                                  '<td align="center">'.$de->cantIng.'</td>'.
                                  '<td align="center"><img src="'.$de->urlImg.'"/>  '.$de->diferencia.'</td>'.
                                  '</tr>';
                    }
                    
                }else{
                     $gridTr1 .=   
                                  '<tr  class="Estilo3">'.	
                                  '<td align="center">'.$de->counta.'</td>'.                                                         
                                  '<td align="center">'.$de->idProd.'</td>'.    
                                  '<td align="center">'.$de->nombProd.'</td>'.
                                  '<td align="center">'.$de->cantIng.'</td>'.
                                  '<td align="center"><img src="'.$de->urlImg.'"/>  '.$de->diferencia.'</td>'.
                                  '</tr>';
                    
                    $gridTr2.= $gridTableSalto. $gridTr1 . '</table>';
                }           
            
            
                if($contador == 25){
                    $gridTr3  = $gridTr3 . $gridTr2;
                    $gridTr2  = "";
                    $gridTr1  = "";
                    $contador = 0;
                }
                
        }  
    
    
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid4#",$gridTr3,$html);



    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter", "portrait");   
    
    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("inventario.pdf");
    
    
    
return $dompdf;

}

function gerexpClientePDF($detalle){		
$file = "pdf/ClientesPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
$grid1 = '';
					
			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.							
                                '<td align="center">'.$de->id_cliente.'</td>'.
                                '<td>'.$de->nombre.'</td>'.
                                '<td>'.$de->direccion.'</td>'.
                                '<td>'.$de->telefono.'</td>'.
                                '<td align="center"><img WIDTH=15 HEIGHT=15 src="'.$de->urlImg.'"/>'.$de->fecha.'</td>'.
                                '<td align="center">'.$de->activo.'</td>'.
                               '</tr>';
				
			}    
 	$html = str_replace("#grid1#",$grid1,$html);	

    
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("ClientesPDf.pdf");
    
return $dompdf;

}

function gerexpTalonarioPDF($detalle){		
$file = "pdf/TalonarioPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
$grid1 = '';
					
			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.							
                                '<td align="center">'.$de->nCheque.'</td>'.
                                '<td align="center">'.$de->estado.'</td>'.
                                '<td align="center">$ '.$de->monto.'</td>'.
                                '<td align="center">'.$de->fecha.'</td>'.
                                '<td>'.$de->ordeDe.'</td>'.
                                '<td>'.$de->descripcion.'</td>'.
                                '<td>'.$de->facturas.'</td>'.
                        
                               '</tr>';
				
			}    
 	$html = str_replace("#grid1#",$grid1,$html);	

  
    
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("TalonarioPDf.pdf");
    
return $dompdf;

}

function generarInventarioValidarPDF($detalle){		       
$file = "pdf/InventarioValPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
    
$grid2 =$detalle[0]->fecha;   
$gridTr1="";
$gridTr2="";
$gridTr3="";
$max = sizeof($detalle); 
$contador = 0;    
$contador2 = 0;
    
$gridTableSalto = ' <table  width="90%" height="100px" style="font-size: 12px;  page-break-after:always;" align="center">
                                <tr>
                                  <th class="Estilo8" COLSPAN="4">PRODUCTOS</th>                                     
                                </tr>
                                <tr style="font-size: 8px">
									    <th width="10%" align="center">#</th>                                    
									    <th width="10%" align="center">CODIGO</th>
										<th width="20%" align="center">NOMBRE</th>
                                        <th width="15%" align="center">STOCK</th>	                    
								  </tr>
                            ';
    
        foreach ($detalle AS $de){	

              $contador   = $contador  + 1;
              $contador2  = $contador2 + 1;

                if($contador != 25){
            
                    
                    if($contador2 == $max){

                        $gridTr1 .=   
                          '<tr  class="Estilo3">'.
                          '<td align="center">'.$de->counta.'</td>'.                                
                          '<td align="center">'.$de->idProd.'</td>'.    
                          '<td align="center">'.$de->nombProd.'</td>'.
                          '<td align="center">'.$de->stock.'</td>'.
                          '</tr>';
                    
                         $gridTr2.= $gridTableSalto. $gridTr1 . '</table>';
                         $gridTr3  = $gridTr3 . $gridTr2;
                    }else{
                        
                             $gridTr1 .=   
                                  '<tr  class="Estilo3">'.	
                                  '<td align="center">'.$de->counta.'</td>'.                                                              
                                  '<td align="center">'.$de->idProd.'</td>'.   
                                  '<td align="center">'.$de->nombProd.'</td>'.
                                  '<td align="center">'.$de->stock.'</td>'.
                                  '</tr>';
                    }
                    
                }else{
                     $gridTr1 .=   
                                  '<tr  class="Estilo3">'.	
                                  '<td align="center">'.$de->counta.'</td>'.                                                         
                                  '<td align="center">'.$de->idProd.'</td>'.    
                                  '<td align="center">'.$de->nombProd.'</td>'.
                                  '<td align="center">'.$de->stock.'</td>'.
                                  '</tr>';
                    
                    $gridTr2.= $gridTableSalto. $gridTr1 . '</table>';
                }           
            
            
                if($contador == 25){
                    $gridTr3  = $gridTr3 . $gridTr2;
                    $gridTr2  = "";
                    $gridTr1  = "";
                    $contador = 0;
                }
                
        }  
    
    
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid4#",$gridTr3,$html);



    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter", "portrait");   
    
    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("inventario.pdf");
    
    
    
return $dompdf;

}

function gerexpRutaPDF($detalle){		
$file = "pdf/RutaPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
    
    
    
$grid5 =$detalle[0]->id_informe;          
$grid4 =$detalle[0]->fActual;             
$grid3 =$detalle[0]->cantPed;        
$grid2 =$detalle[0]->nTransp;     
$grid1 = '';
					
foreach ($detalle AS $de){	
                
$grid1 .= '<tr  class="Estilo3">'.							
 '<td align="center">'.$de->formaPago.'</td>'.
 '<td align="center"><table style="width:100%" >
  <tr class="Estilo3">
    <th>E</th>
    <th>TB</th> 
    <th>TF</th> 
    <th>CV</th> 
    <th>CH</th>           
  </tr>
  <tr>

  </tr>  
</table></td>'.
                                '<td>$ '.$de->total.'</td>'.
                                '<td></td>'.
                                '<td align="center">'.$de->nPedido.'</td>'.
                                '<td align="center">'.$de->tipo.'</td>'.
                                '<td align="center">'.$de->pesos.'</td>'.
                                /*'<td align="center"><img WIDTH=20 HEIGHT=20  src="'.$de->sector.'"/></td>'.*/
                                '<td align="center">'.$de->telefono.'</td>'.
                                '<td>'.$de->nombre.'</td>'.
                                '<td></td>'.
                                '<td>'.$de->direccion.'</td>'.                              
                                '<td>'.$de->obsClie.'</td>'.  
                                '<td>'.$de->usua.'</td>'.   

						       '</tr>';
				
			}    
 	$html = str_replace("#grid1#",$grid1,$html);	
 	$html = str_replace("#grid2#",$grid2,$html);	
 	$html = str_replace("#grid3#",$grid3,$html);	
 	$html = str_replace("#grid4#",$grid4,$html);	
 	$html = str_replace("#grid5#",$grid5,$html);	

    
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->setPaper('A4', 'landscape'); 

$dompdf->render();
$dompdf->stream("RutaPDf.pdf");
    
return $dompdf;

}

function generarProductoPDFSTOCK($detalle){		
$file = "ProductoStockPDF.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
  
 
$grid1 = '';
					
			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.							
                                '<td align="center"></td>'.
                                '<td align="center"></td>'.
                        
                                '<td align="center">'.$de->id_prod.'</td>'.
                                '<td>'.$de->nombreProd.'</td>'.
                                '<td>'.$de->nombreCategoria.'</td>'.
                                '<td>'.$de->nombreMarca.'</td>'.                              
                                '<td>'.$de->stock.'</td>'.   
						       '</tr>';
				
			}    
 	$html = str_replace("#grid1#",$grid1,$html);	
 		

    
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("productosPDF.pdf");
    
return $dompdf;

}

function generarClientePDFRuta($detalle){    
$file = "pdf/ClienteRutaPdf.html";
$handle = fopen($file,"r");
$html = fread($handle, filesize($file));
$grid1 = '';
$grid2 = $detalle[0]->diaSelecionado;    
					
			foreach ($detalle AS $de){	
                
					$grid1 .= '<tr  class="Estilo3">'.							
                                '<td align="center">'.$de->id_cliente.'</td>'.
                                '<td><img WIDTH=15 HEIGHT=15  src="'.$de->urlImgTipoClie.'"/>'.$de->nombre.'</td>'.
                                '<td><img WIDTH=15 HEIGHT=15  src="'.$de->objDirecCant.'"/>'.$de->direccion.'</td>'.
                                '<td>'.$de->telefono.'</td>'.                        
                                '<td align="center"><img WIDTH=15 HEIGHT=15  src="'.$de->urlImg.'"/> ' .$de->fecha.'</td>'.
                                '<td>'.$de->diaSelObs.'</td>'.
    
                               '</tr>';
				
			}    
 	$html = str_replace("#grid1#",$grid1,$html);	
 	$html = str_replace("#grid2#",$grid2,$html);	

    
    
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("ClientesPDfRuta.pdf");
    
return $dompdf;

}


function  imprimirTermicaCaja($detalle){		
    
if($detalle[0]->folio  != ''){       

$nombre_impresora = "jorgito7"; 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
echo 1;


$printer->setJustification(Printer::JUSTIFY_CENTER);



try{
	$logo = EscposImage::load($source_img, false);
    $printer->bitImage($logo);
}catch(Exception $e){}


$printer->setTextSize(2, 2);
$printer->text("N°". $detalle[0]->idPedido."\n");

#La fecha también
$printer->setTextSize(1, 1);    
date_default_timezone_set("America/Mexico_City");
$printer->text(date("d-m-Y H:i:s") . "\n");
$printer->text("---------------------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("CANT  DESCRIPCION                       BODEGA\n");
$printer->text("----------------------------------------------"."\n");

    
    
    	foreach ($detalle AS $de){	
            	$printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(" $de->cantidad  ");
                $printer->text("$de->nombreProd  ");
                $printer->text("Bod-".$de->bodega."\n");
			}   
    
    

    

$printer->text("----------------------------------------------"."\n");


$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("RESPALDO BODEGA\n");

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

    
}else{
    
    return 0;    
}

}


function  imprimirTermica($detalle){		

/*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;    
        
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
    
    
$printer = new Printer($connector);
echo 1;


$printer->setJustification(Printer::JUSTIFY_CENTER);



try{
	$logo = EscposImage::load($source_img, false);
    $printer->bitImage($logo);
}catch(Exception $e){}


$printer->setTextSize(2, 2);
$printer->text("N°". $detalle[0]->idPedido."\n");

#La fecha también
$printer->setTextSize(1, 1);    
date_default_timezone_set("America/Mexico_City");
$printer->text(date("d-m-Y H:i:s") . "\n");
$printer->text("---------------------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("CANT  DESCRIPCION                       BODEGA\n");
$printer->text("----------------------------------------------"."\n");

    
    
    	foreach ($detalle AS $de){	
            	$printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(" $de->cantidad  ");
                $printer->text("$de->nombreProd  ");
                $printer->text("Bod-".$de->bodega."\n");
			}   
    
    

    

$printer->text("----------------------------------------------"."\n");


$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("RESPALDO BODEGA\n");

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

    
return $dompdf;

}

function imprimirTermicaBoletaElect($detalle){	
    
       global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
    
    
$bandera = $detalle[0]->banderaOferta;    
    
    
if($detalle[0]->folio  != ''){   
    

/*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else /*if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
        
    
    
$printer = new Printer($connector);
//Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;


$printer->setJustification(Printer::JUSTIFY_CENTER);



try{
   	$logoCabe = EscposImage::load('img/cabeBoleta.png', false);
	$logoPie = EscposImage::load('img/pieBoleta.png', false);
    
    $logoWhassap = EscposImage::load('logoPrintWhassap.png', false);
    $logoLLamar  = EscposImage::load('logoPrintLlamar.png', false);

    $logoCasa  = EscposImage::load('casaPrint.png', false);

    
  
}catch(Exception $e){

}


 $printer->bitImage($logoCabe);
    
$printer->setTextSize(1, 1);
$printer->text("JUAN CARLOS ARANIBAR CASTRO\n");  
$printer->text("16.466.904-1\n"); 

/*$printer->bitImage($logoCasa);    
$printer->text(" BARROS ARANA #3033 ");   
$printer->bitImage($logoLLamar);    
$printer->text("58 2316112 ");    
$printer->bitImage($logoWhassap);      
$printer->text("+56 9 74224952\n"); */   
    
$printer->text("IMP. Y VENTA DE ALIMENTOS, ACCESORIOS, ART ASEO
PARA MASCOTAS Y DIST LÁCTEOS\n");   
date_default_timezone_set("America/Mexico_City");
$printer->text("Fecha Emisión: ");      
$printer->text(date("d-m-Y") . "\n");    
$printer->text("BOLETA ELECTRONICA Folio");  
$printer->text(" N°: ". $detalle[0]->folio."\n");
$printer->text("ARICA-CHILE\n");    
    

//La fecha también
$printer->setTextSize(1, 1);    

$printer->text("----------------------------------------------". "\n");
$printer->text("Forma De Pago:" .$detalle[0]->FormaPago."\n");    
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("CANT  DESCRIPCION                      TOTAL\n");

$printer->text("----------------------------------------------". "\n");

    
    
    	foreach ($detalle AS $de){	
            	$printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(" $de->cantidad  ");
                $printer->text("$de->nombreProd ");
                $printer->text(" $ ".$de->Precio ."\n");
       }   
    
    

 
$printer->text("----------------------------------------------". "\n");
$printer->text("                             TOTAL: ". $detalle[0]->TotalBol."\n");


$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->bitImage($logoPie);
    
 /**************************************/   
    
if($bandera == true){   
    
        $printer->text("\n\n----------------RECORTAR----------------". "\n\n");
        $printer->setTextSize(4, 5); 
        $printer->text("30% Desc.\n");  
        $printer->setTextSize(1, 1); 
        $printer->text("En accesorios  y aseo para mascotas\n");  
        $printer->text("Canjear solo en tiendas\n");    
        $printer->text("VALIDO POR 5 DIAS\n");
        $printer->text("Fecha Emisión: ");  		
        $printer->text(date("d-m-Y") . "\n");      

    
}
    
/*****************************************/
    
   
$printer->text("N°". $detalle[0]->idPedido."\n");    

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

    
return $detalle[0]->folio;
    
}else{
    
return 0;    
    
}

}

function  imprimirTermicaBoletaVentas($detalle){
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
        
    
if($detalle[0]->folio  != ''){     
    
/*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else /*if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
    
    
    
$printer = new Printer($connector);
//Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;


// Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/

try{
	
	
$logo = EscposImage::load("img/cabeBoletaVenta.png", false);
//$printer->bitImage($logo);
$logo2 = EscposImage::load("img/pieBoleta.png", false);

$printer->text("\n");  	
$printer->setTextSize(1, 1);
/*$printer->text("JUAN CARLOS ARANIBAR CASTRO\n");  
$printer->text("16.466.904-1\n");*/
/*
$printer->text("IMP. Y VENTA DE ALIMENTOS, ACCESORIOS, ART ASEO
PARA MASCOTAS Y DIST LÁCTEOS\n");  
date_default_timezone_set("America/Mexico_City");*/
$printer->text("Fecha Entrega: ");      
$printer->text(date("d-m-Y") . "\n");    
$printer->text("Numero Venta");  
$printer->text(" N°: ". $detalle[0]->folio."\n");
/*$printer->text("ARICA-CHILE\n");    */
   
$printer->setTextSize(1, 1);    

$printer->text("-------------------------------------------". "\n");
/*$printer->text("Forma De Pago:" .$detalle[0]->FormaPago."\n");    */
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("CANT  DESCRIPCION                      TOTAL\n");

$printer->text("-------------------------------------------". "\n");
        foreach ($detalle AS $de){
            $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(" $de->cantidad  ");
                $printer->text("$de->nombreProd ");
                $printer->text(" $ ".$de->Precio ."\n");
       }  
   
     
$printer->text("-------------------------------------------". "\n");
$printer->text("                             TOTAL: ". $detalle[0]->TotalBol."\n");


$printer->setJustification(Printer::JUSTIFY_CENTER);

/*$printer->bitImage($logo2);

$printer->text("N°". $detalle[0]->idPedido."\n"); */  

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

	
	
}catch(Exception $e){


$printer->text($e->getMessage() . "\n");
}
    
    
    
}else{
    
    
    return 0;    
    
}
    
    
}

function generarCotizacionOCPDF($detalle){  
    $file = "pdf/CotizacionOC.html";
    $handle = fopen($file,"r");

    $html = fread($handle, filesize($file));


    
    $grid1 = '';
    $grid9 = '';
    
    $gridSalto = '';
    $gridSaltoMas = '';



    $contador = 0;
    $gridTable = '';
    
    $max = sizeof($detalle);
    
    $canti = 20 - $max;
    $cantiMas = 45 - $max;

    
    $gridTableSaltoLinea ="<table  width='70%' height: '100px';     style='font-size: 14px;  page-break-after:always;' align='center'>
                                <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA ORDEN DE COMPRA</th>                                     
                                </tr>
                                <tr style='font-size: 14px'>
									 <th width='15%'>CODIGO</th>
										<th width='70%'>DESCRIPCION</th>		
										<th width='15%'>CANT.</th>
								  </tr>";
    
    
    $gridTableSinSalto = "<table  width='70%' height: '100px';  style='font-size: 14px' align='center'>
                              <tr>
                                  <th class='Estilo8' COLSPAN='5'>DETALLE DE LA ORDEN DE COMPRA:</th>                                     
                                </tr>      
                              <tr style='font-size: 14px'>
                                                             <th width='15%' style='text-align:center'>CODIGO</th>
                                                                <th width='70%'  >DESCRIPCION</th>		
                                                                <th width='15%' style='text-align:center'>CANT.</th>
                              </tr>";
    
    
				 foreach ($detalle AS $de){	
        
        
                       if($contador < 20){
                                $grid1 .= '<tr>'.							
                                              '<td><div  class="Estilo3" style="text-align:center">'.$de->codProducto.'</div></td>'.
                                              '<td><div  class="Estilo3" >'.$de->nombreProd.'</div></td>'.
                                              '<td><div  class="Estilo4" style="text-align:center">'.$de->cantidad.'</div></td>'.		
                                          '</tr>';
                        }
                     
                     
                         if($contador >= 20){
                                    if($contador < 40){

                                         $grid9 .= '<tr>'.							
                                            '<td><div  class="Estilo3" style="text-align:center">'.$de->codProducto.'</div></td>'.
                                        '<td><div  class="Estilo3" style="text-align:center">'.$de->nombreProd.'</div></td>'.
                                            '<td><div  class="Estilo4" style="text-align:center">'.$de->cantidad.'</div></td>'.		
                                                   '</tr>';

                                    }
                         }
                     
                        $contador = $contador  + 1;

                           
               }
                           
                           
                       //   $grid1 .= "</table>";  
                      //    $grid9 .= "</table>";  

    
    
    for ($x = 0; $x <= $canti; $x++) {
             $gridSalto .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                           '</tr>';
    } 
    
    
    for ($x = 0; $x <= $cantiMas; $x++) {
             $gridSaltoMas .= '<tr>'.							
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '<td class="Estilo10"><div  class="Estilo10">HOLA</div></td>'.
                              '</tr>';
    } 
    
    
    if($max > 20){
       $gridTableSaltoLinea = $gridTableSaltoLinea . $grid1 . '</table>' . $gridTableSinSalto . $grid9 . $gridSaltoMas . '</table>' . '<br>';        
    }else{
       $gridTableSaltoLinea = $gridTableSinSalto .  $grid1 . $gridSalto . '</table>'  . '<br>';        
    }
    
  $gridTable = $gridTableSaltoLinea;
 


    
    $html = str_replace("#gridTable#",$gridTable,$html);

    //$html = str_replace("#grid9#",$grid9,$html);
    //$html = str_replace("#grid1#",$grid1,$html);	
    $html = str_replace("#grid2#",$grid2,$html);
    $html = str_replace("#grid3#",$grid3,$html);
    $html = str_replace("#grid4#",$grid4,$html);
    $html = str_replace("#grid5#",$grid5,$html);
    $html = str_replace("#grid6#",$grid6,$html);
    $html = str_replace("#grid7#",$grid7,$html);
    $html = str_replace("#grid8#",$grid8,$html);
    $html = str_replace("#grid10#",$grid10,$html);
    $html = str_replace("#grid11#",$grid11,$html);
    $html = str_replace("#grid12#",$grid12,$html);


    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper("letter", "portrait");   
    
    
    
    ini_set('output_buffering', true); // no limit
    ini_set('output_buffering', 12288);  
    ini_set('memory_limit', '-1');

    $dompdf->render();
    $dompdf->stream("certificado.pdf");
    

    return $dompdf;
}

function generaDTEBoleta(  $FchEmision,                           
                           $RznSoc, 
                           $Giro, 
                           $Comuna, 
                           $Direccion, 
                           $Detalles){


	global $Nubox;

	


	$filename = sys_get_temp_dir().DIRECTORY_SEPARATOR.time().'.csv';

	$fp = fopen($filename, 'w');




	$header_row = array(
        'TIPO',
        'FOLIO',
        'SECUENCIA',
        'FECHA',
        'RUT',
        'RAZONSOCIAL',
        'GIRO',
        'COMUNA',
        'DIRECCION',
        'AFECTO',
        'PRODUCTO',
        'DESCRIPCION',
        'CANTIDAD',
        'PRECIO',
        'PORCENTDSCTO',
        'EMAIL',
        'TIPOSERVICIO',
        'PERIODODESDE',
        'PERIODOHASTA',
        'FECHAVENCIMIENTO',
        'CODSUCURSAL',
        'VENDEDOR',
        'CODRECEPTOR',
        'CODITEM',
        'UNIDADMEDIDA',
        'PORCENTDSCTO2',
        'PORCENTDSCTO3',
        'CODIGOIMP',
        'MONTOIMP',
        'INDICADORTRASLADO',
        'FORMAPAGO',
        'MEDIOPAGO',
        'TERMINOSPAGOSDIAS',
        'TERMINOSPAGOCODIGO',
        'COMUNADESTINO',
        'RUTSOLICITANTEFACTURA',
        'PRODUCTOCAMBIOSUJETO',
        'CANTIDADMONTOCAMBIOSUJETO',
        'TIPOGLOBALAFECTO',
        'VALORGLOBALAFECTO',
        'TIPOGLOBALEXENTO',
        'VALORGLOBALEXENTO',
        'PRECIOCAMBIOSUJETO',
        'INDICADORMONTOSBRUTOS');


    

	//Insertamos la fila encabezado en el CSV

	fputcsv($fp, $header_row, ';', '"');



	//Por cada detalle creamos una fila en el CSV

	//Partimos en 1 ya que el valor de SECUENCIA, parte en 1

	for ($i=1; $i <= count($Detalles); $i++) { 
         $row ="";
		//Creamos una referencia al detalle actual

		$d = $Detalles[$i-1];

		//Insertamos los detalles de la fila, copiando en encabezado cada vez, dejamos en blanco las columnas que no usaremos

		//El folio lo dejamos siempre en 1, configuraremos que NUBOX se encarge de darnos los folios

		$row = array('39', 
                     '1', 
                     $i, 
                     $FchEmision, 
                     '55555555-5', 
                     $RznSoc, 
                     $Giro, 
                     $Comuna, 
                     $Direccion, 
                     $d["Afecto"], 
                     $d["Nombre"], 
                     $d['Descripcion'], 
                     $d['Cantidad'], 
                     $d['Precio'], 
                     '0', 
                     '', 
                     '3', 
                     '', 
                     '', 
                     '', 
                     '00001', 
                     '', 
                     '', 
                     $d["Codigo"], 
                     '', 
                     '', 
                     '', 
                     '',
                     '', 
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     'NO');

		//Insertamos la fila en el CSV

		fputcsv($fp, $row, ';'," ");

	}



	//Cerramos el archivo para escritura

	fclose($fp);

	//*** PROCESO DE AUTENTICACION ***//

	$resp = $Nubox->autenticar();

	if( $resp == "OK"){
        
		//*** PROCESO DE ENVIO DE DTE ***//
        $ident = $Nubox->enviarCSV($filename);
        
            // var_dump($ident); 

        
       
        
 $folio = xml_attribute($ident->Documentos->Documento,'Folio');
 $identificador = $ident->Identificador;
 $tipo = xml_attribute($ident->Documentos->Documento,'Tipo');
 $resultado = $ident->Descripcion;

		//echo $ident->Identificador;

     /*
	if( $resultado == ""){        
        
        $resultInsertFac =  insertarNubox($folio, $identificador,$tipo, $idPedido);		
		return $resultInsertFac;
        
    }else{
        echo "$folio;;;$identificador;;;$tipo;;;$resultado;;;$idPedido";
        return 0;
        
    }
        */
        
        
	} else {

		echo $resp;

		return $resp;

	}

	//Eliminamos el archivo CSV

	unlink($filename);

}


function  imprimirTermicaInicioCaja($idCaja){		
    
    if($idCaja!=""){

/*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
        
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
          
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.



# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
    
	el logo
*/


	
	/*
$logo = EscposImage::load("img/cabeBoletaVenta.png", false);
$printer->bitImage($logo);*/
//$logo2 = EscposImage::load("img/pieBoleta.png", false);

//$printer->text("\n");  	
$printer->setTextSize(1, 1);
date_default_timezone_set("America/Mexico_City");
$printer->text("Fecha Emisión: ");      
$printer->text(date("d-m-Y") . "\n");    
$printer->text("Numero Caja");  
$printer->text(" N°: ".$idCaja."\n");   
$printer->setTextSize(1, 1);    

$printer->text("------------------------------------". "\n");
$printer->text("VALIDAR CAJA POR FAVOR\n");
$printer->text("------------------------------------". "\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
/*$printer->bitImage($logo2);

$printer->text("N°". $detalle[0]->idPedido."\n"); */  

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

	
	

        
        
    }else{
        return 0;
    }
        
}



function generaDTEBoletaCert($FchEmision,                           
                               $Afecto,
                               $DevID,
                               $Detalles){

	
	global $NuboxCert;
	
	//Creamos el objecto que será el JSON
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
    	    "rutContraparte"    =>  "66666666-6",   //Boleta Anónima
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

   


function generarPedidoAplicacion($FchEmision,                           
                               $Nombres,
                               $Email,
                               $Telefono,
                               $Direccion,
                               $FormaPago,
                               $Total,
                               $Detalle,
                               $DevID){
    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->generarPedidoAplicacion(date('Y-m-d', strtotime($FchEmision)),                           
                               $Nombres,
                               $Email,
                               $Telefono,
                               $Direccion,
                               $FormaPago,
                               $Total,
                               $Detalle,
                               $DevID);
}


function generarCanjearAplicacion($idProd, $DevID){    
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->generarCanjearAplicacion($idProd, $DevID);
}







function imprimirTermicaReciboDinero($detalle){	

    
 /*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
     global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;     
        
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else /*if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
       

    
    
$printer = new Printer($connector);
//Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;


$printer->setJustification(Printer::JUSTIFY_CENTER);



try{
   //	$logoCabe = EscposImage::load('img/cabeBoleta.png', false);


}catch(Exception $e){

}


 //$printer->bitImage($logoCabe);
    
$printer->setTextSize(2, 2);
$printer->text("RECIBO DINERO ARANIBAR\n");  
$printer->text("He recibido de: ". $detalle[0]->nombCliente."\n");
date_default_timezone_set("America/Mexico_City");
$printer->text("Fecha Pago: ");      
$printer->text(date("d-m-Y") . "\n");    
$printer->setTextSize(3, 3); 
$printer->text("N Recibo: ". $detalle[0]->nRecibo."\n");
    

//La fecha también
$printer->setTextSize(1, 1);    

$printer->text("----------------------------------------------". "\n");
$printer->text("Forma De Pago:" .$detalle[0]->formaPago."\n");    
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("#   FOLIO      NOTA                    TOTAL\n");

$printer->text("----------------------------------------------". "\n");

    
    
    	foreach ($detalle AS $de){	
            
            
                $printer->text(" $de->conteo  ");
            
            if(empty($de->folio)){
                $printer->text(" ---- ");
             }else{
                $printer->text(" $de->folio  ");
             }
            
             if(empty($de->nota)){
                $printer->text("      ---- ");
             }else{
                $printer->text("      $de->nota  ");
             }
             $printer->text("           $ ".$de->total ."\n");
        }   
    
    

 
$printer->text("----------------------------------------------". "\n");
$printer->text("                             TOTAL: ". $detalle[0]->totalRecibo."\n");
$printer->text("                             ABONO: ". $detalle[0]->totalAbono."\n");
$printer->text("                             SALDO: ". $detalle[0]->totalSaldo."\n");



$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();


}




function  imprimirTermicaFinCaja($idCaja, 
                                 $efectivo, 
                                 $debito, 
                                 $transferencia, 
                                 $descuento, 
                                 $totalVenta,
                                 $gastoLocal,
                                  $detalle){		


/*****************************************/    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;      
        
 $idUsuario = $detalle[0]->id_usuario;   
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
/******************************************/    
    
    
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.



# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/

try{
	
	/*
$logo = EscposImage::load("img/cabeBoletaVenta.png", false);
$printer->bitImage($logo);
$logo2 = EscposImage::load("img/pieBoleta.png", false);

$printer->text("\n");  	*/
$printer->setTextSize(1, 1);
date_default_timezone_set("America/Mexico_City");
$printer->text("-----------------------------------------". "\n");
$printer->text("Cierre de Caja: ");      
$printer->text(date("d-m-Y") . "\n");    

$printer->text("Numero Caja");  
$printer->text(" N° ".$idCaja."\n");       
$printer->text("-----------------------------------------". "\n");    
$printer->text("Efectivo");  
$printer->text(" $ ".$efectivo."\n");   
    
$printer->text("Debito");  
$printer->text(" $ ".$debito."\n");   
    
$printer->text("Transferencia");  
$printer->text(" $ ".$transferencia."\n");  
    
$printer->text("Total Venta");  
$printer->text(" $ ".$totalVenta."\n");  
    
$printer->text("Desc Gastos");  
$printer->text(" $ ".$gastoLocal."\n");       
    
$printer->text("Desc Prod. en Ventas");  
$printer->text(" $ ".$descuento."\n");   
    
    
    
    
    
$printer->setTextSize(2, 2);
$printer->text("RESUMEN VENTAS"."\n");

#La fecha también
$printer->setTextSize(1, 1);    
$printer->text("-----------------------------------------". "\n");
$printer->text("ID.PED    FOLIO   MONTO   FORMA\n");
$printer->text("-----------------------------------"."\n\n");

           
    	foreach ($detalle AS $de){	
                $printer->text("$de->id_pedido     ");
                $printer->text("    $de->folio   ");
                $printer->text("    $de->total   ");
            
                if($de->anulada == "S"){
                    $printer->text("Anulado"."\n\n");
                }else{
                    $printer->text("$de->nombre_cobro"."\n\n");

                }

                 
			}   
    
    

    
    
$printer->setTextSize(1, 1);    

$printer->text("-----------------------------------------". "\n");
$printer->text("VALIDAR CAJA POR FAVOR\n");
$printer->text("-----------------------------------------". "\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
/*$printer->bitImage($logo2);

$printer->text("N°". $detalle[0]->idPedido."\n"); */  

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

	
	imprimirTermicaCajaListadoProductos($idUsuario);
    
    
    
}catch(Exception $e){


$printer->text($e->getMessage() . "\n");


}
    
  	return 1;  
}




function  imprimirTermicaFacturas(){		

    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
        
    
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
        
    
    
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;


# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/

try{
	
	


$printer->text("\n");  	
$printer->setTextSize(1, 1);
date_default_timezone_set("America/Mexico_City");
$printer->text("------- DISTRIBUIDORA ARANIBAR ---------". "\n\n");    
$printer->text("Quien Recibio:          Fecha Ingreso:__/__/__". "\n\n");      
$printer->text("----------------------------------------". "\n\n");    
$printer->text("Efectivo __    Transferencia __   Documento __". "\n\n");  

    
$printer->text("Observacion:". "\n\n\n\n\n\n\n\n");  

    
    

$printer->text("----------------------------------------". "\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
/*$printer->bitImage($logo2);

$printer->text("N°". $detalle[0]->idPedido."\n"); */  

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

	
	
}catch(Exception $e){


$printer->text($e->getMessage() . "\n");


}
    
    
    
}







function  imprimirTermicaObservacionInformeTransporte($detalle){		
    
    
    

if( $detalle !="" ){ 
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
//192.168.1.13 IMPRESORA TRANSPORTE
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.13", 9100);   
    
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
    
    
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.
echo 1;


# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/

try{
	
	


$printer->setTextSize(1, 1);
date_default_timezone_set("America/Mexico_City");
$printer->text("- OBSERVACION TRANSPORTE ARANIBAR -". "\n\n");    
$printer->text("Nombre Chofer:                  ". "\n\n");      
$printer->text("--------------------------------". "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);    
$printer->text($detalle[0]->observacion. "\n");  
    
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Observacion:". "\n\n");      


    
    

$printer->text("------Nombre del Validador------". "\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
/*$printer->bitImage($logo2);

$printer->text("N°". $detalle[0]->idPedido."\n"); */  

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

	
	
}catch(Exception $e){


$printer->text($e->getMessage() . "\n");
}
    
} 
    
    
}






function  imprimirTermicaCajaListadoProductos($idUsuario){		
    
   
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
$detalleProd = joinPedidosTiendas($idUsuario,  date("Y-m-d")   , date("Y-m-d")  );    
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;     
        
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
          $connector = new NetworkPrintConnector("192.168.1.123", 9100);   

     
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
} 
    
    
    
$printer = new Printer($connector);
echo 1;


$printer->setJustification(Printer::JUSTIFY_CENTER);





$printer->setTextSize(2, 2);
$printer->text("RESUMEN SALIDAS". $detalle[0]->idPedido."\n");

#La fecha también
$printer->setTextSize(1, 1);    
date_default_timezone_set("America/Mexico_City");
$printer->text(date("d-m-Y H:i:s") . "\n");
$printer->text("-----------------------------------"."\n");
$printer->text("NOMBRE\n");
$printer->text("     ID PROD  CANTIDAD \n");    
$printer->text("-----------------------------------"."\n\n");

    
$printer->setJustification(Printer::JUSTIFY_LEFT);    
    	foreach ($detalleProd AS $de){	
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("$de->nombreProd"."\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("$de->id_prod - "); 
                $printer->text("$de->cantidad "."\n");
			}   
    
    

    

$printer->text("---------------------------------------"."\n");


$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();


}



function generaDTE($TpoDTE, 
                   $FchEmision, 
                   $Rut, 
                   $RznSoc, 
                   $Giro, 
                   $Comuna, 
                   $Direccion, 
                   $Email, 
                   $IndTraslado, 
                   $ComunaDestino, 
                   $Vendedor, 
                   $FormaPago,
                   $TerminosPagos,
                   $Detalles,                  
                   $idPedido,
                   $TipoServicio){

	

	//*** PROCESO DE CREACION CSV ***//

	global $Nubox;

	

	//Una fila por detalle, con el encabezado repetido

	$filename = sys_get_temp_dir().DIRECTORY_SEPARATOR.time().'.csv';

	$fp = fopen($filename, 'w');



	//La primera fila debe ser siempre igual en los CSV

	$header_row = array(
        'TIPO',
        'FOLIO',
        'SECUENCIA',
        'FECHA',
        'RUT',
        'RAZONSOCIAL',
        'GIRO',
        'COMUNA',
        'DIRECCION',
        'AFECTO',
        'PRODUCTO',
        'DESCRIPCION',
        'CANTIDAD',
        'PRECIO',
        'PORCENTDSCTO',
        'EMAIL',
        'TIPOSERVICIO',
        'PERIODODESDE',
        'PERIODOHASTA',
        'FECHAVENCIMIENTO',
        'CODSUCURSAL',
        'VENDEDOR',
        'CODRECEPTOR',
        'CODITEM',
        'UNIDADMEDIDA',
        'PORCENTDSCTO2',
        'PORCENTDSCTO3',
        'CODIGOIMP',
        'MONTOIMP',
        'INDICADORTRASLADO',
        'FORMAPAGO',
        'MEDIOPAGO',
        'TERMINOSPAGOSDIAS',
        'TERMINOSPAGOCODIGO',
        'COMUNADESTINO',
        'RUTSOLICITANTEFACTURA',
        'PRODUCTOCAMBIOSUJETO',
        'CANTIDADMONTOCAMBIOSUJETO',
        'TIPOGLOBALAFECTO',
        'VALORGLOBALAFECTO',
        'TIPOGLOBALEXENTO',
        'VALORGLOBALEXENTO',
        'PRECIOCAMBIOSUJETO',
        'INDICADORMONTOSBRUTOS');



	//Insertamos la fila encabezado en el CSV

	fputcsv($fp, $header_row, ';', '"');



	//Por cada detalle creamos una fila en el CSV

	//Partimos en 1 ya que el valor de SECUENCIA, parte en 1

	for ($i=1; $i <= count($Detalles); $i++) { 
         $row ="";
		//Creamos una referencia al detalle actual

		$d = $Detalles[$i-1];

		//Insertamos los detalles de la fila, copiando en encabezado cada vez, dejamos en blanco las columnas que no usaremos

		//El folio lo dejamos siempre en 1, configuraremos que NUBOX se encarge de darnos los folios

		$row = array($TpoDTE, 
                     '1', 
                     $i, 
                     $FchEmision, 
                     $Rut, 
                     $RznSoc, 
                     $Giro, 
                     $Comuna, 
                     $Direccion, 
                     $d["Afecto"], 
                     $d["Nombre"], 
                     $d['Descripcion'], 
                     $d['Cantidad'], 
                     $d['Precio'], 
                     '0', 
                     $Email, 
                     $TipoServicio, 
                     '', 
                     '', 
                     '', 
                     '00001', 
                     $Vendedor, 
                     '', 
                     $d["Codigo"], 
                     '', 
                     '', 
                     '', 
                     '',
                     '', 
                     $IndTraslado,
                      $FormaPago,
                     '',
                      $TerminosPagos,
                     '',
                     $ComunaDestino,
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     '',
                     'NO');

		//Insertamos la fila en el CSV

		fputcsv($fp, $row, ';'," ");

	}



	//Cerramos el archivo para escritura

	fclose($fp);

	//*** PROCESO DE AUTENTICACION ***//

	$resp = $Nubox->autenticar();

	if( $resp == "OK"){
        
                //*** PROCESO DE ENVIO DE DTE ***//
                $ident = $Nubox->enviarCSV($filename);

                    // var_dump($ident); 




         $folio = xml_attribute($ident->Documentos->Documento,'Folio');
         $identificador = $ident->Identificador;
         $tipo = xml_attribute($ident->Documentos->Documento,'Tipo');
         $resultado = $ident->Descripcion;

                //echo $ident->Identificador;


            if( $resultado == ""){      
                $resultInsertFac =  insertarNubox($folio, $identificador,$tipo, $idPedido);		
                return "$folio;;;$tipo;;;$identificador";

            }else{
                echo "$folio;;;$identificador;;;$tipo;;;$resultado;;;$idPedido";
                return 0;
            }
        
        
        
	} else {

	//	echo $resp;

		return $resp;

	}

	//Eliminamos el archivo CSV

	unlink($filename);

}


 function ingresarVenta($arrVenta, $descuentoPunto){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->ingresarVenta($arrVenta, $descuentoPunto);
}



function generarBoletaVentas($arrVenta, $descuentoPunto, $rutPunto){
	$Afecto = "SI";
	global $NuboxCert;
    $i = 1;
	
	//Creamos el objecto que será el JSON
	$JSON = array(
	    "afecto"            =>  $Afecto === "SI",
	    "productos"         =>  array()
	);

    $total = 0;

	//Partimos en 1 ya que el valor de SECUENCIA, parte en 1
     foreach ($arrVenta AS $de){	
    
	//for ($i=1; $i <= count($arrVenta); $i++) { 

		//Creamos una referencia al detalle actual
	//	$d = $arrVenta[$i-1];

		//El folio lo dejamos siempre en 1, configuraremos que NUBOX se encarge de darnos los folios
		
      

		$row = array(
    	    "fechaEmision"      =>  date("Y-m-d"),
    	    "folio"             =>  1,
    	    "rutContraparte"    =>  "55555555-5",   //Boleta Anónima
    	    "comunaContraparte" =>  "Arica",        //Igual que el emisor
    	    "razonSocialContraparte"    =>  ".",
            "giroContraparte"   =>  ".",
            "direccionContraparte"  =>  "Arica",
    	    "codigoSucursal"    =>  "00001",
    	    "afecto"            =>  $Afecto,
    	    "producto"          =>  $de->nombreNubox,
    	    "valor"             =>  $de->totalNubox,
    	    "cantidad"          =>  $de->cantidad,
    	    "fechaVencimiento"  =>  date("Y-m-d"),
    	    "fechaPeriodoDesde" =>  date("Y-m-d"),
    	    "fechaPeriodoHasta" =>  date("Y-m-d"),
    	    "codigoSIITipoDeServicio"   => 3,
    	    "secuencia"         =>  $i
    	);
    	
         $i = $i + 1 ;
         
    	$total += intval($de->totalNubox);
    	
    	$JSON["productos"][] = $row;

	}

	//*** PROCESO DE AUTENTICACION ***//

 $resp = $NuboxCert->autenticar();
//	$resp = "OK";

     if ($descuentoPunto > 0) {
         $responsePunto = obtenerPuntos($rutPunto);
         $objPunto = json_decode($responsePunto);
         if ($descuentoPunto > $objPunto->data) {
             return 0;
         }
     }
	if( $resp == "OK"){
        
        $JSON_NUBOX = $JSON;
        $JSON_NUBOX["productos"] = array(
            array(
                "fechaEmision"      =>  date("Y-m-d"),
                "folio"             =>  1,
                "rutContraparte"    =>  "55555555-5",   //Boleta Anónima
                "comunaContraparte" =>  "Arica",        //Igual que el emisor
                "razonSocialContraparte"    =>  ".",
                "giroContraparte"   =>  ".",
                "direccionContraparte"  =>  "Arica",
                "codigoSucursal"    =>  "00001",
                "afecto"            =>  $Afecto,
                "producto"          =>  "Venta de Productos",
                "valor"             =>  $total - $descuentoPunto,
                "cantidad"          =>  1,
                "fechaVencimiento"  =>  date("Y-m-d"),
                "fechaPeriodoDesde" =>  date("Y-m-d"),
                "fechaPeriodoHasta" =>  date("Y-m-d"),
                "codigoSIITipoDeServicio"   => 3,
                "secuencia"         =>  1
            )
        );

        $arr = $NuboxCert->enviaBoleta($JSON_NUBOX);
      //  $arr = array("folio"=>-1000);
        $ret = json_encode($arr);
        
        /*
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia = new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		$instancia->insertarBoleta($total, date('Y-m-d', strtotime($FchEmision)), $DevID, $arr["folio"], $Afecto === "SI");
        */
        
        
     if($arr["folio"] != ""){
     
       $idPedidoIngresarVenta =  ingresarVenta($arrVenta, $descuentoPunto);
       $resultInsertFac       =  insertarNubox($arr["folio"], '', '', $idPedidoIngresarVenta);
      
       
         
         /*****************POST GUARDAR ARCHIVO SII LOCAL *********************/
         $toPost=array("idFolio" => $idPedidoIngresarVenta, "imagen"=> $arr["pdf417"]);
         
         $curl = curl_init();

            curl_setopt_array($curl, array(
            //  CURLOPT_URL => "https://aranibar.cl/Tienda/Funciones.php?act=subirArchivoFolio",
            CURLOPT_URL => "http://localhost/Tienda/Funciones.php?act=subirArchivoFolio",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$toPost,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           // echo $response;
           //////////*********************************************/////////////
         
         
         
            
         /*****************POST GUARDAR ARCHIVO SII SERVIDOR *********************/
         $toPost=array("idFolio" => $idPedidoIngresarVenta, "imagen"=> $arr["pdf417"]);
         
         $curl = curl_init();

            curl_setopt_array($curl, array(
            //  CURLOPT_URL => "https://aranibar.cl/Tienda/FunctionIntranet.php?act=subirArchivoFolio",
            CURLOPT_URL => "https://aranibar.cl/barrosaranas/Funciones.php?act=subirArchivoFolio",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$toPost,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           // echo $response;
           //////////*********************************************/
         
 
            //boletaTiendaVentasPDF($idPedidoIngresarVenta, "Tienda", $descuentoPunto);
            //ticketsRetiroBodega($idPedidoIngresarVenta);
	    
	    
            boletaTiendaVentasPDF($idPedidoIngresarVenta, "Tienda");
            ticketsRetiroBodega($idPedidoIngresarVenta);
            

         return $idPedidoIngresarVenta;//1;
     }else{
         
           return 0;
     }
        
       
         
         
    
        
      
        
	} else {

		return 0;

	}

}





function subirArchivoFolio($idFolio, $imagen){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->subirArchivoFolio($idFolio, $imagen);
}

function listaDetalleVentas($idVenta){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listaDetalleVentas($idVenta);
}


function listaDetalleTiendas($idVenta, $tienda){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		return $instancia->listaDetalleTiendas($idVenta,$tienda);
}










function boletaTiendaVentasPDF($idPedido, $imprimir){
    
    
if($idPedido != ""){    
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   

    
$detalleOfertaDesc = listarOfertasProductosDescuento();
$detalleVenta      = listaDetalleVentas($idPedido);     
$banderaOferta     = false;    
    

    
    foreach ($detalleOfertaDesc AS $of){	
            foreach ($detalleVenta['detalles'] AS $de){
                   if($of->id_prod == $de->id_prod){
                      
                       $banderaOferta =true;
                         
                       break;
                    }
    
             }
    }


$descuentoPunto  = $detalleVenta['pedidoDescuento'];
$idFolio         = $detalleVenta['folio'];
$fechaVenta      = $detalleVenta['fecha'];
$idPedido        = $detalleVenta['id_pedido'];
//$idPedido        = $detalleVenta['idPedido'];
    
$formaPago       = $detalleVenta['nombre'];

 /*   
$nombre_impresora = $nombreCompartidoImpresora; 
$connector = new WindowsPrintConnector($nombre_impresora);*/

    
//$connector = new NetworkPrintConnector("192.168.1.123", 9100);    
   
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($imprimir == "Tienda"){
    
        if($ambiente == "1"){

              $connector = new NetworkPrintConnector("192.168.1.123", 9100);   

        }else /*if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{    


              $connector = new WindowsPrintConnector($nombre_impresora);
        }
    
}else if($imprimir == "SupervisorTransporte"){
    
       $connector = new NetworkPrintConnector("192.168.1.13", 9100);   /*Impresora de Supervisor de transportes*/
}
    
    
$printer   = new Printer($connector);



$printer->setJustification(Printer::JUSTIFY_CENTER);
$logoCabe = EscposImage::load("img/cabeceraVentaTienda.png", false);
$printer -> bitImageColumnFormat($logoCabe);


 
$printer->setTextSize(1, 1);
$printer->text($nombreRazonSocial."\n");  
$printer->text($rutRazonSocial."\n");  
$printer->text($nombreDireccion."\n");        
$printer->text("IMP. Y VENTA DE ALIMENTOS, ACCESORIOS, ART ASEO
PARA MASCOTAS Y DIST LACTEOS\n");   
date_default_timezone_set("America/Mexico_City");
$printer->text("Fecha Emisión: ");      
$printer->text(date("d-m-Y") . "\n");    
$printer->text("BOLETA ELECTRONICA Folio");  
$printer->text(" N°: ".$idFolio."\n");
$printer->text("ARICA-CHILE\n");    
    

//La fecha también
$printer->setTextSize(1, 1);    
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Forma De Pago:" .$formaPago."\n");    
$printer->setJustification(Printer::JUSTIFY_CENTER);    
$printer->text("-----------------------------------". "\n");    
$printer->setJustification(Printer::JUSTIFY_LEFT);    
$printer->text("DESCRIPCION                            \n");
$printer->setJustification(Printer::JUSTIFY_CENTER);     
$printer->text("CANTIDAD X PRECIO  =  TOTAL \n");
$printer->setJustification(Printer::JUSTIFY_CENTER);        
$printer->text("-----------------------------------". "\n");

$fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
//$moneda = $fmt->formatCurrency(18000, "CLP");    
   
    $total = 0;
    	foreach ($detalleVenta['detalles'] AS $de){
            	$printer->setJustification(Printer::JUSTIFY_LEFT);               
                $printer->text("$de->nombreProd"."\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER); 
                $printer->text("$de->cantidad "." x "." ".$fmt->formatCurrency($de->precio_vendido, "CLP")."  =  ".$fmt->formatCurrency($de->total, "CLP")."\n");
               // $printer->text(" $ ".$de->Precio ."\n");
            
                 $total += intval($de->total);
         }   

 if ($descuentoPunto > 0) {
     $printer->setJustification(Printer::JUSTIFY_CENTER);
     $printer->text("-----------------------------------". "\n");
     $printer->text("      DESCUENTO: ".$fmt->formatCurrency($descuentoPunto, "CLP")."\n");
 }

     $printer->setJustification(Printer::JUSTIFY_CENTER);  
     $printer->text("-----------------------------------". "\n");
     $printer->text("      MONTO TOTAL: ".$fmt->formatCurrency($total-$descuentoPunto, "CLP")."\n");



    
$urlFolio = "Table/imagenFolio/".$idPedido.".png";

$printer->setJustification(Printer::JUSTIFY_CENTER);
$logoDetalle = EscposImage::load($urlFolio, false);
$printer -> bitImageColumnFormat($logoDetalle);    
    
 $printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("TIMBRE ELECTRONICO SII\n");     
$printer->text("Res. 80 del 22/08/2014\n");     
 
 $printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Verifique su documento en\n");     
$printer->text("www.sii.cl\n");         
    
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("N° ".$idPedido."\n");    

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();
    
    
 /**************************************/   
    
    
if($banderaOferta == true){

   boletaOfertaProd();
    
}    
    
    
/*****************************************/    

    
return 1;
    
}
    
    
}








function boletaOfertaProd(){
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;   
       
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
$nombre_impresora = $nombreCompartidoImpresora;     
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.123", 9100);   
    
}else/* if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
}
    
    
    
$printer = new Printer($connector);
    
$printer->setJustification(Printer::JUSTIFY_CENTER);
$logoCabe = EscposImage::load("img/descuentoVentaTienda.png", false);
$printer -> bitImageColumnFormat($logoCabe);
$printer -> feed();
$printer->text("Fecha Emisión: ");  		
$printer->text(date("d-m-Y") . "\n");  
$printer->feed(3);
$printer->cut();


 
    
$printer->setJustification(Printer::JUSTIFY_CENTER);
$logoCabe = EscposImage::load("img/descuentoVentaTiendaArenas.png", false);
$printer -> bitImageColumnFormat($logoCabe);
$printer -> feed();
$printer->text("Fecha Emisión: ");  		
$printer->text(date("d-m-Y") . "\n");  
$printer->feed(3);
$printer->cut();
    
$printer->pulse();
$printer->close();    
    
    
    
}







function ticketsRetiroBodega($idPedido){
    
    
//192.168.1.123 IMPRESORA BOLETAS
//192.168.1.153 IMPRESORA TICKET BODEGA
    
    
    global $nombreRazonSocial,
           $rutRazonSocial,
           $ambiente,
           $nombreCompartidoImpresora,
           $nombreDireccion;      
    
    
$nombre_impresora = $nombreCompartidoImpresora; 
 //$connector = new WindowsPrintConnector($nombre_impresora);    
    
if($ambiente == "1"){
    
      $connector = new NetworkPrintConnector("192.168.1.153", 9100);   
    
}else /*if( $ambiente == "2" || $ambiente == "3" || $ambiente == "4")*/{        
      
      $connector = new WindowsPrintConnector($nombre_impresora);
}    
    
    
$printer   = new Printer($connector);    
    
$detalleVenta      = listaDetalleVentas($idPedido);

$descuentoPunto  = $detalleVenta['pedidoDescuento'];
$idFolio         = $detalleVenta['folio'];
$fechaVenta      = $detalleVenta['fecha'];
$idPedido        = $detalleVenta['id_pedido'];
$formaPago       = $detalleVenta['nombre'];
    
    
//$nombre_impresora = "XP-80C";    
//$connector = new WindowsPrintConnector($nombre_impresora);

    


$printer->setJustification(Printer::JUSTIFY_CENTER); 
$printer->setTextSize(3, 3);
$printer->text($idPedido."\n");  
$printer->setTextSize(1, 1);    
$printer->text("Fecha Emisión: ");      
$printer->text(date("d-m-Y") . "\n");  
    
$printer->setJustification(Printer::JUSTIFY_CENTER);    
$printer->text("-----------------------------------". "\n");    
$printer->setJustification(Printer::JUSTIFY_CENTER);     
$printer->text("CANTIDAD - DESCRIPCION\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);        
$printer->text("-----------------------------------". "\n");

    $total = 0;
    	foreach ($detalleVenta['detalles'] AS $de){
            	$printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("$de->cantidad"." - "."$de->nombreProd"."\n");             
            
         }   
    

$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();
    
    
    
return 1;
    

    
    
}









function generarBoletaDesdeListadoPedidos($idPedido){
    
    $arrVenta      = listaDetalleVentas($idPedido);     
    


	$Afecto = "SI";
	global $NuboxCert;
    $i = 1;
	
	//Creamos el objecto que será el JSON
	$JSON = array(
	    "afecto"            =>  $Afecto === "SI",
	    "productos"         =>  array()
	);

    $total = 0;

     foreach ($arrVenta['detalles'] AS $de){



		$row = array(
    	    "fechaEmision"      =>  date("Y-m-d"),
    	    "folio"             =>  1,
    	    "rutContraparte"    =>  "66666666-6",   //Boleta Anónima
    	    "comunaContraparte" =>  "Arica",        //Igual que el emisor
    	    "razonSocialContraparte"    =>  ".",
            "giroContraparte"   =>  ".",
            "direccionContraparte"  =>  "Arica",
    	    "codigoSucursal"    =>  "00001",
    	    "afecto"            =>  $Afecto,
    	    "producto"          =>  "Venta de Productos Transp",
    	    "valor"             =>  $de->precio_vendido,
    	    "cantidad"          =>  $de->cantidad,
    	    "fechaVencimiento"  =>  date("Y-m-d"),
    	    "fechaPeriodoDesde" =>  date("Y-m-d"),
    	    "fechaPeriodoHasta" =>  date("Y-m-d"),
    	    "codigoSIITipoDeServicio"   => 3,
    	    "secuencia"         =>  $i
    	);
    	
         $i = $i + 1 ;
         
    	$total += intval($de->precio_vendido);
    	
    	$JSON["productos"][] = $row;

	}

	//*** PROCESO DE AUTENTICACION ***//

	$resp = $NuboxCert->autenticar();

	if( $resp == "OK"){
        
        
        

        $arr = $NuboxCert->enviaBoleta($JSON);
        $ret = json_encode($arr);
        
        /*
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
		$instancia = new bdBoletaDispositivo($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
		$instancia->insertarBoleta($total, date('Y-m-d', strtotime($FchEmision)), $DevID, $arr["folio"], $Afecto === "SI");
        */
        
        
     if($arr["folio"] != ""){
     
     
       $resultInsertFac       =  insertarNubox($arr["folio"], '', '39', $idPedido);		            
      
       
         
         /*****************POST GUARDAR ARCHIVO SII LOCAL *********************/
         $toPost=array("idFolio" => $idPedido, "imagen"=> $arr["pdf417"]);
         
         $curl = curl_init();

            curl_setopt_array($curl, array(
 
              CURLOPT_URL => "http://192.168.1.128:80/Tienda/Funciones.php?act=subirArchivoFolio",   
                
                

              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$toPost
            ));

            $response = curl_exec($curl);
         
            if ( curl_errno($curl) ) {
                $errormsg=sprintf( "<b>Error</b>:%s<hr />",curl_error($curl) );
                $response=json_encode( array( "error"=>$errormsg) );
            }
                  
         
        //  echo "tirando excepcion " , curl_error($curl);

            curl_close($curl);
           // echo $response;
           //////////*********************************************/////////////
         
         
         
            
         /*****************POST GUARDAR ARCHIVO SII SERVIDOR *********************/
         $toPost=array("idFolio" => $idPedido, "imagen"=> $arr["pdf417"]);
         
         $curl = curl_init();

            curl_setopt_array($curl, array(
            //  CURLOPT_URL => "https://aranibar.cl/Tienda/Funciones.php?act=subirArchivoFolio",
            CURLOPT_URL => "https://aranibar.cl/barrosaranas/Funciones.php?act=subirArchivoFolio",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$toPost
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           // echo $response;
           //////////*********************************************/
         
 
   //         boletaTiendaVentasPDF($idPedido);

         return $arr["folio"];
     }else{
        
           return 0;
     }
        
       
         
         
    
        
      
        
	} else {
         
		return 0;

	}

}

function generarNotaCreditoSII($data){
    global $NuboxTest;
    $resp = $NuboxTest->autenticar();

	if( $resp == "OK"){
        $nuboxResp = $NuboxTest->enviaDTE($data);

        if($nuboxResp == null || strlen($nuboxResp) > 20){ 
            //Si es una respuesta larga, es un mensaje de error y no folio
            return $nuboxResp;
        }

        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
        $instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
        $bdRet = $instancia->guardaFolioCredito($nuboxResp, $data["id_credito"]);

        if($bdRet == 1)
            return "OK";
        else return $bdRet;
    }
}



function listarDescuentos(){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdPedido($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->listarDescuentos();
}





function listEscalaProducto( $idMarca, $idCategoria){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->listEscalaProducto( $idMarca, $idCategoria);
}



    function consultarRelacionProductoMarca( $codMarca, $codCategoria){
        global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
        $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
        return $instancia->consultarRelacionProductoMarca( $codMarca, $codCategoria);
    }


function insertarEscalaProd($idProd, $idMarca, $idCategoria){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->insertarEscalaProd($idProd, $idMarca, $idCategoria);
}


function borrarProdEscala($idEscala){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->borrarProdEscala($idEscala);
}



function actualizarProdEscala($idEscala, $pescala, $descripEsc, $catMinima){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdStock($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->actualizarProdEscala($idEscala, $pescala, $descripEsc, $catMinima);
}

function actualizarMarca($codMarca, $nombreMarca, $codCategoria, $activoMarca){
    global $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName;
    $instancia= new bdProducto($dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName);
    return $instancia->actualizarMarca($codMarca, $nombreMarca, $codCategoria, $activoMarca);
}





?>