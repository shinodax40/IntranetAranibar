<?php
class bdStock{
	
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
	
     
public function insertarStock($objCabe, $objDeta, $objCliente, $obserbIng) {
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);		
	     mysqli_query($mysql, "SET NAMES 'utf8'");
        $idAccion = $objCliente[0]->idAccion;
       
        if($idAccion == "2"){	
              $query = "INSERT INTO tbl_proveedores(rut, nombre) 
                 VALUES ('".$objCliente[0]->rut."',
                         '".$objCliente[0]->nombre."')";		
                 $resultCabe = mysqli_query($mysql, $query);	
                 $id_clie = mysqli_insert_id($mysql);
       
         }else if($idAccion == "1"){
            /*$query = "UPDATE tbl_proveedores
                      SET    rut='".$objCliente[0]->rut."',   
                          nombre='".$objCliente[0]->nombre."'
                      WHERE id_proveedor='".$objCliente[0]->idProveedor."'";
            $resultCabe = mysqli_query($mysql, $query);	*/
            $id_clie = $objCliente[0]->idProveedor;
            
        }
       
        
         $query = "INSERT INTO tbl_ingresos(fecha, 
		                                    id_proveedor, 
											observacion) 
         VALUES (CURDATE() , 
		         '".$id_clie."',
				 '".$obserbIng."')";		
         $resultCabe = mysqli_query($mysql, $query);	
         $id_ped = mysqli_insert_id($mysql);
         

        
        $query = "INSERT INTO  tbl_factura_ingresos(num_factura, 
		                     id_ingresos) 
        VALUES( '".$objCliente[0]->numFactura."',
		        '".$id_ped."')";
        $resultFactura = mysqli_query($mysql, $query);	
        
        
         
         $resultDeta = 0;
        
        if($resultCabe == 1){
            
            foreach($objDeta as $detalle){
                
            /* $queryBod =   "UPDATE productos p
                            SET p.bodega='".$detalle->bodega."',
                                p.cod_barra='".$detalle->codBarra."'
                            WHERE p.id='".$detalle->id."';";
        

                $resultprod = mysqli_query($mysql, $queryBod);	*/
                

                $query = "INSERT INTO 
                        tbl_ingresos_deta(id_ingresos, 
                                        id_prod, 
                                        cantidad) 
                        VALUES('".$id_ped."', 
                                '".$detalle->id."' , 
                                '".$detalle->cantidadProd."');";

                $resultDeta = mysqli_query($mysql, $query);	
                
                /*
                $query = "UPDATE tbl_precio_factura
                            SET activo=0
                            WHERE id_prod='".$detalle->id."';";
                $resultCabe = mysqli_query($mysql, $query);	*/

                $query = "INSERT INTO 
                tbl_precio_factura(id_ingresos, 
                                        id_prod, 
                                    descuento, 
                                    valor_neto, 
                                precio_venta, 
                                    cantidad,
                                        activo,
                                diferenciaPrecio) 
                VALUES(        '".$id_ped."', 
                        '".$detalle->id."', 
                    '".$detalle->descuento."', 
                '".$detalle->valor_neto."', 
                '".$detalle->precio_venta."', 
                '".$detalle->cantidadProd."',
                0,
                '".$detalle->diferenciaPrecio."');";

                $resultDeta = mysqli_query($mysql, $query);	
                
            
                

                if($resultDeta == "0"){
                    return "Error al generar el pedido, favor contactar con el Administrador";
                    break;
                    $resulEstado="0";
                }
                
            }


        }else{

        $resultDeta="0";
            
        }
        return $resultDeta;			
}
    
