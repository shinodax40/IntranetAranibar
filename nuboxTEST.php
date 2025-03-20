<?php

class NuboxConnectTest{
/*
    private $RUTCLIENTE     = '16466904-1';
	private $RUTUSUARIO     = '16466904-1';*/
    
       private $RUTCLIENTE     = '77839652-1';
	private $RUTUSUARIO     = '15980152-7';
    

    private $TOKEN;
	private $TOKENTIME;
	private $AUTH_URL 	    = 'https://api.nubox.com/nubox.api/autenticar';
//	private $ENCODEDAUTH    = 'TnExRFlqeGthUHJkOkJGSTdza2Q3';//'MGZjQU1QeXA0Q3J0OnJTTDk5a1Vl';
      private $ENCODEDAUTH        = 'd0R5V3FrcnlFUHJkOkZYeXFDU3pC';// API SOCIEDAD

  private $SERIE          = 0;
    
    
	function __construct(){
		
	}

	public function autenticar(){
		//Checkear si ya est谩 autenticado o si el token ya expir贸
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
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->AUTH_URL);
                
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
          E_USER_ERROR
        );
			}
		}
	}
	
	public function enviaDTE($JSON){
	  $URL_ENVIO = "https://api.nubox.com/Nubox.API/factura/documento/$this->RUTCLIENTE/1/rutFuncionario/1/emitir/ventaExtendido?rutFuncionario=$this->RUTUSUARIO&emitir=true";
		
		$data = array(
		    "productos" => $JSON["productos"], 
		    "documentoReferenciado" => $JSON["documentoReferenciado"]
		);
		$data_string = json_encode($data);

		try {
      $ch = curl_init();
  
      // Check if initialization had gone wrong*    
      if ($ch === false) {
          throw new Exception('failed to initialize');
      } 
		
			curl_setopt($ch, CURLOPT_HTTPHEADER, 
        array(
          "Content-Type: application/json",
          "Content-Length: " . strlen($data_string),
          "token: $this->TOKEN"
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL_ENVIO);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); // timeout in seconds
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
        
        // Check the return value of curl_exec(), too
        if ($response === false) {
          echo "tirando excepcion";
          throw new Exception(curl_error($ch), curl_errno($ch));
        }

        $body = $response;
    
        curl_close($ch);

        $jsonResponse = json_decode($body, true);

        if ($jsonResponse["Folio"] == null) {
          return "Error: " . $jsonResponse["Message"];
        } 

        return $jsonResponse['Folio'];
		} catch(Exception $e) {
                
		  trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR
      );
		}
	}
	
	public function obtieneEstadoCompra($rutCliente, $folio, $tipo_doc = 'FAC-EL'){
	    $curl = curl_init();
	    
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
	
}

?>