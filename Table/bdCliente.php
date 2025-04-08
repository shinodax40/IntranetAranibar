<?php
require_once('Bean/Cliente.php');
require_once('Bean/Usuario.php');
class bdCliente{
	
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
	
public function listarClientesEmpresa($nombreClie, $responsable, $tipo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        
        $ano= date("Y");
        $mes= date("n");

       
        $idUsua ="";
        if($responsable != ""){        
            $idUsua= "AND u.id='".$responsable."'";            
        }
    
        if($tipo != ""){        
            $tipoComp= "AND c.tipo_comprador='".$tipo."'";            
        }
            
            
		$query =     
            "select    c.id_cliente,
                       c.rut,
                       c.nombre,
                      /* c.direccion ,*/
                       
                          IFNULL(direc.direccion, c.direccion ) as direccion ,
                       
                       
                       c.telefono,
                       c.giro,
                       c.tipo_comprador,
                       c.id_usuario,
                       IFNULL(u.nombre,'') as nombCliente,
                       IFNULL(u.apellido,'') as apeCliente,
                       c.observacion,
                       MAX(p.fecha_entrega) as fecha,
                       DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
                       
                       
                       
                       /*(SELECT SUM(d.cantidad * d.precio_vendido) 
                            FROM tbl_pedido pe 
                            INNER JOIN tbl_deta_pedido d 
                            ON d.id_pedido = pe.id_pedido 
                            WHERE 
                                  pe.anulada='N'
                            AND   pe.id_usuario  NOT IN (3) 
                            AND   pe.id_cliente NOT IN (87,162) 
                            AND   YEAR(pe.fecha_entrega)= ".$ano."
                            AND   MONTH(pe.fecha_entrega)= ".$mes."
                            AND   pe.id_cliente = c.id_cliente
                        ) as mesVentAct,*/
                        c.activo,
						
						(select COUNT(p.id_pedido) 
							from tbl_pedido p 
							where p.id_cliente=c.id_cliente
							AND   YEAR(p.fecha_entrega)= ".$ano."
							AND   MONTH(p.fecha_entrega)= ".$mes."
							AND   p.estado_pedido = 1
						) as cantPedidoMes,
						
						(select COUNT(p.id_pedido) 
							from tbl_pedido p 
							where p.id_cliente=c.id_cliente
							AND   YEAR(p.fecha_entrega)= ".$ano."
							AND   p.estado_pedido = 1
						) as cantPedidoAno,
                        
						c.id_observacion,
						c.fecha_obs_mod,
						
						
						(SELECT  (SUM(d.cantidad * d.precio_vendido))
						FROM tbl_pedido p
						LEFT OUTER JOIN tbl_deta_pedido d 
						ON d.id_pedido = p.id_pedido
						WHERE /*p.fecha_cobro <= NOW()
						and */p.id_cliente=c.id_cliente
						and p.estado_cobranza=4
						and p.anulada='N') as saldoPendiente,
                        c.credito,
                        
                       (SELECT 
                        COUNT(*) as pedPendCobro
                        FROM tbl_pedido pe 
                        LEFT OUTER JOIN (
                        SELECT  p.id_pedido,
                                DATEDIFF( NOW(), p.fecha_entrega) as difDia
                        FROM tbl_pedido p
                                WHERE p.estado_cobranza=4
                                and p.anulada='N'
                                GROUP by p.id_pedido) tt		
                        ON tt.id_pedido = pe.id_pedido
                        LEFT OUTER JOIN tbl_cliente cc 
                        ON cc.id_cliente = pe.id_cliente
                        WHERE cc.id_cliente = c.id_cliente
                        AND pe.id_pedido = tt.id_pedido
                        AND tt.difDia > c.credito
                        AND cc.credito <> 0) as pedPendCobro,
                        

                        
                        (SELECT 
                        COUNT(*) as pedPendCobro
                        FROM tbl_pedido pe 
                        LEFT OUTER JOIN (
                        SELECT  p.id_pedido,
                                DATEDIFF( NOW(), p.fecha_entrega) as difDia
                        FROM tbl_pedido p
                                WHERE p.estado_cobranza=4
                                and p.anulada='N'
                                GROUP by p.id_pedido) tt		
                        ON tt.id_pedido = pe.id_pedido
                        LEFT OUTER JOIN tbl_cliente cc 
                        ON cc.id_cliente = pe.id_cliente
                        WHERE cc.id_cliente = c.id_cliente
                        AND pe.id_pedido = tt.id_pedido
                        AND cc.credito <> 0) as pedPendCobroTotal,
                        
                        
                      /*  sec.id_sector,*/
                        
                           IFNULL(direc.id_sector, sec.id_sector ) as id_sector,
                        
                        l.dias_atencion,
                        l.dias_alarma,
                        
                       
                         (SELECT count(cr.id) FROM tbl_direcciones_clientes cr where cr.id_cliente = c.id_cliente) as cantidaddirecc,
                         direc.deta_direccion,
                         direc.ubicacion_geo
                         

                       
                       
                from tbl_cliente c
                LEFT OUTER JOIN tbl_pedido p 
                ON p.id_cliente = c.id_cliente
                LEFT OUTER JOIN usuario u
                ON u.id = c.id_usuario
                LEFT OUTER JOIN tbl_sector sec
                ON sec.id_sector = c.id_sector
                
               LEFT OUTER JOIN tbl_atencion_cliente l 
               ON l.tipo_cliente = c.tipo_comprador
               
          LEFT OUTER JOIN tbl_direcciones_clientes direc 
               ON direc.id_cliente = c.id_cliente
               AND direc.principal = 1
               
               
                
                WHERE c.nombre like '%".$nombreClie."%'
                AND p.anulada <> 'S'"
                  . 
                      $idUsua 
                  . 
            
                     $tipoComp
                  .
                "GROUP By c.id_cliente,
                       c.nombre,
                       c.direccion,
                       c.telefono,
                       c.giro,
                       c.tipo_comprador,
                       c.id_usuario,
                       u.nombre ,
                       u.apellido ,
                       c.id_observacion,
				       c.fecha_obs_mod,
                       direc.ubicacion_geo,
                       direc.deta_direccion
              /*  ORDER BY  mesVentAct DESC*/";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id_cliente        = $row->id_cliente;			
			$tmp->nombre            = $row->nombre;
			$tmp->direccion         = $row->direccion;
			$tmp->deta_direccion         = $row->deta_direccion;
			$tmp->telefono          = $row->telefono;
			$tmp->giro              = $row->giro;
			$tmp->tipo_comprador    = $row->tipo_comprador;
			$tmp->id_usuario        = $row->id_usuario;
			$tmp->fecha             = $row->fecha;
			$tmp->countFecha        = $row->countFecha;
			$tmp->rut               = $row->rut;
			$tmp->nombCliente       = $row->nombCliente;
			$tmp->apeCliente        = $row->apeCliente;
            $tmp->observacion       = $row->observacion;
            $tmp->mesVentAct        = round ((($row->mesVentAct) * 0.19)+($row->mesVentAct));
                        $tmp->cantidaddirecc       = $row->cantidaddirecc;
                        $tmp->ubicacion_geo       = $row->ubicacion_geo;

            
            
            
            if($row->tipo_comprador == "Mascotero"){      
                 if($row->countFecha < 8){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }                
            }else  if($row->tipo_comprador == "Particular"){
                if($row->countFecha < 31){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }     
            }else  if($row->tipo_comprador == "Almacen"){
                if($row->countFecha < 22){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }     
            }else  if($row->tipo_comprador == "Veterinaria"){
                if($row->countFecha < 22){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }     
            }else  if($row->tipo_comprador == "Avicola"){
                if($row->countFecha < 8){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }     
            }else  if($row->tipo_comprador == "Peluqueria"){
                if($row->countFecha < 22){
                     $tmp->countFecha = 0;
                 }else{                     
                     $tmp->countFecha = 1;
                 }     
            }
            
            $tmp->activo        = $row->activo;
            $tmp->cantPedidoMes = $row->cantPedidoMes;
            $tmp->cantPedidoAno = $row->cantPedidoAno;
			
			$tmp->id_observacion=$row->id_observacion;
			
			if($row->fecha_obs_mod=='0000-00-00'){
                  $tmp->fecha_obs_mod      = '';
            }else{
			     $tmp->fecha_obs_mod = $row->fecha_obs_mod;
            }

			$tmp->saldoPendiente = round ((($row->saldoPendiente) * 0.19)+($row->saldoPendiente));
            
			$tmp->credito     =$row->credito;
            
			$tmp->pedPendCobro     =$row->pedPendCobro;
            
			$tmp->pedPendCobroTotal     =$row->pedPendCobroTotal;
			$tmp->id_sector     =$row->id_sector;
            
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
}
    
public function listarVendedores() {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =     
            "SELECT            u.id,
                               u.nombre as name ,
                               u.apellido,
                               u.id_tipo,
                               t.nombre_tipo
                        FROM usuario u 
                        INNER JOIN tipo_usuario t 
                        ON t.id_tipo = u.id_tipo
                        WHERE t.id_tipo NOT IN (6,5,4,3) ;";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id                = $row->id;			
			$tmp->name              = $row->name;
			$tmp->apellido          = $row->apellido;
			$tmp->id_tipo           = $row->id_tipo;
			$tmp->nombre_tipo       = $row->nombre_tipo;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}    

public function listarSector() {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =     
            "SELECT            u.id_sector as id,
                               u.nombre_sector as name 
                        FROM tbl_sector u";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id                = $row->id;			
			$tmp->name              = $row->name;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}    
    
public function guardarCliente($arrCliente){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
       $activo = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM tbl_cliente c WHERE c.rut = '".$arrCliente[0]->rut."' "));    
    

    if($arrCliente[0]->accion == "Modificar"){
    
		$queryCliente = "UPDATE tbl_cliente 
                    SET  
                         rut         = '".$arrCliente[0]->rut."' 
                    ,nombre          = '".$arrCliente[0]->nombre."' 
                    ,direccion       = '".$arrCliente[0]->direccion."' 
                    ,telefono        = '".$arrCliente[0]->telefono."' 
                    ,giro            = '".$arrCliente[0]->giro."' 
                    ,tipo_comprador  = '".$arrCliente[0]->tipoCliente."' 
                    ,id_usuario      = '".$arrCliente[0]->idUsuario."' 
                    ,observacion     = '".$arrCliente[0]->obserMod."' 
                    ,comuna          = 'Arica'
                    ,activo          = '".$arrCliente[0]->activo."' 
                    ,id_sector          = '".$arrCliente[0]->idSector."' 
                   WHERE id_cliente  =  '".$arrCliente[0]->idCliente."';";
        
        mysqli_query($mysql, $queryCliente);
        
        
        		$queryDireccion = "UPDATE tbl_direcciones_clientes 
                    SET  
                     direccion            = '".$arrCliente[0]->direccion."' 
                    ,deta_direccion       = '".$arrCliente[0]->dirModDeta."' 
                    ,id_sector            = '".$arrCliente[0]->idSector."' 
                    ,ubicacion_geo        = '".$arrCliente[0]->dirUbiGeo."' 
                   WHERE id_cliente       = '".$arrCliente[0]->idCliente."'
                   AND principal=1;";
        
        mysqli_query($mysql, $queryDireccion);
        
        
        
        
        
        
        
        if(mysqli_query($mysql, $queryCliente)){
                return 1;
            } else {
                return 2;
            }
    
    
    }else if($arrCliente[0]->accion  == "Nuevo"){        

        if($activo == 0) {   
            
               

                    $queryCliente ="INSERT INTO tbl_cliente
                              (rut, 
                              nombre, 
                              telefono, 
                              giro, 
                              tipo_comprador, 
                              id_usuario,
                              comuna,
                              bandera_new,
                              fecha_ingreso,
                              activo
                              ) 
                             VALUES (
                             '".$arrCliente[0]->rut."',
                             '".$arrCliente[0]->nombre."',
                             ".$arrCliente[0]->telefono.",
                             '".$arrCliente[0]->giro."',
                             '".$arrCliente[0]->tipoCliente."',
                             '".$arrCliente[0]->idUsuario."',
                             'Arica',
                              1,
                              CURDATE(),
                              1);
                              ";

                               mysqli_query($mysql, $queryCliente);
                                    $id_clie = mysqli_insert_id($mysql); 


                      $queryDireccion = "INSERT INTO tbl_direcciones_clientes(
                                                        id_cliente, 
                                                        direccion, 
                                                        deta_direccion, 
                                                        id_sector, 
                                                        comuna, 
                                                        ubicacion_geo,
                                                        principal) 
                                                        VALUES ('".$id_clie."',
                                                                '".$arrCliente[0]->direccion."',
                                                                '".$arrCliente[0]->dirModDeta."',
                                                                '".$arrCliente[0]->idSector."',
                                                                'Arica',
                                                                '".$arrCliente[0]->dirUbiGeo."',                                                                
                                                                1);
                                                        ";

                                 

                 if(mysqli_query($mysql, $queryDireccion)){
                            return 1;
                        } else {
                            return 2;
                        }
            
            
            

                }else {   
                      return 3;  
                 }
    
    }
    
 
}
    