public function listarIngresos($desde, $hasta, $idProv, $tipBusq) {
    $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
    mysqli_select_db($mysql, $this->DATABASE_NAME);
    mysqli_query($mysql, "SET NAMES 'utf8'");
    $idPro ="";
    $tipBus="";
    
    if($tipBusq=="Ingreso"){
        $tipBus="t.fecha  BETWEEN ('".$desde."')  and ('".$hasta."')";
    }else{
        $tipBus="t.fecha_cobro  BETWEEN ('".$desde."')  and ('".$hasta."')";

    }
    
    if($idProv != ""){
            $idPro=" AND t.id_proveedor = '".$idProv."'";
    }
    
    $query = "
      SELECT t.id,
	         t.fecha,
             p.nombre,
             p.rut,
             t.observacion,
             t.fecha_cobro,
             t.cod_documento,
             t.estado_cobranza,
             t.estado_pedido,
             u.num_factura,
             u.estadoSII,
			 t.activo
        FROM tbl_ingresos t 
        INNER JOIN tbl_proveedores p 
        ON p.id_proveedor = t.id_proveedor
        INNER JOIN tbl_factura_ingresos u
        ON u.id_ingresos = t.id
        WHERE "
        .
        $tipBus
        .
        " "
        .
         $idPro
        .
        " GROUP BY t.id,
	         t.fecha,
             p.nombre,
             p.rut,
             t.observacion,
             t.fecha_cobro,
             t.cod_documento,
             t.estado_cobranza,
             t.estado_pedido,
             u.num_factura,
             u.estadoSII
        ORDER BY t.id DESC";
    $result = mysqli_query($mysql, $query);
    $total = mysqli_num_rows($result);

    $ret = array();
    while ($row = mysqli_fetch_object($result)) {
        $tmp =new Pedido();        
            $tmp->id    	      = $row->id;			
            $tmp->fecha           = $row->fecha;
            $tmp->nombre          = $row->nombre;
            $tmp->rut             = $row->rut;
            $tmp->observacion     = $row->observacion;
            $tmp->cod_documento   = $row->cod_documento;
            $tmp->estado_cobranza = $row->estado_cobranza;
            $tmp->estado_pedido   = $row->estado_pedido;
            $tmp->num_factura     = $row->num_factura;
            $tmp->estadoSII       = $row->estadoSII;
            $tmp->activo          = $row->activo;
       
        
        
            if($row->fecha_cobro=='0000-00-00'){
                  $tmp->fecha_cobro      = '';
            }else{
                  $tmp->fecha_cobro      = $row->fecha_cobro;   
            }
        
        
        $ret[] = $tmp;
    }
    mysqli_free_result($result);
          return ($ret);
}    

    
    
