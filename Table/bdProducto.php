<?php
require_once('Bean/Producto.php');
require_once('Bean/Tipo.php');
require_once('Bean/Marca.php');
require_once('Bean/Precio.php');
require_once('Bean/ProductoAplicacion.php');



class bdProducto{
	
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
	
	 public function getDataTipos() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT codCategoria,nombreCategoria, grupo FROM categoria";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Tipo();
			$tmp->codCategoria = $row->codCategoria;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->grupo = $row->grupo;
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
	

	 public function getDataMarca($idTipo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT codMarca, 
                       nombreMarca 
                       FROM marca 
                       WHERE codCategoria='".$idTipo."' 
                       and activo=1 
                       ORDER BY nombreMarca";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Marca();
			$tmp->codMarca = $row->codMarca;
			$tmp->nombreMarca = $row->nombreMarca;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
	
	
 	 public function insertarMarca($tipo,$nombMarca) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
		
		$query = "INSERT INTO marca(codCategoria, nombreMarca) VALUES ('".$tipo."' , '".$nombMarca."')";		
		$result = mysqli_query($mysql, $query);		
	

	return "Datos Guardados";
	}
	
	 public function insertarProducto($id,$codProducto,$nombreProd,$marcaProd,$categoriaProd,  $precioCosto, $stockProd,$accionProducto,$ObservacionProd,$precioVenta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);

		$total = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM productos WHERE codProducto='".$codProducto."'"));

		switch ($accionProducto){
		case "M":			
				$query = "UPDATE productos 
				SET codProducto ='".$codProducto."', 
				nombreProd  ='".$nombreProd."', 
				marcaProd	='".$marcaProd."', 
				categoriaProd ='".$categoriaProd."',
				precioCosto ='".$precioCosto."',
				stockProd     ='".$stockProd."',
				ObservacionProd ='".$ObservacionProd."',
				precioVenta ='".$precioVenta."'
				WHERE codProducto='".$codProducto."'";		
				
				$result = mysqli_query($mysql, $query);
				return "0";					
		break;

		case "A":
			if($total==0){
				$query = "INSERT INTO productos(codProducto, nombreProd, marcaProd, categoriaProd, precioCosto, stockProd, ObservacionProd) VALUES ('".$codProducto."','".$nombreProd."','".$marcaProd."','".$categoriaProd."','".$precioCosto."','".$stockProd."','".$ObservacionProd."')";		
				$result = mysqli_query($mysql, $query);
				return "0";		
			}else{
				return "1";
			}
		break;
		
		case "E":
				$query = "DELETE FROM productos WHERE codProducto='".$codProducto."'";		
				$result = mysqli_query($mysql, $query);
				return "Producto fue eliminado.";				
		break;
}
		
	
		
		
	}
	
	public function listarProductos($codProducto, $nombreProd, $marcaProd,$categoriaProd/*, $tipoBusq*/) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
        
        
        
        $query = "
			SELECT 
           /* p.cod_barra,*/

               (SELECT
          GROUP_CONCAT(n.codigo_barra SEPARATOR ',') AS nota
          FROM tbl_codigo_barra n
          where n.id_prod= p.id) as cod_barra,


			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			/*p.foto,*/
			
           /* p.precioVenta as precioVenta,*/
            
            IFNULL(f.precio_venta, p.precioVenta ) as precioVenta,          
          
            f.id    as idPrecioFactura,            
            
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             p.porcentaje,
             p.prod_venta_act,
             p.precioPart,
             p.bodega,
             p.img_url1 as imgUrl1,
             p.pesosProd,
             p.nodo_prod,
             aa.id_prod as countDesc
             
			FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
            LEFT OUTER JOIN (SELECT j.id_prod, count(j.id) FROM tbl_descuento_venta j  GROUP BY j.id_prod) aa
            ON aa.id_prod = p.id
            
			WHERE 
                p.marcaProd        = '".$marcaProd."'
            AND p.categoriaProd    = '".$categoriaProd."'
            AND p.codProducto LIKE '%".$codProducto."%'
            AND p.nombreProd LIKE  '%".$nombreProd."%'
            AND p.activo = 1

            
            ORDER BY p.nombreProd
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
            
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->idPrecioFactura = $row->idPrecioFactura;
			$tmp->porcentaje      = $row->porcentaje;
			$tmp->bodega      = $row->bodega;
			$tmp->cod_barra      = $row->cod_barra;
			$tmp->countDesc      = $row->countDesc;

            
            
            
			$tmp->prod_venta_act  = $row->prod_venta_act;
                          
            
//			$tmp->precioVenta     = $row->precioVenta;

            $tmp->precioVenta  = round($row->precioVenta - round($row->precioVenta * ($row->porcentaje/100)));
            
  			//$tmp->precioPart      = $row->precioPart;
                                     
            $tmp->precioPart  = round($row->precioPart - round($row->precioPart * ($row->porcentaje/100)));
                         
          
            $tmp->precioDifPr      = round((($tmp->precioPart )- round(($tmp->precioVenta * 0.19)+$tmp->precioVenta))/1.19);
            
              
            $porcionesImg = explode(",", $row->imgUrl1);
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
            
            
            $ramdom =  mt_rand();
            $tmp->foto  = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
          /*  $file = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png"; 
            $file_headers = @get_headers($file); 
            
            if(!$file_headers || $file_headers[0]  == 'HTTP/1.1 404 Not Found') 
            {
                 $tmp->foto = $porcionesImg[0];
            } else 
            { 
                
            }*/

            
            
      			$tmp->pesosProd = $row->pesosProd;
      			$tmp->nodo_prod = $row->nodo_prod;


 
       
            
            
            
		/*	$tmp->foto            = $row->foto;*/
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
	
	
    
    
    	public function listProductosTotal($codProducto, $nombreProd, $marcaProd, $categoriaProd, $bodega, $clasif, $clasifFiltro, $nombreProdBusq, $codigoProdBusq, $tipoBusqueda) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
        $busq = "";     
        $length = strlen(utf8_decode($codigoProdBusq));    
            
        if($tipoBusqueda == "1"){
               $busq = "

            WHERE p.marcaProd   = '".$marcaProd."'                            
            AND p.categoriaProd = '".$categoriaProd."'                            
            ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
                        ";
            
        }else if($tipoBusqueda == "2"){
             $busq = "
             WHERE p.id_proveedor  = '".$codProducto."'
             ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
            ";
        }else if($tipoBusqueda == "3"){
            
              $busq = " WHERE p.bodega = '".$bodega."'
                ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
          ";   
        }else if($tipoBusqueda == "4"){
            
              $busq = " ";   
        }else if($tipoBusqueda == "5"){
            
              $busq = "
               WHERE p.nombreProd like '%".$nombreProdBusq."%'
               ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
              ";   
        }else if($tipoBusqueda == "6"){
            
                       
        if($length < 5){
             $busq=" 
             WHERE
             p.id  = '".$codigoProdBusq."'
             ";
        }else{
            /* $busq=" 
             WHERE
             p.cod_barra = '".$codigoProdBusq."'";*/
            
            
                   $sqlC = "SELECT c.id_prod as idProd FROM tbl_codigo_barra c where c.codigo_barra='".$codigoProdBusq."' GROUP BY c.id_prod";    
            
		$resultX = mysqli_query($mysql, $sqlC);
        $filaCodProd = mysqli_fetch_assoc($resultX);
        $idprodCod =   $filaCodProd["idProd"] ;
            
        /*  if($idprodCod == "" || $idprodCod == null ) {
               $busq=" p.cod_barra = '".$idProd."'  ";
          } else{
              $busq=" p.id  = '".$idprodCod."' ";              
          }*/
            
            $busq=" WHERE
             p.id  = '".$idprodCod."' ";        
            
        }        
           
            
            
        }
            