public function listarClientesProd($id,  $desde, $hasta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =  "SELECT    pe.id,       
                       pe.nombreProd,
                       SUM(d.cantidad) AS total 
                FROM tbl_pedido p  
                LEFT OUTER JOIN tbl_deta_pedido d 
                on d.id_pedido = p.id_pedido
                LEFT OUTER JOIN productos pe 
                on pe.id = d.id_prod
                LEFT OUTER JOIN categoria c 
                ON c.codCategoria = pe.categoriaProd 
                LEFT OUTER JOIN marca m 
                ON m.codMarca = pe.marcaProd
                WHERE 
                p.fecha_entrega  BETWEEN '".$desde."'  and '".$hasta."'
                and  p.anulada = 'N'
                and  p.id_cliente = '".$id."'
                GROUP by   pe.codProducto, 
                           pe.nombreProd
                ORDER BY total DESC";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id                = $row->id;			
			$tmp->name              = $row->nombreProd;
			$tmp->total          = $row->total;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}      
    
public function updateObservacionClie($idObs, $idCli) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_cliente c
                  SET c.id_observacion='".$idObs."',
				      c.fecha_obs_mod = CURDATE() 
                  WHERE c.id_cliente='".$idCli."';
        ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
}    
      
public function guardarObsClie($idObs, $idCli) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_cliente c
                  SET c.observacion='".$idObs."',
				      c.fecha_obs_mod = CURDATE() 
                  WHERE c.id_cliente='".$idCli."';
        ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
}
    
    
public function guardarObsInvt($idObs, $idCli) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_inventario p 
                  SET p.observacion='".$idObs."'
                  WHERE p.id='".$idCli."';
                 ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
}  
    
    
public function guardarObsCredito($idCred, $obsCred) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "                  
                 UPDATE tbl_credito c SET  c.observacion_credito='".$obsCred."' where c.id='".$idCred."';  
                  
                 ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
}        
         
    
      
