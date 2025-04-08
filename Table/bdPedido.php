<?php
require_once('Bean/Factura.php');
require_once('Bean/Pedido.php');
require_once('Bean/PedidoDetalle.php');
require_once('Bean/InformeTransp.php'); 

class bdPedido{	
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
	

public function updateConf($id , $activo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");

       	$query = "UPDATE tbl_config pe
                    SET pe.activo      = '".$activo."'                        
                    WHERE  pe.modo='General' and pe.id     = '".$id."';
                ";
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
    
      mysqli_close($mysql);
}          
    
public function consulEstadoConfig() {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'"); 
    $query = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM tbl_config WHERE  modo='General' and activo='1'"));  
    return $query;
    mysqli_close($mysql);
}    
    
    
    
    
    
public function insertarPedido($objCabe, $objDeta, $objCliente) {
    	$horaDiferencia = 0;

    
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);	
    
    
    if (mysqli_connect_errno())
      {
             echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
    
         mysqli_set_charset($mysql, "utf8");
    
         mysqli_autocommit($mysql, FALSE);    
         
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);

	    $id_clie     = $objCliente[0]->id;
        $tipo_compra = $objCliente[0]->tipoComprador;
	    $id_direccion= $objCliente[0]->idDireccion;

 //$activo = mysqli_num_rows(mysqli_query($mysql, "SELECT p.id FROM tbl_config p WHERE  p.modo='General' and p.activo=1;"));      
    
//     $activo = (mysqli_query($mysql, "SELECT p.id FROM tbl_config p WHERE  p.modo='General' and p.activo=1;"));      

        
try{
    
    
    
  //if(mysqli_num_rows($activo) != 0) {
       // Do Something 
      //
   if(1 != 0) {   
    
    
//if($activo == 1) {                  
                          
                if($tipo_compra == "Nuevo"){
                    
                           $queryCliente = "INSERT INTO tbl_cliente( 
                                                 nombre, 
                                                 direccion, 
                                                 tipo_comprador, 
                                                 telefono,
                                                 comuna,
                                                 id_usuario,
                                                 activo,
                                                 id_sector) 
                           VALUES ('".$objCliente[0]->nombre."',
                                   '',
                                   'Particular',
                                   '".$objCliente[0]->celular."',
                                   'Arica',
                                   '".$objCabe[0]->idUsuario."',                                                          
                                   1,
                                   0)";
                         mysqli_query($mysql, $queryCliente);
                        $id_clie = mysqli_insert_id($mysql);
                    
                    
                        $queryDireccion = "
                        
                                            INSERT INTO tbl_direcciones_clientes(
                                            id_cliente, 
                                            direccion, 
                                            deta_direccion, 
                                            id_sector, 
                                            comuna, 
                                            ubicacion_geo) 
                                            VALUES ('".$id_clie."',
                                                    '".$objCliente[0]->direccion."',
                                                    '".$objCliente[0]->direccion_deta."',
                                                    '".$objCliente[0]->idSector."',
                                                    'Arica',
                                                    '')
                                            ";
                    
                         mysqli_query($mysql, $queryDireccion);
                         $id_direccion = mysqli_insert_id($mysql); 
                    
                    
                    
                    
                    
                    

                }else if($tipo_compra == "Particular"){
                    
                            $queryCliente = "UPDATE tbl_cliente
                              SET 
                                nombre         = '".$objCliente[0]->nombre."'
                              , telefono       = '".$objCliente[0]->celular."'

                              WHERE id_cliente = '".$id_clie."'";
                                            
                  mysqli_query($mysql, $queryCliente);

                
                }else if($tipo_compra == "Cliente"){
                    
                            $queryCliente = "UPDATE tbl_cliente
                                  SET  
                                   telefono       = '".$objCliente[0]->celular."'
                                  WHERE id_cliente = '".$id_clie."'";
                    
                          mysqli_query($mysql, $queryCliente);

                 
                
                }

                $descuento = (isset($objCliente[0]->descuento) ? $objCliente[0]->descuento : 0);
         $queryPedidoCabe = "INSERT INTO tbl_pedido(fecha, 
                                          id_usuario, 
                                          id_cliente, 
                                          anulada,
                                          estado_cobranza,
                                          estado_pedido,
                                          hora,
                                          fecha_entrega,
                                          jornada,
                                          observacion,
                                          estado_cobro_dep,
                                          entregaPed,
                                          id_tipo_ped,
                                          id_direccion,
                                          descuento
                                          ) 
         VALUES (CURDATE() , 
                 '".$objCabe[0]->idUsuario."',
                 '".$id_clie."', 
                 'N',
                 '".$objCliente[0]->tipoDePago."', 
                 '3',
                 NOW() + INTERVAL ".$horaDiferencia." HOUR,
                 '".$objCliente[0]->despacho."',
                 '".$objCliente[0]->jornada."',
                 '".$objCliente[0]->observacioPed."',
                 '".$objCliente[0]->cobroDespacho."',
                 '".$objCliente[0]->entregaPed."',
                 '".$objCliente[0]->tipoPedido."',
                 ".$id_direccion.", ".$descuento.")";
                
    mysqli_query($mysql, $queryPedidoCabe);

    
         $id_ped = mysqli_insert_id($mysql);


    
//if($resultCabe == "1"){
    //$queryPedidoDeta = "";
    foreach($objDeta as $detalle){

        $queryPedDeta = "INSERT INTO 
                 tbl_deta_pedido(id_pedido, 
                                   id_prod, 
                                   cantidad, 
                                   precio_vendido, 
                                   descuento, 
                                   aumento,  
                                   total,
                                   id_precio_factura) 
                 VALUES('".$id_ped."', 
                        '".$detalle->id."', 
                        '".$detalle->cantidadProd."', 
                        '".$detalle->precioVenta."',
                        '".$detalle->descuento."', 
                        '".$detalle->aumento."', 
                        '".$detalle->totalProd."',
                        '".$detalle->idPrecioFactura."'
                        ); ";  

            mysqli_query($mysql, $queryPedDeta);

        
    }
    
    
echo $id_ped;
    
}else{
    
  return "3";    
    
}
    
    
    
}catch( mysqli_sql_exception  $e ){
    var_dump($e->getTrace()[0]);
    return "0";       
   mysqli_rollback($mysql);
    
    
   
}

mysqli_commit($mysql);    
    


}                          
  
public function listarPedidosGeneral($tipoPagos, $nombre, $idPedido, 
                                     $folio,
                                     $desde1, 
                                     $hasta1,                                     
                                     $desde2, 
                                     $hasta2,                                     
                                     $desde3, 
                                     $hasta3,                                     
                                     $desde4, 
                                     $hasta4,
                                     $tipBusq, $idTransp, $busqRap,$tipoUsuario, $idUsuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
         $tipBus="";
         $tipBus2="";    

    
if($tipoUsuario == 'Vendedor' || $tipoUsuario == 'Bodeguero'){
   $tipBus2=" AND p.id_usuario='".$idUsuario."' ";    
}  
    
    
    
switch ($tipBusq) {
    case "1": //Ingreso
        
        
           $tipBus=" WHERE p.fecha  BETWEEN ('".$desde1."')  and ('".$hasta1."') ";
        
        
        break;
        
        
    case "2": //Despacho
             $tipBus="  
            WHERE p.fecha_entrega  BETWEEN ('".$desde2."')  AND ('".$hasta2."') 
                       AND p.anulada <>'S' 
                       AND c.id_cliente <> '162'
                       AND p.estado_pedido <> '1'
                    ";
        
      
        
        
  
        break;
          
       case "3": // Pago
        
        $tipBus="  
            WHERE  e.id_cobro = '".$tipoPagos."'  
            AND p.fecha_entrega  BETWEEN ('".$desde3."')  and ('".$hasta3."') ";
        
                
        break;
    
        
        case "4": // Nombre
        
        $tipBus="  
            WHERE     c.nombre like '%".$nombre."%' 
            AND p.fecha_entrega  BETWEEN ('".$desde4."')  and ('".$hasta4."') ";
        
                
        break;    
        
  
        
            case "5": // Folio
        
        $tipBus="  
            WHERE     f.folio = '".$folio."' ";
        
                
        break;   
        
        
               case "6": // Folio
        
        $tipBus="  
            WHERE     p.id_pedido = '".$idPedido."' ";
        
                
        break;  
        
        
        /*
       case "5": 
        
             $tipBus = "
          WHERE 
           p.fecha_entrega  =  CURDATE() 
         
         AND p.id_cliente   <>'162'
         AND p.entregaPed =2
         
         
         ";
        
                
        break;        
        */
}    
    
    
    /*
if($busqRap ==''){ 
       if($tipBusq=="Ingreso"){
            $tipBus="  
            WHERE 
                c.nombre like '%".$nombre."%' 
                
            "
            .
            $tipBus3
            .
			"  
                
                and e.id_cobro like '".$tipoPagos."%'  
            AND p.fecha  BETWEEN ('".$desde."')  and ('".$hasta."')";
        }else if($tipBusq=="Pago"){
            $tipBus="  
            WHERE 
                c.nombre like '%".$nombre."%' 
             "
            .
            $tipBus3
            .
			"      
                
                and e.id_cobro like '".$tipoPagos."%'  
            AND p.fecha_cobro  BETWEEN ('".$desde."')  and ('".$hasta."') AND p.anulada <>'S' ";
        }else if($tipBusq=="Despacho"){
           
                $tipBus="  
            WHERE 
            
                c.nombre like '%".$nombre."%' 
                 "
            .
            $tipBus3
            .
			"  
            AND e.id_cobro like '".$tipoPagos."%'                  
            AND p.fecha_entrega  BETWEEN ('".$desde."')  
                       AND ('".$hasta."') 
                       AND p.anulada <>'S' 
                       AND c.id_cliente <> '162'
                       AND p.estado_pedido <> '1'
                    ";
         
           
           if ($idTransp != 0){
                    $tipBus="";
                     $tipBus .=" 
                        LEFT OUTER JOIN  tbl_informe_transp_deta d
                        ON d.id_pedido = p.id_pedido
                        LEFT OUTER JOIN tbl_informe_transp_cabe cc 
                        ON cc.id = d.id_informe
                     WHERE 
                     
                       c.nombre like '%".$nombre."%' 
                       AND e.id_cobro like '".$tipoPagos."%'  
                       AND cc.id_transp = (".$idTransp.")  
                       AND p.fecha_entrega  BETWEEN ('".$desde."')  AND ('".$hasta."') 
                       AND p.anulada <>'S' 
                       AND c.id_cliente <> '162'
                       AND p.estado_pedido <> '1' ";
            }
           
           
        }else if($tipBusq == "Retiro en tienda"){
             $tipBus2 = "
          WHERE 
           p.fecha_entrega  =  CURDATE() 
         
         AND p.id_cliente   <>'162'
         AND p.entregaPed =2
         
         
         ";
        }
    
      if($idPedido != ""){
          
         $tipBus2 = "
         
         and f.folio like ('%".$idPedido."%') ";
      }

    
}else{
    
    
if($busqRap != "111"){
         $tipBus="  
         WHERE 
             c.nombre   like '%".$nombre."%' 
              "
            .
            $tipBus3
            .
			"  
         AND e.id_cobro like '".$tipoPagos."%'  
         AND p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."') 
         AND p.anulada = 'N' 
         AND p.id_cliente   <>'162'
         AND p.estado_pedido = (".$busqRap.")  
        
        
        ";
         }else{
          $tipBus="  
          WHERE 
                c.nombre like '%".$nombre."%' 
                
            "
            .
            $tipBus3
            .
			"  
                
          AND e.id_cobro like '".$tipoPagos."%'  
          
          AND p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."') 
          AND p.id_cliente   <>'162'
          AND p.anulada = 'S'  ";
      }
    
   

}    */
    
 		$query = "
			    select 
                p.id_pedido,    
                p.descuento,
                DATE_FORMAT(p.fecha,'%Y-%m-%d') as fecha,
                c.rut, 
                c.nombre, 
                c.telefono, 
                u.usuario , 
                t.nombre_tipo,
                p.anulada,
                /*c.direccion,*/
 
               IFNULL(direc.direccion, c.direccion ) as direccion ,

                direc.deta_direccion,
                
                c.giro,
                c.id_cliente,
				f.folio,                
                UPPER(c.comuna),
                p.estado_cobranza,
                e.nombre as nombre_cobro,
                DATE_FORMAT(p.fecha_cobro,'%Y-%m-%d') as fecha_cobro,
                p.cod_documento,
                c.tipo_comprador,
                p.observacion,
                pe.id_estado,
                pe.nombre as nombre_estado,
                p.estado_pedido,
                (select COUNT(t.id) from tbl_ingreso_tienda_cabe t where t.id_pedido=p.id_pedido) as tienda,
                p.hora,
                p.hora_transporte,
                DATE_FORMAT(p.fecha_transporte,'%d-%m-%Y') as fecha_transporte ,
                DATE_FORMAT(p.fecha_entrega,'%Y-%m-%d') as fecha_entrega,
                p.obser_vend, 
                p.obser_transp,
                p.cobrado_vend,
                (select ROUND(SUM( (ped.cantidad * ped.precio_vendido)*1.19) , 0) 
                from tbl_deta_pedido ped where ped.id_pedido=p.id_pedido) as totalPedido,
                p.jornada,
                CONCAT(u.nombre,' ', u.apellido) as nombreUsuario,
                p.estado_cobro_dep,
				p.orden_ruta,
				
				(SELECT SUM(pr.pesosProd * pp.cantidad)
					FROM tbl_deta_pedido pp
					LEFT OUTER JOIN productos pr 
					on pr.id = pp.id_prod
					where pp.id_pedido = p.id_pedido) as pesos,
                c.credito,
                p.entregaPed,
                
      
                
                IFNULL(  (select cx.id_transp  
               from tbl_informe_transp_cabe cx 
                    INNER JOIN tbl_informe_transp_deta dx
                    ON cx.id = dx.id_informe
                    WHERE dx.id_pedido = p.id_pedido
                    and cx.estado <> 1 ), 0) as id_transp,
                
               /* c.id_sector,*/
               
                  IFNULL(direc.id_sector, c.id_sector ) as id_sector,
               
               
                p.id_tipo_ped,
                
                    (select d.id_informe from tbl_informe_transp_cabe c 
                    INNER JOIN tbl_informe_transp_deta d
                    ON c.id = d.id_informe
                    WHERE d.id_pedido = p.id_pedido
                    and c.estado <> 1 ) as id_informe,
                    
                    u.correo,
                
                /*,
                p.est_bodega_ped,
                p.est_transp_ped*/
                
                   (SELECT 
                    COUNT(l.id)
                    FROM tbl_log l 
                    INNER JOIN usuario t 
                    ON t.id = l.id_usuario
                    WHERE  l.id_pedido = p.id_pedido) as countLog,
                    f.identificacion,
                    f.tipo as tipo_folio,
                    
              
              
       (SELECT CASE WHEN pp.img_transf IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM tbl_pedido pp WHERE pp.id_pedido=p.id_pedido) as estadoFotoTransf,
                    p.direccion_pedido,
                    
                    (SELECT COUNT(rc.n_pedido) as id FROM tbl_recibo_deta rc WHERE rc.n_pedido=p.id_pedido) as id_recibo_bol,
                    
                    p.cobrado_vend,
                    
                    (SELECT count(py.id) FROM tbl_credito py where py.id_pedido = p.id_pedido) as id_count_credito


            from tbl_pedido p 
            inner join tbl_cliente c 
            on c.id_cliente = p.id_cliente
             inner join usuario u 
            on u.id = p.id_usuario
             inner join tipo_usuario t 
            on t.id_tipo = u.id_tipo
			left outer join tbl_nubox_facturas f 
            on f.id_pedido  = p.id_pedido
            inner join  tbl_estado_cobro e 
            on e.id_cobro  = p.estado_cobranza
            left outer join tbl_estado_pedido pe
            on pe.id_estado = p.estado_pedido
            
           /*  LEFT OUTER JOIN  tbl_informe_transp_deta d
            ON d.id_pedido = p.id_pedido
            LEFT OUTER JOIN tbl_informe_transp_cabe cc 
            ON cc.id = d.id_informe*/
            
           /* left outer join tbl_informe_transp_deta id 
            ON id.id_pedido = p.id_pedido   */  
            
           /* left outer join tbl_recibo_deta rc 
            ON rc.n_pedido = p.id_pedido*/
            
            LEFT OUTER JOIN tbl_direcciones_clientes direc 
            ON direc.id = p.id_direccion
            
            

			
                "
            
            
            .
            $tipBus
            .
        
           
			"            
            
             
            
            "
            .
            $tipBus2
            .
            
            
            "
            
            ORDER BY  c.tipo_comprador, p.id_pedido desc ;
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
        $countList = 0;
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
            
                         	$tmp->id_count_credito    	   = $row->id_count_credito;			

         	$tmp->cobrado_vend    	   = $row->cobrado_vend;			
    
			$tmp->id_pedido    	   = $row->id_pedido;			
			$tmp->fecha            = $row->fecha;
			$tmp->rut  	           = $row->rut;
			$tmp->nombre           = $row->nombre;
			$tmp->telefono         = $row->telefono;
			$tmp->usuario          = $row->usuario;
			$tmp->nombre_tipo      = $row->nombre_tipo;			
			$tmp->anulada          = $row->anulada;			
            $tmp->giro             = $row->giro;			
            $tmp->id_cliente       = $row->id_cliente;	
            $tmp->folio            = $row->folio;
            $tmp->comuna           = $row->comuna;
            $tmp->nombre_cobro     = $row->nombre_cobro;
            $tmp->estado_cobranza  = $row->estado_cobranza;
            $tmp->cod_documento    = $row->cod_documento;
            $tmp->tipo_comprador   = $row->tipo_comprador;
            $tmp->observacion      = $row->observacion;
            $tmp->id_estado        = $row->id_estado;
            $tmp->nombre_estado    = $row->nombre_estado;
            $tmp->estado_pedido    = $row->estado_pedido;
            $tmp->tienda           = $row->tienda;
            $tmp->id_recibo_bol    = $row->id_recibo_bol;
          
            $countList = $countList  + 1 ;
            
            $tmp->cantidadListar   = $countList ;
            

			
            if($row->fecha_cobro=='0000-00-00'){
                  $tmp->fecha_cobro      = '';
            }else{
                  $tmp->fecha_cobro      = $row->fecha_cobro;   
            }
            
            if($row->hora=='00:00:00'){
                  $tmp->hora      = '';
            }else{
                   $tmp->hora           = $row->hora;
            }
            
            
           if($row->hora_transporte=='00:00:00'){
                  $tmp->hora_transporte      = '';
            }else{
                   $tmp->hora_transporte           = $row->hora_transporte;
            }
           
            
            if($row->fecha_transporte=='00-00-0000'){
                  $tmp->fecha_transporte      = '';
            }else{
                   $tmp->fecha_transporte    = $row->fecha_transporte;
            }
            
            
            if($row->fecha_entrega=='00-00-0000'){
                  $tmp->fecha_entrega      = '';
            }else{
                   $tmp->fecha_entrega    = $row->fecha_entrega;
            }
            
            $tmp->obser_vend           = $row->obser_vend;
            $tmp->obser_transp         = $row->obser_transp;
            $tmp->cobrado_vend         = $row->cobrado_vend;
            $tmp->totalPedido          = $row->totalPedido;
            $tmp->jornada              = $row->jornada;
            $tmp->nombreUsuario        = $row->nombreUsuario;
            $tmp->estado_cobro_dep     = $row->estado_cobro_dep;
            $tmp->orden_ruta           = $row->orden_ruta;
            $tmp->pesos                = $row->pesos;
            $tmp->credito              = $row->credito;			
            $tmp->entregaPed           = $row->entregaPed;			
            $tmp->id_transp            = $row->id_transp;
            $tmp->id_sector            = $row->id_sector;
            $tmp->id_tipo_ped          = $row->id_tipo_ped;
            $tmp->estadoFotoTransf     = $row->estadoFotoTransf;
      
            
            
 /*
            $tmp->est_bodega_ped            = $row->est_bodega_ped;
            $tmp->est_transp_ped            = $row->est_transp_ped;

            */
           
            $tmp->isRowSelected        = "false";            
            if($row->credito=='0' || $row->credito=='1' ){
                  $tmp->formaPago      = 'Contado';
                  $tmp->credito         = '0';    
            }else{
                  $tmp->formaPago    = 'Credito';
            }
            
           $tmp->id_informe        = $row->id_informe;
           $tmp->correo            = $row->correo;
           $tmp->countLog          = $row->countLog;
           $tmp->identificacion          = $row->identificacion;            
           $tmp->tipo          = "";
           $tmp->tipoDocAct        = "0";


            if($row->tipo_folio =="39" ){
              $tmp->tipo_folio          = "Bol";
            
            }else if($row->tipo_folio =="33" ){
                
                $tmp->tipo_folio          = "Fac";
           }
            
                 $tmp->direccion_pedido = $row->direccion_pedido;            
                 $tmp->direccion        = $row->direccion;			
                 $tmp->descuento        = $row->descuento;


          if($row->direccion_pedido==""){
               $tmp->direccion        = $row->direccion.", ".$row->deta_direccion;		
              
          }else{
               $tmp->direccion       = $row->direccion_pedido;            

          }
            
            
            
            
            $query2 = "
			SELECT 
            d.id_pedido, 
            p.id as id_prod, 
            p.codProducto, 
            p.nombreProd, 
            p.precioVenta, 
            d.cantidad, 
            d.precio_vendido,
            d.descuento,
            d.aumento,
            d.total,
            p.prod_venta_act
            FROM tbl_deta_pedido d 
            INNER JOIN productos p 
            ON p.id = d.id_prod
            WHERE d.id_pedido='".$row->id_pedido."'";
		$result2 = mysqli_query($mysql, $query2);
		$total2 = mysqli_num_rows($result2);
    
		$retDeta = array();
		while ($row = mysqli_fetch_object($result2)) {
			$tmp2 =new PedidoDetalle();
			$tmp2->id_pedido    	 = $row->id_pedido;			
			$tmp2->id_prod        = $row->id_prod;
			$tmp2->codProducto  	 = $row->codProducto;
			$tmp2->nombreProd     = $row->nombreProd;
			$tmp2->precioVenta    = $row->precioVenta;
			$tmp2->cantidad       = $row->cantidad;
			$tmp2->precio_vendido = $row->precio_vendido;
            $tmp2->descuento      = $row->descuento;
            $tmp2->aumento        = $row->aumento;
			$tmp2->total          = $row->total;	
			$tmp2->prod_venta_act = $row->prod_venta_act;	
            
			$retDeta[] = $tmp2;
		}
            
            $tmp->detalle            = $retDeta;
 

            

            $ret[] = $tmp;
		}
