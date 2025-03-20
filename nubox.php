<?php

class NuboxConnect{
/*
	private $TOKEN;
	private $TOKENTIME;
	private $AUTH_URL 	= 'https://www.servipyme.cl/Perfilamiento/Ws/Autenticador.asmx?WSDL';
	private $SEND_URL	= 'https://www.servipyme.cl/ServiFacturaCert/WebServices/ArchivoDeVentasElectronicas.asmx?WSDL';
	private $RUTCLIENTE = '10821074-5';
	private $RUTUSUARIO = '25000001-4';
	private $CONTRASENA = 'nubox.co';
	private $SISTEMA 	= 1; //1: Certificación, 2: Producción
	private $NUMSERIE 	= 2;*/
    
    
   	private $TOKEN;
	private $TOKENTIME;
	private $AUTH_URL 	= 'https://ws.nubox.com/Perfilamiento/Ws/Autenticador.asmx?WSDL';
	//private $SEND_URL	= 'http://ws.nubox.com/ServiFactura/WebServices/ArchivoDeVentasElectronicas.asmx?WSDL';
 	private $SEND_URL	= 'https://ws.nubox.com/ServiFactura/WebServices/ArchivoDeVentasElectronicas.asmx?WSDL';
   /*
    private $RUTCLIENTE = '16466904-1';
	private $RUTUSUARIO = '16466904-1';
	private $CONTRASENA = 'aranibar777';    
    */
    
        private $RUTCLIENTE = '77839652-1';
	    private $RUTUSUARIO = '15980152-7';
	    private $CONTRASENA = 'aranibar3033'; 
    
    
    /*
    private $RUTCLIENTE = '19045686-2';
	private $RUTUSUARIO = '19045686-2';
	private $CONTRASENA = 'aranibar007';
    */
  /*  private $RUTCLIENTE = '19045686-2';
	private $RUTUSUARIO = '19045686-2';
    
    
	private $CONTRASENA = 'aranibar007';*/

    
	private $SISTEMA 	= 2; //1: Certificación, 2: Producción
	private $NUMSERIE 	= 1;
    
    
	function __construct(){
		
	}

	public function autenticar(){
		//Checkear si ya está autenticado o si el token ya expiró
		if(!isset($TOKEN) || (time() - $TOKENTIME > 999)){
			$parametros					= 	array(); 
			$parametros['rutCliente']	= 	$this->RUTCLIENTE;
			$parametros['rutUsuario']	= 	$this->RUTUSUARIO;
			$parametros['contrasena']	= 	$this->CONTRASENA;
			$parametros['sistema']		= 	$this->SISTEMA;
			$parametros['numeroSerie']	= 	$this->NUMSERIE;

			try {
				$client = new SoapClient($this->AUTH_URL, $parametros);
				$result = $client->Autenticar($parametros);
				$this->TOKEN = $result->AutenticarResult;
				$this->TOKENTIME = time();
				return "OK";
                print_r("OK"); 

			} catch (Exception $e) {
				$error = "";
			    $e->getMessage();
                
                print_r($e);    

				if (preg_match("/USUARIO NO TIENE PERMISO PARA ACCEDER AL SISTEMA COMPUTACIONAL/i", $e)) {
					$error = "USUARIO NO TIENE PERMISO PARA ACCEDER AL SISTEMA COMPUTACIONAL";
				}elseif (preg_match("/El valor entregado debe ser positivo./i", $e)) {
						$error = "DEBE INGRESAR UN NUMERO DE SERIE (SOLO NUMEROS)";
				}elseif (preg_match("/Rut inválido/i", $e)) {
						$error = "DEBE INGRESAR UN RUT VALIDO (11111111-1)";
				}elseif (preg_match("/CLAVE INCORRECTA/i", $e)) {
						$error ="CLAVE INCORRECTA";
				}elseif (preg_match("/USUARIO BLOQUEADO/i", $e)) {
						$error ="USUARIO BLOQUEADO";
				}elseif (preg_match("/Value cannot be null/i", $e)) {
						$error ="DEBE INGRESAR AMBOS RUT (CLIENTE Y USUARIO)";
				}

				return $error;
			}
		}
	}

	public function enviarCSV($path){
		$csv = fopen ( $path , "rb");
		$content = fread($csv, filesize($path));
		$content = (string)$content;

		$parametros								= 	array();
		$parametros['token']					= 	$this->TOKEN;
		$parametros['archivo']					= 	$content;
		$parametros['opcionFolios']				= 	1; //Folios Asignados por Nubox
		$parametros['opcionRutClienteExiste']	= 	2; //Actualizar datos Cliente, excepto el Email
		$parametros['opcionRutClienteNoExiste']	= 	1; //Si no existe el Cliente, crearlo en el Maestro de Nubox


		$client = new SoapClient($this->SEND_URL, $parametros);
		$resultCSV = $client->CargarYEmitir($parametros);
                       

		$xml = new SimpleXMLElement($resultCSV->CargarYEmitirResult->any);
        
    //   print_r($xml); 
        
        
      
		return $xml;

	}

	public function obtienePDF($identificador){
		$parametros							= 	array(); 
		$parametros['token']				= 	$this->TOKEN;
		$parametros['identificadorArchivo']	=  	$identificador;

		$client = new SoapClient($this->SEND_URL, $parametros);
		$resultXML = $client->ObtenerPDF($parametros);
		$pdf = $resultXML->ObtenerPDFResult;
		
		return $pdf;
	}
	
}

?>