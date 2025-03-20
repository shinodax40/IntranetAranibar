<?php
require_once('Bean/Factura.php');

class bdFactura{
	
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
	
	/* public function listarSalidaBoleta($fechaBoleta,$codBoleta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "SELECT codMarca, nombreMarca FROM marca WHERE codCategoria='".$idTipo."'";
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
	}*/
	
	
	 public function insertarFactura($arrayCabe, $arrayDeta, $arrayTotal) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);			
		
		$existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM facturacabe WHERE numeroFactura='".$arrayCabe[0]->numeroFactura."'"));
	    $FacturaCod = $arrayCabe[0]->numeroFactura;

	     
		if($existe == 0){	
			foreach($arrayCabe as $cabecera){
				$query = "INSERT INTO  facturacabe (numeroFactura,nombre,fecha,estadoFac) 
						  VALUES ('".$cabecera->numeroFactura."','".$cabecera->nombre."','".$cabecera->fecha."','S');";
				$result = mysqli_query($mysql, $query);	
			}
			
			foreach($arrayDeta as $detalle){
				$query = "INSERT INTO  facturadeta (numeroFactura,codProducto,tipo,marca,nombreProd,detalle,precioCosto,stock,cantidad,precio,total,descuento) 
						  VALUES ('".$FacturaCod."','".$detalle->codProducto."','".$detalle->tipo."','".$detalle->marca."','".$detalle->nombreProd."','".$detalle->detalle."','".$detalle->precioCosto."','".$detalle->stock."','".$detalle->cantidad."','".$detalle->precio."','".$detalle->total."','".$detalle->descuento."');";
				$result = mysqli_query($mysql, $query);	
				
				$query = "UPDATE  productos
						  SET stockProd ='".$detalle->resultadoCantStock."'
						  WHERE codProducto='".$detalle->codProducto."'";
				$result = mysqli_query($mysql, $query);				
			}

			
			foreach($arrayTotal as $totales){
				$query = "INSERT INTO  facturatotal (numeroFactura,totalCantidad,totalPrecio,totalFactura,fechaFactura,totalDescuento) 
						  VALUES ('".$totales->numeroFactura."','".$totales->totalCantidad."','".$totales->totalPrecio."','".$totales->totalFactura."','".$totales->fechaFactura."','".$totales->totalDescuento."');";
				$result = mysqli_query($mysql, $query);	
			}
			    return "0";
		}else{
		    return "1";
		}
			
	}
	
	
	public function listarFacturas($numeroFactura, $nombre, $fecha) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "
		SELECT p.numeroFactura, 
			   p.nombre, 
			   p.fecha,
			   p.id,
			   p.estadoFac
		FROM facturacabe p
		WHERE p.numeroFactura LIKE '%".$numeroFactura."%'
		AND p.nombre 	      LIKE '%".$nombre."%'
		AND p.fecha 	      LIKE '%".$fecha."%'
		ORDER BY p.id";

		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       
	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
			$tmp->id    		  = $row->id;			
			$tmp->numeroFactura   = $row->numeroFactura;
			$tmp->nombre  	  	  = $row->nombre;
			$tmp->fecha     	  = $row->fecha;
			$tmp->estadoFac 	  = $row->estadoFac;
	
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
	
	
		public function listarFacturasDeta($numeroFacturas) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$ret = array();
		for($i = 0; $i < count($numeroFacturas); $i++)
		{
		$FacturaCod = $numeroFacturas[$i]->numeroFactura;

		$query = "
		SELECT p.id, 
			   p.numeroFactura, 
			   p.codProducto,
			   p.tipo,
			   p.marca,
               p.nombreProd,
               p.detalle,
               p.precioCosto,
               p.stock,
               p.cantidad,
               p.precio,
               p.total,
			   p.descuento
		FROM facturadeta p
		WHERE p.numeroFactura = '".$FacturaCod."'";
		$result = mysqli_query($mysql, $query);
       
	
		
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
			$tmp->id    		  = $row->id;			
			$tmp->numeroFactura   = $row->numeroFactura;
			$tmp->codProducto = $row->codProducto;
			$tmp->tipo     	  = $row->tipo;
			$tmp->marca 	  = $row->marca;
			$tmp->nombreProd  = $row->nombreProd;
			$tmp->detalle 	  = $row->detalle;
			$tmp->precioCosto = $row->precioCosto;
			$tmp->stock 	  = $row->stock;
			$tmp->cantidad 	  = $row->cantidad;
			$tmp->precio 	  = $row->precio;
			$tmp->total 	  = $row->total;
			$tmp->descuento   = $row->descuento;
			//$ret[] = $tmp;
			array_push($ret,$tmp );
		}
		mysqli_free_result($result);
	}
		
		

		      return ($ret);
	}
	
	
	 public function cancelarFactura($arrayDeta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);				     
			/*
			$query = "UPDATE  facturacabe
						 SET estadoFac =  'N'
						  WHERE numeroFactura='".$arrayDeta[0]->numeroFactura."'";						  
			$result = mysqli_query($mysql, $query);	*/
			
			
			foreach($arrayDeta as $detalle){
				$query = "UPDATE  productos
						  SET stockProd =  stockProd +  '".$detalle->cantidad."'
						  WHERE codProducto='".$detalle->codProducto."'";
				$result = mysqli_query($mysql, $query);				
			}	
		//eliminar cabecera
			$query = "DELETE   FROM   facturacabe
					  WHERE numeroFactura='".$arrayDeta[0]->numeroFactura."'";						  
			$result = mysqli_query($mysql, $query);	
		//eliminar detalle
			$query = "DELETE   FROM   facturadeta
					  WHERE numeroFactura='".$arrayDeta[0]->numeroFactura."'";						  
			$result = mysqli_query($mysql, $query);			
				
		//eliminar suma
			$query = "DELETE   FROM   facturatotal
					  WHERE numeroFactura='".$arrayDeta[0]->numeroFactura."'";						  
			$result = mysqli_query($mysql, $query);	

			
			
		return "0";	
	}
	
	public function modificarFactura($arrayDeta, $arrayTotal){
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
	
		//For para aumentar los productos al stock
		$ret = array();
		$FacturaCod = $arrayTotal[0]->numeroFactura;

		$query = "
		SELECT p.id, 
			   p.numeroFactura, 
			   p.codProducto,
			   p.tipo,
			   p.marca,
               p.nombreProd,
               p.detalle,
               p.precioCosto,
               p.stock,
               p.cantidad,
               p.precio,
               p.total
		FROM facturadeta p
		WHERE p.numeroFactura = '".$FacturaCod."'";
		$result = mysqli_query($mysql, $query);
       
	
		
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
			$tmp->id    		  = $row->id;			
			$tmp->numeroFactura   = $row->numeroFactura;
			$tmp->codProducto = $row->codProducto;
			$tmp->tipo     	  = $row->tipo;
			$tmp->marca 	  = $row->marca;
			$tmp->nombreProd  = $row->nombreProd;
			$tmp->detalle 	  = $row->detalle;
			$tmp->precioCosto = $row->precioCosto;
			$tmp->stock 	  = $row->stock;
			$tmp->cantidad 	  = $row->cantidad;
			$tmp->precio 	  = $row->precio;
			$tmp->total 	  = $row->total;
			//$ret[] = $tmp;
			array_push($ret,$tmp );
		}
	
		foreach($ret as $detalle){
				$query = "UPDATE  productos
						  SET stockProd =  stockProd +  '".$detalle->cantidad."'
						  WHERE codProducto='".$detalle->codProducto."'";
				$result = mysqli_query($mysql, $query);				
		}	
		
		//eliminar detalle
		
		$query = "DELETE   FROM   facturadeta
					  WHERE numeroFactura='".$FacturaCod."'";						  
		$result = mysqli_query($mysql, $query);			
				
		//eliminar suma
		$query = "DELETE   FROM   facturatotal
					  WHERE numeroFactura='".$FacturaCod."'";						  
		$result = mysqli_query($mysql, $query);	
			
		//Restar Stock producto
		foreach($arrayDeta as $detalle){
				$query = "UPDATE  productos
						  SET stockProd =  stockProd -  '".$detalle->cantidad."'
						  WHERE codProducto='".$detalle->codProducto."'";
				$result = mysqli_query($mysql, $query);				
		}
			
		//Insertar detalle factura
		foreach($arrayDeta as $detalle){
				$query = "INSERT INTO  facturadeta (numeroFactura,codProducto,tipo,marca,nombreProd,detalle,precioCosto,stock,cantidad,precio,total,descuento) 
						  VALUES ('".$FacturaCod."','".$detalle->codProducto."','".$detalle->tipo."','".$detalle->marca."','".$detalle->nombreProd."','".$detalle->detalle."','".$detalle->precioCosto."','".$detalle->stock."','".$detalle->cantidad."','".$detalle->precio."','".$detalle->total."','".$detalle->descuento."');";
				$result = mysqli_query($mysql, $query);				
		}		
		//Insertar suma total de factura
		foreach($arrayTotal as $totales){
				$query = "INSERT INTO  facturatotal (numeroFactura,totalCantidad,totalPrecio,totalFactura,fechaFactura,totalDescuento) 
						  VALUES ('".$totales->numeroFactura."','".$totales->totalCantidad."','".$totales->totalPrecio."','".$totales->totalFactura."','".$totales->fechaFactura."','".$totales->totalDescuento."');";
				$result = mysqli_query($mysql, $query);	
		}
	
	return "0";
	}
    
    
    
    
    
    
    
    
    
 public function insertarFacturaNubox($arrayNubox, $numPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
		

     	$query = "INSERT INTO  tbl_nubox_facturas (id_pedido, fecha, folio , identificacion, tipo) 
						  VALUES ('".$numPedido."', CURDATE() , '".$arrayNubox[0]."','".$arrayNubox[1]."','".$arrayNubox[2]."');";               
       
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
    

}
    
    
 public function insertarNubox($folio, $identificador,$tipo, $idPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
		

     	$query = "INSERT INTO  tbl_nubox_facturas (id_pedido, fecha, folio , identificacion, tipo) 
						  VALUES ('".$idPedido."', CURDATE() , '".$folio."','".$identificador."','".$tipo."');";               
       
		$result = mysqli_query($mysql, $query);		
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
    

}
    
    
    
    
  public function insertarFacturaNuboxJoin($arrayNubox, $arrayPedido) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);	
		
      foreach($arrayPedido as $pedidos){
          if($pedidos!=""){

                $query = "INSERT INTO  tbl_nubox_facturas (id_pedido, fecha, folio , identificacion, tipo) 
                                  VALUES ('".$pedidos."',CURDATE(), '".$arrayNubox[0]."','".$arrayNubox[1]."','".$arrayNubox[2]."');";    
              $result = mysqli_query($mysql, $query);	
      
          }
          
       }
       
			
	
    if($result == "1"){
        return 1;
    }else{
        return 0;
    }
    

}
	}
?>