public function listarDetalleIngresos($ingreso) {
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
	
   // $arraySubProd = array(1, 2, 3, 4, 5);
    
	   $query = "SELECT d.id_ingresos,
                    d.id_prod,
                    d.cantidad,
                    p.nombreProd,
                    /*f.precio_venta as precioVenta,*/
					f.precio_venta as precioVenta,
                    p.codProducto,
                    f.descuento,
                    /*f.valor_neto,*/
                    f.valor_neto as valor_neto,
                    f.id as idPrecio,					
                    sd.precio_venta as precioVentaAnt,
					f.activo,
                    sd.valor_neto as precioNetoAnt,
                    p.precioPart,                    
                   /* sp.id as idNodo,
                    sp.nombreProd as nombreNodo,
                    sp.stockProd as stockNodo,*/
                    ''/*(SELECT count(p.id) FROM productos p where p.nodo_prod like d.id_prod)*/ as subProdPadre

                    
                    FROM tbl_ingresos_deta d 
                    INNER JOIN productos p 
                    ON p.id = d.id_prod
                    LEFT OUTER JOIN tbl_precio_factura f 
                    on f.id_ingresos = d.id_ingresos
                    and f.id_prod = d.id_prod
                    
                 /*   LEFT OUTER JOIN (
                     SELECT pp.id,
                            pp.nombreProd,
                            (IFNULL((SELECT SUM(d.cantidad)
                                            FROM tbl_ingresos pe
                                            INNER JOIN tbl_ingresos_deta d 
                                            ON d.id_ingresos = pe.id
                                      WHERE d.id_prod=pp.id
                                      and pe.activo=1),0) 
                                        -
                                      IFNULL(  (SELECT SUM(d.cantidad)
                                            FROM tbl_pedido pe
                                            INNER JOIN tbl_deta_pedido d 
                                            ON d.id_pedido = pe.id_pedido
                                         WHERE d.id_prod=pp.id
                                         AND pe.anulada='N'),0)						 
                            ) as stockProd,
                        pp.nodo_prod
  
                            
                     FROM productos pp 
                     WHERE pp.id
                    ) sp ON sp.nodo_prod = d.id_prod*/

                    LEFT OUTER JOIN ((
				     select tp.precio_venta,
                           tp.id_prod,
                           tp.id_ingresos,
						   tp.valor_neto
					From tbl_precio_factura tp 
					where tp.activo=1
                   								
					)) sd ON sd.id_prod =  d.id_prod
                     and sd.id_ingresos = (SELECT MAX(f.id_ingresos) 
											FROM tbl_precio_factura f
											WHERE f.id_prod=d.id_prod
											and f.activo=1)	
                     WHERE d.id_ingresos='".$ingreso."'
                     order by d.id DESC

                    
                    ";
	
	
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
    
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
            
            
            			$tmp->subProdPadre    = $row->subProdPadre;			

			$tmp->id_ingresos    = $row->id_ingresos;			
			$tmp->id_prod        = $row->id_prod;
			$tmp->cantidad       = $row->cantidad;
			$tmp->nombreProd     = $row->nombreProd;
			$tmp->precioVenta    = round((($row->precioVenta)*0.19)+(($row->precioVenta)));
			$tmp->codProducto    = $row->codProducto;
			$tmp->descuento      = $row->descuento;
			$tmp->valor_neto     = round($row->valor_neto / $row->cantidad);
			$tmp->idPrecio       = $row->idPrecio;
			$tmp->precioVentaAnt = round((($row->precioVentaAnt)*0.19)+(($row->precioVentaAnt)));
			$tmp->activo         = $row->activo;
			$tmp->precioNetoAnt  = round($row->precioNetoAnt / $row->cantidad);
			
			if($row->activo == 0){
				$tmp->precioVenta    = round((($row->precioVentaAnt)*0.19)+(($row->precioVentaAnt)));
			}else if($tmp->activo == 1){
				$tmp->precioVenta    = round((($row->precioVenta)*0.19)+(($row->precioVentaAnt)));
			}

			
			if($row->valor_neto ==0){			
//		     	$tmp->valor_neto     = round($row->precioNetoAnt / $row->cantidad);
  		     	$tmp->valor_neto     = round($row->precioNetoAnt);
  		}else if($row->valor_neto !=0){
//			   	$tmp->valor_neto     = round($row->valor_neto / $row->cantidad);	
			   	$tmp->valor_neto     = round($row->valor_neto);			
    
			}
            			$tmp->precioPart         = $row->precioPart;
            /*
            			$tmp->idNodo            = $row->idNodo;
            			$tmp->nombreNodo        = $row->nombreNodo;
            			$tmp->stockNodo         = $row->stockNodo;

			*/
           // $tmp->subProd         = $result;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}
    
    
    
    
    
function guardarObserFact($id, $obse, $estCobro, $estPedido, $fechCobro, $numDoc){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
		$query = "UPDATE tbl_ingresos 
                    SET  observacion = '".$obse."' 
                    ,fecha_cobro     = '".$fechCobro."'
                    ,cod_documento     = '".$numDoc."'
                    ,estado_cobranza   = '".$estCobro."'
                    ,estado_pedido     = '".$estPedido."'
                   WHERE id          =  ".$id.";
                    ";
    
		$result = mysqli_query($mysql, $query);	
    
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}    
	
	
function updatePrecioFactura($arrProd){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
    
             
      $arrayBaseDatos[0] = "aranibar_aranibar";
      $arrayBaseDatos[1] = "aranibar_santa_maria";
      $arrayBaseDatos[2] = "aranibar_tucapel";
    
	
	foreach($arrProd as $detalle){
        
              $query = "UPDATE tbl_ingresos_deta
							  SET cantidad='".$arrProd[0]->cantidad."'
							  WHERE id_ingresos='".$arrProd[0]->idIng."'
                              and id_prod='".$arrProd[0]->id_prod."';";
				  $resultCabe = mysqli_query($mysql, $query);
        
        
		
		 if($detalle->activo == '1'){
				  $query = "UPDATE tbl_precio_factura
							  SET activo=0
							  WHERE id_prod='".$detalle->id_prod."';";
				  $resultCabe = mysqli_query($mysql, $query);
			 
			      $query = "UPDATE tbl_precio_factura
							  SET activo=1
							  WHERE id_prod='".$detalle->id_prod."'
							  and id_ingresos='".$arrProd[0]->idIng."' ;";
				 $resultCabe = mysqli_query($mysql, $query);
		 }
        
         for($i=0;$i<count($arrayBaseDatos);$i++) {  
        
        	$mysqlTiendas = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
            mysqli_select_db($mysqlTiendas,  $arrayBaseDatos[$i]);


            $query = "UPDATE productos p 
                    SET p.precioPart = '".$detalle->precioPart."'
                    WHERE p.id='".$detalle->id_prod."' ;";
                    $resultProd = mysqli_query($mysqlTiendas, $query);
             
         }
        
        
		$query = "
                   UPDATE tbl_precio_factura f
					SET f.valor_neto='".$detalle->valor_neto."',
						f.precio_venta='".$detalle->precioVenta."',
						f.diferenciaPrecio='".$detalle->difPrecio."'
					WHERE f.id_ingresos='".$arrProd[0]->idIng."'
					and f.id_prod='".$detalle->id_prod."'   
				   
                    ";
    
		         $result = mysqli_query($mysql, $query);	
    
	}		
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}  	
	
	
function activarDesactivarFact($idIng, $activo){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
	
		$query = "  update tbl_ingresos p 
					set p.activo='".$activo."'
					where p.id='".$idIng."';
				   
                    ";
    
		$result = mysqli_query($mysql, $query);	
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}
    
    

    
 public function insertarGrupoProd($idProd, $cantIng, $cantDesc, $idProdDes) {
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql, $this->DATABASE_NAME);		
	     mysqli_query($mysql, "SET NAMES 'utf8'");
 
     
        $queryPedidoCabe = "INSERT INTO tbl_pedido(fecha, 
                                          id_cliente, 
                                          anulada,
                                          observacion
                                          ) 
         VALUES (CURDATE() , 
                 '162',
                 'N',
                 'Productos desagrupado')";		
         mysqli_query($mysql, $queryPedidoCabe);
         $id_ped_ing = mysqli_insert_id($mysql);      
        
        $queryPedDeta = "INSERT INTO 
                 tbl_deta_pedido(id_pedido, 
                                   id_prod, 
                                   cantidad) 
                 VALUES('".$id_ped_ing."', 
                        '".$idProdDes."', 
                        '".$cantDesc."' 
                        ); ";  

            mysqli_query($mysql, $queryPedDeta);

     
        
         $query = "INSERT INTO tbl_ingresos(fecha, 
											observacion,
                                            activo) 
         VALUES (CURDATE() , 
				 'Productos desagrupado',
                 1)";		
         $resultCabe = mysqli_query($mysql, $query);	
         $id_ped     = mysqli_insert_id($mysql);
        

         $query = "INSERT INTO 
                 tbl_ingresos_deta(id_ingresos, 
                                   id_prod, 
                                   cantidad) 
                 VALUES('".$id_ped."', 
                        '".$idProd."', 
                        '".$cantIng."');";

         $resultDeta = mysqli_query($mysql, $query);	       
        
    