//		mysqli_free_result($result);
    	//	mysqli_free_result($result2);

		      return ($ret);
    
      mysqli_close($mysql);
}        
   
  
    
    
public function listarPedidos($tipoPagos, $nombre, $desde, $hasta, $idPedido, $tipBusq, $idTransp, $busqRap,$tipoUsuario, $idUsuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
         $tipBus="";
         $tipBus2="";    
         $tipBus3="";    

    
if($tipoUsuario == 'Vendedor' || $tipoUsuario == 'Bodeguero'){
   $tipBus3=" AND p.id_usuario='".$idUsuario."' ";    
}  
    
    
if($busqRap ==''){ 
       if($tipBusq=="Ingreso"){
            $tipBus="  
            WHERE 
                c.nombre like '%".$nombre."%' 
                
            "
            .
            $tipBus3
            .
			"  
                
                and e.id_cobro like '".$tipoPagos."%'  
            AND p.fecha  BETWEEN ('".$desde."')  and ('".$hasta."')";
        }else if($tipBusq=="Pago"){
            $tipBus="  
            WHERE 
                c.nombre like '%".$nombre."%' 
             "
            .
            $tipBus3
            .
			"      
                
                and e.id_cobro like '".$tipoPagos."%'  
            AND p.fecha_cobro  BETWEEN ('".$desde."')  and ('".$hasta."') AND p.anulada <>'S' ";
        }else if($tipBusq=="Despacho"){
           
                $tipBus="  
            WHERE 
            
                c.nombre like '%".$nombre."%' 
                 "
            .
            $tipBus3
            .
			"  
            AND e.id_cobro like '".$tipoPagos."%'                  
            AND p.fecha_entrega  BETWEEN ('".$desde."')  
                       AND ('".$hasta."') 
                       AND p.anulada <>'S' 
                       AND c.id_cliente <> '162'
                       AND p.estado_pedido <> '1'
                    ";
         
           
           if ($idTransp != 0){
                    $tipBus="";
                     $tipBus .=" 
                        LEFT OUTER JOIN  tbl_informe_transp_deta d
                        ON d.id_pedido = p.id_pedido
                        LEFT OUTER JOIN tbl_informe_transp_cabe cc 
                        ON cc.id = d.id_informe
                     WHERE 
                     
                       c.nombre like '%".$nombre."%' 
                       AND e.id_cobro like '".$tipoPagos."%'  
                       AND cc.id_transp = (".$idTransp.")  
                       AND p.fecha_entrega  BETWEEN ('".$desde."')  AND ('".$hasta."') 
                       AND p.anulada <>'S' 
                       AND c.id_cliente <> '162'
                       AND p.estado_pedido <> '1' ";
            }
           
           
        }else if($tipBusq == "Retiro en tienda"){
             $tipBus2 = "
          WHERE 
           p.fecha_entrega  =  CURDATE() 
         
         AND p.id_cliente   <>'162'
         AND p.entregaPed =2
         
         
         ";
        }
    
      if($idPedido != ""){
          
         $tipBus2 = "
         
         and f.folio like ('%".$idPedido."%') ";
      }

    
}else{
    
    
if($busqRap != "4"){
         $tipBus="  
         WHERE 
             c.nombre   like '%".$nombre."%' 
              "
            .
            $tipBus3
            .
			"  
         AND e.id_cobro like '".$tipoPagos."%'  
         AND p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."') 
         AND p.anulada = 'N' 
         AND p.id_cliente   <>'162'
         AND p.estado_pedido = (".$busqRap.")  
        
        
        ";
         }else{
          $tipBus="  
          WHERE 
                c.nombre like '%".$nombre."%' 
                
            "
            .
            $tipBus3
            .
			"  
                
          AND e.id_cobro like '".$tipoPagos."%'  
          
          AND p.fecha_entrega  BETWEEN ('".$desde."')  and ('".$hasta."') 
          AND p.id_cliente   <>'162'
          AND p.anulada = 'S'  ";
      }
    
   

}    
    
 		$query = "
			    select 
                p.id_pedido,                 
                DATE_FORMAT(p.fecha,'%Y-%m-%d') as fecha,
                c.rut, 
                c.nombre, 
                c.telefono, 
                u.usuario , 
                t.nombre_tipo,
                p.anulada,
                /*c.direccion,*/
 
               IFNULL(direc.direccion, c.direccion ) as direccion ,

                direc.deta_direccion,
                
                c.giro,
                c.id_cliente,
				f.folio,                
                UPPER(c.comuna),
                p.estado_cobranza,
                e.nombre as nombre_cobro,
                DATE_FORMAT(p.fecha_cobro,'%Y-%m-%d') as fecha_cobro,
                p.cod_documento,
                c.tipo_comprador,
                p.observacion,
                pe.id_estado,
                pe.nombre as nombre_estado,
                p.estado_pedido,
                (select COUNT(t.id) from tbl_ingreso_tienda_cabe t where t.id_pedido=p.id_pedido) as tienda,
                p.hora,
                p.hora_transporte,
                DATE_FORMAT(p.fecha_transporte,'%d-%m-%Y') as fecha_transporte ,
                DATE_FORMAT(p.fecha_entrega,'%Y-%m-%d') as fecha_entrega,
                p.obser_vend, 
                p.obser_transp,
                p.cobrado_vend,
                (select ROUND(SUM( (ped.cantidad * ped.precio_vendido)*1.19) , 0) 
                from tbl_deta_pedido ped where ped.id_pedido=p.id_pedido) as totalPedido,
                p.jornada,
                CONCAT(u.nombre,' ', u.apellido) as nombreUsuario,
                p.estado_cobro_dep,
				p.orden_ruta,
				
				(SELECT SUM(pr.pesosProd * pp.cantidad)
					FROM tbl_deta_pedido pp
					LEFT OUTER JOIN productos pr 
					on pr.id = pp.id_prod
					where pp.id_pedido = p.id_pedido) as pesos,
                c.credito,
                p.entregaPed,
                
      
                
                IFNULL(  (select cx.id_transp  
               from tbl_informe_transp_cabe cx 
                    INNER JOIN tbl_informe_transp_deta dx
                    ON cx.id = dx.id_informe
                    WHERE dx.id_pedido = p.id_pedido
                    and cx.estado <> 1 ), 0) as id_transp,
                
               /* c.id_sector,*/
               
                  IFNULL(direc.id_sector, c.id_sector ) as id_sector,
               
               
                p.id_tipo_ped,
                
                    (select d.id_informe from tbl_informe_transp_cabe c 
                    INNER JOIN tbl_informe_transp_deta d
                    ON c.id = d.id_informe
                    WHERE d.id_pedido = p.id_pedido
                    and c.estado <> 1 ) as id_informe,
                    
                    u.correo,
                
                /*,
                p.est_bodega_ped,
                p.est_transp_ped*/
                
                   (SELECT 
                    COUNT(l.id)
                    FROM tbl_log l 
                    INNER JOIN usuario t 
                    ON t.id = l.id_usuario
                    WHERE  l.id_pedido = p.id_pedido) as countLog,
                    f.identificacion,
                    f.tipo as tipo_folio,
                    
              
              
       (SELECT CASE WHEN pp.img_transf IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM tbl_pedido pp WHERE pp.id_pedido=p.id_pedido) as estadoFotoTransf,
                    p.direccion_pedido,
                    
                    (SELECT COUNT(rc.n_pedido) as id FROM tbl_recibo_deta rc WHERE rc.n_pedido=p.id_pedido) as id_recibo_bol,
                    
                    p.cobrado_vend,
                    
                    (SELECT count(py.id) FROM tbl_credito py where py.id_pedido = p.id_pedido) as id_count_credito


            from tbl_pedido p 
            inner join tbl_cliente c 
            on c.id_cliente = p.id_cliente
             inner join usuario u 
            on u.id = p.id_usuario
             inner join tipo_usuario t 
            on t.id_tipo = u.id_tipo
			left outer join tbl_nubox_facturas f 
            on f.id_pedido  = p.id_pedido
            inner join  tbl_estado_cobro e 
            on e.id_cobro  = p.estado_cobranza
            left outer join tbl_estado_pedido pe
            on pe.id_estado = p.estado_pedido
           /*  LEFT OUTER JOIN  tbl_informe_transp_deta d
            ON d.id_pedido = p.id_pedido
            LEFT OUTER JOIN tbl_informe_transp_cabe cc 
            ON cc.id = d.id_informe*/
            
           /* left outer join tbl_informe_transp_deta id 
            ON id.id_pedido = p.id_pedido   */  
            
           /* left outer join tbl_recibo_deta rc 
            ON rc.n_pedido = p.id_pedido*/
            
            LEFT OUTER JOIN tbl_direcciones_clientes direc 
            ON direc.id = p.id_direccion
            
            
			
                "
            
            
            .
            $tipBus
            .
        
           
			"            
            
             
            
            "
            .
            $tipBus2
            .
            
            
            "
            
            ORDER BY  c.tipo_comprador, p.id_pedido desc ;
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
        $countList = 0;
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
            
                         	$tmp->id_count_credito    	   = $row->id_count_credito;			

         	$tmp->cobrado_vend    	   = $row->cobrado_vend;			
    
			$tmp->id_pedido    	   = $row->id_pedido;			
			$tmp->fecha            = $row->fecha;
			$tmp->rut  	           = $row->rut;
			$tmp->nombre           = $row->nombre;
			$tmp->telefono         = $row->telefono;
			$tmp->usuario          = $row->usuario;
			$tmp->nombre_tipo      = $row->nombre_tipo;			
			$tmp->anulada          = $row->anulada;			
            $tmp->giro             = $row->giro;			
            $tmp->id_cliente       = $row->id_cliente;	
            $tmp->folio            = $row->folio;
            $tmp->comuna           = $row->comuna;
            $tmp->nombre_cobro     = $row->nombre_cobro;
            $tmp->estado_cobranza  = $row->estado_cobranza;
            $tmp->cod_documento    = $row->cod_documento;
            $tmp->tipo_comprador   = $row->tipo_comprador;
            $tmp->observacion      = $row->observacion;
            $tmp->id_estado        = $row->id_estado;
            $tmp->nombre_estado    = $row->nombre_estado;
            $tmp->estado_pedido    = $row->estado_pedido;
            $tmp->tienda           = $row->tienda;
            $tmp->id_recibo_bol    = $row->id_recibo_bol;
          
            $countList = $countList  + 1 ;
            
            $tmp->cantidadListar   = $countList ;
            

			
            if($row->fecha_cobro=='0000-00-00'){
                  $tmp->fecha_cobro      = '';
            }else{
                  $tmp->fecha_cobro      = $row->fecha_cobro;   
            }
            
            if($row->hora=='00:00:00'){
                  $tmp->hora      = '';
            }else{
                   $tmp->hora           = $row->hora;
            }
            
            
           if($row->hora_transporte=='00:00:00'){
                  $tmp->hora_transporte      = '';
            }else{
                   $tmp->hora_transporte           = $row->hora_transporte;
            }
           
            
            if($row->fecha_transporte=='00-00-0000'){
                  $tmp->fecha_transporte      = '';
            }else{
                   $tmp->fecha_transporte    = $row->fecha_transporte;
            }
            
            
            if($row->fecha_entrega=='00-00-0000'){
                  $tmp->fecha_entrega      = '';
            }else{
                   $tmp->fecha_entrega    = $row->fecha_entrega;
            }
            
            $tmp->obser_vend           = $row->obser_vend;
            $tmp->obser_transp         = $row->obser_transp;
            $tmp->cobrado_vend         = $row->cobrado_vend;
            $tmp->totalPedido          = $row->totalPedido;
            $tmp->jornada              = $row->jornada;
            $tmp->nombreUsuario        = $row->nombreUsuario;
            $tmp->estado_cobro_dep     = $row->estado_cobro_dep;
            $tmp->orden_ruta           = $row->orden_ruta;
            $tmp->pesos                = $row->pesos;
            $tmp->credito              = $row->credito;			
            $tmp->entregaPed           = $row->entregaPed;			
            $tmp->id_transp            = $row->id_transp;
            $tmp->id_sector            = $row->id_sector;
            $tmp->id_tipo_ped          = $row->id_tipo_ped;
            $tmp->estadoFotoTransf     = $row->estadoFotoTransf;
      
            
            
 /*
            $tmp->est_bodega_ped            = $row->est_bodega_ped;
            $tmp->est_transp_ped            = $row->est_transp_ped;

            */
           
            $tmp->isRowSelected        = "false";            
            if($row->credito=='0' || $row->credito=='1' ){
                  $tmp->formaPago      = 'Contado';
                  $tmp->credito         = '0';    
            }else{
                  $tmp->formaPago    = 'Credito';
            }
            
           $tmp->id_informe        = $row->id_informe;
           $tmp->correo            = $row->correo;
           $tmp->countLog          = $row->countLog;
           $tmp->identificacion          = $row->identificacion;            
            $tmp->tipo          = "";

            if($row->tipo_folio =="39" ){
              $tmp->tipo_folio          = "Bol";
            
            }else if($row->tipo_folio =="33" ){
                
                $tmp->tipo_folio          = "Fac";
           }
            
                 $tmp->direccion_pedido = $row->direccion_pedido;            
                 $tmp->direccion        = $row->direccion;			


          if($row->direccion_pedido==""){
               $tmp->direccion        = $row->direccion.", ".$row->deta_direccion;		
              
          }else{
               $tmp->direccion       = $row->direccion_pedido;            

          }
            
            
            
            
            $query2 = "
			SELECT 
            d.id_pedido, 
            p.id as id_prod, 
            p.codProducto, 
            p.nombreProd, 
            p.precioVenta, 
            d.cantidad, 
            d.precio_vendido,
            d.descuento,
            d.aumento,
            d.total,
            p.prod_venta_act
            FROM tbl_deta_pedido d 
            INNER JOIN productos p 
            ON p.id = d.id_prod
            WHERE d.id_pedido='".$row->id_pedido."'";
		$result2 = mysqli_query($mysql, $query2);
		$total2 = mysqli_num_rows($result2);
    
		$retDeta = array();
		while ($row = mysqli_fetch_object($result2)) {
			$tmp2 =new PedidoDetalle();
			$tmp2->id_pedido    	 = $row->id_pedido;			
			$tmp2->id_prod        = $row->id_prod;
			$tmp2->codProducto  	 = $row->codProducto;
			$tmp2->nombreProd     = $row->nombreProd;
			$tmp2->precioVenta    = $row->precioVenta;
			$tmp2->cantidad       = $row->cantidad;
			$tmp2->precio_vendido = $row->precio_vendido;
            $tmp2->descuento      = $row->descuento;
            $tmp2->aumento        = $row->aumento;
			$tmp2->total          = $row->total;	
			$tmp2->prod_venta_act = $row->prod_venta_act;	
            
			$retDeta[] = $tmp2;
		}
            
            $tmp->detalle            = $retDeta;
 

            

            $ret[] = $tmp;
		}
