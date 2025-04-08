<?php
require_once('Bean/Usuario.php');
class bdUsuario{	
	private $DATABASE_SERVER;
	private $DATABASE_USERNAME;
	private $DATABASE_PASSWORD;
	private $DATABASE_NAME;
	
		
function __construct($host,$username,$password,$db_name){
		$this->DATABASE_SERVER=$host;
		$this->DATABASE_USERNAME=$username;
		$this->DATABASE_PASSWORD=$password;
		$this->DATABASE_NAME=$db_name;
}
	
	
public function loginUsua($usua,$pass) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
      	$existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM usuario WHERE usuario='".$usua."' AND password='".$pass."'"));
			 if($existe == 0){	
			    return"0";
			 }else{
			    return"1";
			 }	 
           mysqli_close($mysql);
	 }
	 
        
public function consultarUsuario($usua, $pass) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
      	mysqli_query($mysql, "SET NAMES 'utf8'");
        $length = strlen(utf8_decode($usua)); 
    
   if ($conexion->connect_error) {
    die("Ha fallado la conexión: " . $conexion->connect_error);
}
    
   /*  if (!$mysql) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos mi_bd es genial." . PHP_EOL;
echo "Información del host: " . mysqli_get_host_info($mysql) . PHP_EOL;*/
    
    
    
   /*   printf ("entro usuario");
      if (!$mysql) {
    die('No pudo conectarse: ' . mysql_error());
}
echo 'Conectado satisfactoriamente';*/
   
    
 
        if($length > 7){
             $tipBus=" u.rut  = '".$usua."'";
        }else{
             $tipBus=" u.usuario = '".$usua."'";
        }    
        
     	$query = "SELECT  u.id,
                          u.usuario,
                          u.nombre,
                          u.apellido,
                          t.nombre_tipo,
                          u.id_tienda,
                          t.id_tipo,
                          IFNULL(tp.id, 0) as idTransporte
                FROM usuario u
                INNER JOIN tipo_usuario t
                ON t.id_tipo = u.id_tipo
                LEFT OUTER JOIN tbl_transportes tp
                ON tp.id_usuario = u.id
                WHERE
                
                "
             .
               $tipBus
             .  
            " 
               AND u.password='".$pass."'
                
                
                ";
		$result = mysqli_query($mysql, $query);
  //  printf("La selección devolvió %d filas.\n", $result->num_rows);
    
	
		$ret = array();
       
	  	while ($row = mysqli_fetch_object($result)) {
           // printf ("%s (%s)\n", $row->id, $row->usuario);
			$tmp =new Usuario();
			$tmp->id    	   = $row->id;			
			$tmp->usuario      = $row->usuario;
			$tmp->nombre  	   = $row->nombre;
			$tmp->apellido     = $row->apellido;
			$tmp->nombre_tipo  = $row->nombre_tipo;
			$tmp->id_tienda    = $row->id_tienda;
 			$tmp->id_tipo      = $row->id_tipo;
  			$tmp->idTransporte = $row->idTransporte;
           
            
            
           

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
         mysqli_close($mysql);
}
    
    
    
    
    
    
    
 public function listarDireccionesCliente($id_cliente) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");



    $query = "
    
      
                SELECT c.id_cliente,
                       c.direccion,
                       c.deta_direccion,
                       c.id_sector,
                       s.nombre_sector,
                       c.id,
                       c.principal,
                       c.ubicacion_geo

                FROM tbl_direcciones_clientes c 
                LEFT OUTER JOIN tbl_sector s
                ON s.id_sector = c.id_sector
                where c.id_cliente = '".$id_cliente."'
             
             
             
             ";



    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->rut                = $row->rut;
        $tmp->deta_direccion  	 = $row->deta_direccion;
        $tmp->id_sector          = $row->id_sector;
        $tmp->direccion          = $row->direccion.", ".$row->deta_direccion;
        $tmp->nombre_sector      = $row->nombre_sector;
        $tmp->id                 = $row->id;
        $tmp->principal                 = $row->principal;
        $tmp->ubicacion_geo                 = $row->ubicacion_geo;

       
	
        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
         mysqli_close($mysql);
}
     
    
    
    
public function listarClientes($rut, $nombre, $tipo) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");

    if($tipo =='Cliente'){
        $tip =" AND clie.tipo_comprador in ('Mascotero', 'Almacen','Veterinaria','Avicola','Peluqueria','Otro');";
    }else{
        $tip =" AND clie.tipo_comprador = 'Particular';";
    }	

    $query = "SELECT 
              clie.id_cliente, 
              clie.rut, 
              clie.nombre,
              clie.direccion, 
              clie.telefono,
              clie.giro,
              clie.comuna,
              clie.tipo_comprador,
              clie.id_sector,
              clie.credito
              FROM tbl_cliente clie
              WHERE clie.nombre like '%".$nombre."%'
              AND clie.activo=1
             ".$tip;




    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->rut                = $row->rut;
        $tmp->nombre  	         = $row->nombre;
        $tmp->direccion          = $row->direccion;
        $tmp->telefono           = $row->telefono;
        $tmp->giro               = $row->giro;
        $tmp->comuna             = $row->comuna;
        $tmp->tipo_comprador     = $row->tipo_comprador;
        $tmp->id_sector     = $row->id_sector;
       
        $tmp->credito     = $row->credito;

	
        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
         mysqli_close($mysql);
}
    
    
public function listarProveedores($rut, $nombre) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    $query = "SELECT 
              clie.id_proveedor, 
              clie.rut, 
              clie.nombre, 
              clie.direccion, 
              clie.telefono,
              clie.giro
              FROM tbl_proveedores clie
              WHERE /*clie.rut LIKE '%".$rut."%'
              AND */clie.nombre LIKE '%".$nombre."%'";
    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_proveedor    		  = $row->id_proveedor;			
        $tmp->rut      = $row->rut;
        $tmp->nombre  	  = $row->nombre;
        $tmp->direccion     = $row->direccion;
        $tmp->telefono = $row->telefono;
        $tmp->giro = $row->giro;

        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
         mysqli_close($mysql);
}
    
    
public function listarProvSelection() {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
     	$query = "select p.id_proveedor, 
                          p.rut, 
                          p.nombre 
                    from tbl_proveedores p 
                    where p.id_proveedor not IN (1,2,4,5,14)
                    order by p.nombre";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
       
	  	while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->id_proveedor    		  = $row->id_proveedor;			
			$tmp->rut                     = $row->rut;
			$tmp->nombre  	              = $row->nombre;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
         mysqli_close($mysql);
}
    
    
    
    
public function guardarClientePrecio($arrCliente){        
    $mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);	
    mysqli_query($mysql, "SET NAMES 'utf8'");

    $existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM usuario WHERE rut='".$arrCliente[0]->rut."'"));

     if($existe  == "0"){        
                $query ="INSERT INTO usuario
                          (rut, 
                          nombre, 
                          telefono,
                          password,
                          id_tipo) 
                         VALUES (
                         '".$arrCliente[0]->rut."',
                         '".$arrCliente[0]->nombre."',
                         '".$arrCliente[0]->telefono."',
                         '".$arrCliente[0]->pass."',
                         '6');";

              $resulInserta = mysqli_query($mysql, $query);	
      }


    if($existe == 1){	
        return"1";
     }else if($resulInserta == "1"){
        return"2";
     }	            
    		mysqli_free_result($resulInserta);
         mysqli_close($mysql);

}
    
    
public function listUsuarioSeguimiento($id_usua, $desde, $hasta) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
     	$query = "SELECT v.id_seg,
                         v.fecha,
                         v.cant_visitas_clie,
                         v.cant_visita_nueva,
                         CONCAT(ELT(WEEKDAY(v.fecha) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'))  as dia_semana,
                        
                        
        
                        
                        if(v.cant_ped_nuevo_v=0,
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador = 'Veterinaria'
                        AND c.bandera_new = 1)  ,
                       v.cant_ped_nuevo_v) as cant_ped_nuevo_v,

                        
                      if(v.cant_ped_nuevo_m=0,
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador = 'Mascotero'
                        AND c.bandera_new = 1)  ,
                       v.cant_ped_nuevo_m) as cant_ped_nuevo_m,

                        if(v.cant_ped_nuevo_a=0,
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador = 'Almacen'
                        AND c.bandera_new = 1)  ,
                       v.cant_ped_nuevo_a) as cant_ped_nuevo_a,

                        
                        
                        
                        (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador in ('Mascotero')
                        AND c.bandera_new <> 1) as cant_mascotero,
                        
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador in ('Particular')
                        AND c.bandera_new <> 1) as cant_particular,
                       
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador in ('Almacen')
                        AND c.bandera_new <> 1) as cant_almacen,
                        
                       (SELECT COUNT(p.id_pedido) 
                        FROM tbl_pedido p
                        LEFT OUTER JOIN tbl_cliente c 
                        ON c.id_cliente  = p.id_cliente
                        WHERE p.id_usuario = v.id_usuario 
                        AND p.fecha_entrega = v.fecha
                        AND c.tipo_comprador in ('Veterinaria')
                        AND c.bandera_new <> 1) as cant_veterinaria
                        
            FROM tbl_seguimiento_vend v
            WHERE v.fecha  BETWEEN ('".$desde."')  and ('".$hasta."')
            AND v.id_usuario = '".$id_usua."'";
		$result = mysqli_query($mysql, $query);	
		$ret = array();       
	  	while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->id_seg    		              = $row->id_seg;			                
			$tmp->fecha    		                  = $row->fecha;			
			$tmp->cant_visitas_clie               = $row->cant_visitas_clie;
			$tmp->cant_visita_nueva  	          = $row->cant_visita_nueva;
 			$tmp->dia_semana  	                  = $row->dia_semana;
			$tmp->cant_ped_nuevo_v  	          = $row->cant_ped_nuevo_v;
			$tmp->cant_ped_nuevo_m  	          = $row->cant_ped_nuevo_m;
			$tmp->cant_ped_nuevo_a  	          = $row->cant_ped_nuevo_a;            
			$tmp->cant_mascotero  	              = $row->cant_mascotero;
			$tmp->cant_particular  	              = $row->cant_particular;
			$tmp->cant_almacen  	              = $row->cant_almacen;
 			$tmp->cant_veterinaria  	          = $row->cant_veterinaria;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
      mysqli_close($mysql);
}
    
    
public function updateInforme($cliente,  $nuevo , $idConf) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  

		$query = "UPDATE tbl_seguimiento_vend p
                    SET p.cant_visitas_clie = ".$cliente.", p.cant_visita_nueva= ".$nuevo."
                    WHERE p.id_seg = ".$idConf.";
                ";
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }   
    		mysqli_free_result($result);

      mysqli_close($mysql);
}
    