/*
            
            
if($clasifFiltro == "0" || $clasifFiltro == ""){  
            
if($clasif == 1){            
            
         if($bodega == ""){      
                if($codProducto != ""){    

                     if($marcaProd != "" || $categoriaProd !=""){
                           $busq = "

                             WHERE p.marcaProd   = '".$marcaProd."'                            
                             AND p.categoriaProd = '".$categoriaProd."'                            
                             AND p.id_proveedor  = '".$codProducto."'
                             ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;

                           ";
                        }else{

                           if($codProducto != ""){    

                                     $busq = "
                                    WHERE pr.id_proveedor = '".$codProducto."'                                    
                                    ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
                                   ";
                           }
                        }    



                }else{

                      if($marcaProd != ""){ 
                           $busq = "

                            WHERE p.marcaProd = '".$marcaProd."'
                           AND p.categoriaProd = '".$categoriaProd."'
                           ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
                           
                           ";
                        } else {
                          $busq = "

                            WHERE p.categoriaProd = '".$categoriaProd."'
                           ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
                           
                           ";
                          
                      }

                }
        }else{

          $busq = " WHERE p.bodega = '".$bodega."'

           ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
          ";     
         }
            
}else{
    
 $busq = " WHERE p.bodega = '".$bodega."'
  
  ORDER BY p.clasificacion_prod DESC;
 
 ";     
  
    
} 
    
    
    
    
}else{
    
    if($bodega == ""){
    
    $busq = "

                            WHERE p.clasificacion_prod   = '".$clasifFiltro."'
                             ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;

                           ";
    }else{
          $busq = "

                            WHERE p.clasificacion_prod   = '".$clasifFiltro."'
                              AND p.bodega = '".$bodega."'
                             ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;

                           ";
        
        
        
    }    
        
    
}            
   */
            
            
            
            
		$query = "
			SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            
       /*     IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,*/
             
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			p.precioVenta as precioVenta,
            
       /*     (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,*/
             
            m.cod_proveedor,
            
            	IFNULL(( SELECT SUM(d.cantidad) 
                     FROM tbl_pedido pe 
                     INNER JOIN tbl_deta_pedido d 
                     ON d.id_pedido = pe.id_pedido 
                     WHERE d.id_prod=p.id 
                     AND pe.anulada='N'
                    AND pe.id_usuario  NOT IN (3) 
                    AND pe.id_cliente NOT IN (87,162) 
                    ),0) as salidasProdRanking,
                    
            IFNULL(f.precio_venta, p.precioVenta ) as precioMasc,     
            p.precioPart,

            pr.nombre as nombreProv,
			f.diferenciaPrecio,
            p.porcentaje,
            
            p.pesosProd,
            p.bodega,
            cl.id as clasId,
            cl.nombre as clasProd,
            p.id_proveedor,
         /*   (SELECT CASE WHEN pp.foto IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM productos pp WHERE pp.id=p.id) as estadoFoto,*/
           /* p.cod_barra,*/
         /*  cb.codigo_barra as cod_barra,*/
           
           (SELECT
          GROUP_CONCAT(n.codigo_barra SEPARATOR ',') AS nota
          FROM tbl_codigo_barra n
          where n.id_prod= p.id) as cod_barra,
           
           
           
            p.fecha_vencimiento,
            p.img_url1 as imgUrl1,
            p.activo,
            p.nodo_prod,
            p.grupo_prod,
            
            (SELECT CONCAT(pp.id, ' - ', pp.nombreProd)  
            FROM productos pp 
            WHERE pp.id = p.nodo_prod) as nodo_nombre_prod,
             
             p.activo_tienda
            
			
			
            
            FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_proveedores pr             
            ON pr.id_proveedor = p.id_proveedor
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
            LEFT OUTER JOIN tbl_clasificacion_productos cl
            ON cl.id = p.clasificacion_prod
            
            
            /*LEFT OUTER JOIN tbl_codigo_barra cb
            ON cb.id_prod = p.id*/
            

		"
          .
            $busq
          .    
        "    
	
           
		";
            
        
        	/*	AND p.codProducto LIKE '%".$codProducto."%'
			AND p.nombreProd LIKE '%".$nombreProd."%'*/    
            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
            
            
            $retStockProd  =   $this->consultarInventarioTiendas($row->id);
            
            
            $tmp->stockTiendas = $retStockProd;
            
            
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->precioVenta     = $row->precioVenta;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->cod_proveedor   = $row->cod_proveedor;
            
			/*$tmp->countStock     = $row->stockProd - $row->salidasProd; */ 
            