return 1;			
}


public function updateEstadoCompraSII($id_factura, $folio, $estado){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
	
		$query = "  update tbl_factura_ingresos f
					set f.estadoSII='".$estado."'
					where f.id_ingresos='".$id_factura."' AND f.num_factura = '".$folio."'
				   
                    ";
    
		$result = mysqli_query($mysql, $query);	
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}


     
        
public function listEscalaProducto($idMarca, $idCategoria){
       $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
       mysqli_select_db($mysql, $this->DATABASE_NAME);
       mysqli_query($mysql, "SET NAMES 'utf8'");
       $query = "        
             SELECT 
                c.id,
                c.id_prod,
                p.nombreProd,
                c.cantidad_minima,
                c.descripcion,
                f.valor_neto as pcosto,/*Con iva*/
                c.valor_neto as pescala,/*Valor neto por escala*/
                IFNULL(f.precio_venta, p.precioVenta ) as pcomerciante, /*valor neto comerciante*/
                ROUND(p.precioPart/1.19) as pparticular
                FROM  productos p
                INNER JOIN tbl_descuento_venta c  
                ON c.id_prod = p.id   
                LEFT OUTER JOIN tbl_precio_factura f 
                ON f.id_prod = p.id
                AND f.activo = 1
                WHERE 
                p.categoriaProd = '".$idCategoria."'
                AND p.marcaProd = '".$idMarca."'
                ORDER BY c.id_prod, c.valor_neto DESC;      
       ";
       $result = mysqli_query($mysql, $query);
       $total = mysqli_num_rows($result);
   
       $ret = array();
       while ($row = mysqli_fetch_object($result)) {
           $tmp = new stdClass();
                      $tmp->id        =   $row->id;

           $tmp->id_prod        =   $row->id_prod;
           $tmp->nombreProd     =   $row->nombreProd;
           $tmp->cantidad_minima=   $row->cantidad_minima;
           $tmp->descripcion    =   $row->descripcion;
           $tmp->pcosto         =   $row->pcosto;
           $tmp->pescala        =   $row->pescala;
           $tmp->pcomerciante   =   $row->pcomerciante;
           $tmp->pparticular    =   $row->pparticular;
           $tmp->estado_mod =   "0";

     
           $ret[] = $tmp;
       }
       mysqli_free_result($result);
        return ($ret);

        mysqli_close($mysql);
}
    
    
    
    
    