public function arrayUpdateInforme($arrayInfor) {
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
        
      foreach($arrayInfor as $detalle){          
          
           	$query = "UPDATE tbl_seguimiento_vend pe
                    SET pe.cant_ped_nuevo_v = ".$detalle->cant_ped_nuevo_v.",
                        pe.cant_ped_nuevo_m = ".$detalle->cant_ped_nuevo_m.",
                        pe.cant_ped_nuevo_a = ".$detalle->cant_ped_nuevo_a."    
                    WHERE pe.id_seg     = ".$detalle->id_seg.";
                ";

            $resultDeta = mysqli_query($mysql, $query);	

          
        
    }
      if($resultDeta == "1"){
                return 1;
            }else{
                return 0;
            }
    		mysqli_free_result($result);

      mysqli_close($mysql);
}
    
    
public function listCantPedSemana($desde, $hasta) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
       $queryMax  = "SELECT MAX(p.id), p.fecha_desde, p.fecha_hasta FROM tbl_fecha_semanas p;";
        $resultMax = mysqli_query($mysql, $queryMax);
        $retMax = array();
		while ($row = mysqli_fetch_object($resultMax)) {
			$tmp =new Pedido();
			$tmp->desde          = $row->fecha_desde;
			$tmp->hasta          = $row->fecha_hasta;
            
			$retMax[] = $tmp;
		}
    
    
     	$query = "SELECT 
            tp.nombre_tipo,
            CONCAT(us.nombre, ' ', us.apellido) as nombre
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 0 then 1 END) AS CHAR) AS lunes
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 1 then 1 END) AS CHAR) AS martes
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 2 then 1 END) AS CHAR) AS miercoles
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 3 then 1 END) AS CHAR) AS jueves
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 4 then 1 END) AS CHAR) AS viernes
            ,cast(COUNT(CASE WHEN weekday(date(p.fecha_entrega)) = 5 then 1 END) AS CHAR) AS sabado
            ,p.entregaPed	
            FROM tbl_pedido p 
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            WHERE 
                p.anulada      = 'N'
            AND   p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."')

            and us.id_tipo IN (2,7,1,4,8)
            and us.id NOT IN (158)
            and p.estado_pedido = 1
            GROUP BY us.nombre
            ORDER BY us.nombre , p.entregaPed ASC; ";
            
		$result = mysqli_query($mysql, $query) ;		
		$ret = array();       
	  	while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->nombre_tipo    		              = $row->nombre_tipo;			                
			$tmp->nombre   		                  = $row->nombre;			
			$tmp->lunes                           = $row->lunes;
			$tmp->martes  	                      = $row->martes;
 			$tmp->miercoles  	                  = $row->miercoles;
			$tmp->jueves  	                      = $row->jueves;
			$tmp->viernes           	          = $row->viernes;
			$tmp->sabado            	          = $row->sabado;       
  			$tmp->entregaPed            	      = $row->entregaPed;       
          

            $tmp->tipoEntrega = '';
             if($row->entregaPed=='1'){
                  $tmp->tipoEntrega      = 'Transporte';
            }else{
                  $tmp->tipoEntrega      = 'Bodega';
            }
            
		
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
      mysqli_close($mysql);
}
    
    
 public function listarClientesRuta($idUsua) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");


    $query = "SELECT 
              clie.id_cliente, 
              clie.nombre,
             
            IFNULL(direc.direccion, clie.direccion ) as direccion ,

              MAX(p.fecha_entrega) as fecha,
              DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
              l.dias_atencion,
              l.dias_alarma,
              clie.tipo_comprador,
              COUNT(p.id_pedido) as cant_ped_nuevo,
              clie.activo
              
              
              
              FROM tbl_cliente clie
              LEFT JOIN tbl_semana_ruta r
              ON r.id_cliente = clie.id_cliente
              AND  r.id_usuario = clie.id_usuario
              
              LEFT OUTER JOIN tbl_pedido p
              ON p.id_cliente = clie.id_cliente
           
             
              LEFT OUTER JOIN tbl_atencion_cliente l 
              ON l.tipo_cliente = clie.tipo_comprador
           
              LEFT OUTER JOIN tbl_direcciones_clientes direc 
               ON direc.id_cliente = clie.id_cliente
               AND direc.principal = 1
           
              
               WHERE 
               clie.activo=1
               AND clie.tipo_comprador not in ('Otro', 'Avicola','Particular')
               AND clie.id_usuario ='".$idUsua."'
               AND r.id_cliente IS NULL
               AND p.anulada = 'N'
               AND p.estado_pedido IN (1,3,4) 
               GROUP By          
               clie.id_cliente, 
              clie.nombre,
              clie.direccion
               ORDER BY  clie.tipo_comprador ASC;";




    $result = mysqli_query($mysql, $query);

    $ret = array();
     
     
 

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->nombre  	         = $row->nombre;
        $tmp->direccion          = $row->direccion;
        $tmp->fecha            = $row->fecha;
        
        $tmp->countFecha            = $row->countFecha;
        
      /*$tmp->dias_atencion             = $row->dias_atencion;
        $tmp->dias_alarma               = $row->dias_alarma;*/
        $tmp->tipo_comprador            = $row->tipo_comprador;
         $tmp->cant_ped_nuevo            = $row->cant_ped_nuevo;
       
                $tmp->activo            = $row->activo;

        
        if($row->countFecha < $row->dias_alarma){
            
            $tmp->verde             = true;
            $tmp->rojo              = false;
            $tmp->amarillo          = false;
            
        }else if( ($row->dias_atencion - $row->countFecha ) <= 0){
             $tmp->verde             = false;
            $tmp->rojo              = true;
            $tmp->amarillo          = false;
            
            
        }else if( $row->countFecha >= $row->dias_alarma && ($row->dias_atencion - $row->countFecha )  > 0){
             $tmp->verde             = false;
             $tmp->rojo              = false;
              $tmp->amarillo          = true;
        } 
        
   
        
        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
       mysqli_close($mysql);
}
    
    
    
    

    /***Produccion*
     $dataBaseServerGPS="dev.magnussoluciones.cl/phpmyadmin";
 	 $dataBaseUsernameGPS="aranibar_vendedor";
   	 $dataBaseUserPasswordGPS="qDChknOVeDbvNRK8";
     $dataBaseNameGPS="aranibar_vendedor";**/
      
    
    
    
    
    
    
 public function listarRutaGPS(){
     
            $dataBaseServerGPS="dev.magnussoluciones.cl";
 	   $dataBaseUsernameGPS="aranibar_vendedor";
   	   $dataBaseUserPasswordGPS="qDChknOVeDbvNRK8";
       $dataBaseNameGPS="aranibar_vendedor";
      
      
        $mysqlGPS = mysqli_connect($dataBaseServerGPS,
                                   $dataBaseUsernameGPS,
                                   $dataBaseUserPasswordGPS);
      mysqli_select_db($mysqlGPS, $dataBaseNameGPS);

      $queryGPS="SELECT tt.id,
                   tt.client_id, 
                   tt.latitude, 
                   tt.longitude,
                   tt.optionalText,
                   DATE_FORMAT(tt.reportDate, '%Y-%m-%d') as fecha_gps
                        FROM reporteposicion tt
                        INNER JOIN
                            (
                            SELECT client_id, MAX(id) AS MaxDateTime
                            FROM reporteposicion
                            WHERE client_id  IS NOT NULL
                                 GROUP BY client_id                

                            ) groupedtt 
                        ON tt.client_id = groupedtt.client_id
                        AND tt.id =  groupedtt.MaxDateTime";
      
   
       $resultGPS = mysqli_query($mysqlGPS, $queryGPS);
       $totalGPS = mysqli_num_rows($resultGPS);
   
       $retGPS = array();
       while ($row = mysqli_fetch_object($resultGPS)) {
           $tmp = new stdClass();
           $tmp->id     =   $row->id;
           $tmp->client_id     =   $row->client_id;
           $tmp->ubica_gps =   $row->latitude.",".$row->longitude;
           $tmp->fecha_gps =   $row->fecha_gps;
           $tmp->optionalText =   $row->optionalText;

           
           
           $retGPS[] = $tmp;
       }
      mysqli_free_result($resultGPS);
        return ($retGPS);

        mysqli_close($mysqlGPS);
     
     
 }    
    
    
    
    
    
  public function listarClientesSemana($idUsua) {
      
   //   $r = new bdUsuario();
        $retGPS  =   $this->listarRutaGPS(); 
      
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
      
      
    if($idUsua != ""){
             $tipBus = "   s.id_usuario = '".$idUsua."'
                           AND c.activo = 1  ";
      }else{
             $tipBus = "  c.activo = 1  ";
      }      
      
      
    $query = "SELECT   c.id_cliente, 
                        c.nombre, 
                        /*c.direccion,*/
                         IFNULL(direc.direccion, c.direccion ) as direccion ,
                         direc.deta_direccion,
                        s.id_dia,
                        s.id as id_ruta,
                        c.id_usuario,
                        MAX(p.fecha_entrega) as fecha,
                        DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
                        l.dias_atencion,
                        l.dias_alarma,
                        c.tipo_comprador,
                        c.fecha_obs_mod,
                        c.id_observacion,
                        c.observacion,
                        c.activo,
                        c.telefono,
                        (SELECT count(cr.id) FROM tbl_direcciones_clientes cr where cr.id_cliente = c.id_cliente) as cantidaddirecc,
                        direc.ubicacion_geo
                        FROM  tbl_semana_ruta s 
                        LEFT  JOIN tbl_cliente c 
                        ON c.id_cliente = s.id_cliente
                        LEFT OUTER JOIN tbl_pedido p
                        ON p.id_cliente = c.id_cliente
                        LEFT OUTER JOIN tbl_atencion_cliente l
                        ON l.tipo_cliente = c.tipo_comprador
                        LEFT OUTER JOIN tbl_direcciones_clientes direc 
                        ON direc.id_cliente = c.id_cliente
                        AND direc.principal = 1
                        
                        
                        WHERE 
                
                 "
            . $tipBus.
                "
                
                
                          
                AND p.anulada='N'
                AND p.estado_pedido IN (1,3,4) 
                AND c.tipo_comprador not in  ('Otro','Particular','Avicola')
                       GROUP By           
                       c.id_cliente, 
                       c.nombre, 
                       c.direccion,
                        s.id_dia,
                        s.id ,
                        c.id_usuario";
      
      
      
    $result = mysqli_query($mysql, $query);

    $ret = array();

    $current_date = date('Y-m-d'); // fecha actual  
      
    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->nombre  	         = $row->nombre;
        $tmp->direccion          = $row->direccion;
        $tmp->id_dia             = $row->id_dia;
        $tmp->id_ruta            = $row->id_ruta;
        $tmp->fecha              = $row->fecha;
        $tmp->countFecha         = $row->countFecha;
        $tmp->dias_atencion      = $row->dias_atencion;
        $tmp->dias_alarma        = $row->dias_alarma; 
        
        if($row->countFecha < $row->dias_alarma){
            
            $tmp->verde             = true;
            $tmp->rojo              = false;
            $tmp->amarillo          = false;
            
        }else if( ($row->dias_atencion - $row->countFecha ) <= 0){
            $tmp->verde             = false;
            $tmp->rojo              = true;
            $tmp->amarillo          = false;
            
            
        }else if( $row->countFecha >= $row->dias_alarma && ($row->dias_atencion - $row->countFecha )  > 0){
             $tmp->verde             = false;
             $tmp->rojo              = false;
              $tmp->amarillo          = true;
        } 
                $tmp->tipo_comprador     = $row->tipo_comprador; 
                $tmp->fecha_obs_mod      = $row->fecha_obs_mod; 
                $tmp->id_observacion     = $row->id_observacion; 
                $tmp->observacion        = $row->observacion; 
                $tmp->activo             = $row->activo;         
        	    $tmp->telefono           = $row->telefono; 
        $tmp->cantidaddirecc     = $row->cantidaddirecc; 
                $tmp->ubicacion_geo     = $row->ubicacion_geo; 

        

                $tmp->deta_direccion     = $row->deta_direccion; 
            
                $tmp->fecha_gps            = "";
                $tmp->fecha_actual_gps     = "";
                $tmp->optionalText         = "";
        
        foreach($retGPS as $fila) {
              
                
            if( $row->id_cliente == $fila->client_id){
                     $tmp->fecha_gps     = $fila->fecha_gps;
                     $tmp->ubica_gps     = $fila->ubica_gps;
                     $tmp->optionalText  = $fila->optionalText;
                
                if($tmp->fecha_gps == $current_date){
                    $tmp->fecha_actual_gps  ="1";
                }
            }
        }
        
     

        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
        mysqli_close($mysql);
}
 
    
    
    
 public function listarClientesGeo() {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
      
      
      
    $query = "SELECT     
              c.id_cliente, 
              c.nombre, 
              MAX(p.fecha_entrega) as fecha,
              DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
              l.dias_atencion,
              l.dias_alarma,
              c.tipo_comprador,
              df.direccion,
              df.comuna,
              df.ubicacion_geo,
               c.id_usuario,
               sm.id_dia
                FROM  tbl_cliente c 
                LEFT OUTER JOIN tbl_pedido p
                ON p.id_cliente = c.id_cliente
                LEFT OUTER JOIN tbl_atencion_cliente l
                ON l.tipo_cliente = c.tipo_comprador                
                LEFT OUTER JOIN tbl_direcciones_clientes df
                ON df.id_cliente = c.id_cliente
                LEFT OUTER JOIN tbl_semana_ruta sm 
                ON sm.id_cliente = c.id_cliente
                WHERE  c.activo = 1   
                
                AND c.tipo_comprador in ('Mascotero','Almacen', 'Veterinaria', 'Avicola', 'Peluqueria')
                AND p.anulada='N'
                AND p.estado_pedido IN (1,3,4)
                GROUP by  c.id_cliente, 
              c.nombre, 
              l.dias_atencion,
              l.dias_alarma,
              c.tipo_comprador,
              df.direccion,
              df.comuna,
              df.ubicacion_geo,
               c.id_usuario";
      
      
      
      
    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->nombre  	         = $row->nombre;
        $tmp->direccion          = $row->direccion;
        $tmp->fecha              = $row->fecha;
        $tmp->countFecha         = $row->countFecha;
        $tmp->dias_atencion      = $row->dias_atencion;
        $tmp->dias_alarma        = $row->dias_alarma; 
        $tmp->tipo_comprador     = $row->tipo_comprador; 
        $tmp->ubicacion_geo      = $row->ubicacion_geo; 
        $tmp->id_usuario         = $row->id_usuario; 
       
       
        
        switch ($row->id_dia) {
            case "1":
                 $tmp->dia_semana             = "LUNES";
                break;
            case "2":
                 $tmp->dia_semana             = "MARTES";
                break;
            case "3":
                 $tmp->dia_semana             = "MIERCOLES";
                break;
            case "4":
                 $tmp->dia_semana             = "JUEVES";
                break;
            case "5":
                 $tmp->dia_semana             = "VIERNES";
                break;   
              case "6":
                 $tmp->dia_semana             = "SABADO";
                break;     
               case "":
                 $tmp->dia_semana             = "SIN ASIGNAR";
                break;      
                
        }
        
        
        

        
        if($row->countFecha < $row->dias_alarma){
            
            $tmp->verde             = true;
            $tmp->rojo              = false;
            $tmp->amarillo          = false;
            
        }else if( ($row->dias_atencion - $row->countFecha ) <= 0){
            $tmp->verde             = false;
            $tmp->rojo              = true;
            $tmp->amarillo          = false;
            
            
        }else if( $row->countFecha >= $row->dias_alarma && ($row->dias_atencion - $row->countFecha )  > 0){
             $tmp->verde             = false;
             $tmp->rojo              = false;
              $tmp->amarillo          = true;
        } 


        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
        mysqli_close($mysql);
}
     
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
public function eliminarClientesSemana($nRuta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "delete from tbl_semana_ruta    where id = '".$nRuta."';";		
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
                    return 1;
                }else{
                    return 0;
    }
     mysqli_free_result($result);
     mysqli_close($mysql);
}
    
    
public function insertarClientesSemana($idUsua, $idClie, $idDia) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
	
       $query = "INSERT INTO tbl_semana_ruta(  id_usuario, 
                                          id_cliente, 
                                          id_dia
                                          ) 
         VALUES ('".$idUsua."',
                 '".$idClie."',
                 '".$idDia."')";		

    
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
                    return 1;
                }else{
                    return 0;
    }
        mysqli_free_result($result);
     mysqli_close($mysql);
}    
    
    
    
    
    
  public function listarClieSemanaPdf($idUsua, $idDia) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
      
  $query = "SELECT      
                        /*c.id_cliente,*/ 
                        IFNULL(direc.direccion, c.direccion ) as direccion ,
                        c.nombre, 
                        /*c.direccion,*/
                        s.id_dia,
                        s.id as id_ruta,
                        DATE_FORMAT(MAX(p.fecha_entrega),'%d/%m/%Y') as fecha,
                        DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
                        l.dias_atencion,
                        l.dias_alarma,
                        c.tipo_comprador,
                        c.id_observacion,
                        c.observacion,
                        DATE_FORMAT((c.fecha_obs_mod),'%d/%m/%Y') as fecha_obs_mod,
                        c.activo,
                        c.telefono,
                        (SELECT count(cr.id) FROM tbl_direcciones_clientes cr where cr.id_cliente = c.id_cliente) as cantidaddirecc

              
              	
                FROM  tbl_semana_ruta s 
                LEFT JOIN tbl_cliente c 
                ON c.id_cliente = s.id_cliente
                LEFT OUTER JOIN tbl_pedido p
                ON p.id_cliente = c.id_cliente
                LEFT OUTER JOIN tbl_atencion_cliente l 
                ON l.tipo_cliente = c.tipo_comprador
                         
               LEFT OUTER JOIN tbl_direcciones_clientes direc 
               ON direc.id_cliente = c.id_cliente
               AND direc.principal = 1
               
                WHERE s.id_usuario = '".$idUsua."'
                and s.id_dia       = '".$idDia."'
                AND p.anulada = 'N'
                AND  c.activo=1
                GROUP By          c.id_cliente, 
                                  c.nombre,
                                  c.direccion,
                                   s.id_dia,
                                            s.id
                ORDER BY c.tipo_comprador  ASC";
      
      
      
      
    $result = mysqli_query($mysql, $query) ;

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        $tmp->id_cliente    	 = $row->id_cliente;			
        $tmp->nombre  	         = $row->nombre;
        $tmp->direccion          = $row->direccion;
        $tmp->id_dia             = $row->id_dia;
        $tmp->id_ruta            = $row->id_ruta;
        $tmp->fecha              = $row->fecha;
          $tmp->countFecha       = $row->countFecha;
        $tmp->dias_atencion      = $row->dias_atencion;
        $tmp->dias_alarma        = $row->dias_alarma;
                $tmp->tipo_comprador        = $row->tipo_comprador;

              
        if($row->countFecha < $row->dias_alarma){
            
             $tmp->verde             = true;
             $tmp->rojo              = false;
             $tmp->amarillo          = false;
            
        }else if( ($row->dias_atencion - $row->countFecha ) <= 0){
             $tmp->verde             = false;
             $tmp->rojo              = true;
             $tmp->amarillo          = false;
            
            
        }else if( $row->countFecha >= $row->dias_alarma && ($row->dias_atencion - $row->countFecha )  > 0){
             $tmp->verde             = false;
             $tmp->rojo              = false;
             $tmp->amarillo          = true;
        } 
        

             $tmp->id_observacion  = $row->id_observacion;
             $tmp->observacion     = $row->observacion;
             $tmp->fecha_obs_mod   = $row->fecha_obs_mod;
             $tmp->activo          = $row->activo;
                     $tmp->telefono          = $row->telefono;
                     $tmp->cantidaddirecc          = $row->cantidaddirecc;


        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
       mysqli_close($mysql);
}
    
    
   