//		mysqli_free_result($result);
    	//	mysqli_free_result($result2);

		      return ($ret);
    
      mysqli_close($mysql);
}        
    
public function listarDetallePedido($pedido) {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT 
            d.id_pedido, 
            p.id as id_prod, 
            p.codProducto, 
            p.nombreProd, 
            p.precioVenta, 
            d.cantidad, 
            d.precio_vendido,
            d.descuento,
            d.aumento,
            d.total,
            p.prod_venta_act,
            p.bodega,
            f.folio
            FROM tbl_deta_pedido d 
            INNER JOIN productos p 
            ON p.id = d.id_prod
            left outer join tbl_nubox_facturas f 
            on f.id_pedido  = d.id_pedido
            WHERE d.id_pedido='".$pedido."'
            ORDER BY p.nombreProd, p.clasificacion_prod";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_pedido    	 = $row->id_pedido;			
			$tmp->id_prod        = $row->id_prod;
			$tmp->codProducto  	 = $row->codProducto;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = $row->precioVenta;
			$tmp->cantidad       = $row->cantidad;
			$tmp->precio_vendido = $row->precio_vendido;
            $tmp->descuento      = $row->descuento;
            $tmp->aumento        = $row->aumento;
			$tmp->total          = $row->total;	
			$tmp->prod_venta_act = $row->prod_venta_act;	
      		$tmp->bodega         = $row->bodega;	
      		$tmp->folio         = $row->folio;	

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}
    
    
public function listarDetallePedidoProd($pedido, $idProd) {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT 
            d.id_pedido, 
            p.id as id_prod, 
            p.codProducto, 
            p.nombreProd, 
            p.precioVenta, 
            d.cantidad, 
            d.precio_vendido,
            d.descuento,
            d.aumento,
            d.total,
            p.prod_venta_act,
            p.bodega,
            f.folio
            FROM tbl_deta_pedido d 
            INNER JOIN productos p 
            ON p.id = d.id_prod
            left outer join tbl_nubox_facturas f 
            on f.id_pedido  = d.id_pedido
            WHERE d.id_pedido='".$pedido."' 
            and d.id_prod  ='".$idProd."' ;
              
              ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_pedido    	 = $row->id_pedido;			
			$tmp->id_prod        = $row->id_prod;
			$tmp->codProducto  	 = $row->codProducto;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = $row->precioVenta;
			$tmp->cantidad       = $row->cantidad;
			$tmp->precio_vendido = $row->precio_vendido;
            $tmp->descuento      = $row->descuento;
            $tmp->aumento        = $row->aumento;
			$tmp->total          = $row->total;	
			$tmp->prod_venta_act = $row->prod_venta_act;	
      		$tmp->bodega         = $row->bodega;	
      		$tmp->folio         = $row->folio;	

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
    

public function anularPedido($pedido, $oberv, $folio, $idUsuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_pedido p
                  SET p.anulada='S',
                  p.observacion = '".$observ."'
                  WHERE p.id_pedido='".$pedido."';
        ";		
		$result = mysqli_query($mysql, $query);		
	
	 if($result == "1"){
         
           $query =  "DELETE FROM tbl_nubox_facturas  WHERE id_pedido='".$pedido."';";
		   $result = mysqli_query($mysql, $query);		
         
                     $query = "INSERT INTO tbl_credito(id_pedido, 
                                                  id_folio,
                                                  f_anulacion,
                                                  observacion,
                                                  id_usuario) 
                         VALUES ('".$pedido."' ,
                                 '".$folio."',
                                 CURDATE(),
                                 '".$oberv."',
                                 '".$idUsuario."');"; 

               $resultDeta = mysqli_query($mysql, $query);	
         
                eliminarPuntos($pedido);
         
                    return 1;
                }else{
                    return 0;
      }
    mysqli_free_result($result);
    
   mysqli_close($mysql);    
}    
    
    
    
 public function eliminarFolioPedido($pedido, $idFolio, $observacion, $id_usuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		     
        $query =  "DELETE FROM tbl_nubox_facturas  WHERE id_pedido='".$pedido."';";
		$result = mysqli_query($mysql, $query);		
	
    
    if($result == "1"){
        
        
        
                $query = "INSERT INTO tbl_credito(id_pedido, 
                                                  id_folio,
                                                  f_anulacion,
                                                  observacion,
                                                  id_usuario) 
                         VALUES ('".$pedido."' ,
                                 '".$idFolio."',
                                 CURDATE(),
                                 '".$observacion."',
                                 '".$id_usuario."');"; 

               $resultDeta = mysqli_query($mysql, $query);	
        
        
        
                    return 1;
                }else{
                    return 0;
    }
    mysqli_free_result($result);
    
   mysqli_close($mysql);
}    
       
    
    
public function anularPedidoListado($pedido, $observ) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_pedido p
                  SET p.anulada='S',
                  p.observacion = '".$observ."'
                  WHERE p.id_pedido='".$pedido."';
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
    
    
    
public function agregarProdListado($detalleProd, $accionProd){
       $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
        $bb =""; 
    
        for ($i = 0; $i < count($detalleProd); $i++) {
                  $query2 = "(SELECT SUM(d.cantidad) as stock
                                FROM tbl_pedido pe
                                INNER JOIN tbl_deta_pedido d 
                                ON d.id_pedido = pe.id_pedido
                                WHERE d.id_prod='".$detalleProd[$i]->id."'
                                AND pe.id_pedido='".$detalleProd[$i]->id_ped."'
                                AND pe.anulada='N')";
            
                  $result2 = mysqli_query($mysql, $query2);
                //  $fila2 = mysqli_num_rows($result2); //cantidadModificada
             $fila2 = mysqli_fetch_row($result2);
            
                    $query = "SELECT 
                            p.id		 as id,
                            p.nombreProd as nombreProd,

                            SUM(IFNULL((SELECT SUM(d.cantidad)
                                FROM tbl_ingresos pe
                                INNER JOIN tbl_ingresos_deta d 
                                ON d.id_ingresos = pe.id
                             WHERE d.id_prod=p.id
							 and pe.activo=1),0)              
                             -
                            IFNULL((SELECT SUM(d.cantidad)
                                FROM tbl_pedido pe
                                INNER JOIN tbl_deta_pedido d 
                                ON d.id_pedido = pe.id_pedido
                             WHERE d.id_prod=p.id
                             AND pe.anulada='N'),0)) as stock             
                            FROM productos p
                            where p.id='".$detalleProd[$i]->id."'";
                        $result = mysqli_query($mysql, $query);

                      //  $fila = mysqli_num_rows($result); //79
                       
                          $fila = mysqli_fetch_row($result);
            
                                     //    77                   12
               $cantResult = $detalleProd[$i]->cantidadProd - $fila2[0] ;
            
        /*    echo "resultaIng1  ->(" . $fila2[0] . ")";
            echo "resultaIng2  ->(" . $fila[2] . ")";            
            echo "resultaPRod  ->(" . $cantResult . ")";
          */  
               if($cantResult > 0){
                      //77         > 79
                   if( $cantResult >  $fila[2]){
                        $bb .= " <br/>- ". $fila[1]; 
                   }
                   
               }
                           
        
           /*
            if ($detalleProd[$i]->cantidadProd > $fila[2]) {
                $bb .= " <br/>- ". $fila[1]; 
             }            */
           	mysqli_free_result($result);
            mysqli_free_result($result2);
        }
    
    
        if($bb == ""){
    
                 
            foreach($detalleProd as $detalle){
                    
                           

                
                     if($accionProd=="M"){
                         
                         
                      $query="UPDATE tbl_deta_pedido p 
                                SET p.cantidad='".$detalle->cantidadProd."',
                                    p.precio_vendido =  '".$detalle->precioVenta."',
                                    p.descuento      = '".$detalle->descuento."',
                                    p.aumento        = '".$detalle->aumento."',
                                    p.total        = '".$detalle->totalProd."'
                                WHERE p.id_prod='".$detalle->id."'
                                and p.id_pedido='".$detalleProd[0]->id_ped."';";
                         
                         
                     }else{

                     $query = "INSERT INTO 
                              tbl_deta_pedido(id_pedido, 
                                               id_prod, 
                                               cantidad, 
                                               precio_vendido, 
                                               descuento, 
                                               aumento,  
                                               total) 
                             VALUES('".$detalleProd[0]->id_ped."', 
                                    '".$detalle->id."', 
                                    '".$detalle->cantidadProd."', 
                                    '".$detalle->precio_vendido."',
                                    '".$detalle->descuento."', 
                                    '".$detalle->aumento."', 
                                    '".$detalle->totalProd."'									
                                    )";  
                     
                     
                     }
                     
                     

                    $resultDeta = mysqli_query($mysql, $query);	
                }
            
             if($resultDeta == "1"){
                    return 1;
                }else{
                    return 0;
             }
    
        }else{
            
            return $bb;
            
        }    
    	mysqli_free_result($resultDeta);
    	mysqli_free_result($result);
    	mysqli_free_result($result2);
    
         mysqli_close($mysql);

    
}
    
public function eliminarProdList($pedido, $idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "delete from tbl_deta_pedido  where id_pedido='".$pedido."' and id_prod='".$idProd."'";		
		$result = mysqli_query($mysql, $query);		
	
    
    if($result == "1"){
                    return 1;
                }else{
                    return 0;
    }
    mysqli_free_result($result);
    
   mysqli_close($mysql);
} 
    
    
 
    
    
public function modificarPedido($cliente) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "UPDATE tbl_cliente clie
                    SET clie.rut          = '".$cliente[0]->rut."' , 
                        clie.nombre       = '".$cliente[0]->nombre."', 
                        clie.direccion    = '".$cliente[0]->direccion."', 
                        clie.giro         = '".$cliente[0]->giro."'
                    WHERE clie.id_cliente = '".$cliente[0]->idCliente."';
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

public function joinPedidos($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT  p.id_prod, 
                pr.codProducto, 
                pr.nombreProd, 
                pr.precioVenta, 
                m.nombreMarca ,
			    c.nombreCategoria as tipo,
                SUM(p.cantidad) as cantidad,
                pr.bodega,
                cc.nombre                
        FROM tbl_deta_pedido p 
        LEFT OUTER  JOIN tbl_pedido ff
        ON ff.id_pedido = p.id_pedido
        inner join productos pr 
        on pr.id = p.id_prod 
        INNER JOIN marca m
		ON pr.marcaProd = m.codMarca
        INNER JOIN categoria c
		ON pr.categoriaProd = c.codCategoria
              LEFT OUTER JOIN tbl_clasificacion_productos cc 
        ON cc.id = pr.clasificacion_prod
        WHERE p.id_pedido in (".$pedido.") 
        AND ff.anulada = 'N'
        GROUP BY p.id_prod, 
		pr.codProducto, 
		pr.nombreProd, 
		pr.precioVenta,
        m.nombreMarca ,
        c.nombreCategoria,
        pr.bodega
        ORDER BY pr.bodega,  cc.nombre, pr.nombreProd  ASC;
            
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
			$tmp->id_prod        = $row->id_prod;
			$tmp->codProducto  	 = $row->codProducto;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = $row->precioVenta;
			$tmp->cantidad       = $row->cantidad;
			$tmp->nombreMarca    = $row->nombreMarca;
			$tmp->tipo           = $row->tipo;
			$tmp->bodega         = $row->bodega;
         	$tmp->nombre         = $row->nombre;   
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}
    
    
    
    
    
public function joinPedidosTiendas($idUsuario, $fInicio, $fFin) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT  p.id_prod, 
                pr.codProducto, 
                pr.nombreProd, 
                pr.precioVenta, 
                m.nombreMarca ,
			    c.nombreCategoria as tipo,
                SUM(p.cantidad) as cantidad,
                pr.bodega,
                cc.nombre                
        FROM tbl_deta_pedido p 
        LEFT OUTER  JOIN tbl_pedido ff
        ON ff.id_pedido = p.id_pedido
        inner join productos pr 
        on pr.id = p.id_prod 
        INNER JOIN marca m
		ON pr.marcaProd = m.codMarca
        INNER JOIN categoria c
		ON pr.categoriaProd = c.codCategoria
        LEFT OUTER JOIN tbl_clasificacion_productos cc 
        ON cc.id = pr.clasificacion_prod
        WHERE ff.fecha_entrega BETWEEN ('".$fInicio."')  and ('".$fFin."') 
        AND ff.anulada = 'N'
        AND ff.id_usuario = (".$idUsuario.") 
        GROUP BY p.id_prod, 
		pr.codProducto, 
		pr.nombreProd, 
		pr.precioVenta,
        m.nombreMarca ,
        c.nombreCategoria,
        pr.bodega
        ORDER BY   pr.nombreProd  ASC;";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
			$tmp->id_prod        = $row->id_prod;
			$tmp->codProducto  	 = $row->codProducto;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = $row->precioVenta;
			$tmp->cantidad       = $row->cantidad;
			$tmp->nombreMarca    = $row->nombreMarca;
			$tmp->tipo           = $row->tipo;
			$tmp->bodega         = $row->bodega;
         	$tmp->nombre         = $row->nombre;   
            
            
            
            $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id_prod.".png?".$ramdom; 
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}    
    
    
    
    
    
    
    

public function listarCreditosPendienteFactura() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
       
      SELECT 
            c.id,
            c.id_pedido,
            c.id_folio,
            c.folio_nota,
            c.f_anulacion,
            p.estado_pedido,
            u.nombre as nombre_usuario,
            u.apellido as apellido_usuario,
            cl.rut,
            cl.nombre,
            cl.direccion,
            cl.giro,
            p.anulada,
            cl.tipo_comprador,
            es.nombre as nombre_estado,
            c.observacion,
            c.observacion_credito

            FROM tbl_credito c 
            INNER JOIN tbl_pedido p 
            ON p.id_pedido = c.id_pedido
            INNER JOIN usuario u 
            ON u.id = c.id_usuario
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            INNER JOIN tbl_estado_pedido es 
            ON es.id_estado = p.estado_pedido
            ORDER BY c.id DESC
        
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
			$tmp->id                 = $row->id;
			$tmp->id_pedido  	     = $row->id_pedido;
			$tmp->id_folio           = $row->id_folio;
            $tmp->folio_nota         = $row->folio_nota;
			$tmp->f_anulacion        = $row->f_anulacion;
			$tmp->estado_pedido      = $row->estado_pedido;
			$tmp->nombre_usuario     = $row->nombre_usuario;
			$tmp->apellido_usuario   = $row->apellido_usuario;
            $tmp->rut                = $row->rut;
			$tmp->nombre             = $row->nombre;
			$tmp->direccion          = $row->direccion;
            $tmp->giro                = $row->giro;
          	$tmp->anulada            = $row->anulada;
          	$tmp->tipo_comprador     = $row->tipo_comprador;
          	$tmp->nombre_estado      = $row->nombre_estado;
          	$tmp->observacion        = $row->observacion;
          	$tmp->observacion_credito        = $row->observacion_credito;

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}
    
    
    
public function listarCreditosPorPedido($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
       
      SELECT 
            c.id,
            c.id_pedido,
            c.id_folio,
            p.estado_pedido,
            u.nombre as nombre_usuario,
            u.apellido as apellido_usuario,
            cl.nombre,
            cl.direccion,
            p.anulada,
            cl.tipo_comprador,
            es.nombre as nombre_estado,
            c.observacion,
            c.observacion_credito

            FROM tbl_credito c 
            INNER JOIN tbl_pedido p 
            ON p.id_pedido = c.id_pedido
            INNER JOIN usuario u 
            ON u.id = c.id_usuario
            INNER JOIN tbl_cliente cl 
            ON cl.id_cliente = p.id_cliente
            INNER JOIN tbl_estado_pedido es 
            ON es.id_estado = p.estado_pedido
            WHERE 
            c.id_pedido = (".$pedido.")  
            ORDER BY c.id DESC
        
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
			$tmp->id                 = $row->id;
			$tmp->id_pedido  	     = $row->id_pedido;
			$tmp->id_folio           = $row->id_folio;
			$tmp->estado_pedido      = $row->estado_pedido;
			$tmp->nombre_usuario     = $row->nombre_usuario;
			$tmp->apellido_usuario   = $row->apellido_usuario;
			$tmp->nombre             = $row->nombre;
			$tmp->direccion          = $row->direccion;
          	$tmp->anulada            = $row->anulada;
          	$tmp->tipo_comprador     = $row->tipo_comprador;
          	$tmp->nombre_estado      = $row->nombre_estado;
          	$tmp->observacion        = $row->observacion;
          	$tmp->observacion_credito        = $row->observacion_credito;

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}        
    
    
    