/*             			$tmp->stockProd       = $row->stockProd;*/

            $tmp->precioMasc     = $row->precioMasc;
			$tmp->precioPart     = $row->precioPart;
			$tmp->nombreProv     = $row->nombreProv;
			
			if($row->diferenciaPrecio < 0){				
				$tmp->estadoPrecio = 'flechaAbajo';
				$tmp->diferenciaPrecio = -($row->diferenciaPrecio);
			}else{
				
				if($row->diferenciaPrecio == 0){					
				 $tmp->estadoPrecio = '';
				 $tmp->diferenciaPrecio = '';					
				}else{
				 $tmp->estadoPrecio = 'flechaArriba';
				 $tmp->diferenciaPrecio = $row->diferenciaPrecio;
				}
			
			}
            //$tmp->activo_obs       = $row->activo_obs;
			//$tmp->cantidad_obs     = $row->cantidad_obs;
			$tmp->porcentaje       = $row->porcentaje;
			$tmp->pesosProd        = $row->pesosProd;
			$tmp->bodega           = $row->bodega;
			$tmp->clasId           = $row->clasId;
			$tmp->clasProd         = $row->clasProd;
			$tmp->id_proveedor     = $row->id_proveedor;
			//$tmp->foto             = $row->foto;
 			$tmp->cod_barra                = $row->cod_barra;
        //    $tmp->estadoFoto        = $row->estadoFoto;
                      $tmp->activo        = $row->activo;
      
            
            $porcionesImg = explode(",", $row->imgUrl1);
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
            $tmp->nodo_nombre_prod        =  $row->nodo_nombre_prod;
          
            
            
            
             $ramdom =  mt_rand();
             $file  = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 

            
  
            $image_path = $file;

            $file_headers = @get_headers($image_path);
            //Prints the response out in an array
            //print_r($file_headers); 

            
             $tmp->foto = $file;/*
            
            if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
                 $tmp->foto = $porcionesImg[0];
            }else{
                 $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png"; 
            }*/

            
            
            
            $tmp->nodo_prod       = $row->nodo_prod;
            $tmp->grupo_prod       = $row->grupo_prod;

            
            	
            
            
           if($row->fecha_vencimiento=='0000-00-00'){
                  $tmp->fecha_vencimiento      = '';
            }else{
                  $tmp->fecha_vencimiento      = $row->fecha_vencimiento;   
            }
                  $tmp->activo_tienda       = $row->activo_tienda;

			$ret[] = $tmp;
		}
		mysqli_free_result($result) ;
		      return ($ret);
	  }
    
    

    
    
     public function listPreciosFactura($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "      SELECT p.nombreProd,
                        f.id_ingresos,
                        f.activo,
                        f.precio_venta,
                        f.cantidad,
                        f.valor_neto,
                        f.descuento,
                        g.fecha,
                        f.id as idFactura,
                      
                        IFNULL((select SUM(d.cantidad) 
                         from tbl_deta_pedido d 
                         where d.id_prod= p.id
                         and d.id_precio_factura= f.id),0) as salidaFact,
                         p.id as idProd,
                         o.num_factura,
                         pr.nombre
                         
                        FROM productos p
                        INNER JOIN tbl_precio_factura f 
                        ON f.id_prod = p.id
                        INNER JOIN tbl_ingresos g
                        ON g.id = f.id_ingresos   
                        INNER JOIN tbl_factura_ingresos o 
                        ON o.id_ingresos = g.id
                        INNER JOIN tbl_proveedores pr 
                        ON pr.id_proveedor = g.id_proveedor
                        WHERE p.id = '".$idProd."'
                        ORDER BY p.nombreProd;";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Precio();
			$tmp->nombreProd   = $row->nombreProd;
			$tmp->id_ingresos  = $row->id_ingresos;
			$tmp->activo       = $row->activo;
			$tmp->precio_venta = $row->precio_venta;
			$tmp->cantidad     = $row->cantidad;
			$tmp->valor_neto   = $row->valor_neto;
			$tmp->descuento    = $row->descuento;
			$tmp->fecha        = $row->fecha;
			$tmp->idFactura    = $row->idFactura;
			$tmp->salidaFact   = $row->salidaFact;
			$tmp->idProd       = $row->idProd;
			$tmp->num_factura  = $row->num_factura;
			$tmp->nombre       = $row->nombre;

          if($row->activo=='0'){
                  $tmp->activo      = 'false';
            }else{
                  $tmp->activo      = 'true';
            }
            
            
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
	 public function guardarPrecioFact($objPre) {
        
         
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
         
        $query = "UPDATE tbl_precio_factura f
                  SET f.activo = '0' 
                  WHERE f.id_prod = '".$objPre[0]->idProd."';
                 ";
		$result = mysqli_query($mysql, $query);
         
         
         
		$query = "UPDATE tbl_precio_factura f
                  SET f.descuento  = '".$objPre[0]->desc."'
                  , f.valor_neto   = '".$objPre[0]->valorNet."'
                  , f.precio_venta = '".$objPre[0]->precVenta."'
                  , f.cantidad     = '".$objPre[0]->canti."'
                  , f.activo       = '1'
                  WHERE f.id = '".$objPre[0]->idFac."';
                 ";
		$result = mysqli_query($mysql, $query);
	
		  if($result == "1"){
                return 1;
            }else{
                return 0;
            }
	}
    
    
    
public function listarProductosInventario($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
        $length = strlen(utf8_decode($idProd)); 
 
    
    
    

    
    
    
    
                 
        if($length < 5){
             $tipBus=" p.id  = '".$idProd."'";
        }else{
                    
            
                    $sqlC = "SELECT c.id_prod as idProd FROM tbl_codigo_barra c where c.codigo_barra='".$idProd."' GROUP BY c.id_prod";    
            
                    $resultX = mysqli_query($mysql, $sqlC);
                    $filaCodProd = mysqli_fetch_assoc($resultX);
                    $idprodCod =   $filaCodProd["idProd"] ;
            

            
            $tipBus=" p.id  = '".$idprodCod."' ";      
            
            
            
            
            
            
            
            
            
            
        }        
            

		$query = "
			SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
            
            IFNULL(f.precio_venta, p.precioVenta ) as precioVenta,
            
            p.precioPart,

            
            f.id    as idPrecioFactura,
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             p.cod_barra,
             
             
                ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE " 
                        .
                              $tipBus
                        .
                      "
                AND pc.estado_pedido = 2
                AND pc.anulada='N') as cantProdConObs,
             
             
             ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE " 
                        .
                              $tipBus
                        .
                      "
                AND pc.estado_pedido = 3
                AND pc.anulada='N') as cantProdPendiente,
                
                
                ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE " 
                        .
                              $tipBus
                        .
                      "
                AND pc.estado_pedido = 4
                AND pc.anulada='N') as cantProdEnDesp,
                
                
             
                
                  (SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE " 
                        .
                              $tipBus
                        .
                      "
                AND pc.estado_pedido = 5
                AND pc.anulada='N') as cantProdEnBodega,                
                
                
                 ( SELECT sum(d.cantidad) as pendiente
                FROM tbl_deta_pedido d 
                LEFT OUTER JOIN tbl_pedido pc 
                ON pc.id_pedido = d.id_pedido
                LEFT OUTER JOIN productos p 
                ON p.id = d.id_prod 
                WHERE " 
                        .
                              $tipBus
                        .
                      "
                AND pc.estado_pedido = 6
                AND pc.anulada='N') as cantProdRechazado,
                p.bodega,
                fd.fecha_ingreso as f_inventario,
                fd.id_tipo_inventario
                

			FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
            LEFT OUTER JOIN (
            
       SELECT tt.id_prod,
                  tt.fecha_ingreso,
                  tt.id_tipo_inventario
            FROM tbl_inventario tt
            INNER JOIN
                (SELECT id_prod, MAX(fecha_ingreso) AS MaxDateTime
                FROM tbl_inventario
                 WHERE anulado = 0
                GROUP BY id_prod) groupedtt 
            ON tt.id_prod = groupedtt.id_prod
            AND tt.fecha_ingreso = groupedtt.MaxDateTime
            GROUP BY tt.id_prod
            ) fd
            ON fd.id_prod = p.id
            
            
            
			WHERE"
             .
               $tipBus
             .      
            " ORDER BY p.nombreProd
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->precioVenta     = $row->precioVenta;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->idPrecioFactura = $row->idPrecioFactura;
			$tmp->cod_barra       = $row->cod_barra;

			$tmp->precioPart      = $row->precioPart;

            
			$tmp->cantProdConObs         = $row->cantProdConObs;
			$tmp->cantProdPendiente      = $row->cantProdPendiente;
			$tmp->cantProdEnDesp         = $row->cantProdEnDesp;
			$tmp->cantProdEnBodega       = $row->cantProdEnBodega;
			$tmp->cantProdRechazado      = $row->cantProdRechazado;
        	$tmp->bodega                 = $row->bodega;
        	$tmp->f_inventario                 = $row->f_inventario;
        	$tmp->id_tipo_inventario                 = $row->id_tipo_inventario;

            
            
            $ramdom =  mt_rand();

            $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
}
    
    
    
    
    
    
public function anularInventario($id_asociado, $id_tipo_inv, $id_invent) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
    
    
      if($id_tipo_inv == 2){
          
          $query = "UPDATE tbl_pedido p
                  SET p.anulada='S',
                  p.observacion = 'Anulado desde Administracion Inventario Bodega'
                  WHERE p.id_pedido='".$id_asociado."';
        ";		
		$result = mysqli_query($mysql, $query);	
          
           
      $query = "UPDATE tbl_inventario  p 
                SET p.anulado = 1 
                WHERE p.id_asociado=
                '".$id_asociado."
                  ';
        ";		
		$result = mysqli_query($mysql, $query);	
      }
    
       if($id_tipo_inv == 1){
          
          $query = "UPDATE tbl_ingresos p 
                    SET p.activo = 0,
                    p.observacion='Anulado desde Administracion Inventario Bodega'
                    WHERE p.id='".$id_asociado."
                  ';
        ";		
		$result = mysqli_query($mysql, $query);	
           
            
      $query = "UPDATE tbl_inventario  p 
                SET p.anulado = 1 
                WHERE p.id_asociado=
                '".$id_asociado."
                  ';
        ";		
		$result = mysqli_query($mysql, $query);	
          
      }
			
   
           if($id_tipo_inv == 0){
                         
            $query = "UPDATE tbl_inventario  p 
                SET p.anulado = 1 
                WHERE p.id=
                '".$id_invent."
                  ';
        ";		
		$result = mysqli_query($mysql, $query);	
               
           }

			
	
	return 1;
     mysqli_close($mysql);
}   
    
    
 
     public function listarInventarioBodega($fechaListar, $idProducto) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
         
    if($idProducto == ""){     
		$query = " SELECT
                        i.id,
                        i.id_prod,
                        p.nombreProd,
                        i.fecha_ingreso,
                        i.id_tipo_inventario,
                        i.id_asociado,
                        i.cantidad,
                        i.cantidad_ingresada,
                        i.anulado,
                        i.observacion
                        FROM tbl_inventario i 
                        INNER JOIN productos p
                        ON p.id = i.id_prod
                        WHERE i.fecha_ingreso='".$fechaListar."'";
    }else{
        
        $query = " SELECT
                        i.id,
                        i.id_prod,
                        p.nombreProd,
                        i.fecha_ingreso,
                        i.id_tipo_inventario,
                        i.id_asociado,
                        i.cantidad,
                        i.cantidad_ingresada,
                        i.anulado,
                        i.observacion
                        FROM tbl_inventario i 
                        INNER JOIN productos p
                        ON p.id = i.id_prod
                        WHERE i.id_prod = '".$idProducto."' 
                              ORDER BY i.fecha_ingreso DESC";
    }
         
         
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id                     = $row->id;
			$tmp->id_prod                = $row->id_prod;
			$tmp->nombreProd             = $row->nombreProd;
			$tmp->fecha_ingreso          = $row->fecha_ingreso;
			$tmp->id_tipo_inventario     = $row->id_tipo_inventario;
			$tmp->id_asociado            = $row->id_asociado;
			$tmp->cantidad               = $row->cantidad;
			$tmp->cantidad_ingresada     = $row->cantidad_ingresada;
			$tmp->anulado                = $row->anulado;
			$tmp->observacion            = $row->observacion;


            
            
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}    
    
    
    
  public function ingresarInventario($arrSal, $arrIng, $arrCua) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");  
      
      /**********/
      
    $activoSal = mysqli_num_rows(mysqli_query($mysql, " SELECT * FROM tbl_inventario p where p.id_prod='".$arrSal[0]->idProd."'  and p.fecha_ingreso = CURDATE() and p.anulado=0"));    
    $activoIng = mysqli_num_rows(mysqli_query($mysql, " SELECT * FROM tbl_inventario p where p.id_prod='".$arrIng[0]->idProd."'  and p.fecha_ingreso = CURDATE() and p.anulado=0"));    
    $activoCua = mysqli_num_rows(mysqli_query($mysql, " SELECT * FROM tbl_inventario p where p.id_prod='".$arrCua[0]->idProd."'  and p.fecha_ingreso = CURDATE() and p.anulado=0"));    
          
      
      if($activoSal==0 && $activoIng ==0 && $activoCua == 0  ){
      
      /**********/
      
      $arrSal_length = count($arrSal);       
      $arrIng_length = count($arrIng); 
      $arrCua_length = count($arrCua); 
          

       if($arrSal_length != 0){
    
        $query1 = "INSERT INTO tbl_pedido(fecha, 
                                          id_usuario, 
                                          id_cliente, 
                                          anulada,
                                          estado_cobranza,
                                          estado_pedido,
                                          observacion,
                                          fecha_entrega) 
         VALUES (CURDATE() , 
                 '3',
                 '162', 
                 'N',
                 '4',
                 '1',
                 'Descuento desde la aplicacion inventario',
                 CURDATE());";		
           
       $resultCabeSal = mysqli_query($mysql, $query1);	
       $id_ped = mysqli_insert_id($mysql);
           
       }
      
      if($arrIng_length != 0){
           
           
       $query2 = "INSERT INTO tbl_ingresos(fecha, 
                                          id_proveedor,
										  activo,
                                          observacion) 
                 VALUES (CURDATE() , 
                         '14',
						   1,
                          'Aumento desde la aplicacion inventario');";	
      
       $resultIngreso = mysqli_query($mysql, $query2);	
       $id_ingr = mysqli_insert_id($mysql);
          
         
      }
           
      /**************INGRESAR SALIDA****************/     
           
    foreach($arrSal as $detalle){

        $query3 = "INSERT INTO 
                 tbl_deta_pedido(id_pedido, 
                                   id_prod, 
                                   cantidad, 
                                   precio_vendido, 
                                   descuento, 
                                   aumento,  
                                   total,
                                   id_precio_factura) 
                 VALUES('".$id_ped."', 
                        '".$detalle->idProd."', 
                        '".$detalle->cantidad."', 
                        '0',
                        '0', 
                        '0', 
                        '0',
                        ''
                        );";  
      $resultDetaSal = mysqli_query($mysql, $query3);	
        
          
      $queryInventario ="INSERT INTO 
                        tbl_inventario(    
                            fecha_ingreso, 
                            id_prod, 
                            cantidad, 
                            id_tipo_inventario,
                            id_asociado,
                            cantidad_ingresada)

                        VALUES (
                        CURDATE(),
                         '".$detalle->idProd."',
                         '".$detalle->cantidad."',
                         '2',
                         '".$id_ped."',
                         '".$detalle->cantIngresada."'
                        )";
       $resultDetaSal = mysqli_query($mysql, $queryInventario);	


   /*    $query4 = "UPDATE productos p
                    SET p.bodega='".$detalle->codBodega."'
                    WHERE p.id='".$detalle->idProd."';"; 
        
        $resultDetaSalProd = mysqli_query($mysql, $query4);	*/

   
    }
      
      
    foreach($arrIng as $detalle){
                            $query5 = "INSERT INTO 
                                     tbl_ingresos_deta(id_ingresos, 
                                                       id_prod, 
                                                       cantidad ) 
                                     VALUES('".$id_ingr."', 
                                            '".$detalle->idProd."' , 
                                            '".$detalle->cantidad."');";

                            $resultDetaIng = mysqli_query($mysql, $query5);	        

                              $queryInventario ="INSERT INTO 
                                        tbl_inventario(    
                                            fecha_ingreso, 
                                            id_prod, 
                                            cantidad, 
                                            id_tipo_inventario,
                                            id_asociado,
                                            cantidad_ingresada)

                                        VALUES (
                                        CURDATE(),
                                         '".$detalle->idProd."',
                                         '".$detalle->cantidad."',
                                         '1',
                                         '".$id_ingr."',
                                         '".$detalle->cantIngresada."'
                                        )";
                             $resultDetaSal = mysqli_query($mysql, $queryInventario);	


                     /*       $query6 = "UPDATE productos p
                                        SET p.bodega='".$detalle->codBodega."'
                                        WHERE p.id='".$detalle->idProd."';";  

                            $resultDetaProd = mysqli_query($mysql, $query6);	*/
    }
          
          
          
          
        foreach($arrCua as $detalle){
 
                              $queryInventario ="INSERT INTO 
                                        tbl_inventario(    
                                            fecha_ingreso, 
                                            id_prod, 
                                            cantidad, 
                                            id_tipo_inventario,
                                            id_asociado,
                                            cantidad_ingresada)

                                        VALUES (
                                        CURDATE(),
                                         '".$detalle->idProd."',
                                         '".$detalle->cantidad."',
                                         '0',
                                         '0',
                                         '".$detalle->cantIngresada."'
                                        )";
                             $resultDetaSal = mysqli_query($mysql, $queryInventario);	


                     /*       $query6 = "UPDATE productos p
                                        SET p.bodega='".$detalle->codBodega."'
                                        WHERE p.id='".$detalle->idProd."';";  

                            $resultDetaProd = mysqli_query($mysql, $query6);	*/
    }      
          
      

       return 1;   
          
      }else{
          
       return 0;   

          
      }
          
          
        
  }
    
      public function openFileCoverPage($name) {
          $PRODUCT_IMG = __DIR__ .'/img';
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $name);
        $img = file_get_contents($directory);
        return base64_encode($img);
    }
        
        