public function listCantPedMensual($idUsua, $idTipo) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");   
    
         if($idTipo == '7' || $idTipo == '1'){
               $tip ="";
          }else{
               $tip ="   AND p.id_usuario = '".$idUsua."' ";
         }
    
    
     	$query = "SELECT 
            tp.nombre_tipo,
            CONCAT(us.nombre, ' ', us.apellido) as nombre
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Particular' then 0 END) AS CHAR) AS particular
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Mascotero' then 0 END) AS CHAR) AS mascotero
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Almacen'   then 0 END) AS CHAR) AS almacen
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Veterinaria' then 0 END) AS CHAR) AS veterinaria
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Avicola' then 0 END) AS CHAR) AS avicola
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Peluqueria' then 0 END) AS CHAR) AS peluqueria
            ,cast(COUNT(CASE WHEN ((c.tipo_comprador)) = 'Otro' then 0 END) AS CHAR) AS otro
             ,p.entregaPed
            FROM tbl_pedido p 
            LEFT OUTER JOIN usuario us 
            ON us.id = p.id_usuario
            LEFT OUTER JOIN tipo_usuario tp 
            ON tp.id_tipo = us.id_tipo
            
            LEFT OUTER JOIN tbl_cliente c 
            ON c.id_cliente = p.id_cliente
            
            WHERE 
            p.anulada      = 'N'
            AND   MONTH(p.fecha_entrega) = MONTH(NOW())
              AND YEAR(p.fecha_entrega) = YEAR(NOW())
            AND us.id_tipo IN  (2,7,1,4,8)
            AND us.id NOT IN (158)
            AND p.estado_pedido = 1 "
               .
            $tip
            .
        "
            GROUP BY us.nombre, tp.nombre_tipo, p.entregaPed
            ORDER BY us.nombre , p.entregaPed	  ASC";
            
		$result = mysqli_query($mysql, $query);		
		$ret = array();       
	  	while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->nombre_tipo    		          = $row->nombre_tipo;			                
			$tmp->nombre   		                  = $row->nombre;			
			$tmp->particular                      = $row->particular;
			$tmp->mascotero  	                  = $row->mascotero;
 			$tmp->almacen  	                      = $row->almacen;
			$tmp->veterinaria  	                  = $row->veterinaria;
			$tmp->avicola           	          = $row->avicola;
			$tmp->peluqueria            	      = $row->peluqueria;       
  			$tmp->otro            	              = $row->otro;       
          

            $tmp->tipoEntrega = '';
             if($row->entregaPed=='1'){
                  $tmp->tipoEntrega      = 'Transporte';
            }else{
                  $tmp->tipoEntrega      = 'Bodega';
            }
            
		
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
     
     
public function listarNotaTotalPedido($idCliente, $nPedido) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
/* AND p.estado_pedido ='1'    */    
    $query = "SELECT
                SUM(d.cantidad * d.precio_vendido) as total
                FROM  tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido p 
                ON d.id_pedido = p.id_pedido
                WHERE p.id_pedido = '".$nPedido."'
                AND p.id_cliente ='".$idCliente."' 
                AND p.anulada ='N' 
                  
             ";




    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        
        //$tmp->total          = $row->total;      	

        
       $tmp->total          = round ((($row->total) * 0.19)+($row->total));      	
        $ret[] = $tmp;
    }
    	mysqli_free_result($result);
		      return ($ret);
}
    
    
    
       
public function listarNotaTotalFolio($idCliente, $nFolio) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
/* AND p.estado_pedido ='1'    */    
    $query = "            
               SELECT
                SUM(d.cantidad * d.precio_vendido) as total,
                d.id_pedido
                FROM  tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido p 
                ON d.id_pedido = p.id_pedido
                LEFT OUTER JOIN tbl_nubox_facturas f
                on f.id_pedido = p.id_pedido
                WHERE f.folio = '".$nFolio."'
                AND p.id_cliente ='".$idCliente."' 
                AND p.anulada ='N';";




    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        

        
       $tmp->total          = round ((($row->total) * 0.19)+($row->total));      
       $tmp->id_pedido          = $row->id_pedido;      	
  
        
        $ret[] = $tmp;
    }
    	mysqli_free_result($result);
		      return ($ret);
}  
    

    
 public function insertarRecibo($idClie, 
                                $fCobro, 
                                $nRecibo, 
                                $tipPago, 
                                $totalRecibo, 
                                $totalAbono, 
                                $observacion, 
                                $deviceId, 
                                $objDeta) {
     
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
     
       $id_usuario ="";
       $queryConsulta = "SELECT u.id FROM usuario u where u.device_id  = '".$deviceId."'";
       $resultUsuario = mysqli_query($mysql, $queryConsulta);
       $ret = array();
       while ($row = mysqli_fetch_object($resultUsuario)) {
			           $tmp =  new Usuario();
                $id_usuario = $row->id;
       }
     
     
     
                $query1 = " INSERT INTO tbl_recibo_cabe
                (fecha_ingreso, 
                fecha_cobro, 
                id_cliente, 
                n_recibo, 
                modo_pago,
                total_recibo,
                abono_recibo,
                observacion,
                device_id,
                id_usuario)
                
                 VALUES (CURDATE(),
                         '".$fCobro."',
                         '".$idClie."',
                         '".$nRecibo."',
                         '".$tipPago."',
                         '".$totalRecibo."',
                         '".$totalAbono."',
                         '".$observacion."',
                         '".$deviceId."',
                         '".$id_usuario."');";
               $resultCliente = mysqli_query($mysql, $query1);	
               $idSolicitud = mysqli_insert_id($mysql);            
             
             //  mysqli_free_result($resultCliente);
          
    
            if($idSolicitud != ""){
//     echo "id Recibo: " . $idSolicitud;
                  foreach($objDeta as $detalle){

                          
                    $queryPedDeta = "INSERT INTO tbl_recibo_deta
                                                 (id_recibo_bol, 
                                                       n_pedido, 
                                                       tipo_pedido,
                                                       total_pedido,
                                                       n_folio) 
                             VALUES('".$idSolicitud."', 
                                    '".$detalle->nPedido."', 
                                    '".$detalle->nTipo."',
                                    '".$detalle->total."',
                                    '".$detalle->nFolio."');";  

                 $resultCliente3 =       mysqli_query($mysql, $queryPedDeta);

        
              }
               

           }
     
    
     
     

              

       //        mysqli_free_result($resultCSolicitud);
            return $idSolicitud;
              mysqli_close($mysql);
}    
    
    
public function listarRecibos($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $tipoUsuario, $idUsuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
    $tipBus="";
    
if($tipoUsuario=="Vendedor Tienda"){    
    

    if($tipBusq =='1'){        
      $tipBus=" c.fecha_ingreso BETWEEN '".$fIngreso."' AND '".$fIngresoHasta."' AND  c.id_usuario='".$idUsuario."'";        
    }else if($tipBusq =='2'){        
             $tipBus=" c.fecha_cobro BETWEEN '".$fEntrega."' AND '".$fEntregaHasta."' AND c.id_usuario='".$idUsuario."'";        
    }else if($tipBusq =='4'){
             $tipBus=" c.n_recibo='".$folio."' AND c.id_usuario='".$idUsuario."'";
    }else if($tipBusq =='3'){
             $tipBus=" cl.nombre like '%".$nombre."%' AND c.id_usuario='".$idUsuario."'";
    }
    
}else{
    
       if($tipBusq =='1'){        
      $tipBus=" c.fecha_ingreso BETWEEN '".$fIngreso."' AND '".$fIngresoHasta."' ";        
    }else if($tipBusq =='2'){        
             $tipBus=" c.fecha_cobro BETWEEN '".$fEntrega."' AND '".$fEntregaHasta."' ";        
    }else if($tipBusq =='4'){
             $tipBus=" c.n_recibo='".$folio."' ";
    }else if($tipBusq =='3'){
             $tipBus=" cl.nombre like '%".$nombre."%' ";
    }
    
    
}
    
    
    
    
		$query = "
                        SELECT 
                        c.id, 
                        c.fecha_ingreso, 
                        c.fecha_cobro, 
                        c.id_cliente, 
                        c.n_recibo, 
                        c.modo_pago, 
                        c.total_recibo, 
                        c.abono_recibo,
                        cl.nombre,
                        u.nombre as nombUsuario,
                        c.observacion,
                        ec.nombre as nombrePago,
                        c.estado_recibo_val
                        FROM tbl_recibo_cabe c 
                        LEFT OUTER JOIN tbl_cliente cl 
                        on cl.id_cliente = c.id_cliente
                        LEFT OUTER JOIN usuario u 
                        ON u.id = c.id_usuario 
                        AND u.device_id = c.device_id
                        LEFT OUTER JOIN tbl_estado_cobro ec 
                        ON ec.id_cobro = c.modo_pago
                Where 
                ".
            $tipBus
            .
            "
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->id                = $row->id;
			$tmp->fecha_ingreso  	= $row->fecha_ingreso;
			$tmp->fecha_cobro       = $row->fecha_cobro;          
        	$tmp->id_cliente        = $row->id_cliente; 
           	$tmp->n_recibo          = $row->n_recibo; 
        	$tmp->modo_pago         = $row->modo_pago; 
        	$tmp->total_recibo      = $row->total_recibo; 
        	$tmp->abono_recibo      = $row->abono_recibo; 
        	$tmp->nombre            = $row->nombre; 
          	$tmp->nombUsuario       = $row->nombUsuario; 
           	$tmp->observacion       = $row->observacion; 
            $tmp->nombrePago        = $row->nombrePago; 
                       $tmp->estado_recibo_val        = $row->estado_recibo_val; 
 
            
            
    
            
            $ramdom =  mt_rand();
            $tmp->foto = "https://aranibar.cl/barrosaranas/Table/documentosTransferencias/".$row->id.".png?".$ramdom; 

            if($row->abono_recibo > 0 ){
                $tmp->saldo      = $row->total_recibo - $row->abono_recibo;                
            }else{
                 $tmp->saldo      = 0;
            }
            
            $ramdom =  mt_rand();
            $tmp->fotoFirma = "https://aranibar.cl/barrosaranas/Table/firmasRecibo/".$row->id.".png?".$ramdom; 

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

}
    
    
    
    
  public function insertarReciboUsuario($idClie, 
                                $fCobro, 
                                $nRecibo, 
                                $tipPago, 
                                $totalRecibo, 
                                $totalAbono, 
                                $observacion, 
                                $id_usuario,
                                $objDeta) {
     
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
     
      /* $id_usuario ="";
       $queryConsulta = "SELECT u.id FROM usuario u where u.device_id  = '".$deviceId."'";
       $resultUsuario = mysqli_query($mysql, $queryConsulta);
       $ret = array();
       while ($row = mysqli_fetch_object($resultUsuario)) {
			           $tmp =  new Usuario();
                $id_usuario = $row->id;
       }*/
     
     
     
                $query1 = " INSERT INTO tbl_recibo_cabe
                (fecha_ingreso, 
                fecha_cobro, 
                id_cliente, 
                n_recibo, 
                modo_pago,
                total_recibo,
                abono_recibo,
                observacion,
                device_id,
                id_usuario)
                
                 VALUES (CURDATE(),
                         '".$fCobro."',
                         '".$idClie."',
                         '".$nRecibo."',
                         '".$tipPago."',
                         '".$totalRecibo."',
                         '".$totalAbono."',
                         '".$observacion."',
                         '',
                         '".$id_usuario."');";
               $resultCliente = mysqli_query($mysql, $query1);	
               $idSolicitud = mysqli_insert_id($mysql);            
             
             //  mysqli_free_result($resultCliente);
          
    
            if($idSolicitud != ""){
//     echo "id Recibo: " . $idSolicitud;
                  foreach($objDeta as $detalle){

                          
                    $queryPedDeta = "INSERT INTO tbl_recibo_deta
                                                 (id_recibo_bol, 
                                                       n_pedido, 
                                                       tipo_pedido,
                                                       total_pedido,
                                                       n_folio) 
                             VALUES('".$idSolicitud."', 
                                    '".$detalle->nPedido."', 
                                    '".$detalle->nTipo."',
                                    '".$detalle->total."',
                                    '".$detalle->nFolio."');";  

                 $resultCliente3 =       mysqli_query($mysql, $queryPedDeta);

        
              }
               

           }
     
    
     
     

              

       //        mysqli_free_result($resultCSolicitud);
            return $idSolicitud;
              mysqli_close($mysql);
}   
    
    
    