public function joinPedidosProductoSalidas($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
       SELECT  p.id_prod, 
                pr.codProducto, 
                pr.nombreProd, 
                pr.precioVenta, 
                m.nombreMarca ,
			    c.nombreCategoria as tipo,
                SUM(p.cantidad) as cantidad,
                pr.bodega,
                cc.nombre,
                

             
             
                ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE
                 p.id = pr.id
                AND pc.estado_pedido = 2
                AND pc.anulada='N') as cantProdConObs,
             
             
             ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE p.id = pr.id
                AND pc.estado_pedido = 3
                AND pc.anulada='N') as cantProdPendiente,
                
                
                ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE p.id = pr.id
                AND pc.estado_pedido = 4
                AND pc.anulada='N') as cantProdEnDesp,
                
                
             
                
                  (SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE p.id = pr.id
                AND pc.estado_pedido = 5
                AND pc.anulada='N') as cantProdEnBodega,                
                
                
                 ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE  p.id = pr.id
                AND pc.estado_pedido = 6
                AND pc.anulada='N') as cantProdRechazado,
                
            	(IFNULL((SELECT SUM(d.cantidad)
							FROM tbl_ingresos pe
							INNER JOIN tbl_ingresos_deta d 
							ON d.id_ingresos = pe.id
					  WHERE d.id_prod=pr.id
					  and pe.activo=1),0) 
						-
					  IFNULL(  (SELECT SUM(d.cantidad)
							FROM tbl_pedido pe
							INNER JOIN tbl_deta_pedido d 
							ON d.id_pedido = pe.id_pedido
						 WHERE d.id_prod=pr.id
						 AND pe.anulada='N'),0)) as stockProd
                
        FROM tbl_deta_pedido p 
        inner join productos pr 
        on pr.id = p.id_prod 
        INNER JOIN marca m
		ON pr.marcaProd = m.codMarca
        INNER JOIN categoria c
		ON pr.categoriaProd = c.codCategoria
              LEFT OUTER JOIN tbl_clasificacion_productos cc 
        ON cc.id = pr.clasificacion_prod
        where p.id_pedido in (".$pedido.")  
        GROUP BY p.id_prod, 
		pr.codProducto, 
		pr.nombreProd, 
		pr.precioVenta,
        m.nombreMarca ,
        c.nombreCategoria,
        pr.bodega
       ORDER BY pr.bodega,  cc.nombre, pr.nombreProd  ASC
       
       
            
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
			$tmp->id_prod        = $row->id_prod;
			$tmp->codProducto  	 = $row->codProducto;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = $row->precioVenta;
			$tmp->cantidad       = $row->cantidad;
			$tmp->nombreMarca    = $row->nombreMarca;
			$tmp->tipo           = $row->tipo;
			$tmp->bodega         = $row->bodega;
         	$tmp->nombre         = $row->nombre;  
            
           	$tmp->cantProdConObs         = $row->cantProdConObs;
			$tmp->cantProdPendiente      = $row->cantProdPendiente;
			$tmp->cantProdEnDesp         = $row->cantProdEnDesp;
			$tmp->cantProdEnBodega       = $row->cantProdEnBodega;
			$tmp->cantProdRechazado      = $row->cantProdRechazado;
            			$tmp->stockProd      = $row->stockProd;

            
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}    
    
    
    
    
    
    
    
public function listarCobros() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
			SELECT id_cobro, nombre
            FROM tbl_estado_cobro;
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();	
			$tmp->id_cobro        = $row->id_cobro;
			$tmp->nombre          = $row->nombre;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
    
         mysqli_close($mysql);
}    
     
public function listarEstadoPedido(){
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
			SELECT id_estado, 
                   nombre
            FROM tbl_estado_pedido;
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();	
			$tmp->id_estado       = $row->id_estado;
			$tmp->nombre          = $row->nombre;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
        return ($ret);
    
         mysqli_close($mysql);
}    
    
    
    
    
    
    
public function modificarVentaTienda($idPedido, $idPago, $tienda) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $tienda);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
       	$query = "UPDATE tbl_pedido pe
                    SET pe.estado_cobranza = '".$idPago."'
                    WHERE pe.id_pedido     = '".$idPedido."';
                ";
    
		$result = mysqli_query($mysql, $query); /*or die(mysql_error());	*/

	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
         mysqli_free_result($result);
 
         mysqli_close($mysql);
}    
    
    
    
    
    
    

public function modificarPedidoCobro($cliente) {
    $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
       	$query = "UPDATE tbl_pedido pe
                    SET pe.estado_cobranza = '".$cliente[0]->cobros."' ,
                        pe.cod_documento   = '".$cliente[0]->cod_documento."',
                        pe.fecha_cobro     = '".$cliente[0]->fechaCobro."',
                        pe.observacion     = '".$cliente[0]->observacion."'
                    WHERE pe.id_pedido     = '".$cliente[0]->pedido."';
                ";
		$result = mysqli_query($mysql, $query); /*or die(mysql_error());	*/
    
    
       $query2 = "INSERT INTO tbl_log (id_pedido,
                                       fecha_log,
                                      accion, 
                                      id_usuario,
                                      hora) 
                           VALUES ('".$cliente[0]->pedido."',
                                   CURDATE(),
                                   'modificarPedidoCobro',
                                   '".$cliente[0]->idUsuario."',
                                   NOW() + INTERVAL ".$horaDiferencia." HOUR);
                    ";
       $result2 = mysqli_query($mysql, $query2);
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
      mysqli_free_result($result);
         mysqli_free_result($result2);
 
         mysqli_close($mysql);
}   
    
public function modificarPedidoEstado($cliente) {
    $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
       	$query = "UPDATE tbl_pedido pe
                    SET pe.observacion     = '".$cliente[0]->observacion."',
                        pe.estado_pedido   = '".$cliente[0]->estadoPedido."'
                    WHERE pe.id_pedido     = '".$cliente[0]->pedido."';
                ";
		$result = mysqli_query($mysql, $query);		
    
        
    
    
       $query2 = "INSERT INTO tbl_log (id_pedido,
                                       fecha_log,
                                      accion, 
                                      id_usuario,
                                      hora) 
                           VALUES ('".$cliente[0]->pedido."',
                                   CURDATE(),
                                   'modificarPedidoEstado',
                                   '".$cliente[0]->idUsuario."',
                                   NOW() + INTERVAL ".$horaDiferencia." HOUR);
                    ";
       $result2 = mysqli_query($mysql, $query2); /*or die(mysql_error());*/
    
        
	
    if($result == "1"){
        return $result2;
    }else{
        return $result2;
    }
        mysqli_free_result($result);
         mysqli_free_result($result2);
 
         mysqli_close($mysql);
}    
    
    
    
    
    

public function modificarTalonario($talonario) {
    $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    /*
        UPDATE tbl_recibo_cabe c 
                            SET c.modo_pago  =  '".$talonario[0]->estadoPedido."', 
                                c.observacion = '".$talonario[0]->observacion."' 
                        WHERE c.id = '".$talonario[0]->pedido."';*/
    
       	$query = "
         
         
         
         
                UPDATE  tbl_recibo_deta d
                INNER JOIN tbl_recibo_cabe c 
                ON c.id = d.id_recibo_bol 
                INNER JOIN tbl_pedido p 
                ON p.id_pedido = d.n_pedido
                SET c.modo_pago ='".$talonario[0]->estadoPedido."',
                c.observacion ='".$talonario[0]->observacion."',
                p.estado_cobranza='".$talonario[0]->estadoPedido."'
                WHERE d.id_recibo_bol = '".$talonario[0]->pedido."'
         
         
                        
                ";
		$result = mysqli_query($mysql, $query);		
    
        

	
    if($result == "1"){
        return 1;
    }else{
        return 2;
    }
        mysqli_free_result($result);
 
         mysqli_close($mysql);
}    
    
    
    
    
    
    
    
    
    
    
public function traspasoPedidoTienda($arrDeta) {
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
        $resulEstado="1";
	    $id_tienda   = $arrDeta[0]->tienda;
	    $id_pedido   = $arrDeta[0]->id_ped;

         $query = "INSERT INTO tbl_ingreso_tienda_cabe(fecha, 
                                                       id_tienda,
                                                       id_pedido) 
         VALUES (CURDATE() , 
                 '".$id_tienda."',
                 '".$id_pedido."'
                 )";		
         $resultCabe = mysqli_query($mysql, $query);	
         $id_ing = mysqli_insert_id($mysql);

        if($resultCabe == "1"){
            foreach($arrDeta as $detalle){

                $query2 = "INSERT INTO 
                         tbl_ingreso_tienda_deta(id_ing_tienda, 
                                                 id_prod, 
                                                 cantidad) 
                         VALUES(".$id_ing.", 
                                ".$detalle->id_prod.", 
                                ".$detalle->cantidad."
                                )";  

                $resultDeta = mysqli_query($mysql, $query2);	

            if($resultDeta == "0"){
                
                
                return "Error al traspasar el pedido a tienda, favor contactar con el Administrador".mysql_error();
                break;
                 $resulEstado="0";
            }

            }
        }else{
              $resulEstado="0";
        }
return $resulEstado;	
        mysqli_free_result($resultDeta);
         mysqli_free_result($resultCabe);
 
         mysqli_close($mysql);
}
    
    
    
  
    


public function listarVentasGeneradas($desde, $hasta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT  
                p.fecha,
                p.id_pedido,
                u.nombre as nombUsua,
                p.anulada,
                p.estado_cobranza,
				u.apellido as apellUsua,
                (Select SUM( d.cantidad * d.precio_vendido)
                 From tbl_deta_pedido d 
                 Where d.id_pedido = p.id_pedido) as total,
                 
                 (Select SUM( d.cantidad * d.descuento)
                 From tbl_deta_pedido d 
                 Where d.id_pedido = p.id_pedido) as totalDescuento,
                 MAX(p.descuento) as pedido_descuento,
                 p.hora,
                 f.folio
        FROM tbl_pedido p 
        inner join tbl_deta_pedido t 
        on t.id_pedido = p.id_pedido
        left outer join usuario u
        on u.id = p.id_usuario
        left outer join tbl_nubox_facturas f 
            on f.id_pedido  = p.id_pedido
        where p.fecha  BETWEEN '".$desde."'  and '".$hasta."'
        AND u.id_tipo in (4,8)
        GROUP by       p.fecha,
                p.id_pedido,
                u.nombre ,
                p.anulada,
				u.apellido ,
                 p.hora,
                  p.estado_cobranza
                ORDER BY p.id_pedido DESC
        ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->fecha  	     = $row->fecha;
			$tmp->id_pedido      = $row->id_pedido;
			$tmp->nombUsua       = $row->nombUsua;
         
            
			$tmp->apellUsua            = $row->apellUsua;
    		$tmp->anulada              = $row->anulada;
            $tmp->estado_cobranza      = $row->estado_cobranza;

			$tmp->totalDescuento       = round ((($row->totalDescuento) * 0.19)+($row->totalDescuento));	//total con iva

            
			$tmp->total          = round ((($row->total) * 0.19)+($row->total)) - $row->pedido_descuento;	//total con iva
        
            if($row->hora=='00:00:00'){
                  $tmp->hora      = '';
            }else{
                   $tmp->hora           = $row->hora;
            }
                		$tmp->folio              = $row->folio;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);

         mysqli_close($mysql);
}
    
    
    
    
    
    
    
    
    
    
public function listarVentasGeneradasTiendas($desde, $hasta, $tienda, $usuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $tienda);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT  
                p.fecha,
                p.id_pedido,
                u.nombre as nombUsua,
                p.anulada,
                p.estado_cobranza,
				u.apellido as apellUsua,
                (Select SUM( d.cantidad * d.precio_vendido)
                 From tbl_deta_pedido d 
                 Where d.id_pedido = p.id_pedido) as total,
                 
                 (Select SUM( d.cantidad * d.descuento)
                 From tbl_deta_pedido d 
                 Where d.id_pedido = p.id_pedido) as totalDescuento,
                 MAX(p.descuento) as pedido_descuento,
                 p.hora,
                 f.folio,
                 u.id as id_usuario
        FROM tbl_pedido p 
        inner join tbl_deta_pedido t 
        on t.id_pedido = p.id_pedido
        left outer join usuario u
        on u.id = p.id_usuario
        left outer join tbl_nubox_facturas f 
            on f.id_pedido  = p.id_pedido
        where p.fecha  BETWEEN '".$desde."'  and '".$hasta."'
        AND u.id_tipo in (4,8)
        AND u.id = '".$usuario."'
        GROUP by       p.fecha,
                p.id_pedido,
                u.nombre ,
                p.anulada,
				u.apellido ,
                 p.hora,
                  p.estado_cobranza
                ORDER BY p.id_pedido DESC
        ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->fecha  	     = $row->fecha;
			$tmp->id_pedido      = $row->id_pedido;
			$tmp->nombUsua       = $row->nombUsua;
         
            			$tmp->id_usuario       = $row->id_usuario;

			$tmp->apellUsua            = $row->apellUsua;
    		$tmp->anulada              = $row->anulada;
            $tmp->estado_cobranza      = $row->estado_cobranza;

			$tmp->totalDescuento       = round ((($row->totalDescuento) * 0.19)+($row->totalDescuento));	//total con iva

            
			$tmp->total          = round ((($row->total) * 0.19)+($row->total)) - $row->pedido_descuento;	//total con iva
        
            if($row->hora=='00:00:00'){
                  $tmp->hora      = '';
            }else{
                   $tmp->hora           = $row->hora;
            }
                		$tmp->folio              = $row->folio;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);

         mysqli_close($mysql);
}    
    
    
    
    public function listaDetalleTiendas($idVenta, $tienda) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $tienda);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "   SELECT                 
                        deta.cantidad,
                        deta.id_prod,
                        SUM(deta.cantidad * deta.precio_vendido) as total,
                        p.nombreProd,
                        deta.precio_vendido,
                        p.id,
                        pg.nombre,
                        c.fecha,
                        c.descuento as pedido_descuento,
                        ff.folio,
                        c.id_pedido
                FROM tbl_deta_pedido deta 
                LEFT OUTER JOIN productos p 
                ON p.id = deta.id_prod
                LEFT OUTER JOIN tbl_pedido c 
                ON c.id_pedido = deta.id_pedido
                LEFT OUTER JOIN tbl_estado_cobro pg
                on pg.id_cobro = c.estado_cobranza
                LEFT OUTER JOIN tbl_nubox_facturas ff 
                on ff.id_pedido = c.id_pedido
                WHERE deta.id_pedido = '".$idVenta."'
                  GROUP by 
                  deta.cantidad,
                        deta.id_prod,
                        p.nombreProd,
                        deta.precio_vendido                           
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array(
            'detalles' => array(),
            'pedidoDescuento' => 0,
            'idFolio' => null,
            'fechaVenta' => '',
         //   'idPedido' => null,            
            'id_pedido' => null,/*Se cambio idPedido*/

            'formaPago' => ''
        );
        $first = true;
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();

            if ($first) {
                $ret['pedidoDescuento'] = $row->pedido_descuento;
                $ret['idFolio'] = $row->folio;
                $ret['fechaVenta'] = $row->fecha;
                $ret['id_pedido'] = $row->id_pedido;
                $ret['formaPago'] = $row->nombre;
                $first = false;
            }
            
			$tmp->cantidad       = $row->cantidad;
			$tmp->id_prod  	     = $row->id_prod;
			$tmp->id  	         = $row->id;
            
            
            
		    $tmp->total          = round ((($row->total) * 0.19)+($row->total));	

			$tmp->nombreProd     = $row->nombreProd;
            $tmp->precio_vendido     = round ((($row->precio_vendido) * 0.19)+($row->precio_vendido));
			
            
                       $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
           $tmp->timbElec = "https://aranibar.cl/barrosaranas/Table/imagenFolio/".$row->id_pedido.".png?".$ramdom;
            
            $ret['detalles'][] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
} 
    
    
    
public function listaDetalleVentas($idVenta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "   SELECT                 
                        deta.cantidad,
                        deta.id_prod,
                        SUM(deta.cantidad * deta.precio_vendido) as total,
                        p.nombreProd,
                        deta.precio_vendido,
                        p.id,
                        pg.nombre,
                        c.fecha,
                        c.descuento as pedido_descuento,
                        ff.folio,
                        c.id_pedido
                FROM tbl_deta_pedido deta 
                LEFT OUTER JOIN productos p 
                ON p.id = deta.id_prod
                LEFT OUTER JOIN tbl_pedido c 
                ON c.id_pedido = deta.id_pedido
                LEFT OUTER JOIN tbl_estado_cobro pg
                on pg.id_cobro = c.estado_cobranza
                LEFT OUTER JOIN tbl_nubox_facturas ff 
                on ff.id_pedido = c.id_pedido
                WHERE deta.id_pedido = '".$idVenta."'
                  GROUP by 
                  deta.cantidad,
                        deta.id_prod,
                        p.nombreProd,
                        deta.precio_vendido                           
            ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array(
            'detalles' => array(),
            'pedidoDescuento' => 0,
            'idFolio' => null,
            'fechaVenta' => '',
         //   'idPedido' => null,            
            'id_pedido' => null,/*Se cambio idPedido*/

            'formaPago' => ''
        );
        $first = true;
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();

            if ($first) {
                $ret['pedidoDescuento'] = $row->pedido_descuento;
                $ret['idFolio'] = $row->folio;
                $ret['fechaVenta'] = $row->fecha;
                $ret['id_pedido'] = $row->id_pedido;
                $ret['formaPago'] = $row->nombre;
                $first = false;
            }
            
			$tmp->cantidad       = $row->cantidad;
			$tmp->id_prod  	     = $row->id_prod;
			$tmp->id  	         = $row->id;
            
            
            
		    $tmp->total          = round ((($row->total) * 0.19)+($row->total));	

			$tmp->nombreProd     = $row->nombreProd;
            $tmp->precio_vendido     = round ((($row->precio_vendido) * 0.19)+($row->precio_vendido));
			
            
                       $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
           $tmp->timbElec = "https://aranibar.cl/barrosaranas/Table/imagenFolio/".$row->id_pedido.".png?".$ramdom;
            
            $ret['detalles'][] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
} 
 
public function modificarPedidoTransp($arrEstado) {
        $horaDiferencia = 1;
    
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");

       	$query = "UPDATE tbl_pedido pe
                    SET pe.obser_transp      = '".$arrEstado[0]->observacion."',
                        pe.estado_pedido    = '".$arrEstado[0]->estadoPedido."',
                        pe.hora_transporte  = NOW() + INTERVAL ".$horaDiferencia." HOUR,
                        pe.fecha_transporte = CURDATE(),
                        pe.fecha_cobro      = DATE_ADD(CURDATE(), INTERVAL ".$arrEstado[0]->creditoClie." DAY)
                    WHERE pe.id_pedido     = '".$arrEstado[0]->pedido."';
                ";
	$result = mysqli_query($mysql, $query);		
    
    
    
    
        $query2 = "INSERT INTO tbl_log (id_pedido, 
                                        fecha_log,
                                           accion, 
                                       id_usuario,
                                       hora) 
                           VALUES ('".$arrEstado[0]->pedido."',
                                   CURDATE(),
                                   'modificarPedidoEstadoTransporte',
                                   '".$arrEstado[0]->idUsuario."',
                                   NOW() + INTERVAL ".$horaDiferencia." HOUR);
       
                ";
        $result2 = mysqli_query($mysql, $query2);
    
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
          mysqli_free_result($result2);
          mysqli_free_result($result);

         mysqli_close($mysql);
}        

