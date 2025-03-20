<?php

require_once('Bean/ProductoAplicacion.php');

class bdBoletaDispositivo{	
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
	
	
	 public function insertarBoleta($total, $fchEmis, $devId, $folio, $afecto) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);			
		
		$existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM boleta_dispositivo WHERE folio=".$folio." AND afecto = ".$afecto));
		
		if(!$existe){	

			$query = "INSERT INTO  boleta_dispositivo (total,emision,deviceId,folio, afecto) VALUES (".$total.",'".$fchEmis."','".$devId."',".$folio.", ".$afecto.");";

				$result = mysqli_query($mysql, $query);	
		}
			
	}
    
    
    
    public function generarPedidoAplicacion($FchEmision,                           
                                       $Nombres,
                                       $Email,
                                       $Telefono,
                                       $Direccion,
                                       $FormaPago,
                                       $Total,
                                       $Detalle,
                                       $DevID) {
        
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);			
		
		

			$query = "INSERT INTO tbl_pedido_dispositivo(  fecha_ingreso, 
                                     nombres, 
                                     email, 
                                     telfono, 
                                     direccion, 
                                     forma_pago,
                                     total,
                                     detalle, 
                                     devid) VALUES  ('".$FchEmision."',
                                                     '".$Nombres."',
                                                     '".$Email."',
                                                     '".$Telefono."',
                                                     '".$Direccion."',
                                                      '".$FormaPago."',
                                                     '".$Total."',
                                                     '".$Detalle."',
                                                     '".$DevID."');";

				$result = mysqli_query($mysql, $query);	
        
            if($result == "1"){
                    return 1;
                }else{
                    return 0;
                }

			
	}
    
    
     public function listProductosAplicacion($listarOpcion) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
         
         $listarConcatenas ="";
         
         switch ($listarOpcion) {
            case 0://Perros alimentos secos
               $listarConcatenas = " and p.categoriaProd      = '3'
                                     and p.clasificacion_prod = '5' ";
                 
                break;
            case 1:// Alimentos medicados
                          $listarConcatenas = "   and p.categoriaProd      = '3'
                                                    and p.clasificacion_prod in (20) ";
                break;
            case 2: // Alimentos humedos Latas y sobres
                           $listarConcatenas = "    and p.categoriaProd      = '3'
                                                    and p.clasificacion_prod in (1,2) ";
                break;
             case 3: //Pulgas y Garrapatas
                           $listarConcatenas = "    and p.categoria_menu      = '3'
                                                    and p.clasificacion_prod in (14,15) ";
                break;
             case 4: //Snack
                           $listarConcatenas = "   and p.categoriaProd      = '3'
                                                   and p.clasificacion_prod in (7,8,13) ";
                break;
              case 5: //Ropa
                           $listarConcatenas = " ";
                break;
            
              case 6: //Juguetes
                           $listarConcatenas = " ";
                break;
            
              case 7: // Higuiene shampoo
                           $listarConcatenas = "   and p.categoria_menu      = '3'
                                                   and p.clasificacion_prod in (9,10) ";
                break;    
                
              case 8: //vitaminas
                           $listarConcatenas = "   and p.categoria_menu      = '3'
                                                   and p.clasificacion_prod in (16) ";
                break;  
                 
              case 9: // Leche en polvo
                           $listarConcatenas = "   and p.categoria_menu      = '3'
                                                   and p.clasificacion_prod in (19)";
                break;       
                
             case 10: //educadores
                           $listarConcatenas = " ";
                break;   
                 
            case 11: // platos
                           $listarConcatenas = " ";
                break;     
                 
            case 12: //capillos
                           $listarConcatenas = " ";
                break;     
                 
           case 13: // collares y correa
                           $listarConcatenas = " ";
                break;     
                 
        case 14: // placas geolocalizadores
                           $listarConcatenas = " ";
                break;     
                 
        case 15: // pechera
                           $listarConcatenas = " ";
                break;     
                 
        case 16: // cama y frazada
                           $listarConcatenas = " ";
                break;     
                 
        case 17: // aromitizantes ambientales
                           $listarConcatenas = " ";
                break;     
                 
     
        }

            
		$query = "

          SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,     
            dd.stockProd,                    
            p.precioPart,            
            p.img_url1 as imgUrl1
			
            
            FROM productos p
            INNER JOIN
            ((
			
			SELECT 	pp.id		 as id,
                    pp.activo,
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
						 AND pe.anulada='N'
                          ),0)						 
			) as stockProd
			FROM productos pp      
			WHERE pp.activo=1
			
			)) dd
             ON dd.id = p.id
             AND dd.activo =1
             
            WHERE p.activo=1 " .$listarConcatenas. " 
            
             ORDER BY p.nombreProd  ASC; ";
            
        
        	            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new ProductoAplicacion();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
    		$tmp->precioPart      = $row->precioPart;
			$tmp->stockProd       = $row->stockProd;
			$tmp->foto            = $row->imgUrl1;

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
         
       
       //  return $result;
		      return ($ret);
	  }
    
    public function listComprasAplicacion($idDispositivo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
         
                   
		$query = "
                SELECT     p.fecha_ingreso,
                           pp.fecha_entrega,
                           p.nombres,
                           p.direccion,
                           pp.estado_pedido,
                           pp.id_pedido,
                           xx.total,
                           p.total as totalDisp,
                           pp.anulada
                        FROM tbl_pedido_dispositivo p 
                        LEFT OUTER JOIN tbl_pedido pp 
                        ON pp.id_pedido = p.id_pedido
                        LEFT OUTER JOIN ((

                            SELECT SUM(dd.cantidad * dd.precio_vendido) as total,
                                   dd.id_pedido
                            FROM tbl_deta_pedido dd                
                            GROUP by dd.id_pedido

                        ))xx 
                        ON xx.id_pedido = pp.id_pedido            
                        WHERE p.devid = 'dd4e55486897d84f'
                        ORDER BY p.id   DESC
         ";
            
        
        	            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new ProductoAplicacion();
            
			$tmp->fecha_ingreso    = $row->fecha_ingreso;			
			$tmp->fecha_entrega    = $row->fecha_entrega;
    		$tmp->nombres          = $row->nombres;
			$tmp->direccion        = $row->direccion;
			$tmp->estado_pedido    = $row->estado_pedido;
			$tmp->id_pedido        = $row->id_pedido;
			$tmp->total            = $row->total;
			$tmp->totalDisp        = $row->totalDisp;
			$tmp->anulada            = $row->anulada;

            
            
            if( $row->id_pedido == ""){
                 $tmp->id_pedido= "SIN ASIGNAR";                
            }else{
                 $tmp->id_pedido =$row->id_pedido;
            }
            
            if( $row->fecha_entrega == ""){
                 $tmp->fecha_entrega= "SIN ASIGNAR";                
            }else{
                 $tmp->fecha_entrega =$row->fecha_entrega;
            }
            
            
             if( $row->total == ""){
                 $tmp->total= $row->totalDisp;                
            }else{
                 $tmp->total =$row->total;
            } 
            
            
           // https://aranibar.cl/wp-content/uploads/2021/06/despachoDisp.png     Despacho
           // https://aranibar.cl/wp-content/uploads/2021/06/gestionadoDisp.png   En Gestion
     
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
         
       
       //  return $result;
		      return ($ret);
	  }
    
    
        public function listProductosCompradosAplicacion($IdDiv) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
      

            
                $query = "SELECT    dd.id_prod,
                                  pp.id_pedido,
                              SUM(dd.cantidad) as stockCompra,
                              ddd.stockCanjear,
                              pr.nombreProd,
                              pr.img_url1 as foto,
                               dddx.id as idcanjeado

                        FROM tbl_pedido_dispositivo pp
                        INNER JOIN tbl_pedido p 
                        ON p.id_pedido = pp.id_pedido
                        INNER JOIN tbl_deta_pedido dd 
                        ON dd.id_pedido = p.id_pedido
                        INNER JOIN productos pr 
                        ON pr.id = dd.id_prod
                        LEFT OUTER JOIN (SELECT
                                         SUM(c.cantidad) as stockCanjear,
                                         c.devid,
                                         c.id_prod
                                    FROM tbl_canjear c
                                    GROUP by c.devid,
                                         c.id_prod) ddd
                                    ON ddd.id_prod = dd.id_prod 
                                    AND ddd.devid ='dd4e55486897d84f'
                                    
                                    
                        LEFT OUTER JOIN (SELECT
                                          c.id,
                                          c.id_prod,
                                          c.devid
                                    FROM tbl_canjear c
                                         WHERE c.id_pedido = 0) dddx
                                    ON dddx.id_prod = dd.id_prod 
                                    AND dddx.devid ='dd4e55486897d84f'
                                               
                                    
                        WHERE pp.devid ='dd4e55486897d84f'
                        AND p.anulada ='N'
                        GROUP BY  dd.id_prod";
            
        
        	            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new ProductoAplicacion();
			$tmp->id_prod    	   = $row->id_prod;			
			$tmp->id_pedido        = $row->id_pedido;
        	$tmp->stockProd        = $row->stockCompra - $row->stockCanjear;
            $tmp->nombreProd       = $row->nombreProd;
            $tmp->foto             = $row->foto;

            
            if($row->idcanjeado > 0){ //si tiene ID es porque esta canjeado
                $tmp->idcanjeado ="mostrar";//NO canjeado
            }else{
                $tmp->idcanjeado ="";//SI canjeado
            }
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
         
       
       //  return $result;
		      return ($ret);
	  }
    
    
    
      public function generarCanjearAplicacion($idProd, 
                                       $DevID) {
        
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);			
		
		

			$query = "INSERT INTO tbl_canjear(id_prod, 
                          fecha_ingreso, 
                          cantidad, 
                          id_pedido, 
                          devid) 
                          VALUES ('".$idProd."',
                                  CURDATE(),
                                   5,
                                   0,
                                   '".$DevID."');";

				$result = mysqli_query($mysql, $query);	
        
            if($result == "1"){
                    return 1;
                }else{
                    return 0;
                }

			
	}
    
    
    
    
     public function listCanjeadosPendientesDispositivo($idDiv, $idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
      

             
                $query =   "SELECT c.id_prod 
                            FROM tbl_canjear c 
                            where c.id_pedido = 0 
                            and c.devid = '".$idDiv."'
                            and c.id_prod in ('".$idProd."')";
            
        
        	            
		$result = mysqli_query($mysql, $query);
       
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new ProductoAplicacion();
			$tmp->id_prod    	   = $row->id_prod;			        
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
         
       
       //  return $result;
		      return ($ret);
	  }
    
    
    
    
    