public function eliminarReciboAplicacion($idPed) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    $query = "DELETE FROM tbl_recibo_cabe  WHERE id = '".$idPed."';";		
	$result = mysqli_query($mysql, $query);	
    
    $query = " DELETE FROM tbl_recibo_deta   where id_recibo_bol = '".$idPed."';";		
	$result = mysqli_query($mysql, $query);	
    
    
   
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
             mysqli_close($mysql);

}
    
   
    
public function listarRecibosAplicacion($fConsulta, $andrID) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
        $tipBus=" c.fecha_ingreso BETWEEN '".$fConsulta."' AND '".$fConsulta."' 
                  AND c.device_id = '".$andrID."' ";        
   
    
    
		$query = "
                   SELECT 
                        c.id, 
                        c.fecha_ingreso, 
                        c.fecha_cobro, 
                        c.id_cliente, 
                        c.n_recibo, 
                        c.modo_pago, 
                        c.total_recibo, 
                        c.abono_recibo,
                        cl.nombre
                        FROM tbl_recibo_cabe c 
                        LEFT OUTER JOIN tbl_cliente cl 
                        on cl.id_cliente = c.id_cliente
                Where 
                ".
            $tipBus
            .
            "
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Usuario();
			$tmp->id                = $row->id;
			$tmp->fecha_ingreso  	= $row->fecha_ingreso;
			$tmp->fecha_cobro       = $row->fecha_cobro;          
        	$tmp->id_cliente        = $row->id_cliente; 
           	$tmp->n_recibo          = $row->n_recibo; 
        	$tmp->modo_pago         = $row->modo_pago; 
        	$tmp->total_recibo      = $row->total_recibo; 
        	$tmp->abono_recibo      = $row->abono_recibo; 
        	$tmp->nombre            = $row->nombre; 

            if($row->abono_recibo > 0 ){
                $tmp->saldo      = $row->total_recibo - $row->abono_recibo;
                
            }else{
                 $tmp->saldo      = 0;
            }
            
              $ramdom =  mt_rand();

             $tmp->foto = "https://aranibar.cl/barrosaranas/Table/documentosTransferencias/".$row->id.".png?".$ramdom; 
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

}
    
           
         