public function listarClientesRojo($idUsua) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        
    
            
		$query ="select    c.id_cliente,
                       c.rut,
                       c.nombre,
                       c.direccion,
                       c.telefono,
                       c.giro,
                       c.tipo_comprador,
                       c.id_usuario,
                       IFNULL(u.nombre,'') as nombCliente,
                       IFNULL(u.apellido,'') as apeCliente,
                       c.observacion,
                       MAX(p.fecha_entrega) as fecha,
                       
                       DATEDIFF(CURDATE(),MAX(p.fecha_entrega)) as countFecha,
                        l.dias_atencion,
                        l.dias_alarma
                       
                       
                from tbl_cliente c
                INNER JOIN tbl_pedido p 
                ON p.id_cliente = c.id_cliente
                LEFT OUTER JOIN usuario u
                ON u.id = c.id_usuario
                LEFT OUTER JOIN tbl_sector sec
                ON sec.id_sector = c.id_sector
                
               LEFT OUTER JOIN tbl_atencion_cliente l 
               ON l.tipo_cliente = c.tipo_comprador
                
                WHERE
                 p.anulada = 'N'
                 AND u.id='212'
                 AND c.tipo_comprador in ('Almacen','Veterinaria','Mascotero','Peluqueria') 
                 GROUP By c.id_cliente,
                       c.nombre,
                       c.direccion,
                       c.telefono,
                       c.giro,
                       c.tipo_comprador,
                       c.id_usuario,
                       u.nombre ,
                       u.apellido;";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id_cliente        = $row->id_cliente;			
			$tmp->nombre            = $row->nombre;
			$tmp->direccion         = $row->direccion;
			$tmp->telefono          = $row->telefono;
			$tmp->giro              = $row->giro;
			$tmp->tipo_comprador    = $row->tipo_comprador;
			$tmp->id_usuario        = $row->id_usuario;
			$tmp->fecha             = $row->fecha;
			$tmp->countFecha        = $row->countFecha;
			$tmp->rut               = $row->rut;
			$tmp->nombCliente       = $row->nombCliente;
			$tmp->apeCliente        = $row->apeCliente;
            $tmp->observacion       = $row->observacion;
            
        
			
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}
    