public function subirImagenRecibo($idRecibos, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");	
     /*  $query = "UPDATE productos p 
                    SET p.codProducto   = '".$objProductos[0]->codProducto."' , 
                        p.nombreProd    = '".$objProductos[0]->nombreProd."' ,
                        p.marcaProd     = '".$objProductos[0]->marcaProd."',
                        p.categoriaProd = '".$objProductos[0]->categoriaProd."',
                        p.bodega        = '".$objProductos[0]->bodegaProd."',
                        p.pesosProd     = '".$objProductos[0]->pesosProd."',
                        p.clasificacion_prod = '".$objProductos[0]->calfProd."',
                        p.id_proveedor       = '".$objProductos[0]->idProv."',
                        p.precioPart         = '".$objProductos[0]->precioPart."',
                        p.cod_barra          = '".$objProductos[0]->cod_barra."',
                        p.fecha_vencimiento  = '".$objProductos[0]->fVencimiento."'
                        
                    WHERE p.id = '".$objProductos[0]->idProd."'
                 ";         */
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/documentosTransferencias';
    
        $f = finfo_open();
        $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        $formato = '';
        if ($mime_type === "image/png") {
            $formato = 'png';
        } else {          
            return 3;
        }
     
        

      //  $resultCSolicitud = mysqli_query($mysql, $query);	    
        
        $archivo = sprintf('%s.%s', $idRecibos, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

        $status = file_put_contents($directory, base64_decode($imagen));
    

        if (!$status) {    
             return $status;
        }
    
    }else{
        
        return 1;
        
        // $resultCSolicitud = mysqli_query($mysql, $query);	    
    }
             /*   if($resultCSolicitud == "1"){
                    return 1;
                }else{
                    return 0;
                }
             mysqli_free_result($resultCSolicitud);
    
         mysqli_close($mysql);*/
    
}
    
    

public function subirImagenReciboFirma($idRecibos, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");	

    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/firmasRecibo';
    
        $f = finfo_open();
        $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        $formato = '';
        if ($mime_type === "image/png") {
            $formato = 'png';
        } else {          
            return 3;
        }
     
        

      //  $resultCSolicitud = mysqli_query($mysql, $query);	    
        
        $archivo = sprintf('%s.%s', $idRecibos, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

        $status = file_put_contents($directory, base64_decode($imagen));
    

        if (!$status) {    
             return $status;
        }
    
    }else{
        
        return 1;
        
        // $resultCSolicitud = mysqli_query($mysql, $query);	    
    }
             /*   if($resultCSolicitud == "1"){
                    return 1;
                }else{
                    return 0;
                }
             mysqli_free_result($resultCSolicitud);
    
         mysqli_close($mysql);*/
    
}    
    
    
    
    
	
} 		
?>