public function listarDetalleRecibo($idRecibo) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
/* AND p.estado_pedido ='1'    */    
/*    $query = "SELECT d.id_deta, 
                       d.id_recibo_bol, 
                       d.n_pedido, 
                       d.tipo_pedido,
                       d.total_pedido,
                       d.n_folio, 
                       c.observacion 
                FROM tbl_recibo_deta d
                INNER JOIN tbl_recibo_cabe c 
                ON c.id = d.id_recibo_bol 
                WHERE d.id_recibo_bol = '".$idRecibo."';";
    
    */
    
    $query ="SELECT d.id_deta, 
                       d.id_recibo_bol, 
                       d.n_pedido, 
                       d.tipo_pedido,
                       d.total_pedido,
                       d.n_folio, 
                       c.observacion,
                       p.fecha_cobro,
                       p.fecha_entrega,
                       tp.nombre as tpPedido
                       
                FROM tbl_recibo_deta d
                INNER JOIN tbl_recibo_cabe c 
                ON c.id = d.id_recibo_bol 
                INNER JOIN tbl_pedido p 
                ON p.id_pedido = d.n_pedido
                INNER JOIN tbl_tipo_pedido tp 
                ON tp.id = p.id_tipo_ped
                WHERE d.id_recibo_bol = '".$idRecibo."'";



    $result = mysqli_query($mysql, $query);

    $ret = array();

    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Usuario();
        
      $tmp->id_deta           = $row->id_deta;      	
      $tmp->id_recibo_bol     = $row->id_recibo_bol;      	
      $tmp->n_pedido          = $row->n_pedido;      	
      $tmp->tipo_pedido       = $row->tipo_pedido;      
      $tmp->total             = $row->total_pedido;      
        
     if($row->tipo_pedido == "not"){         
         $tmp->tipo_pedido       = "Nota de Pedido"; 
         $tmp->n_pedido          = $row->n_pedido; 
     }else{
         $tmp->tipo_pedido       = "Factura";
         $tmp->n_pedido          = $row->n_folio; 
     }    
        
      $tmp->observacion             = $row->observacion;      
      $tmp->fecha_cobro             = $row->fecha_cobro;      
      $tmp->fecha_entrega             = $row->fecha_entrega;      
      $tmp->tpPedido             = $row->tpPedido;      

        
        $ret[] = $tmp;
    }
    	mysqli_free_result($result);
		      return ($ret);
}    
       
    
}
?>