public function saveProductoModArchivo($idProd, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/img';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'png';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idProd, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));

        
        if (!$status) {    
             return 2;
        }else{
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}     
    
    
    
  
        
public function subirArchivoRuta($idInforme, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/rutas';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'png';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idInforme, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));
echo $status;
        
        if (!$status) {    
             return 2;
        }else{
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}  
    
    
    
public function subirArchivoCamion($idInforme, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/subirArchivoCamion';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'png';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idInforme, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));
echo $status;
        
        if (!$status) {    
             return 2;
        }else{
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}      
    
    
public function subirArchivoFolio($idInforme, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    

    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/imagenFolio';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'jpeg';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idInforme, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));
echo $status;
        
        if (!$status) {    
             return 2;
        }else{
            

            $filename =$PRODUCT_IMG."/".$idInforme.".jpeg";
            $new_filename =$PRODUCT_IMG."/".$idInforme.".png";
            $quality =0;
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                   $image = imagecreatefromjpeg($filename);
                break;
                case 'gif':
                   $image = imagecreatefromgif($filename);
                break;
                case 'png':
                   $image = imagecreatefrompng($filename);
                break;
            }

            imagepng($image, $new_filename, $quality);
            
            
            
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}       
    
    
    
    
     
public function subirArchivoTalonario($idInforme, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/documentosTransferencias';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'png';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idInforme, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));
echo $status;
        
        if (!$status) {    
             return 2;
        }else{
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}   
           
        
 public function subirArchivoCaja($idInforme, $imagen) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		

    
    
    if($imagen != ""){
       
        $PRODUCT_IMG = __DIR__ .'/cierresCajas';

        $f = finfo_open();
    //    $mime_type = finfo_buffer($f, base64_decode($this->openFileCoverPage($imagen)), FILEINFO_MIME_TYPE);
           $mime_type = finfo_buffer($f, base64_decode($imagen), FILEINFO_MIME_TYPE);
        

        $formato = '';
        if ($mime_type === "image/jpeg" || $mime_type === "image/png"  ||  $mime_type === "image/webp"  ) {
            $formato = 'png';
        } else {          
            return 3;
        }
    

        
        $archivo = sprintf('%s.%s', $idInforme, $formato);
        $directory = sprintf('%s/%s', $PRODUCT_IMG, $archivo);

   //  $status = file_put_contents($directory, base64_decode($this->openFileCoverPage($imagen)));
    
     $status = file_put_contents($directory, base64_decode($imagen));
        
echo $status;
        
        if (!$status) {    
             return 2;
        }else{
            return 1;
        }
    
    }else{
        
        return 0;
    }

    
}          
        
    
    
    
    
    
    
        
    
    
    
    
    
   /*
public function saveProductoMod($objProductos) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);   
        mysqli_query($mysql, "SET NAMES 'utf8'");    
        $query = "UPDATE productos p 
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
                 ";         
           if(mysqli_query($mysql, $query)){
               return 1;
            }else{
               return 0;
            } 
}*/
    
 public function saveProductoMod($objProductos) {
            
        $arrayBaseDatos[0] = "aranibar_aranibar";
        $arrayBaseDatos[1] = "aranibar_santa_maria";
        $arrayBaseDatos[2] = "aranibar_tucapel";
  
for($i=0;$i<count($arrayBaseDatos);$i++) {      
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
        mysqli_query($mysql, "SET NAMES 'utf8'");		

      
      
       $query = "UPDATE productos p 
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
                        p.fecha_vencimiento  = '".$objProductos[0]->fVencimiento."',
                        p.grupo_prod         = '".$objProductos[0]->grupoProd."',
                        p.nodo_prod          = '".$objProductos[0]->nodoProd."'                        
                    WHERE p.id = '".$objProductos[0]->idProd."'
                 ";         
    
    
    
     $resultCSolicitud = mysqli_query($mysql, $query);	 
    
}
    
                if($resultCSolicitud == "1"){
                    return 1;
                }else{
                    return 0;
                }
             mysqli_free_result($resultCSolicitud);
    
         mysqli_close($mysql);
    
}    
    
public function saveProductoModFoto($objProductos) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);   
        mysqli_query($mysql, "SET NAMES 'utf8'");    
        $query = "UPDATE productos p 
                    SET 
                        p.foto               = '".$objProductos[0]->foto."'
                        
                    WHERE p.id = '".$objProductos[0]->idProd."'
                 ";         
           if(mysqli_query($mysql, $query)){
               return 1;
            }else{
               return 0;
            } 
}    
    
    
public function listarProductosCajas($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        $length = strlen(utf8_decode($idProd)); 

                 
        if($length < 5){
             $tipBus=" p.id  = '".$idProd."'";
        }else{
            
        $sqlC = "SELECT c.id_prod as idProd FROM tbl_codigo_barra_extra c where c.codigo_barra='".$idProd."' GROUP BY c.id_prod";
		$resultX = mysqli_query($mysql, $sqlC);
        $filaCodProd = mysqli_fetch_assoc($resultX);
        $idprodCod =   $filaCodProd["idProd"] ;
            
          if($idprodCod == "" || $idprodCod == null ) {
               $tipBus=" p.cod_barra = '".$idProd."'";
          } else{
              $tipBus=" p.id  = '".$idprodCod."'";              
          }
            

        }    
            
    
    
    
		$query = "
				SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			/*p.foto,*/
			
           /* p.precioVenta as precioVenta,*/
            
            IFNULL(f.precio_venta, p.precioVenta ) as precioVenta,          
          
            f.id    as idPrecioFactura,            
            
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             p.porcentaje,
             p.prod_venta_act,
             p.precioPart,
              p.img_url1 as imgUrl1
			FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
			WHERE ".
            
            $tipBus
            
            .
             
            "
            ORDER BY p.nombreProd
		";
    
    
    
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->idPrecioFactura = $row->idPrecioFactura;
			$tmp->cod_barra       = $row->cod_barra;
		
            
            $tmp->precioPart      = $row->precioPart;
          
            //if($row->porcentaje!= 0){
                 $tmp->precioVenta  = round($row->precioVenta - round($row->precioVenta * ($row->porcentaje/100)));
                 $tmp->precioPart      = $row->precioPart;                                     
                 $tmp->precioPart  = round($row->precioPart - round($row->precioPart * ($row->porcentaje/100)));        
                 $tmp->precioDifPr      = round((($tmp->precioPart )- round(($tmp->precioVenta * 0.19)+$tmp->precioVenta))/1.19);
            //}else{
               // $tmp->precioVenta  = "";
            //}
           
            

            
            
            $tmp->porcentaje      = $row->porcentaje;

            
                  $porcionesImg = explode(",", $row->imgUrl1);
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
            $tmp->stock          = round($row->stockProd - $row->salidasProd);
            
            $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}    
    
    
    
    
public function listarProductosCajasTienda($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
    
        $length = strlen(utf8_decode($idProd)); 

                 
        if($length < 5){
             $tipBus=" p.id  = '".$idProd."' ";
            
            
        }else{
            
       // $sqlC = "SELECT c.id_prod as idProd FROM tbl_codigo_barra_extra c where c.codigo_barra='".$idProd."' GROUP BY c.id_prod";
            
        $sqlC = "SELECT c.id_prod as idProd FROM tbl_codigo_barra c where c.codigo_barra='".$idProd."' GROUP BY c.id_prod";    
            
		$resultX = mysqli_query($mysql, $sqlC);
        $filaCodProd = mysqli_fetch_assoc($resultX);
        $idprodCod =   $filaCodProd["idProd"] ;
            
          if($idprodCod == "" || $idprodCod == null ) {
               $tipBus=" p.cod_barra = '".$idProd."'  ";
          } else{
              $tipBus=" p.id  = '".$idprodCod."' ";              
          }
            

        }    
            
    
    
    
		$query = "
				SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			/*p.foto,*/
			
           /* p.precioVenta as precioVenta,*/
            
       /*     IFNULL(f.precio_venta, p.precioVenta ) as precioVenta,          */
          
          /*  f.id    as idPrecioFactura,            */
            
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             p.porcentaje,
             p.prod_venta_act,
             p.precioPart,
              p.img_url1 as imgUrl1
			FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
          /*  LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1*/
			WHERE ".
            
            $tipBus
            
            .
             
            "
            ORDER BY p.nombreProd
		";
    
    
    
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->idPrecioFactura = $row->idPrecioFactura;
			$tmp->cod_barra       = $row->cod_barra;
		
            
            $tmp->precioPart      = $row->precioPart;
          
      /*      if($row->porcentaje!= 0){
                 $tmp->precioVenta  = round($row->precioVenta - round($row->precioVenta * ($row->porcentaje/100)));
                 $tmp->precioPart      = $row->precioPart;                                     
                 $tmp->precioPart  = round($row->precioPart - round($row->precioPart * ($row->porcentaje/100)));        
                 $tmp->precioDifPr      = round((($tmp->precioPart )- round(($tmp->precioVenta * 0.19)+$tmp->precioVenta))/1.19);
            }else{
                $tmp->precioVenta  = "";
            }
           
            */

            
            
            $tmp->porcentaje      = $row->porcentaje;

            
                  $porcionesImg = explode(",", $row->imgUrl1);
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
            $tmp->stock          = round($row->stockProd - $row->salidasProd);
            
            $ramdom =  mt_rand();

           $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}    
    
    
    
    

    
    
     
     public function listarSalidasProductos($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "     
                    SELECT YEAR(fecha) ano,
                           MONTH(fecha) mes,
                           SUM(d.cantidad) registros 
                    FROM tbl_pedido  pe
                    INNER JOIN tbl_deta_pedido d 
                    ON d.id_pedido = pe.id_pedido 
                    WHERE d.id_prod='".$idProd."'
                    AND  pe.anulada='N'
                    AND  pe.id_cliente  NOT IN (87,162) 
                    GROUP BY YEAR(fecha),MONTH(fecha)
                    ORDER BY Mes ASC";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Precio();
			$tmp->ano          = $row->ano;
            
			$tmp->mes          = $row->mes;
			
            $tmp->registros    = $row->registros;

               
                      
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
		return $ret;
	}   
    
    
    
    
    public function tipoProductoPrecio() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT codCategoria,
                         nombreCategoria,
                         grupo 
                 FROM categoria WHERE activo=1 ";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Tipo();
			$tmp->codCategoria    = $row->codCategoria;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->grupo           = $row->grupo;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
 	 public function marcaProductoPrecio($idTipo) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT codMarca, nombreMarca FROM marca WHERE codCategoria='".$idTipo."' and activo=1 ORDER BY nombreMarca";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Marca();
			$tmp->codMarca = $row->codMarca;
			$tmp->nombreMarca = $row->nombreMarca;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
	
    
    
    public function listProductosTotalPrecio($codProducto, $nombreProd, $marcaProd, $categoriaProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");

            
        
        if($codProducto != ""){    
  
             if($marcaProd != "" || $categoriaProd !=""){
                   $busq = "

                    WHERE p.marcaProd   = '".$marcaProd."'
                    AND   p.categoriaProd = '".$categoriaProd."'
                    AND   pr.id_proveedor = '".$codProducto."'
                    AND   p.activo=1

                   ";
                } else{
                    
                   if($codProducto != ""){    
                    
                             $busq = "

                            WHERE pr.id_proveedor = '".$codProducto."'
                                  AND p.activo=1
                           ";
                   }
                }    
            
            
            
        }  else {
            
              if($marcaProd != "" || $categoriaProd !=""){
                   $busq = "

                    WHERE p.marcaProd = '".$marcaProd."'
                    AND p.categoriaProd = '".$categoriaProd."'
                    AND p.activo=1
                   ";
                } 
            
        }
            
		$query = "
			SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
             
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			p.precioVenta as precioVenta,
            p.precioPart,
            p.foto,
            
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             
            m.cod_proveedor,
            
            	IFNULL(( SELECT SUM(d.cantidad) 
                     FROM tbl_pedido pe 
                     INNER JOIN tbl_deta_pedido d 
                     ON d.id_pedido = pe.id_pedido 
                     WHERE d.id_prod=p.id 
                     AND pe.anulada='N'
                    AND pe.id_usuario  NOT IN (3) 
                    AND pe.id_cliente NOT IN (87,162) 
                    ),0) as salidasProdRanking,
                    
            p.fecha_vencimiento,
            p.cod_barra

            
            FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_proveedores pr             
            ON pr.id_proveedor = m.cod_proveedor 

		"
          .
            $busq
          .    
        "    
	
            ORDER BY CAST(salidasProdRanking as SIGNED INTEGER) DESC;
		";
            
        
        	/*	AND p.codProducto LIKE '%".$codProducto."%'
			AND p.nombreProd LIKE '%".$nombreProd."%'*/    
            
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->precioVenta     = $row->precioVenta;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->cod_proveedor   = $row->cod_proveedor;
    		$tmp->precioPart      = $row->precioPart;
			$tmp->countStock      = $row->stockProd - $row->salidasProd;
			$tmp->foto            = $row->foto;
			$tmp->cod_barra       = $row->cod_barra;
			$tmp->fecha_vencimiento       = $row->fecha_vencimiento;

            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	  }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
	public function consultarInventarioIng($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
				SELECT 	p.grupo_prod,
                        p.id		 as id,
			            p.nombreProd as nombreProd,
			(IFNULL((SELECT SUM(d.cantidad)
							FROM tbl_ingresos pe
							INNER JOIN tbl_ingresos_deta d 
							ON d.id_ingresos = pe.id
					  WHERE d.id_prod=p.id
					  and pe.activo=1),0) 
						-
					  IFNULL(  (SELECT SUM(d.cantidad)
							FROM tbl_pedido pe
							INNER JOIN tbl_deta_pedido d 
							ON d.id_pedido = pe.id_pedido
						 WHERE d.id_prod=p.id
						 AND pe.anulada='N'),0)						 
			) as stockProd
			FROM productos p           
			WHERE 
            p.id in (".$idProd.")
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);       
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)){
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->stockProd       = $row->stockProd;
 			$tmp->grupo_prod       = $row->grupo_prod;
           
            
            
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
    
    
    
    
    
    
    public function consultarInventarioIngCaja($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");
		$query = "
				SELECT 	p.grupo_prod,
                        p.id		 as id,
			            p.nombreProd as nombreProd,
			(IFNULL((SELECT SUM(d.cantidad)
							FROM tbl_ingresos pe
							INNER JOIN tbl_ingresos_deta d 
							ON d.id_ingresos = pe.id
					  WHERE d.id_prod=p.id
					  and pe.activo=1),0) 
						-
					  IFNULL(  (SELECT SUM(d.cantidad)
							FROM tbl_pedido pe
							INNER JOIN tbl_deta_pedido d 
							ON d.id_pedido = pe.id_pedido
						 WHERE d.id_prod=p.id
						 AND pe.anulada='N'),0)						 
			) as stockProd
			FROM productos p           
			WHERE 
            p.id in ($idProd)
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);       
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)){
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->stockProd       = $row->stockProd;
 			$tmp->grupo_prod       = $row->grupo_prod;
           
            
            
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
    
    
    
	
	
	
	 public function pesoProductos($idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query =   "SELECT SUM(pr.pesosProd * p.cantidad) as pesos
					FROM tbl_deta_pedido p
					LEFT OUTER JOIN productos pr 
					on pr.id = p.id_prod
					where p.id_pedido in (".$idPedido.")";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp = new Producto();
			$tmp->pesos    = $row->pesos;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
    public function listarPorcentajeOferta() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT id, porcentaje FROM tbl_oferta_prod";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id              = $row->id;
			$tmp->porcentaje     = $row->porcentaje;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
      public function updatePorcentaje($idProd, $idPorcentaje) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
        mysqli_query($mysql, "SET NAMES 'utf8'");		
		$query = "UPDATE productos c
                  SET c.porcentaje='".$idPorcentaje."'
                  WHERE c.id='".$idProd."';
        ";		
		
		if(mysqli_query($mysql, $query)){
			return 1;
		} else {
			return 2;
		}
     }    
    
    
    
    
    public function listarProductosOfertas() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");

        $query = "
		    SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
            IFNULL(f.precio_venta, p.precioVenta ) as precioVenta,             
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             p.porcentaje
			FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
			WHERE p.porcentaje <> 0
            ORDER BY p.nombreProd
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id    		  = $row->id;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioVenta     = $row->precioVenta;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->porcentaje      = $row->porcentaje;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
    
    
    
    
    public function listarProductosSinStock() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        mysqli_query($mysql, "SET NAMES 'utf8'");

        $query = "
		    SELECT sl.id_prod,
                   sl.nombreProd,
                   sl.nombreCategoria,
                   sl.nombreMarca,
                   sl.stock

FROM (


SELECT dd.id_prod,
        p.nombreProd,
        ct.nombreCategoria,
        mc.nombreMarca,
       (dd.cantidad - cc.cantidad) as stock
 FROM productos p
			LEFT OUTER JOIN 
              ((SELECT 
                         d.id_prod,
                         SUM(d.cantidad) as cantidad
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE pe.anulada='N'
              GROUP BY d.id_prod)) cc 
             ON cc.id_prod = p.id 
			LEFT OUTER JOIN 
            ((SELECT 
                d.id_prod,
                SUM(d.cantidad) as cantidad
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE /*d.id_prod=p.id
			 and */pe.activo=1
             GROUP BY d.id_prod)) dd
             ON dd.id_prod = p.id
           LEFT OUTER JOIN categoria ct 
           ON ct.codCategoria = p.categoriaProd
           LEFT OUTER JOIN marca mc 
           ON mc.codMarca = p.marcaProd
         GROUP BY dd.id_prod,
         p.nombreProd,
          ct.nombreCategoria,
        mc.nombreMarca) sl
        WHERE sl.stock > 11
        ORDER BY sl.nombreMarca ASC
		";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	   //echo $query;
        
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id_prod    	  = $row->id_prod;			
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->stock       = $row->stock;
			$tmp->nombreCategoria     = $row->nombreCategoria;
			$tmp->nombreMarca     = $row->nombreMarca;

			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
    
    

    
    public function insertarProductoNuevo($codProd, $nombProd, $pesProd, $bodProd, $bodega, $pesosProd, $marcProd, $catProd, $claProd, $idProve, $foto) {
        
        $arrayBaseDatos[0] = "aranibar_aranibar";
        $arrayBaseDatos[1] = "aranibar_santa_maria";
        $arrayBaseDatos[2] = "aranibar_tucapel";

        
     for($i=0;$i<count($arrayBaseDatos);$i++) {    
         
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
		
        $query = "INSERT INTO productos(codProducto, nombreProd, marcaProd, categoriaProd, bodega, activo, pesosProd, clasificacion_prod, id_proveedor, foto, nodo_prod) 
        VALUES ('".$codProd."' , '".$nombProd."','".$marcProd."' , '".$catProd."','".$bodega."' , '1' ,'".$pesosProd."','".$claProd."','".$idProve."','".$foto."', 0);";		

        
		$result = mysqli_query($mysql, $query);		
	
     }
            if($result == "1"){
                return 1;
            }else{
                return 0;
            }
    
    }
    
    
  public function listarClasificacionProd() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT c.id, c.nombre FROM tbl_clasificacion_productos c";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Tipo();
			$tmp->id = $row->id;
			$tmp->nombre = $row->nombre;
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
     public function mostrarImagen($idProd) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT p.foto FROM productos p WHERE p.id=".$idProd.";";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->foto = $row->foto;            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	} 
    
    
    
    public function mostrarImagenTransf($idPed) {
        try {
            $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
            mysqli_select_db($mysql, $this->DATABASE_NAME);
            $query = "SELECT p.img_transf FROM tbl_pedido p WHERE p.id_pedido=".$idPed.";";
            $result = mysqli_query($mysql, $query);

            $ret = array();
            while ($row = mysqli_fetch_object($result)) {
                $tmp =new Producto();
                $tmp->img_transf = (strpos($row->img_transf, 'base64') ? $row->img_transf : $this->image_to_base64($row->img_transf));
                $ret[] = $tmp;
            }
            mysqli_free_result($result);
            return $ret;
        } catch (Exception $e) {
            return [];
        }
	}

    
    
    
    
    private function image_to_base64($file_path) {

        $file_path = IMAGE_FOLDER . $file_path;

        // Verificar si el archivo existe
        if (!file_exists($file_path)) {
            throw new InvalidArgumentException('El archivo no existe.');
        }
    
        // Obtener el archivo y verificar si es imagen
        $file_info = getimagesize($file_path);
        if ($file_info === false) {
            throw new InvalidArgumentException('El archivo no es una imagen vÃ¡lida.');
        }
    
        // Leer el contenido del archivo
        $image_data = file_get_contents($file_path);
        if ($image_data === false) {
            throw new RuntimeException('Error al abrir el archivo.');
        }
    
        // Obtener el tipo MIME del archivo
        $mime_type = $file_info['mime'];
    
        // Codificar la imagen como base64 y crear el esquema URI de los datos
        $base64_image = 'data:' . $mime_type . ';base64,' . base64_encode($image_data);
    
        // Retornar la imagen codificada con base64
        return $base64_image;
    }
    
    
     public function borrarImagenTransf($idPed) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "UPDATE tbl_pedido p SET p.img_transf = null WHERE p.id_pedido=".$idPed.";";
		$result = mysqli_query($mysql, $query);
            if($result == "1"){
                return 1;
            }else{
                return 0;
            }
	} 
    
    
    
        
    public function buscarProductoRapido($nombr, $tipBus) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
        
        $busq = "";
           if($tipBus == "Tienda"){
        
            $busq = " And p.activo_tienda = 1 ";
        
        }
        
        
		$query = "SELECT 
                   p.id, 
                   p.cod_barra,
                   p.nombreProd,
                   dd.stockProd,                    
                   c.nombreCategoria,
                   p.precioPart,
                    p.img_url1 as imgUrl1,
                    p.nodo_prod
            FROM productos p 
            LEFT OUTER JOIN marca m 
            ON m.codMarca = p.marcaProd 
            LEFT OUTER JOIN categoria c 
            ON c.codCategoria = p.categoriaProd
                INNER JOIN
            ((
			
			SELECT 	pp.id		 as id,
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
			) as stockProd
			FROM productos pp      
			/*WHERE pp.activo=1*/
			
			)) dd
             ON dd.id = p.id
            where p.nombreProd like '%".$nombr."%' 
          /*  And p.activo_tienda = 1*/
          
          
          
               "
             .
             $busq
             .
             
            "
          
          
          
          
            order by p.nombreProd asc";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
			$tmp->id              = $row->id;
			$tmp->nombreProd      = $row->nombreProd;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->cod_barra       = $row->cod_barra;
			$tmp->precioPart      = $row->precioPart;

            $porcionesImg = explode(",", $row->imgUrl1);
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
                   $tmp->nodo_prod        = $row->nodo_prod;
     
            
            
            
            $ramdom =  mt_rand();
            
            $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
            
            
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
    public function saveProductoModFotoTransf($objProductos) {
        try {
            $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
            mysqli_select_db($mysql, $this->DATABASE_NAME);   
            mysqli_query($mysql, "SET NAMES 'utf8'");    
            $query = "UPDATE tbl_pedido p 
                        SET 
                            p.img_transf               = '".$this->save_base64_image($objProductos[0]->foto, $objProductos[0]->idPed)."'
                            
                        WHERE p.id_pedido = '".$objProductos[0]->idPed."'
                    ";         
            if(mysqli_query($mysql, $query)){
                return 1;
            } else{
                return 0;
            } 
        } catch (Exception $e) {
            return 0;
        }
		
    }

    
    
    
    //////////////16-05-2023///////////////    
    
    
     public function listarSubProducto($idProducto, $tipBusq) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		
   /*     if($tipBusq == 0){
        
            $busq = "  where p.nodo_prod like '%".$idProducto."%'  ; ";
        
        }else{
            
            $busq = "  where p.id  in (".$idProducto.")  ; ";
        }
         */
         
        $busq = "  where p.id  in (".$idProducto.")  ; ";
         
         $query = "
                 
                 
                 
                 
       SELECT 
			p.id		 as id,
			p.nombreProd as nombreProd,
			p.codProducto as codProducto,
			m.nombreMarca as nombreMarca ,
			c.nombreCategoria as nombreCategoria,
            
            IFNULL((SELECT SUM(d.cantidad)
                FROM tbl_ingresos pe
                INNER JOIN tbl_ingresos_deta d 
                ON d.id_ingresos = pe.id
             WHERE d.id_prod=p.id
			 and pe.activo=1),0) as stockProd,
             
			p.ObservacionProd as ObservacionProd,
			p.precioCosto as precioCosto,
			p.marcaProd as codMarcaProd,
			p.categoriaProd as codTipoProd,
			p.precioVenta as precioVenta,
            
            (SELECT SUM(d.cantidad)
                FROM tbl_pedido pe
                INNER JOIN tbl_deta_pedido d 
                ON d.id_pedido = pe.id_pedido
             WHERE d.id_prod=p.id
             AND pe.anulada='N') as salidasProd,
             
            m.cod_proveedor,
            
            	IFNULL(( SELECT SUM(d.cantidad) 
                     FROM tbl_pedido pe 
                     INNER JOIN tbl_deta_pedido d 
                     ON d.id_pedido = pe.id_pedido 
                     WHERE d.id_prod=p.id 
                     AND pe.anulada='N'
                    AND pe.id_usuario  NOT IN (3) 
                    AND pe.id_cliente NOT IN (87,162) 
                    ),0) as salidasProdRanking,
                    
            IFNULL(f.precio_venta, p.precioVenta ) as precioMasc,     
            p.precioPart,

            pr.nombre as nombreProv,
			f.diferenciaPrecio,
            p.porcentaje,
            
            p.pesosProd,
            p.bodega,
            cl.id as clasId,
            cl.nombre as clasProd,
            p.id_proveedor,
         /*   (SELECT CASE WHEN pp.foto IS NULL OR '' THEN 0 ELSE 1 END AS fotoEstado FROM productos pp WHERE pp.id=p.id) as estadoFoto,*/
           /* p.cod_barra,*/
            
                 (SELECT
          GROUP_CONCAT(n.codigo_barra SEPARATOR ',') AS nota
          FROM tbl_codigo_barra n
          where n.id_prod= p.id) as cod_barra,
            
            
            p.fecha_vencimiento,
            p.img_url1 as imgUrl1,
            p.activo,
            p.nodo_prod,
            p.grupo_prod,
            
             (SELECT CONCAT(pp.id, ' - ', pp.nombreProd)  
             FROM productos pp 
             WHERE pp.id = p.nodo_prod) as nodo_nombre_prod,
             p.activo_tienda
            /* p.cod_barra*/
            
			
			
            
            FROM productos p
			INNER JOIN marca m
			ON p.marcaProd = m.codMarca
			INNER JOIN categoria c
			ON p.categoriaProd = c.codCategoria
            LEFT OUTER JOIN tbl_proveedores pr             
            ON pr.id_proveedor = p.id_proveedor
            LEFT OUTER JOIN tbl_precio_factura f 
            ON f.id_prod = p.id
            AND f.activo = 1
            LEFT OUTER JOIN tbl_clasificacion_productos cl
            ON cl.id = p.clasificacion_prod
             
             
            "
             .
             $busq
             .
             
            "
            
             
          
                 
                 
                 
                 
                 
                 
                 
                 
                 
         
                 ";

         
         
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Producto();
        	$tmp->id    		  = $row->id;            
            	$tmp->nombreProd      = $row->nombreProd;
			$tmp->codProducto  	  = $row->codProducto;
			$tmp->nombreMarca     = $row->nombreMarca;
			$tmp->nombreCategoria = $row->nombreCategoria;
			$tmp->stockProd       = $row->stockProd;
			$tmp->precioCosto     = $row->precioCosto;
			$tmp->ObservacionProd = $row->ObservacionProd;
			$tmp->codMarcaProd    = $row->codMarcaProd;
			$tmp->codTipoProd     = $row->codTipoProd;
			$tmp->precioVenta     = $row->precioVenta;
			$tmp->salidasProd     = $row->salidasProd;
			$tmp->cod_proveedor   = $row->cod_proveedor;
            
			$tmp->countStock     = $row->stockProd - $row->salidasProd;
            $tmp->precioMasc     = $row->precioMasc;
			$tmp->precioPart     = $row->precioPart;
			$tmp->nombreProv     = $row->nombreProv;
			
			if($row->diferenciaPrecio < 0){				
				$tmp->estadoPrecio = 'flechaAbajo';
				$tmp->diferenciaPrecio = -($row->diferenciaPrecio);
			}else{
				
				if($row->diferenciaPrecio == 0){					
				 $tmp->estadoPrecio = '';
				 $tmp->diferenciaPrecio = '';					
				}else{
				 $tmp->estadoPrecio = 'flechaArriba';
				 $tmp->diferenciaPrecio = $row->diferenciaPrecio;
				}
			
			}
  
			$tmp->porcentaje       = $row->porcentaje;
			$tmp->pesosProd        = $row->pesosProd;
			$tmp->bodega           = $row->bodega;
			$tmp->clasId           = $row->clasId;
			$tmp->clasProd         = $row->clasProd;
			$tmp->id_proveedor     = $row->id_proveedor;
 			$tmp->cod_barra                = $row->cod_barra;
            $tmp->activo        = $row->activo;
      
            
            
            $tmp->imgUrl1        = $porcionesImg[0];
            $tmp->imgUrl2        = $porcionesImg[1];
              $tmp->nodo_nombre_prod        =  $row->nodo_nombre_prod;

            
  
    
            
            
            
            $tmp->nodo_prod       = $row->nodo_prod;
            $tmp->grupo_prod       = $row->grupo_prod;

             $tmp->activo_tienda       = $row->activo_tienda;
            
            
            
            
            
            
            
            
            
            
            
            $ramdom =  mt_rand();            
            $tmp->foto = "https://aranibar.cl/barrosaranas/Table/img/".$row->id.".png?".$ramdom; 
            
            $ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    ///////////////////////////////////
    
    
    
    
    
    
    
    
    
    
    
    private function save_base64_image($base64_image, $id) {
        // Chequear si el base64 tiene la extensiÃ³n
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $matches)) {
            $image_data = substr($base64_image, strpos($base64_image, ',') + 1);
            $extension = $matches[1];
        } else {
            // Si el string base64 no tiene URI scheme, asumir que es un base64 plano
            $image_data = $base64_image;
            $extension = 'jpg'; // Configurar una extensiÃ³n por defecto, si no viene en el string base64
        }
    
        // Decodificar el string base64
        $image_data = base64_decode($image_data);
    
        // Verificar si es una imagen vÃ¡lida
        if ($image_data === false || !@imagecreatefromstring($image_data)) {
            throw new InvalidArgumentException('String base64 no es una imagen.');
        }
    
        // Crear un nombre de archivo Ãºnico
        $file_name = "PEDIDO_$id.$extension";
        $file_path = IMAGE_FOLDER . $file_name;
    
        // Guardar la imagen en el archivo
        if (!file_put_contents($file_path, $image_data)) {
            throw new RuntimeException('No se pudo guardar la imagen en el archivo.');
        }
    
        // retornar el nombre de archivo
        return $file_name;
    }
    
    
     public function updateEstadoProductoAct($idProd, $activo) {
         
      $arrayBaseDatos[0] = "aranibar_aranibar";
        $arrayBaseDatos[1] = "aranibar_santa_maria";
        $arrayBaseDatos[2] = "aranibar_tucapel";
	
         
        
    for($i=0;$i<count($arrayBaseDatos);$i++) {    
     //   echo $arrayBaseDatos[$i];
        
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
   
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
        mysqli_query($mysql, "SET NAMES 'utf8'");

         
        $query = "UPDATE productos f
                  SET f.activo = '".$activo."' 
                  WHERE f.id = '".$idProd."';
                 ";
		$result = mysqli_query($mysql, $query);
        
	
    }
         
         
     	  if($result == "1"){
                return 1;
            }else{
                return 0;
            }
        
         
        
	}
    
    
    
    
    	public function consultarInventarioTiendas($idProd) {
            
       $arrayBaseDatos[0] = "aranibar_aranibar";
      $arrayBaseDatos[1] = "aranibar_santa_maria";
      $arrayBaseDatos[2] = "aranibar_tucapel";
 //     $arrayBaseDatos[3] = "aranibar_asocapec";
  
            $ret = array();
            
                for($i=0;$i<count($arrayBaseDatos);$i++) {      
         
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
                 mysqli_query($mysql, "SET NAMES 'utf8'");

         
        $query =  "
				SELECT 
			(IFNULL((SELECT SUM(d.cantidad)
							FROM tbl_ingresos pe
							INNER JOIN tbl_ingresos_deta d 
							ON d.id_ingresos = pe.id
					  WHERE d.id_prod=p.id
					  and pe.activo=1),0) 
						-
					  IFNULL(  (SELECT SUM(d.cantidad)
							FROM tbl_pedido pe
							INNER JOIN tbl_deta_pedido d 
							ON d.id_pedido = pe.id_pedido
						 WHERE d.id_prod=p.id
						 AND pe.anulada='N'),0)						 
			) as stockProd
			FROM productos p           
			WHERE 
            p.id in (".$idProd.")
		";
			$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);   
              $tmp = new stdClass();      
      if($total != 0){              
                    
                 
       while ($row = mysqli_fetch_object($result)) {
           
           $tmp->tienda        =   $arrayBaseDatos[$i];
           $tmp->stock         =   $row->stockProd;  

           
           
           $ret[] = $tmp;
       }
          
    }else{
           
           $tmp->tienda        =   $arrayBaseDatos[$i];
           $tmp->stock     =   0;  
           $ret[]           = $tmp;
          
      }      
     // mysqli_free_result($ret);     
                    
                    
                    
                    
                    
         
     }	
	
            
            return $ret;
            
            
            
	}
    
    
    
    
    
    
    
    
    
    
    
     public function updateEstadoProductoActTienda($idProd, $activo) {
         
      $arrayBaseDatos[0] = "aranibar_aranibar";
      $arrayBaseDatos[1] = "aranibar_santa_maria";
      $arrayBaseDatos[2] = "aranibar_tucapel";
 //     $arrayBaseDatos[3] = "aranibar_asocapec";
      
	
              
              
    for($i=0;$i<count($arrayBaseDatos);$i++) {      
         
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
                 mysqli_query($mysql, "SET NAMES 'utf8'");

         
        $query = "UPDATE productos f
                  SET f.activo_tienda = '".$activo."' 
                  WHERE f.id = '".$idProd."';
                 ";
		$result = mysqli_query($mysql, $query);
         
     }	
		  if($result == "1"){
                return 1;
            }else{
                return 0;
            }
	}
    
    
    
    	 public function listarOfertasProductosDescuento() {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT o.id, 
                          o.id_prod 
                          From tbl_ofertas o";
		$result = mysqli_query($mysql, $query);
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Marca();
			$tmp->id      = $row->id;
			$tmp->id_prod = $row->id_prod;
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		return $ret;
	}
    
    
    
    
    
    
    
    
    
     public function listarCodigoBarra($id){
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);

      $query="SELECT c.id, c.id_prod, c.codigo_barra FROM tbl_codigo_barra c where c.id_prod = (".$id.");";
      
   
       $result = mysqli_query($mysql, $query);
       $total = mysqli_num_rows($result);
   
       $ret = array();
       while ($row = mysqli_fetch_object($result)) {
           $tmp = new stdClass();
           $tmp->id     =   $row->id;
           $tmp->id_prod     =   $row->id_prod;
           $tmp->codigo_barra =   $row->codigo_barra;

           
           
           $ret[] = $tmp;
       }
         mysqli_free_result($result);
         return ($ret);

         mysqli_close($mysql);
     
     
       } 
    
    
    public function listarMarcasProductos(){
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);

        $query="SELECT 
                    m.codMarca,
                    m.codCategoria,
                    m.nombreMarca,
                    c.nombreCategoria,
                    m.activo
                    FROM marca m
                    LEFT OUTER JOIN categoria c 
                    ON c.codCategoria = m.codCategoria;";

        $result = mysqli_query($mysql, $query);
        $total = mysqli_num_rows($result);

        $ret = array();
        while ($row = mysqli_fetch_object($result)) {
            $tmp = new stdClass();
            $tmp->codMarca     =   $row->codMarca;
            $tmp->codCategoria =   $row->codCategoria;
            $tmp->nombreMarca  =   $row->nombreMarca;
            $tmp->nombreCategoria       =   $row->nombreCategoria;

            $tmp->activo                =   $row->activo;
            $tmp->estado_mod                =   0;

            
            
            $ret[] = $tmp;
        }
          mysqli_free_result($result);
          return ($ret);
 
          mysqli_close($mysql);
    }
    
    
    
    
    
    
    
     public function agregarCodigoBarra($id, $cod_barra){
      $arrayBaseDatos[0] = "aranibar_aranibar";
      $arrayBaseDatos[1] = "aranibar_santa_maria";
      $arrayBaseDatos[2] = "aranibar_tucapel";
         
   for($i=0;$i<count($arrayBaseDatos);$i++) {         
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);
       
            $queryCodBarra =  "SELECT * FROM tbl_codigo_barra c where c.codigo_barra = '".$cod_barra."';";   
            $resultCodBarra = mysqli_query($mysql, $queryCodBarra);
            $total = mysqli_num_rows($resultCodBarra);  
       if($total == "0"){

        $query="INSERT INTO tbl_codigo_barra(id_prod, codigo_barra) VALUES ('".$id."','".$cod_barra."');";       
        $result = mysqli_query($mysql, $query);
           
           
       }else{
           
           return 2;
       }
       
       
   }
         
      if($result == "1"){
                return 1;
            }else{
                return 0;
        }   
         
         
       mysqli_close($mysql);
     
     
       }  
    
    
     
     public function eliminarCodigoBarra($id){
      $arrayBaseDatos[0] = "aranibar_aranibar";
      $arrayBaseDatos[1] = "aranibar_santa_maria";
      $arrayBaseDatos[2] = "aranibar_tucapel";
         
   for($i=0;$i<count($arrayBaseDatos);$i++) {         
        $mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
        mysqli_select_db($mysql,  $arrayBaseDatos[$i]);

      $query="DELETE FROM tbl_codigo_barra  where id ='".$id."';";
      
   
       $result = mysqli_query($mysql, $query);
   }
         
      if($result == "1"){
                return 1;
            }else{
                return 0;
        }   
         
         
       mysqli_close($mysql);
     
     
       }  
    
    
    
}
	

?>