public function sumaTotalVentas($fecha){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "        
            SELECT SUM(deta.cantidad * deta.precio_vendido) as total
            FROM tbl_venta_tienda_cabe cabe 
            INNER JOIN tbl_venta_tienda_deta deta 
            ON deta.id_tienda = cabe.id 
            WHERE cabe.fecha BETWEEN '2017-01-24' and '2017-01-24'
            AND cabe.anulada <> 'S';        
        ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->total          = $row->total;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);

         mysqli_close($mysql);
}

public function insertarTotalVentas($fecha, $tienda, $usuario, $valor) {    
        $horaDiferencia = 1;     
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
		$existeCierre = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM tbl_caja WHERE fecha='".$fecha."'"));

        if($existeCierre == 0){     
                $query1 = "INSERT INTO tbl_caja(fecha, 
                                                hora,
                                                id_tienda,
                                                id_usuario,
                                                valor) 
                 VALUES (CURDATE(), 
                         NOW() + INTERVAL ".$horaDiferencia." HOUR,
                         '".$id_usua."',
                         'N');";
               $resultCabeSal = mysqli_query($mysql, $query1);	
               $id_ped = mysqli_insert_id($mysql);            
            return 1;
        }else{
            return 0;            
        }           
      mysqli_free_result($resultCabeSal);
      mysqli_close($mysql);
}

public function modObsDesp($obs, $fecha,  $id, $jornada, $tipoEntrega) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  

		$query = "UPDATE tbl_pedido p
                    SET p.fecha_entrega   = '".$fecha."' , 
                        p.observacion     = '".$obs."' , 
						p.jornada		  = ".$jornada.",
                        p.entregaPed		  = ".$tipoEntrega."

                    WHERE p.id_pedido = ".$id.";
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
    
public function cobradoVendedor($cobrado,  $id) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  

		$query = "UPDATE tbl_pedido p
                    SET p.cobrado_vend    = ".$cobrado."
                    WHERE p.id_pedido = ".$id.";
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

public function tiendas(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT    d.id_tienda, 
                       d.nombre, 
                       d.direccion 
                FROM tbl_tiendas d;";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_tienda          = $row->id_tienda;
			$tmp->nombre             = $row->nombre;			            
            $tmp->direccion          = $row->direccion;
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);  

         mysqli_close($mysql);
}

public function updateEstadoDepacho($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query =   "update tbl_pedido p 
                    set p.estado_pedido=5/*,
                    p.est_bodega_ped = 1*/
                    where p.id_pedido in (".$pedido.");
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
    
public function consultarPedido($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
    

    
		$query =   "select p.estado_pedido from tbl_pedido p where p.id_pedido = ".$pedido.";";		
		$result = mysqli_query($mysql, $query);		
	 /*   $fila = mysqli_num_rows($result);
        $estado =  $fila[0];*/
    
    
    $fila = mysqli_fetch_row($result);
    
    
  
   if($fila[0] != "0"){
        return $fila[0];
    }else{
        return 0;
    } 
             mysqli_free_result($result);

         mysqli_close($mysql);
}   

public function joinRuta($pedido, $arrayRuta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
    	$query =   "update tbl_pedido p 
                    set p.estado_pedido=4
                    where p.id_pedido in (".$pedido.");
        ";		
		$result = mysqli_query($mysql, $query);		
	
    

	 foreach($arrayRuta as $detalle){
        $query =   "update tbl_pedido p 
                     set  p.id_transp = ".$detalle->id_transp."
                    where p.id_pedido in (".$detalle->idPedido.");
        ";		

        $resultDeta = mysqli_query($mysql, $query);	

        if($resultDeta == "0"){
            return "Error al generar ruta, favor contactar con el Administrador";
            break;
        }
        
      }
      
    
    /************************************/

       $query = "INSERT INTO tbl_informe_transp_cabe(fecha, 
                                                 id_transp
                                                 ) 
                 VALUES (CURDATE() ,
                        '".$arrayRuta[0]->id_transp."');"; 

        $resultDeta = mysqli_query($mysql, $query);	
            $id_informe = mysqli_insert_id($mysql);

        if($resultDeta == "0"){
          //  return "Error al generar informe de ruta, favor contactar con el Administrador";
            return false;
        }
        
      /**************************************/
	
     foreach($arrayRuta as $detalle){
        $query = "INSERT INTO tbl_informe_transp_deta(id_informe, 
                                                      id_pedido) 
                 VALUES ('".$id_informe."' ,
                         '".$detalle->idPedido."');"; 

        $resultDeta = mysqli_query($mysql, $query);	


        if($resultDeta == "0"){
            return "Error al generar ruta, favor contactar con el Administrador";
            break;
        }
        
      }
      
    
    
    
	
		$query = "Select 
                p.orden_ruta,
                c.tipo_comprador as tipo,
                c.nombre,
                CONCAT(p.observacion, ' ', p.obser_vend) as observacion,
                p.id_pedido,
                '' as folio,
                SUM(d.cantidad * d.precio_vendido) as total,
                p.id_tipo_ped,
                (SELECT SUM(pr.pesosProd * pp.cantidad)
					FROM tbl_deta_pedido pp
					LEFT OUTER JOIN productos pr 
					on pr.id = pp.id_prod
					where pp.id_pedido = p.id_pedido) as pesos,
                    c.credito,
                    id.id_informe,
                    u.nombre as nombreUsuario,
                    c.id_sector,
                    c.telefono,
                    
                direc.direccion as direccion ,
                direc.deta_direccion    
                    
                    
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido p 
                ON p.id_pedido = d.id_pedido
                LEFT OUTER JOIN tbl_cliente c
                ON c.id_cliente = p.id_cliente
                INNER JOIN tbl_informe_transp_deta id 
                ON id.id_pedido = p.id_pedido
                 INNER JOIN usuario u
                 ON u.id = p.id_usuario
                
                LEFT OUTER JOIN tbl_direcciones_clientes direc
                ON direc.id = p.id_direccion
            
                 
                 
                WHERE d.id_pedido in (".$pedido.")
                GROUP by p.orden_ruta,
                c.tipo_comprador,
                c.nombre,
                c.direccion,
                p.observacion,
                p.id_pedido,
                folio
                ORDER BY c.credito,
                          p.id_pedido;
                
              
            ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->orden_ruta     = $row->orden_ruta;
			$tmp->tipo           = $row->tipo;
			$tmp->nombre         = $row->nombre;			
			$tmp->direccion      =  $row->direccion.", ".$row->deta_direccion;	            
			$tmp->observacion    = $row->observacion;			
			$tmp->id_pedido      = $row->id_pedido;			
			$tmp->folio          = $row->folio;			
			$tmp->total          = round ((($row->total) * 0.19)+($row->total));	
  			$tmp->id_tipo_ped    = $row->id_tipo_ped;		
            $tmp->pesos          = round ($row->pesos);
            $tmp->id_sector      = $row->id_sector;	
            $tmp->telefono      = $row->telefono;	

  
            
            
            if($row->credito=='0' || $row->credito=='1' ){
                  $tmp->formaPago      = 'Pago en:';
            }else{
                  $tmp->formaPago      = 'Credito';
            }
            $tmp->id_informe          =  ($row->id_informe);
                        $tmp->nombreUsuario          =  ($row->nombreUsuario);


            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}		

public function transporteEstado(){
	 $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT 
                        MONTH(now()) as mes,        
                        p.id_transp,
                       IFNULL(tt.peso,0) as peso,
                        IFNULL(aa.anulado,0) as anulado,
                       trasp.src_img_transp,
                       COUNT(IF(p.estado_pedido=1,1, NULL)) 'Entregado',
                       COUNT(IF(p.estado_pedido=2,1, NULL)) 'Incompleto',
                       COUNT(IF(p.estado_pedido=3,1, NULL)) 'Pendiente',
                       COUNT(IF(p.estado_pedido=4,1, NULL)) 'Despacho',
                       COUNT(IF(p.estado_pedido=6,1, NULL)) 'Observacion'
                FROM tbl_pedido p 
                 LEFT OUTER JOIN (
                 SELECT 
                        p.id_transp,
                        round(SUM(d.cantidad * pr.pesosProd)) as Peso
                FROM tbl_pedido p
                        INNER JOIN tbl_deta_pedido d 
                        ON d.id_pedido = p.id_pedido
                        LEFT OUTER JOIN productos pr
                        ON pr.id = d.id_prod
                        WHERE p.estado_pedido=1
                        and MONTH(p.fecha_entrega) = MONTH(NOW())
                        and YEAR(p.fecha_entrega)  = YEAR(NOW())
                        and p.anulada='N'
                       AND p.id_cliente   <>'162'
                        GROUP by p.id_transp) tt	
      ON tt.id_transp = p.id_transp               
                        
   LEFT OUTER JOIN (SELECT 
                        p.id_transp,
                        count(p.id_pedido) as anulado
                FROM tbl_pedido p
                        WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
                        and YEAR(p.fecha_entrega)  = YEAR(NOW())
                        and p.anulada='S'
                    AND p.id_cliente   <> '162'
                        GROUP by p.id_transp)    aa
      ON aa.id_transp = p.id_transp  
                        
    LEFT OUTER JOIN tbl_transportes trasp
    ON trasp.id = p.id_transp 
WHERE 
MONTH(p.fecha_entrega) = MONTH(NOW())
AND  YEAR(p.fecha_entrega) = YEAR(NOW())
AND  p.anulada = 'N'
AND p.id_cliente   <>'162'
GROUP by p.id_transp,
         tt.peso,
           aa.anulado,
       trasp.src_img_transp";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
              $tmp->mes              = $row->mes;
              $tmp->id_transp        = $row->id_transp;
              $tmp->peso             = $row->peso;
              $tmp->anulado          = $row->anulado;
			  $tmp->Entregado        = $row->Entregado;
			  $tmp->Incompleto       = $row->Incompleto;
			  $tmp->Pendiente        = $row->Pendiente;
			  $tmp->Despacho         = $row->Despacho;
			  $tmp->Observacion      = $row->Observacion;
			  $tmp->src_img_transp   = $row->src_img_transp;

            switch ($row->mes) {
                case 1:
                     $tmp->mes="ENERO";
                   break;
                case 2:
                     $tmp->mes="FEBRERO";
                    break;
                case 3:
                     $tmp->mes="MARZO";
                    break;
                case 4:
                     $tmp->mes="ABRIL";
                    break;
                case 5:
                     $tmp->mes="MAYO";
                    break;
                case 6:
                     $tmp->mes="JUNIO";
                    break;
                case 7:
                     $tmp->mes="JULIO";
                    break;
                case 8:
                     $tmp->mes="AGOSTO";
                    break;
                case 9:
                     $tmp->mes="SPTIEMBRE";
                    break;
                case 10:
                     $tmp->mes="OCTUBRE";
                    break;
                case 11:
                     $tmp->mes="NOVIEMBRE";
                    break;
                 case 12:
                     $tmp->mes="DICIEMBRE";
                    break;
            }
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);   

         mysqli_close($mysql);
}
    
public function insertarLog($id_pedido, $accion, $idUsuario) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
   
                $query1 = "INSERT INTO tbl_log(id_pedido, 
                                                accion,
                                                id_usuario,
                                               ) 
                 VALUES ('".$id_pedido."',
                        '".$accion."',
                         '".$idUsuario."');";
               $resultCabeSal = mysqli_query($mysql, $query1);	
    
             mysqli_free_result($resultCabeSal);

         mysqli_close($mysql);

    
}
    
/*

public function unirPedidoList($pedPadre, $pedMadre, $idCliente) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
		$existeCierre = mysqli_num_rows(mysqli_query("select * from tbl_pedido p where p.id_pedido=".$pedMadre." and p.estado_pedido=3 and p.anulada='N'"));

        if($existeCierre != 0){     
            	$query = "
                    INSERT INTO tbl_deta_pedido ped (ped.id_pedido, 
                                                     ped.id_prod, 
                                                     ped.cantidad, 
                                                     ped.precio_vendido,
                                                     ped.descuento,
                                                     ped.aumento,
                                                     ped.total,
                                                     ped.id_precio_factura)
                      select   ".$pedPadre."
                               pe.id_prod, 
                               pe.cantidad, 
                               pe.precio_vendido, 
                               pe.descuento, 
                               pe.aumento, 
                               pe.total, 
                               pe.id_precio_factura
                      from tbl_deta_pedido pe 
                      where pe.id_pedido=".$pedMadre.";
            ";
      		$result = mysqli_query($mysql, $query);
            
            
            return 1;
        }else{
            return 0;            
        }                
}  

*/

public function listaInformeTransp($desde, $hasta, $idInf) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
     
      if($idInf != ""){
         $tipBus2 = "and c.id like ('%".$idInf."%') ";
      }
     
		$query = "SELECT    c.id,
                            c.fecha,
                            c.id_transp,
                            p.id as imagen,
                            c.observacion,
                            c.estado,
                            pt.patente,
                            c.inicio_km,
                            c.id_transporte_patente,
                            c.fin_km,
                            c.carga_combustible_km,
                            c.valor_carga,
                            
                            c.valor_total,
                            c.debito_total,
                            c.transf_total,
                            c.cajavecina_total,
                            c.dataMonto
                FROM tbl_informe_transp_cabe c 
                LEFT OUTER JOIN tbl_transportes p 
                ON p.id = c.id_transp
                
                LEFT OUTER JOIN tbl_transportes pt 
                ON pt.id = c.id_transporte_patente
                WHERE  c.fecha  BETWEEN '".$desde."'and '".$hasta."'
                "
            . $tipBus2.
            "";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id             = $row->id;
			$tmp->fecha  	     = $row->fecha;
			$tmp->id_transp      = $row->id_transp;
			$tmp->imagen         = $row->imagen;
  			$tmp->observacion    = $row->observacion;
            $tmp->estado         = $row->estado;
            $tmp->id_transporte_patente         = $row->id_transporte_patente;
            
            
            $tmp->valor_total         = $row->valor_total;
            $tmp->debito_total         = $row->debito_total;
            $tmp->transf_total         = $row->transf_total;
            $tmp->cajavecina_total         = $row->cajavecina_total;
            
                        $tmp->dataMonto         = $row->dataMonto;

              


            $tmp->patente         = $row->patente;
            $tmp->inicio_km         = $row->inicio_km;
            $tmp->fin_km         = $row->fin_km;
            $tmp->carga_combustible_km         = $row->carga_combustible_km;
            $tmp->valor_carga         = $row->valor_carga;
             $ramdom =  mt_rand();
            
             $tmp->foto         = "https://aranibar.cl/barrosaranas/Table/rutas/".$row->id.".png?".$ramdom; 
            
             $tmp->fotoInfTransp         = "https://aranibar.cl/barrosaranas/Table/subirArchivoCamion/".$row->id.".png?".$ramdom; 
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);

         mysqli_close($mysql);
}
 
public function verDetalleInformeTransp($pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
	
		$query = "Select              
                p.orden_ruta,
                c.tipo_comprador as tipo,
                c.nombre,
                /*c.direccion,*/
                
                IFNULL(direc.direccion, c.direccion ) as direccion,
                
                CONCAT(p.observacion, ' ', p.obser_vend) as observacion,
                p.id_pedido,
                SUM(d.cantidad * d.precio_vendido) as total,
                p.id_tipo_ped,
                (SELECT SUM(pr.pesosProd * pp.cantidad)
					FROM tbl_deta_pedido pp
					LEFT OUTER JOIN productos pr 
					on pr.id = pp.id_prod
					where pp.id_pedido = p.id_pedido) as pesos,
                p.estado_cobranza as codCobro,
                p.cod_documento as documento,
                p.obser_transp,
                c.credito,
                p.estado_pedido,
                p.anulada,
                p.id_tipo_ped,
                
                 (SELECT CASE WHEN pp.img_transf IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM tbl_pedido pp WHERE pp.id_pedido=p.id_pedido) as estadoFotoTransf,
                 u.nombre as nombreUsuario,
                 c.id_sector,
                 ff.folio,
                 p.cobrado_vend,
                 c.telefono,
                 direc.deta_direccion,
                 direc.ubicacion_geo
                
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido p 
                ON p.id_pedido = d.id_pedido
                LEFT OUTER JOIN tbl_cliente c
                ON c.id_cliente = p.id_cliente
                INNER JOIN tbl_informe_transp_deta id 
                ON id.id_pedido = p.id_pedido
                INNER JOIN usuario u
                ON u.id =  p.id_usuario
                LEFT OUTER JOIN tbl_nubox_facturas ff 
                ON ff.id_pedido = p.id_pedido
                
                LEFT OUTER JOIN tbl_direcciones_clientes direc 
                ON direc.id = p.id_direccion

 

                
                
                WHERE id.id_informe = (".$pedido.")
                GROUP by p.orden_ruta,
                c.tipo_comprador,
                c.nombre,
                c.direccion,
                p.observacion,
                p.id_pedido,
                c.credito,
                p.estado_pedido,
                p.anulada
                ORDER BY c.credito,
                          p.id_pedido;";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            
            
                			$tmp->ubicacion_geo     = $row->ubicacion_geo;

			$tmp->orden_ruta     = $row->orden_ruta;
			$tmp->tipo           = $row->tipo;
			$tmp->nombre         = $row->nombre;	
            
			$tmp->direccion      = $row->direccion .", ".$row->deta_direccion;			
			
            
            $tmp->observacion    = $row->observacion;			
			$tmp->id_pedido      = $row->id_pedido;	
            			$tmp->telefono      = $row->telefono;	

			$tmp->folio          = $row->folio;			
			$tmp->total          = round ((($row->total) * 0.19)+($row->total));	
  			$tmp->id_tipo_ped    = $row->id_tipo_ped;		
            $tmp->pesos          = round ($row->pesos);
            $tmp->codCobro        = $row->codCobro;		
             $tmp->documento        = $row->documento;
             $tmp->obser_transp        = $row->obser_transp;
                         $tmp->credito        = $row->credito;

            
            if($row->credito=='0' || $row->credito=='1' ){
                  $tmp->formaPago      = 'Pago en:';
            }else{
                  $tmp->formaPago      = 'CREDITO';
            } 
            
                      $tmp->estado_pedido    = $row->estado_pedido;
                      $tmp->anulada          = $row->anulada;
                      $tmp->id_tipo_ped      = $row->id_tipo_ped;
                      $tmp->estadoFotoTransf = $row->estadoFotoTransf;
                      $tmp->nombreUsuario    = $row->nombreUsuario;
                      $tmp->id_sector        = $row->id_sector;
                                  $tmp->cobrado_vend        = $row->cobrado_vend;

                   
            $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
            
            
         

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
             mysqli_close($mysql);

}
  
    
    