public function listarRecibosClientes($idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT c.id, 
                        c.fecha_ingreso, 
                        c.fecha_cobro, 
                        c.id_cliente, 
                        c.n_recibo, 
                        c.modo_pago, 
                        c.total_recibo, 
                        c.abono_recibo,
                        cl.nombre,
                        c.observacion,
                        u.nombre as nombUsuario,
                        cs.nombre as nombCobro

                        FROM tbl_recibo_cabe c 
                        LEFT OUTER JOIN tbl_cliente cl 
                        on cl.id_cliente = c.id_cliente
                        LEFT OUTER JOIN tbl_recibo_deta d 
                        ON d.id_recibo_bol = c.id 
                        LEFT OUTER JOIN usuario u 
                        ON u.device_id = c.device_id 
                        AND u.id = c.id_usuario
                        LEFT OUTER JOIN tbl_estado_cobro cs
                        ON cs.id_cobro = c.modo_pago
                        WHERE d.n_pedido = '".$idPedido."'";
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
        	$tmp->observacion       = $row->observacion; 
        	$tmp->nombUsuario       = $row->nombUsuario; 
        	$tmp->nombCobro         = $row->nombCobro; 
            
                $ramdom =  mt_rand();

             $tmp->foto = "https://intranet.aranibar.cl/barrosaranas/Table/documentosTransferencias/".$row->id.".png?".$ramdom; 
            

         //   $tmp->foto = "https://intranet.aranibar.cl/barrosaranas/Table/documentosTransferencias/".$row->id.".png?".$ramdom; 
          
            
            
            if($row->abono_recibo > 0 ){
                $tmp->saldo      = $row->total_recibo - $row->abono_recibo;
                
            }else{
                 $tmp->saldo      = 0;
            }
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

}
    
    
    