public function insertarEscalaProd($idProd, $idMarca, $idCategoria) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
    
		$query = "  SELECT *
                    FROM productos p 
                    WHERE 
                    p.id='".$idProd."'
                    and p.marcaProd='".$idMarca."'
                    and p.categoriaProd='".$idCategoria."';
                  
        ";		
		$result = mysqli_query($mysql, $query);		
        $total = mysqli_num_rows($result);
	
	 if($total > 0){
         

         
                     $query = "INSERT INTO tbl_descuento_venta
                             (id_desc, 
                              id_prod, 
                              cantidad_minima,
                              valor_neto, 
                              descripcion)
                         VALUES ('".$idProd."' ,
                                 '".$idProd."',
                                 0,
                                 0,
                                 'Sin definir.');"; 

               $resultDeta = mysqli_query($mysql, $query);	
         
         
         
                    return 1;
                }else{
                    return 0;
      }
    mysqli_free_result($result);
    
   mysqli_close($mysql);    
}    
      
    
    
 
public function borrarProdEscala($idEscala){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
	
		$query = "
				   DELETE FROM tbl_descuento_venta  where id = '".$idEscala."';
                    ";
    
		$result = mysqli_query($mysql, $query);	
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}

   
 public function actualizarProdEscala($idEscala, $pescala, $descripEsc, $catMinima){
		$mysql = mysqli_connect($this->DATABASE_SERVER, $this->DATABASE_USERNAME, $this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
	
		$query = "                   
                      UPDATE tbl_descuento_venta 
                      SET  cantidad_minima='".$catMinima."', 
                      valor_neto='".$pescala."', 
                      descripcion='".$descripEsc."' 
                      WHERE  id='".$idEscala."';    
                    
                    ";
    
		$result = mysqli_query($mysql, $query);	
    
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
}
    
    
    
    
    
    
    
    
    
public function insertarProdIngresoList($idProd, $idMarca, $idCategoria) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
    
		$query = "  SELECT *
                    FROM productos p 
                    WHERE 
                    p.id='".$idProd."'
                    and p.marcaProd='".$idMarca."'
                    and p.categoriaProd='".$idCategoria."';
                  
        ";		
		$result = mysqli_query($mysql, $query);		
        $total = mysqli_num_rows($result);
	
	 if($total > 0){
         

         
                     $query = "INSERT INTO tbl_descuento_venta
                             (id_desc, 
                              id_prod, 
                              cantidad_minima,
                              valor_neto, 
                              descripcion)
                         VALUES ('".$idProd."' ,
                                 '".$idProd."',
                                 0,
                                 0,
                                 'Sin definir.');"; 

               $resultDeta = mysqli_query($mysql, $query);	
         
         
         
                    return 1;
                }else{
                    return 0;
      }
    mysqli_free_result($result);
    
   mysqli_close($mysql);    
}
    
    
    
    
public function listarProductoHijo($idProd){
       $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
       mysqli_select_db($mysql, $this->DATABASE_NAME);
       mysqli_query($mysql, "SET NAMES 'utf8'");
       $query = " 
       
       SELECT p.id, p.nombreProd FROM productos p where p.nodo_prod = '".$idProd."';
           
       ";
       $result = mysqli_query($mysql, $query);
       $total = mysqli_num_rows($result);
   
       $ret = array();
       while ($row = mysqli_fetch_object($result)) {
           $tmp = new stdClass();
                      $tmp->id        =   $row->id;

           $tmp->id             =   $row->id;
           $tmp->nombreProd     =   $row->nombreProd;

          

     
           $ret[] = $tmp;
       }
       mysqli_free_result($result);
        return ($ret);

        mysqli_close($mysql);
}    
    
    
    
    
    
    	
	

}

?>