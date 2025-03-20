<?php

class NuboxConnectCert{
  /*
    private $RUTCLIENTE     = '16466904-1';
	private $RUTUSUARIO     = '16466904-1';*/
    /*  
    private $RUTCLIENTE     = '19045686-2';
    private $RUTUSUARIO     = '19045686-2';*/
      
   private $RUTCLIENTE     = '77839652-1';
	private $RUTUSUARIO     = '15980152-7';
    
  
 
    
    private $SISTEMA 	    = 2; //1: Certificación, 2: Producción
	private $NUMSERIE 	    = 2;
   	private $TOKEN;
	private $TOKENTIME;
	private $AUTH_URL 	    = 'https://api.nubox.com/nubox.api/autenticar';
	
    
    
      private $ENCODEDAUTH        = 'd0R5V3FrcnlFUHJkOkZYeXFDU3pC';// API SOCIEDAD
    
    // private $ENCODEDAUTH    = 'TnExRFlqeGthUHJkOkJGSTdza2Q3'; //JUAN ARANIBAR

    
    //YlY1VVpzNkVhQ3J0OkNqVzVvb2dT//CERTIFICACION
    //ajQxV2lpWG9LUHJkOkpNUkpYRlJl  // Producion
    
 // private $ENCODEDAUTH    = 'bldFTllTQkJVUHJkOnhyWkJ3R3pk'; //MILOSKA ARANIBAR
    
    //6escP3Q0NPrd
    //jCIYsVM6
    
    
	function __construct(){
		
	}

	public function autenticar(){
		//Checkear si ya está autenticado o si el token ya expiró
		if(!isset($this->TOKEN) || (time() - $this->TOKENTIME <= 0)){
			try {
                $ch = curl_init();
            
                // Check if initialization had gone wrong*    
                if ($ch === false) {
                    throw new Exception('failed to initialize');
                } 
			
    			      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Basic $this->ENCODEDAUTH",
                    "Content-Length: 0"
                ));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_URL, $this->AUTH_URL);
                curl_setopt($ch, CURLOPT_POSTFIELDS, '');

                
            
                
                $headers = [];
                curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                  function($curl, $header) use (&$headers)
                  {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                      return $len;
                
                    $headers[strtolower(trim($header[0]))][] = trim($header[1]);
                
                    return $len;
                  }
                );

                # Get the response
                $response = curl_exec($ch);

                $this->TOKENTIME = time() + $headers["tokenexpiry"][0];
                $this->TOKEN = $headers["token"][0];

                // Check the return value of curl_exec(), too
                if ($response === false) {
                    echo "tirando excepcion";
                    throw new Exception(curl_error($ch), curl_errno($ch));
                }
                
                return "OK";
        
                curl_close($ch);
			} catch(Exception $e) {
                    
			    trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
			}
		}
	}
	
	public function enviaBoleta($JSON){
	    $TIPODOC = 39;
	    if(!$JSON["afecto"]) $TIPODOC = 41;
	    $URL_ENVIO = "https://api.nubox.com/Nubox.API/factura/documento/$this->RUTCLIENTE/1/$this->RUTUSUARIO/1/$TIPODOC/dte";
		
		$data = array(
		    "productos" => $JSON["productos"], 
		    "documentoReferenciado" => (object) array()
		);
		$data_string = json_encode($data);

		try {
            $ch = curl_init();
        
            // Check if initialization had gone wrong*    
            if ($ch === false) {
                throw new Exception('failed to initialize');
            } 
		
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Content-Length: " . strlen($data_string),
                "token: $this->TOKEN"
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $URL_ENVIO);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $headers = [];
            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
              function($curl, $header) use (&$headers)
              {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                  return $len;
            
                $headers[strtolower(trim($header[0]))][] = trim($header[1]);
            
                return $len;
              }
            );
            
            # Get the response
            $response = curl_exec($ch);
	
	        #Process Response
	        $headerfilename = $headers["content-disposition"][0];
	        $folio = substr($headerfilename, 31, -4);
	        
	        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $header_size);
            
            return array("folio" => $folio, "pdf417" => base64_encode($body));
	        
	        // Check the return value of curl_exec(), too
            if ($response === false) {
                echo "tirando excepcion";
                throw new Exception(curl_error($ch), curl_errno($ch));
            }
    
            curl_close($ch);
		} catch(Exception $e) {
                
		    trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
		}
		
	    
	}
	
	public function obtieneEstadoCompra($rutCliente, $folio, $tipo_doc = 'FAC-EL'){
	    $curl = curl_init();
	    
	    //return 'https://api.nubox.com/Nubox.API/factura/documento/'.$this->RUTCLIENTE.'/estadocompra/'.$folio.'/FAC/'.$this->NUMSERIE.'/'.$rutCliente;
 
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.nubox.com/Nubox.API/factura/documento/'.$this->RUTCLIENTE.'/estadocompra/'.$folio.'/FAC-EL/1/'.$rutCliente,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'token: '.$this->TOKEN
          ),
        ));
         
        $response = curl_exec($curl);
         
        curl_close($curl);
        
        $response = json_decode($response, true);
        return $response["estado"];
	    
	}
	
	public function obtienePDFCompraSII($rutCliente, $folio){
	    $curl = curl_init();
	    
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.nubox.com/Nubox.API/factura/documento/'.$this->RUTCLIENTE.'/1/'.$folio.'/FAC-EL/'.$rutCliente.'/pdf',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            'token: '.$this->TOKEN
          ),
        ));
         
        $response = curl_exec($curl);
         
        curl_close($curl);
        
        return $response;
	    
	}

  public function obtienePDFNC($folio){
    $curl = curl_init();
    
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.nubox.com/Nubox.API/factura/documento/'.$this->RUTCLIENTE.'/1/'.$folio.'/N%252FC-EL/pdf',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          'token: '.$this->TOKEN
        ),
      ));
       
      $response = curl_exec($curl);
       
      curl_close($curl);
      
      return $response;
    
}
	
}

?>