public function modificarEstadoClienteCobro($idPedido, $documento, $estadoCobro) {    
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");    
       	$query = "UPDATE tbl_pedido pe
                    SET pe.cod_documento      = '".$documento."',
                        pe.estado_cobranza    = '".$estadoCobro."'                      
                    WHERE pe.id_pedido     = '".$idPedido."';  ";
    
	$result = mysqli_query($mysql, $query);		    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }

         mysqli_close($mysql);
}       
    
    
public function listarCobranzaDetaCliente($idClie) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query =  "
        
                  select          c.id_cliente,
                                    c.rut,
                                    c.nombre,						
                                    (SELECT  (SUM(d.cantidad * d.precio_vendido))
                                    FROM tbl_pedido p
                                    LEFT OUTER JOIN tbl_deta_pedido d 
                                    ON d.id_pedido = p.id_pedido
                                    WHERE p.id_cliente=c.id_cliente
                                    and p.estado_cobranza=4
                                    and p.anulada='N') as saldoPendiente,                        
                                    c.credito,
                                    c.cobranza_act,
                                    c.limitado_act,
                                    c.obs_cobranza,
                                    c.limite_compra


                            from tbl_cliente c
                            LEFT OUTER JOIN tbl_pedido p 
                            ON p.id_cliente = c.id_cliente               

                            WHERE c.id_cliente = '".$idClie."'
                            AND p.anulada = 'N'
                            GROUP By c.id_cliente,
                                     c.nombre,
                                     c.credito;
        
        
        ";                            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Cliente();
			$tmp->id_cliente                = $row->id_cliente;			
            $tmp->rut              = $row->rut;
            $tmp->nombre          = $row->nombre;
            $tmp->saldoPendiente          = $row->saldoPendiente;
            $tmp->credito          = $row->credito;
            $tmp->cobranza_act          = $row->cobranza_act;
                        $tmp->limitado_act          = $row->limitado_act;
                        $tmp->obs_cobranza          = $row->obs_cobranza;
                        $tmp->limite_compra          = $row->limite_compra;


			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}      
    
    
 public function actualizarDetalleCobranza($idCli, $actCobra, $actLimit, $obsCobra, $limtCompra) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "      UPDATE tbl_cliente c 
                        SET c.cobranza_act = '".$actCobra."'
                           ,c.limitado_act = '".$actLimit."'
                           ,c.obs_cobranza = '".$obsCobra."'
                           ,c.limite_compra = '".$limtCompra."'
                         WHERE c.id_cliente = '".$idCli."';   ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
}     
    
    
}
?>