public function guardarDetallePago($arrPed, $idInf) {
    
  $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  
    
     $query2 = "UPDATE tbl_informe_transp_cabe p
                    SET p.dataMonto       = '".$arrPed."'
                    WHERE p.id = ".$idInf."";

        $resultDeta2 = mysqli_query($mysql, $query2);	
    
      if($resultDeta2 == "1"){
        return 1;
    }else{
        return 0;
    }
    
}
    
 
    
public function saveInformeTransp($arrPed) {
    $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  


      $query2 = "UPDATE tbl_informe_transp_cabe p
                    SET p.observacion       = '".$arrPed[0]->obsTransp."' ,
                    p.id_transporte_patente	= '".$arrPed[0]->idTransporte."' ,
                    p.inicio_km	= '".$arrPed[0]->inicioKg."' ,
                    p.fin_km	= '".$arrPed[0]->finKg."' ,
                    p.carga_combustible_km	= '".$arrPed[0]->cargaKg."' ,
                    p.valor_carga	= '".$arrPed[0]->valorKm."'
                    WHERE p.id = ".$arrPed[0]->idInforme."";

        $resultDeta2 = mysqli_query($mysql, $query2);	
    
    
    
       foreach($arrPed as $detalle){ 
 
          if($detalle->codCobro != 4 ){
              $query3 = "INSERT INTO tbl_log (id_pedido, 
                                              fecha_log,
                                              accion, 
                                              id_usuario,
                                               hora) 
                                   VALUES ('".$detalle->id_pedido."',
                                           CURDATE(),
                                           'modificarPedidoEstadoMasivoInformeTransp',
                                           '".$detalle->idUsuario."',
                                           NOW() + INTERVAL ".$horaDiferencia." HOUR);

                        ";
                $result2 = mysqli_query($mysql, $query3);
              }
    
        }   
    
    
    
       foreach($arrPed as $detalle){
       $query = "UPDATE tbl_pedido p
                    SET p.cod_documento       = '".$detalle->documento."' , 
                        p.estado_cobranza     = '".$detalle->codCobro."' ,
                         p.obser_transp     = '".$detalle->obsInfor."' ,
                          p.estado_pedido     = '".$detalle->estPedido."' 
                        
                        
                    WHERE p.id_pedido = ".$detalle->id_pedido."";

        $resultDeta = mysqli_query($mysql, $query);	
   

        if($resultDeta == "0"){
            return "Error al generar ruta, favor contactar con el Administrador";
            break;
        }
        
      }
      
    
    
	
    if($resultDeta == "1"){
        return 1;
    }else{
        return 0;
    }
    
    
             mysqli_close($mysql);

}
    
public function eliminarPedidoTrans($idPed) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

     $query = "delete from tbl_informe_transp_deta   where id_pedido='".$idPed."';";		
		$result = mysqli_query($mysql, $query);	
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
             mysqli_close($mysql);

}

public function validarRutaInforme($idInforme) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");

       	$query = "UPDATE tbl_informe_transp_cabe pe
                    SET pe.estado      = '2'                        
                    WHERE pe.id     = '".$idInforme."';";
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
             mysqli_close($mysql);

}      
    
 /*
public function consultarEstadoInforTrans($idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	

       	$query = "
                select d.id_informe 
                    from tbl_informe_transp_cabe c 
                    LEFT OUTER JOIN tbl_informe_transp_deta d
                    ON c.id = d.id_informe
                    WHERE d.id_pedido = '23327'
                    and c.estado <> 1
                 ";
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}*/
    
public function insertarInformTransp($idInforme, $idPedido) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
		$existeCierre = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM tbl_informe_transp_deta WHERE id_pedido='".$idPedido."'"));

        if($existeCierre == 0){     
        
            
            $query  = "INSERT INTO tbl_informe_transp_deta(id_informe, 
                                                               id_pedido) 
                 VALUES ('".$idInforme."',
                         '".$idPedido."');";
$query .= "UPDATE tbl_pedido p  SET p.estado_pedido=4 WHERE p.id_pedido='".$idPedido."';";
            
            
            
               $resultCabeSal = mysqli_multi_query ($mysql, $query);	
                //$id_ped = mysqli_insert_id($mysql);            
            return 1;
        }else{
            return 0;            
        }  
              mysqli_close($mysql);

}    
    
public function listProdListaVentaTotal($idTipList) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT pr.id, 
                   pr.nombreProd,
                   SUM(d.cantidad) AS cantidad
                FROM tbl_pedido p 
                LEFT  OUTER JOIN  tbl_deta_pedido d 
                ON d.id_pedido = p.id_pedido
                LEFT  OUTER JOIN productos pr
                ON pr.id = d.id_prod
                LEFT OUTER JOIN marca m 
                ON m.codMarca = pr.marcaProd
                LEFT OUTER JOIN categoria c 
                ON c.codCategoria = pr.categoriaProd
                LEFT OUTER JOIN tbl_proveedores pv 
                ON pv.id_proveedor = m.cod_proveedor
                LEFT OUTER JOIN tbl_cliente cl 
                ON cl.id_cliente = p.id_cliente
                LEFT OUTER JOIN usuario us 
                ON us.id = p.id_usuario
                WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
                AND  YEAR(p.fecha_entrega) = YEAR(NOW())
                AND p.estado_pedido = 1
                AND p.anulada = 'N'
                and pr.prod_venta_act = '".$idTipList."'
                GROUP BY  pr.nombreProd ASC";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id            = $row->id;
			$tmp->nombreProd  	= $row->nombreProd;
			$tmp->cantidad      = $row->cantidad;            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
             mysqli_close($mysql);

} 
    
public function listProdListaVenta($idVendedor, $idTipList) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT pr.id, 
                   pr.nombreProd,
                   SUM(d.cantidad) AS cantidad
                FROM tbl_pedido p 
                LEFT  OUTER JOIN  tbl_deta_pedido d 
                ON d.id_pedido = p.id_pedido
                LEFT  OUTER JOIN productos pr
                ON pr.id = d.id_prod
                LEFT OUTER JOIN marca m 
                ON m.codMarca = pr.marcaProd
                LEFT OUTER JOIN categoria c 
                ON c.codCategoria = pr.categoriaProd
                LEFT OUTER JOIN tbl_proveedores pv 
                ON pv.id_proveedor = m.cod_proveedor
                LEFT OUTER JOIN tbl_cliente cl 
                ON cl.id_cliente = p.id_cliente
                LEFT OUTER JOIN usuario us 
                ON us.id = p.id_usuario
                WHERE MONTH(p.fecha_entrega) = MONTH(NOW())
                AND  YEAR(p.fecha_entrega) = YEAR(NOW())
                AND p.estado_pedido = 1
                AND p.anulada = 'N'
                and p.id_usuario = '".$idVendedor."'
                and pr.prod_venta_act = '".$idTipList."'
                GROUP BY  pr.nombreProd ASC";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id            = $row->id;
			$tmp->nombreProd  	= $row->nombreProd;
			$tmp->cantidad      = $row->cantidad;            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
             mysqli_close($mysql);

}

public function anularPedidoInformeVenta($idInfo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE tbl_informe_transp_cabe p
                  SET p.estado='1'
                  WHERE p.id='".$idInfo."';";		
		$result = mysqli_query($mysql, $query);		
	

        return 1;
   
             mysqli_close($mysql);

}      
    
    
    
    
    
public function anularVentaTienda($idPedido, $observ, $tienda) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $tienda);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
    
    
    		$query = "UPDATE tbl_pedido p
                  SET p.anulada='S',
                  p.observacion = '".$observ."'
                  WHERE p.id_pedido='".$idPedido."';
        ";	
    
    
    
		$result = mysqli_query($mysql, $query);		
	

	  if($result == "1"){
        return 1;
    }else{
        return 0;
    }
             mysqli_close($mysql);
}       
    
    
/*****************************************************/    
public function modificarEstadoMasivoTransp($arrEstado) {
        $horaDiferencia = 1;
    
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");

    for ($i = 0; $i < count($arrEstado); $i++) {    
       	$query = "UPDATE tbl_pedido pe
                    SET 
                        pe.estado_pedido    = '1',
                        pe.hora_transporte  = NOW() + INTERVAL ".$horaDiferencia." HOUR,
                        pe.fecha_transporte = CURDATE(),
                        pe.fecha_cobro      = DATE_ADD(CURDATE(), INTERVAL ".$arrEstado[$i]->creditoClie." DAY)
                    WHERE pe.id_pedido     = '".$arrEstado[$i]->pedido."';";
	    $result = mysqli_query($mysql, $query);	
    }
    
    for ($i = 0; $i < count($arrEstado); $i++) {    
 
    
     $query2 = "INSERT INTO tbl_log (id_pedido, 
                                           fecha_log,
                                           accion, 
                                       id_usuario,
                                       hora) 
                           VALUES ('".$arrEstado[$i]->pedido."',
                                   CURDATE(),
                                   'modificarPedidoEstadoTransporteMasivo',
                                   '".$arrEstado[$i]->idUsuario."',
                                   NOW() + INTERVAL ".$horaDiferencia." HOUR);";
        $result2 = mysqli_query($mysql, $query2);
    
    }
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
             mysqli_close($mysql);

}

public function listarLogPedido($id_pedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT                                         
                    l.fecha_log,  
                    l.accion, 
                    CONCAT(t.nombre, ' ' , t.apellido) as nombre,
                    l.hora
                    FROM tbl_log l 
                    LEFT OUTER JOIN usuario t 
                    ON t.id = l.id_usuario
                    WHERE  l.id_pedido = '".$id_pedido."'
                    ORDER By l.id ASC;";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->fecha_log     = $row->fecha_log;
			$tmp->accion  	    = $row->accion;
			$tmp->nombre        = $row->nombre;          
        	$tmp->hora        = $row->hora; 
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

}     
    
public function precioUsuaDesc($idUsua) {
	    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
     
      if (mysqli_connect_errno())
      {
             echo "Failed to connect to MySQL: " . mysqli_connect_error();
           return 0; //No puede modificar precio
      }

      	$existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM usuario WHERE id='".$idUsua."' and modPrecDesc=1; "));
      // echo "Failed to connect to MySQL: " . $existe;
     
			 if($existe == 0){	
			    return 0; //No puede modificar precio
			 }else{
			    return 1; //Puede modificar precio
			 }	 
           mysqli_close($mysql);
}
    
    

public function verDetalleObsPed($estado, $idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
	
		$query = "Select               
                c.tipo_comprador as tipo,
                c.nombre,
                c.direccion,
                CONCAT(p.observacion, ' ', p.obser_vend) as observacion,
                p.id_pedido,               
                p.id_tipo_ped,
    
                p.estado_cobranza as codCobro,
                p.cod_documento as documento,
                p.obser_transp,
                c.credito,
                p.estado_pedido,
                p.anulada,
                p.id_tipo_ped,
                 (SELECT CASE WHEN pp.img_transf IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM tbl_pedido pp WHERE pp.id_pedido=p.id_pedido) as estadoFotoTransf,
                 u.nombre as nombreUsuario,
                 (d.cantidad),
                      p.fecha,
                p.fecha_entrega,
                pc.id_transp,
                f.folio,
                pc.id as idInforme,
                pc.fecha as fechaInforme,
                p.entregaPed
                
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido p 
                ON p.id_pedido = d.id_pedido
                LEFT OUTER JOIN tbl_cliente c
                ON c.id_cliente = p.id_cliente
                left outer join tbl_nubox_facturas f 
                on f.id_pedido  = p.id_pedido
                
                LEFT OUTER JOIN tbl_informe_transp_deta pp
                ON pp.id_pedido = p.id_pedido
                
                LEFT OUTER JOIN tbl_informe_transp_cabe pc 
                ON pc.id = pp.id_informe 
                
                INNER JOIN usuario u
                ON u.id =  p.id_usuario
                WHERE p.estado_pedido='".$estado."'
                AND d.id_prod='".$idProd."'
                AND p.anulada='N'
                ORDER BY p.id_pedido desc;";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
            			$tmp->folio           = $row->folio;

			$tmp->tipo           = $row->tipo;
			$tmp->nombre         = $row->nombre;			
			$tmp->direccion      = $row->direccion;			
			$tmp->observacion    = $row->observacion;			
			$tmp->id_pedido      = $row->id_pedido;			
			$tmp->folio          = $row->folio;			
			$tmp->total          = round ((($row->total) * 0.19)+($row->total));	
  			$tmp->id_tipo_ped    = $row->id_tipo_ped;		
            $tmp->pesos          = round ($row->pesos);
            $tmp->codCobro       = $row->codCobro;		
            $tmp->documento     = $row->documento;
            $tmp->obser_transp  = $row->obser_transp;
            $tmp->idInforme     = $row->idInforme;
            $tmp->fechaInforme  = $row->fechaInforme;
            
            if($row->entregaPed=='2' ){
            
              $tmp->entregaPed     = "En Tienda";
            
            }else{
                
               $tmp->entregaPed    = "En Despacho";
    
                
            }


            
            if($row->credito=='0' || $row->credito=='1' ){
                  $tmp->formaPago      = 'Pago en:';
            }else{
                  $tmp->formaPago    = 'Credito';
            } 
            
                      $tmp->estado_pedido  = $row->estado_pedido;
                      $tmp->anulada        = $row->anulada;
                      $tmp->id_tipo_ped    = $row->id_tipo_ped;
                      $tmp->estadoFotoTransf    = $row->estadoFotoTransf;
                      $tmp->nombreUsuario    = $row->nombreUsuario;
                      $tmp->cantidad    = $row->cantidad;
                      $tmp->fecha    = $row->fecha;
                      $tmp->fecha_entrega    = $row->fecha_entrega;
                      $tmp->id_transp    = $row->id_transp;

            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
             mysqli_close($mysql);

}
    
 public function insertarInicioCaja($codUsua) {
                   $horaDiferencia = 1;

		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
		
        $query = "INSERT INTO tbl_caja
        (fecha_caja_inicio,
        hora_caja_inicio, 
        id_usuario) 
        
        VALUES (CURDATE() , 
                 NOW() + INTERVAL ".$horaDiferencia." HOUR ,
                '".$codUsua."' );";		

        
		$result = mysqli_query($mysql, $query);		
	 $id_ped = mysqli_insert_id($mysql);

            if($result == "1"){
                return $id_ped;
            }else{
                return 0;
            }
    
}    
    
    
    public function listarInicioCaja($idUsuario) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT c.id_caja,
                           c.fecha_caja_inicio,
                           c.hora_caja_inicio,
                           c.fecha_caja_fin,
                           c.hora_caja_fin
                           
                    FROM tbl_caja c 
                    WHERE c.id_usuario ='".$idUsuario."'
                    and c.fecha_caja_inicio = CURDATE()";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_caja                 = $row->id_caja;
			$tmp->fecha_caja_inicio  	  = $row->fecha_caja_inicio;
			$tmp->hora_caja_inicio        = $row->hora_caja_inicio;          
            
			$tmp->fecha_caja_fin          = $row->fecha_caja_fin;          
			$tmp->hora_caja_fin           = $row->hora_caja_fin;          
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

   } 
    
    
        
    public function listarCierreCajas($fInicio, $fFin) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT c.id_caja,
                           c.fecha_caja_inicio,
                           c.hora_caja_inicio,
                           c.fecha_caja_fin,
                           c.hora_caja_fin,
                           u.nombre,
                           c.centralizado,
                           c.observacion
                    FROM tbl_caja c 
                      LEFT OUTER JOIN usuario u 
                    ON u.id = c.id_usuario
                    WHERE  c.fecha_caja_inicio  BETWEEN ('".$fInicio."')  and ('".$fFin."')
                    
                    ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_caja                 = $row->id_caja;
			$tmp->fecha_caja_inicio  	  = $row->fecha_caja_inicio;
			$tmp->hora_caja_inicio        = $row->hora_caja_inicio;          
            
			$tmp->fecha_caja_fin          = $row->fecha_caja_fin; 
            $tmp->centralizado            = $row->centralizado; 
            
                       $tmp->observacion            = $row->observacion; 
 
            if($row->fecha_caja_fin == "0000-00-00"){
                	$tmp->fecha_caja_fin          = ""; 
                
            }
            
			$tmp->hora_caja_fin           = $row->hora_caja_fin;          
 			$tmp->nombre           = $row->nombre;       
            $ramdom =  mt_rand(); 
            
                $tmp->foto = "https://aranibar.cl/barrosaranas/Table/cierresCajas/".$row->id_caja.".png?".$ramdom; 
           
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

   } 
    
    
    
    
      public function listarCierreCajasMultiple($fInicio, $fFin) {
        $arrayBaseDatos[0] = "aranibar_aranibar";
        $arrayBaseDatos[1] = "aranibar_santa_maria";
        $arrayBaseDatos[2] = "aranibar_tucapel";  
		$ret = array();
        for($i=0;$i<count($arrayBaseDatos);$i++) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
        mysqli_query($mysql, "SET NAMES 'utf8'");	
    
		$query = "SELECT c.id_caja,
                           c.fecha_caja_inicio,
                           c.hora_caja_inicio,
                           c.fecha_caja_fin,
                           c.hora_caja_fin,
                           u.nombre,
                           c.centralizado,
                           c.observacion,
                           c.id_usuario
                    FROM tbl_caja c 
                      LEFT OUTER JOIN usuario u 
                    ON u.id = c.id_usuario
                    WHERE  c.fecha_caja_inicio  BETWEEN ('".$fInicio."')  and ('".$fFin."')
                    
                    ";
		$result = mysqli_query($mysql, $query);
    
            
  
            

          
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_caja                 = $row->id_caja;
			$tmp->fecha_caja_inicio  	  = $row->fecha_caja_inicio;
			$tmp->hora_caja_inicio        = $row->hora_caja_inicio;          
            
			$tmp->fecha_caja_fin          = $row->fecha_caja_fin; 
            $tmp->centralizado            = $row->centralizado; 
            $tmp->tiendaBase            =  $arrayBaseDatos[$i]; 
 
             $tmp->id_usuario            = $row->id_usuario;
           
            
                       $tmp->observacion            = $row->observacion; 
 
            if($row->fecha_caja_fin == "0000-00-00"){
                	$tmp->fecha_caja_fin          = ""; 
                
            }
            
			$tmp->hora_caja_fin           = $row->hora_caja_fin;          
 			$tmp->nombre           = $row->nombre;       
            $ramdom =  mt_rand(); 
            
                $tmp->foto = "https://aranibar.cl/barrosaranas/Table/cierresCajas/".$row->id_caja.".png?".$ramdom; 
           
			$ret[] = $tmp;
		}
            
   }        
            
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

   }  
    
    
    
    
    
    
    
    
    
    
   
   public function listarCierreCajaCentralizada($fInicio, $fFin) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
    $query = "SELECT c.id_caja_centralizada,
                       c.fecha_caja_inicio,
                       c.hora_caja_inicio,
                       c.fecha_caja_fin,
                       c.hora_caja_fin,
                       c.data,
                       c.sucursal,
                       c.observacion
                FROM tbl_caja_centralizada c 
                WHERE  c.fecha_caja_inicio  BETWEEN ('".$fInicio."')  and ('".$fFin."')
                
                ";
    $result = mysqli_query($mysql, $query);

    $ret = array();
    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Pedido();
        $tmp->id_caja                 = $row->id_caja;
        $tmp->fecha_caja_inicio  	  = $row->fecha_caja_inicio;
        $tmp->hora_caja_inicio        = $row->hora_caja_inicio;          
        
        $tmp->fecha_caja_fin          = $row->fecha_caja_fin; 
        $tmp->centralizado            = $row->centralizado; 
        
              $tmp->observacion            = $row->observacion; 
  
        if($row->fecha_caja_fin == "0000-00-00"){
                $tmp->fecha_caja_fin          = ""; 
            
        }
        
        $tmp->hora_caja_fin           = $row->hora_caja_fin;           
        $tmp->data          = $row->data;   
        $tmp->sucursal      = $row->sucursal;         
        $ramdom =  mt_rand(); 
        
            $tmp->foto = "https://aranibar.cl/barrosaranas/Table/cierresCajas/".$row->id_caja.".png?".$ramdom; 
       
        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return($ret);
         mysqli_close($mysql);

} 
    
    
      
    
  public function updateCajaFin($codUsua, $data = array(), $obserCaja) {
                   $horaDiferencia = 1;

		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	          
            mysqli_query($mysql, "SET NAMES 'utf8'");
  
                      $query ="update tbl_caja c 
                      SET c.fecha_caja_fin = CURDATE() , 
                      c.hora_caja_fin =  NOW() + INTERVAL ".$horaDiferencia." HOUR,
                      data = '".json_encode($data)."' ,
                      observacion ='".$obserCaja."'
                      WHERE c.id_usuario= '".$codUsua."' 
                      AND c.fecha_caja_inicio=CURDATE();";      
      
		$result = mysqli_query($mysql, $query);
        
        if(count($data) > 0){
            // Decodificar el JSON
            $dataArray = $data["retiros"];

            // Comenzar a construir la consulta de inserción
            $query2 = "INSERT INTO tbl_pedido_caja (id_pedido, id_caja) VALUES ";

            // Añadir los valores al query
            $valuesArr = [];
            foreach ($dataArray as $row) {
                $valuesArr[] = "('".$row['id_pedido']."', '".$row['id_caja']."')";
            }

            // Juntar todas las filas en una única sentencia SQL
            $query2 .= implode(', ', $valuesArr);

            // Ejecutar la consulta de inserción
            $result2 = mysqli_query($mysql, $query2);

            if($result == "1" && $result2){
                return 1;
            }else{
                return 0;
            }
        } else {
            if($result == "1"){
                return 1;
            }else{
                return 0;
            }
        }
}   

    public function syncCierreCaja($data, $nombreSucursal, $dataBaseServer,$dataBaseUsername,$dataBaseUserPassword,$dataBaseName) {
        $mysql = mysqli_connect($dataBaseServer, $dataBaseUsername, $dataBaseUserPassword);
        mysqli_select_db($mysql, $dataBaseName);          
        mysqli_query($mysql, "SET NAMES 'utf8'");

        $mysqlMaster = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
        mysqli_select_db($mysqlMaster, $this->DATABASE_NAME);          
        mysqli_query($mysqlMaster, "SET NAMES 'utf8'");

        // Obtener id_caja del array $data
        $idCaja = $data['id_caja'];

        // Buscar en tbl_caja
        $queryBuscar = "SELECT * FROM tbl_caja WHERE id_caja = '".$idCaja."'";
        $resultadoBuscar = mysqli_query($mysql, $queryBuscar);

        if ($fila = mysqli_fetch_assoc($resultadoBuscar)) {
            $queryInsertar = "INSERT INTO tbl_caja_centralizada (fecha_caja_inicio, hora_caja_inicio, fecha_caja_fin, hora_caja_fin, id_usuario, data, id_caja_original, sucursal,observacion) VALUES 
            (
                '" . $fila['fecha_caja_inicio'] . "', 
                '" . $fila['hora_caja_inicio'] . "', 
                '" . $fila['fecha_caja_fin'] . "', 
                '" . $fila['hora_caja_fin'] . "', 
                '" . $fila['id_usuario'] . "', 
                '" . $fila['data'] . "', 
                '" . $idCaja . "', 
                '" . $nombreSucursal . "',
                '" . $fila['observacion'] . "'
            )";
            
               $resultadoInsertar = mysqli_query($mysqlMaster, $queryInsertar);

            if ($resultadoInsertar) {
                // Actualizar tbl_caja
                $queryActualizar = "UPDATE tbl_caja SET centralizado = 1 WHERE id_caja = '".$idCaja."'";
                $resultadoActualizar = mysqli_query($mysql, $queryActualizar);

                if ($resultadoActualizar) {
                    return 1; // Éxito
                }
            }
        }
        return 0; // Falló
    }
    
    
    
    
 public function cambiaEstadoTipoDoc($idPedido, $idTipoDoc) {
 
  		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
      	$query = "UPDATE tbl_pedido p SET p.id_tipo_ped='".$idTipoDoc."'  WHERE p.id_pedido = '".$idPedido."';";		
	 
        $result = mysqli_query($mysql, $query);


     
        if($result = 0){
            return 0; 
        }else{
            return 1; 
        }   
     
     
         mysqli_close($mysql);
     
     
  }    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
   
  public function consultarNuboxPedido($idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	          
        mysqli_query($mysql, "SET NAMES 'utf8'");
   
  
    if ($result = mysqli_query($mysql, "SELECT * FROM tbl_nubox_facturas f where f.id_pedido = '".$idPedido."';")) {

    /* determinar el número de filas del resultado */
    $row_cnt = mysqli_num_rows($result);


    /* cerrar el resulset */
    mysqli_free_result($result);
        
        if($row_cnt == 0){
            return 0; 
        }else{
            return 1; 
        }
        
        
    }
  
  
  
  }
    /*
     {id: '0', name: '--Seleccionar Tienda--'}  ,
    {id: '16', name: 'SANTA MARIA'},
    {id: '17', name: 'TUCAPEL'},
    {id: '18', name: 'ASOCAPEC'}*/
    
    
public function traspasoProductosTienda($idPedido,  $id_tienda) {
   switch ($id_tienda) {
    case 16:
        $dataBaseName="aranibar_santa_maria";
        break;
    case 1:
        $dataBaseName="aranibar_aranibar";
        break;
           
    case 17:
        $dataBaseName="aranibar_tucapel";
    break;       
           
   }
    
    
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $dataBaseName);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  

        $estado = $this->traspasoConsultarPedidoTienda($idPedido,  $id_tienda);
    
        if( $estado != 0){
            return 0; 
        }else{
                        
        $query = "INSERT INTO tbl_ingresos(fecha, 
		                                   id_proveedor, 
		        						   observacion) 
         VALUES (CURDATE() , 
		         '".$id_tienda."',
				 'Transpaso de tienda')";		
         $resultCabe = mysqli_query($mysql, $query);	
         $id_ped = mysqli_insert_id($mysql);
            
         $query = "INSERT INTO 
         tbl_factura_ingresos(num_factura, 
		                     id_ingresos) 
         VALUES( '".$idPedido."',
		        '".$id_ped."')";
         $resultFactura = mysqli_query($mysql, $query);	    
            
   
       $resulDeta = $this->consultarDetalleParaTraspaso($idPedido);         
    
       $ret = array();
		while ($row = mysqli_fetch_object($resulDeta)) {
            
             $queryInsert = "INSERT INTO 
                 tbl_ingresos_deta(id_ingresos, 
                                   id_prod, cantidad) 
                 VALUES('".$id_ped."', 
                        '".$row->id_prod."' , 
                        '".$row->cantidad."');";

            $resultDeta = mysqli_query($mysql, $queryInsert);	
            
		}
    
        
        }
    
    return $id_ped;
     
}    
        
 
 function traspasoConsultarPedidoTienda($idPedido,  $id_tienda) {
    
    switch ($id_tienda) {
    case 16:
        $dataBaseName="aranibar_santa_maria";
        break;
    case 1:
        $dataBaseName="aranibar_aranibar";
        break;
            
          case 17:
        $dataBaseName="aranibar_tucapel";
        break;         
            
     }
    
    
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $dataBaseName);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  
	     
    if ($result = mysqli_query($mysql, "SELECT * FROM tbl_factura_ingresos f where f.num_factura='".$idPedido."';")) {
    /* determinar el número de filas del resultado */
    $row_cnt = mysqli_num_rows($result);
    /* cerrar el resulset */
     mysqli_free_result($result);
     return $row_cnt;       
    }    
    
}
    
    
 function consultarDetalleParaTraspaso($idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT d.id_prod,
                           d.cantidad
                    FROM tbl_deta_pedido d 
                    where d.id_pedido = '".$idPedido."'";
                  
		$result = mysqli_query($mysql, $query);
	//	mysqli_free_result($result);
		      return($result);
             mysqli_close($mysql);

}
    

    
    
public function insertarSolicitudMantencion(
    $nombre, 
    $observacion, 
    $tipo_solicitud, 
    $f_entrega, 
    $telefono,
    $idTransp,
    $kilometraje,
    $objDeta
    ) {
     
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
                $query2 = "INSERT INTO tbl_mantencion_cabe(id_camion, 
                                fecha_ingreso, 
                                fecha_entrega, 
                                telefono, 
                                tipo_solicitud, 
                                observacion,
                                id_estado,
                                nombre_mecanico,
                                kilometraje) 
                 VALUES ( '".$idTransp."', 
                         CURDATE(),
                         '".$f_entrega."',
                         '".$telefono."',
                         '".$tipo_solicitud."',
                         '".$observacion."',
                         2,
                         '".$nombre."',
                         '".$kilometraje."');";
               $resultCSolicitud = mysqli_query($mysql, $query2);	
               $idSolicitud = mysqli_insert_id($mysql);          
    
    
                  foreach($objDeta as $detalle){

                          
        $queryPedDeta = "INSERT INTO 
                 tbl_mantencion_deta(id_solicitud, 
                                   nombreDeta, 
                                   precio, 
                                   activo_deta,
                                   observacion_extra,                                   
                                   id_tipo_mant
                                   ) 
                 VALUES('".$idSolicitud."', 
                        '".$detalle->itemsDetalleProd."', 
                        '".$detalle->itemsDetallePrecio."', 
                        '".$detalle->itemsDetalleAct."',
                        '".$detalle->itemsDetalleProdAdic."',                        
                        '".$detalle->itemsDetalleTipoMant."'
                        ); ";  

            mysqli_query($mysql, $queryPedDeta);

        
    }
    
              

       //        mysqli_free_result($resultCSolicitud);
return $idSolicitud;
               mysqli_close($mysql);
}
    
    
public function getDataTransporte() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT 
                    p.id as id,
                    p.src_img_transp,
                    p.patente as name
                    FROM tbl_transportes p
                    WHERE p.id <> 0";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		    $tmp =new Marca();
			$tmp->id   = "0";
			$tmp->name = "--Seleccionar--";
			$ret[] = $tmp;
    
    
       while ($row = mysqli_fetch_object($result)) {
			$tmp =new Marca();
			$tmp->id   = $row->id;
			$tmp->name = $row->name;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
}
    
    
public function getDataTipoMantenciones() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT 
c.id,
c.nombre
FROM tbl_tipo_mantenciones c ";
		$result = mysqli_query($mysql, $query);
	
    
    	$ret = array();
		    $tmp =new Marca();
			$tmp->id     = "0";
			$tmp->nombre = "--Seleccionar--";
			$ret[] = $tmp;
    
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id = $row->id;
			$tmp->nombre = $row->nombre;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
}
    
    
public function getTipoSolicitudMantenciones() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT 
c.id,
c.nombre
FROM tbl_tipo_solicitud_transporte c ";
		$result = mysqli_query($mysql, $query);
	
    
    	$ret = array();
		    $tmp =new Marca();
			$tmp->id     = "0";
			$tmp->nombre = "--Seleccionar--";
			$ret[] = $tmp;
    
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id = $row->id;
			$tmp->nombre = $row->nombre;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
}    
    
    
  public function getDataMantencionTransporte() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT 
                    t.id,
                    t.nombre
                    FROM tbl_estado_transporte t  ";
		$result = mysqli_query($mysql, $query);
	
    
    	$ret = array();
		    $tmp =new Marca();
			$tmp->id     = "0";
			$tmp->nombre = "--Seleccionar estado--";
			$ret[] = $tmp;
    
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id = $row->id;
			$tmp->nombre = $row->nombre;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
}   
    
    
public function modificarEstadoTransporte($idEstado, $idSol) {
     
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
    
          	$query2 = "                  
                  
                   UPDATE tbl_mantencion_cabe p 
                    SET p.id_estado = '".$idEstado."' 
                    WHERE p.id = '".$idSol."';
                ";		
		

            	$result = mysqli_query($mysql, $query2);
    if($result == "1"){
                    return 1;
                }else{
                    return 0;
    }
         mysqli_close($mysql);

}    
    
    
                      
public function listarSolicitudesMantencion($tipBusq, $folio, $nombre, $fIngreso, $fEntrega, $fIngresoHasta, $fEntregaHasta, $patente) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        

    
    if($tipBusq =='1'){
        
         $tipBus=" c.fecha_ingreso BETWEEN '".$fIngreso."' AND '".$fIngresoHasta."' ";
        
    }else if($tipBusq =='2'){        
             $tipBus=" c.fecha_entrega BETWEEN '".$fEntrega."' AND '".$fEntregaHasta."' ";        
    }else if($tipBusq =='3'){
             $tipBus=" c.id='".$folio."' ";
    }else if($tipBusq =='4'){
             $tipBus=" c.nombre_mecanico like '%".$nombre."%' ";
    }else if($tipBusq == '5'){
           $tipBus =" t.patente like  '%".$patente."%' ";
    }
    
    
		$query = "
                 SELECT 
                    c.id, 
                    c.id_camion, 
                    c.fecha_ingreso, 
                    c.fecha_entrega, 
                    c.telefono, 
                    c.tipo_solicitud,
                    c.observacion,
                    c.id_estado,
                    p.nombre as tipoSolicitud,
                    e.nombre as estadoTransporte,
                    c.nombre_mecanico,
                    c.kilometraje

                    FROM tbl_mantencion_cabe c 
                    INNER JOIN tbl_transportes t 
                    ON t.id  = c.id_camion 
                    LEFT OUTER JOIN tbl_tipo_solicitud_transporte p 
                    ON p.id = c.tipo_solicitud
                    LEFT OUTER JOIN tbl_estado_transporte e 
                    ON e.id =  c.id_estado
                Where 
                
                ".
            
            $tipBus
            .
            "
        ";
		$result = mysqli_query($mysql, $query);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id                = $row->id;
			$tmp->id_camion  	    = $row->id_camion;
			$tmp->fecha_ingreso     = $row->fecha_ingreso;          
        	$tmp->fecha_entrega     = $row->fecha_entrega; 
           	$tmp->telefono          = $row->telefono; 
        	$tmp->tipo_solicitud    = $row->tipo_solicitud; 
        	$tmp->observacion       = $row->observacion; 
        	$tmp->id_estado         = $row->id_estado; 
        	$tmp->tipoSolicitud     = $row->tipoSolicitud; 
        	$tmp->estadoTransporte  = $row->estadoTransporte; 
        	$tmp->nombre_mecanico   = $row->nombre_mecanico; 
        	$tmp->kilometraje       = $row->kilometraje; 

            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return($ret);
             mysqli_close($mysql);

}
  
          
    
  public function modificarSolicitudMantencion($nombre, 
                                              $observacion, 
                                              $tipo_solicitud, 
                                              $f_entrega, 
                                              $telefono, 
                                              $idTransp,
                                              $idPed, 
                                              $kilometraje,
                                              $objDeta) 
  {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		
  
      
      
     /* $query2 = "INSERT INTO tbl_mantencion_cabe(id_camion, 
                                fecha_ingreso, 
                                fecha_entrega, 
                                telefono, 
                                tipo_solicitud, 
                                observacion,
                                id_estado,
                                nombre_mecanico) 
                 VALUES ( '".$idTransp."', 
                         CURDATE(),
                         '".$f_entrega."',
                         '".$telefono."',
                         '".$tipo_solicitud."',
                         '".$observacion."',
                         2,
                         '".$nombre."');";*/
      
      
      	$query2 = "                  
                  
                UPDATE tbl_mantencion_cabe p 
                    SET p.id_camion            = '".$idTransp."' , 
                        p.fecha_entrega        = '".$f_entrega."',
                        p.telefono             = '".$telefono."',
                        p.tipo_solicitud       = '".$tipo_solicitud."',
                        p.observacion          = '".$observacion."',
                        p.nombre_mecanico      = '".$nombre."',
                        p.kilometraje          = '".$kilometraje."'

                    WHERE p.id = '".$idPed."';
                ";		
		
		mysqli_query($mysql, $query2);

      
            
      	$query3 = "DELETE FROM tbl_mantencion_deta  WHERE  id_solicitud = '".$idPed."';";		
		
		mysqli_query($mysql, $query3);
      
      
                         
    foreach($objDeta as $detalle){


          $queryPedDeta = "INSERT INTO 
                 tbl_mantencion_deta(id_solicitud, 
                                   nombreDeta, 
                                   precio, 
                                   activo_deta,
                                   observacion_extra,                                   
                                   id_tipo_mant
                                   ) 
                 VALUES('".$idPed."', 
                        '".$detalle->itemsDetalleProd."', 
                        '".$detalle->itemsDetallePrecio."', 
                        '".$detalle->itemsDetalleAct."',
                        '".$detalle->itemsDetalleProdAdic."',                        
                        '".$detalle->itemsDetalleTipoMant."'
                        ); ";  

            mysqli_query($mysql, $queryPedDeta);
 
        
    }

      
      
    
}    
    
    
  
public function listarDetallePedidoMantencion($pedido) {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
			SELECT 
                        d.id,
                        d.id_solicitud,
                        d.nombreDeta,
                        d.precio,
                        d.activo_deta,
                        d.observacion_extra,
                        d.id_tipo_mant,
                        t.nombre as nombreMant
                        
         
            FROM tbl_mantencion_deta d 
            INNER JOIN tbl_tipo_mantenciones t 
            ON t.id = d.id_tipo_mant
            WHERE d.id_solicitud='".$pedido."'";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id_solicitud         = $row->id_solicitud;			
			$tmp->id                   = $row->id;
			$tmp->codProducto  	       = $row->codProducto;
			$tmp->nombreDeta           = $row->nombreDeta;
			$tmp->precio               = $row->precio;
			$tmp->activo_deta          = $row->activo_deta;
			$tmp->observacion_extra    = $row->observacion_extra;
			$tmp->id_tipo_mant         = $row->id_tipo_mant;
			$tmp->selected             = $row->id_tipo_mant;
			$tmp->nombreMant           = $row->nombreMant;


            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}
    
    /************AQUI ESTOY********/
    
  public function consultarDescTiendaProd() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "SELECT c.id, c.nombre, c.descuento FROM tbl_config c where c.modo='Tienda' and c.activo=1;";

		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id                   = $row->id;
			$tmp->nombre  	           = $row->nombre;
			$tmp->descuento            = $row->descuento;            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}  

    
    
    /*********************/
    
public function listadoInforTransporteAsig($id_transporte, $desde, $hasta) {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
    $tipBus="";
    
   if($id_transporte != '0'){
        
   $tipBus=" t.id = '".$id_transporte."'
                    AND c.fecha  BETWEEN ('".$desde."')  and ('".$hasta."') ";
        
    }else if($id_transporte =='0'){        
           $tipBus=" c.fecha  BETWEEN ('".$desde."')  and ('".$hasta."') ";        
    }
    
    
    
    
    
		$query = "SELECT c.id
                    FROM tbl_informe_transp_cabe c 
                    LEFT OUTER JOIN tbl_transportes t
                    ON t.id = c.id_transp
                    WHERE    ".
            
            $tipBus
            .
            " ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
    
        	$ret = array();
		    $tmp =new Pedido();
			$tmp->id      = "0";
			$tmp->nombre = "--Seleccionar informe--";
			$ret[] = $tmp;
    
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id    	 = $row->id;						
			$tmp->nombre   	 = $row->id;						

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
    
    
    
    public function actualizarTotalInfoTransp($modEfectivo, $modDebito, $modTransferencia, $modCajaVecina, $idTransporte) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
        
        $query = " 
                    UPDATE tbl_informe_transp_cabe c 
                    SET c.valor_total  = '".$modEfectivo."' ,
                    c.debito_total     = '".$modDebito."',
                    c.transf_total     = '".$modTransferencia."' ,
                    c.cajavecina_total = '".$modCajaVecina."'
                    WHERE c.id = '".$idTransporte."';
                 ";
        
        
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
    
      mysqli_close($mysql);
}
    
    
    
public function validarReciboCobranza($idRecibo) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
   
                $query1 = " UPDATE  tbl_recibo_deta d
                            INNER JOIN tbl_recibo_cabe c 
                            ON c.id = d.id_recibo_bol 
                            INNER JOIN tbl_pedido p 
                            ON p.id_pedido = d.n_pedido
                            SET p.cobrado_vend =1,
                            c.estado_recibo_val =1                
                            WHERE d.id_recibo_bol = '".$idRecibo."'";
    
               $resultCabeSal = mysqli_query($mysql, $query1);	
    

    
        if($resultCabeSal == "1"){
        return 1;
    }else{
        return 0;
    }

         mysqli_close($mysql);   
}  
    
    
    
public function listarDescuentosVentas($id_prod) {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
			SELECT p.id,
                   p.nombreProd,
                   d.cantidad_minima,
                   d.valor_neto,
                   d.descripcion,
                   d.id as codDesc
            FROM tbl_descuento_venta d 
            LEFT OUTER JOIN productos p 
            ON p.id = d.id_prod
            WHERE d.id_prod='".$id_prod."'";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id         = $row->id;			
			$tmp->nombreProd                   = $row->nombreProd;
			$tmp->cantidad_minima  	       = $row->cantidad_minima;
			$tmp->valor_neto           = $row->valor_neto;
			$tmp->descripcion               = $row->descripcion;
			$tmp->codDesc               = $row->codDesc;


            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
    
    
 
    
public function consultarProdEnStock($arrProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'"); 
        
        $bb="1";
        for ($i = 0; $i < count($arrProd); $i++) {
                 $query = "SELECT 
                            p.id		 as id,
                            p.nombreProd as nombreProd,
                            SUM(IFNULL((SELECT SUM(d.cantidad)
                                FROM tbl_ingresos pe
                                INNER JOIN tbl_ingresos_deta d 
                                ON d.id_ingresos = pe.id
                             WHERE d.id_prod=p.id
							 and pe.activo=1),0)              
                             -
                            IFNULL((SELECT SUM(d.cantidad)
                                FROM tbl_pedido pe
                                INNER JOIN tbl_deta_pedido d 
                                ON d.id_pedido = pe.id_pedido
                             WHERE d.id_prod=p.id
                             AND pe.anulada='N'),0)) as stock             
                            FROM productos p
                            where p.id='".$arrProd[$i]->id."'";
            
                        $result = mysqli_query($mysql, $query);
            
            

            
                        $fila = mysqli_fetch_row($result);
            
                       

            
                        //$fila = mysqli_num_rows($result);

            if ($arrProd[$i]->cantidadProd > $fila[2]) {
                $bb .= " <br/>- ". $fila[1]; 
             }                        
           	mysqli_free_result($result);
        }

        mysqli_close($mysql);
    
	  return $bb;
   
      
}    
    

public function ingresarVenta($arrVenta, $descuentoPunto = 0) {
    
   //  $validacionStock = consultarProdEnStock();
    

        $horaDiferencia = 0;

    
$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
mysqli_select_db($mysql, $this->DATABASE_NAME);	
    
    
    if (mysqli_connect_errno())
      {
             echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
      }
    
         mysqli_set_charset($mysql, "utf8");
    
         mysqli_autocommit($mysql, FALSE);    
         
/*mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);*/
    
        $id_tienda   = $arrVenta[0]->idTienda;
        $id_usua     = $arrVenta[0]->idUsuario;
        $tipPago     = $arrVenta[0]->tipoDePago;

	 
        
// $activo = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM tbl_config WHERE activo='1'"));      
        
try{
    
/*if($activo == 1) {                */  
               
 
         $queryPedidoCabe = "INSERT INTO tbl_pedido(fecha, 
                                          id_usuario, 
                                          id_cliente, 
                                          anulada,
                                          hora,
                                          estado_cobranza,
                                          fecha_entrega,
                                          estado_pedido,
                                          descuento
                                          ) 
         VALUES (CURDATE() , 
                 '".$id_usua."',
                 '4598', 
                 'N',
                 NOW() + INTERVAL ".$horaDiferencia." HOUR,
                 '".$tipPago."'  ,
                 CURDATE(),
                 1,
                 $descuentoPunto
                 );
                 ";		
                
    mysqli_query($mysql, $queryPedidoCabe);

    
         $id_ped = mysqli_insert_id($mysql);


    foreach($arrVenta as $detalle){

        $queryPedDeta = "INSERT INTO 
                 tbl_deta_pedido(id_pedido, 
                                   id_prod, 
                                   cantidad, 
                                   precio_vendido, 
                                   descuento, 
                                   aumento,  
                                   total                                   
                                   ) 
                 VALUES('".$id_ped."', 
                        '".$detalle->idProd."', 
                        '".$detalle->cantidad."', 
                        '".$detalle->precio_venta."',
                        '".$detalle->descuento."', 
                        '".$detalle->aumento."', 
                        '".$detalle->totalProd."'
                        ); ";  

            mysqli_query($mysql, $queryPedDeta);

        
    }
/*
}else{
    
  return "3";    
    
}
    */
    
    
}catch( mysqli_sql_exception  $e ){
    return "0";       
    mysqli_rollback($mysql);
   
}
 mysqli_commit($mysql);  
 mysqli_close($mysql);   
 return $id_ped;
}  
    
    
    
    
    
    
    
    
    
    

    
public function saveInformeTranspBodega($arrPed) {
    $horaDiferencia = 1;
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");  


      $query2 = "UPDATE tbl_informe_transp_cabe p
                    SET p.observacion       = '".$arrPed[0]->obsTransp."' ,
                    id_transporte_patente	= '".$arrPed[0]->idTransporte."' ,
                    inicio_km	= '".$arrPed[0]->inicioKg."' ,
                    fin_km	= '".$arrPed[0]->finKg."' ,
                    carga_combustible_km	= '".$arrPed[0]->cargaKg."' ,
                    valor_carga	= '".$arrPed[0]->valorKm."'
                    WHERE p.id = ".$arrPed[0]->idInforme."";

        $resultDeta2 = mysqli_query($mysql, $query2);	
    
    
    
       foreach($arrPed as $detalle){ 
 
          if($detalle->codCobro != 4 ){
              $query3 = "INSERT INTO tbl_log (id_pedido, 
                                              fecha_log,
                                              accion, 
                                              id_usuario,
                                               hora) 
                                   VALUES ('".$detalle->id_pedido."',
                                           CURDATE(),
                                           'modificarPedidoEstadoMasivoInformeTransp',
                                           '".$detalle->idUsuario."',
                                           NOW() + INTERVAL ".$horaDiferencia." HOUR);

                        ";
                $result2 = mysqli_query($mysql, $query3);
              }
    
        }   
    
    
    
       foreach($arrPed as $detalle){
       $query = "UPDATE tbl_pedido p
                    SET   p.obser_transp     = '".$detalle->obsInfor."' ,
                          p.estado_pedido     = '".$detalle->estPedido."' 
                        
                        
                    WHERE p.id_pedido = ".$detalle->id_pedido."";

        $resultDeta = mysqli_query($mysql, $query);	
   

        if($resultDeta == "0"){
            return "Error al generar ruta, favor contactar con el Administrador";
            break;
        }
        
      }
      
    
    
	
    if($resultDeta == "1"){
        return 1;
    }else{
        return 0;
    }
    
    
             mysqli_close($mysql);

}    
    
    
    
    
public function listarDescuentos(){
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
       mysqli_select_db($mysql, $this->DATABASE_NAME);
       mysqli_query($mysql, "SET NAMES 'utf8'");
       $query = "        
           SELECT * FROM tbl_descuento       
       ";
       $result = mysqli_query($mysql, $query);
       $total = mysqli_num_rows($result);
   
       $ret = array();
       while ($row = mysqli_fetch_object($result)) {
           $tmp = new stdClass();
           $tmp->id     =   $row->id;
           $tmp->nombre =   $row->nombre;
           $ret[] = $tmp;
       }
       mysqli_free_result($result);
        return ($ret);

        mysqli_close($mysql);
}    
    
    

public function guardaFolioCredito($folio, $id_credito) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);	
    mysqli_query($mysql, "SET NAMES 'utf8'");		
    $query = "UPDATE tbl_credito c SET
              c.folio_nota = '".$folio."',
              c.observacion_credito = 'Realizado N/C-EL - ".$folio."',
              c.fecha_nota = CURDATE() 
              WHERE c.id='".$id_credito."';
    ";		
    $result = mysqli_query($mysql, $query);		

    return $result;
 
    mysqli_free_result($result);
    mysqli_close($mysql);    
}
    
    
    
    
public function listarMes() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
        SELECT m.id, m.nombre_mes FROM tbl_mes m
        
        ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id    	        = $row->id;			
			$tmp->nombre_mes        = $row->nombre_mes;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
 
    
public function listarAno() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
        SELECT a.id, a.nombre_anual FROM tbl_ano a 
        
        ";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Pedido();
			$tmp->id    	        = $row->id;			
			$tmp->nombre_anual        = $row->nombre_anual;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
     mysqli_close($mysql);
}    
    
    
    
 public function queryDemo() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
        SELECT                 
            p.id_pedido as id,
           c.nombre,
            c.tipo_comprador,
            DATE_FORMAT(p.fecha_cobro, '%d-%m-%Y') as fecha_cobro,
            DATE_FORMAT(p.fecha_entrega, '%d-%m-%Y') as fecha_entrega,
            SUM(d.cantidad * d.precio_vendido) as totalPedido,
            c.credito,
            e.nombre as estadoPedido,
            DATEDIFF(CURDATE(),MAX(p. fecha_cobro)) as countFecha,
            ic.id as idInformeTransporte,
            us.nombre as solicitante,
            rc.id as idReciboCobranza,
            rc.total_recibo,
            rc.abono_recibo,
            p.id_tipo_ped as tipo_pedido,
            p.entregaPed
    FROM tbl_pedido p
           INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente c 
            ON c.id_cliente = p.id_cliente                            
            left OUTER join tbl_estado_pedido e 
            ON e.id_estado = p.estado_pedido
            LEFT OUTER JOIN tbl_informe_transp_deta id 
            ON id.id_pedido = p.id_pedido
            LEFT OUTER JOIN tbl_informe_transp_cabe ic 
            ON ic.id = id.id_informe
            INNER JOIN usuario us 
            ON us.id = c.id_usuario                          
            LEFT OUTER JOIN tbl_recibo_deta rd
            ON rd.n_pedido = p.id_pedido 
            LEFT OUTER JOIN tbl_recibo_cabe rc
            ON rc.id = rd.id_recibo_bol                                
            WHERE p.estado_cobranza=4
            and p.anulada='N'
             and YEAR(p.fecha_entrega) in (2022,2023,2024,2025,2026)
            GROUP by 
                     c.nombre,
                     c.tipo_comprador,
                     p.id_pedido,
                     p.fecha_cobro,
                     p.fecha_entrega,
                     c.credito,
                      ic.id,
                      us.nombre
        
        ";
        
        $result = mysqli_query($mysql, $query);
        
		$data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($mysql);

    return $data;
}   
    
    

 public function queryCobranza() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
        SELECT                 
            p.id_pedido as id,
           c.nombre,
            c.tipo_comprador,
            DATE_FORMAT(p.fecha_cobro, '%d-%m-%Y') as fecha_cobro,
            DATE_FORMAT(p.fecha_entrega, '%d-%m-%Y') as fecha_entrega,
            SUM(d.cantidad * d.precio_vendido) as totalPedido,
            c.credito,
            e.nombre as estadoPedido,
            DATEDIFF(CURDATE(),MAX(p. fecha_cobro)) as countFecha,
            ic.id as idInformeTransporte,
            us.nombre as solicitante,
            rc.id as idReciboCobranza,
            rc.total_recibo,
            rc.abono_recibo,
            p.id_tipo_ped as tipo_pedido,
            p.entregaPed
    FROM tbl_pedido p
           INNER JOIN tbl_deta_pedido d 
            ON d.id_pedido = p.id_pedido
            INNER JOIN tbl_cliente c 
            ON c.id_cliente = p.id_cliente                            
            left OUTER join tbl_estado_pedido e 
            ON e.id_estado = p.estado_pedido
            LEFT OUTER JOIN tbl_informe_transp_deta id 
            ON id.id_pedido = p.id_pedido
            LEFT OUTER JOIN tbl_informe_transp_cabe ic 
            ON ic.id = id.id_informe
            INNER JOIN usuario us 
            ON us.id = c.id_usuario                          
            LEFT OUTER JOIN tbl_recibo_deta rd
            ON rd.n_pedido = p.id_pedido 
            LEFT OUTER JOIN tbl_recibo_cabe rc
            ON rc.id = rd.id_recibo_bol                                
            WHERE p.estado_cobranza=4
            and p.anulada='N'
             and YEAR(p.fecha_entrega) in (2022,2023,2024,2025,2026)
            GROUP by 
                     c.nombre,
                     c.tipo_comprador,
                     p.id_pedido,
                     p.fecha_cobro,
                     p.fecha_entrega,
                     c.credito,
                      ic.id,
                      us.nombre
        
        ";
        
        $result = mysqli_query($mysql, $query);
        
		$data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($mysql);

    return $data;
}    
    
    
    
    

 public function listTiendaPedidoPagina() {
 
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
        
        SELECT
                a.id AS id, 
                a.fecha AS fecha, 
                a.metodo_pago AS metodoPago, 
                a.estado AS estado, 
                a.nombre AS nombre, 
                a.celular AS celular, 
                a.correo AS correo,
                a.calle AS calle, 
                a.numero AS numero, 
                a.id_cliente AS idCliente, 
                a.total AS total 
                FROM view_tienda_orden a 
                WHERE 1=1  
          ORDER BY a.fecha DESC;
        
        ";
        
        $result = mysqli_query($mysql, $query);
        
		$data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($mysql);

    return $data;
}   
    
    
    
    
    
    
}

?>