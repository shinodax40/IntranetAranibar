<?php
require_once('Bean/Factura.php');
require_once('dompdf/dompdf_config.inc.php');

class bdBoleta{	
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
	
	
	 public function insertarBoleta($arrayCabe, $arrayDeta, $arrayTotal) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);			
		
		$existe = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM boletacabe WHERE numeroBoleta='".$arrayCabe[0]->numeroBoleta."'"));
	    $boletaCod = $arrayCabe[0]->numeroBoleta;
		
		if($existe == 0){	
			foreach($arrayCabe as $cabecera){
				$query = "INSERT INTO  boletacabe (numeroBoleta,nombre,fecha,estadoBol) 
						  VALUES ('".$cabecera->numeroBoleta."','".$cabecera->nombre."','".$cabecera->fecha."','S');";
						  
				$result = mysqli_query($mysql, $query);	
			}
			
			foreach($arrayDeta as $detalle){
				$query = "INSERT INTO  boletadeta (numeroBoleta,codProducto,tipo,marca,nombreProd,detalle,precioCosto,stock,cantidad,precioCostoActual,total) 
						  VALUES ('".$boletaCod."','".$detalle->codProducto."','".$detalle->tipo."','".$detalle->marca."','".$detalle->nombreProd."','".$detalle->detalle."','".$detalle->precioCosto."','".$detalle->stock."','".$detalle->cantidad."','".$detalle->precioCostoActual."','".$detalle->total."');";
				$result = mysqli_query($mysql, $query);	
				
				$query = "UPDATE  productos
						  SET stockProd ='".$detalle->resultadoCantStock."',
						  precioCosto ='".$detalle->precioCostoActual."'	
						  WHERE codProducto='".$detalle->codProducto."'";
				$result = mysqli_query($mysql, $query);				
			}

			
			foreach($arrayTotal as $totales){
				$query = "INSERT INTO  boletatotal (numeroBoleta,totalCantidad,totalPrecio,totalBoleta,fechaBoleta) 
						  VALUES ('".$totales->numeroBoleta."','".$totales->totalCantidad."','".$totales->totalPrecio."','".$totales->totalBoleta."','".$totales->fechaBoleta."');";
				$result = mysqli_query($mysql, $query);	
			}
			    return "0";
		}else{
		    return "1";
		}
			
	}
	
	
	public function listarBoletas($numeroBoleta, $nombre) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$query = "
		SELECT p.numeroBoleta, 
			   p.nombre, 
			   p.fecha,
			   p.id,
			   p.estadoBol
		FROM boletacabe p
		WHERE p.numeroBoleta LIKE '%".$numeroBoleta."%'
		AND p.nombre 	      LIKE '%".$nombre."%'
		ORDER BY p.id";
		$result = mysqli_query($mysql, $query);
		$total = mysqli_num_rows($result);
       	
		$ret = array();
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
			$tmp->id    		  = $row->id;			
			$tmp->numeroBoleta    = $row->numeroBoleta;
			$tmp->nombre  	  	  = $row->nombre;
			$tmp->fecha     	  = $row->fecha;
			$tmp->estadoBol 	  = $row->estadoBol;	
			$ret[] = $tmp;
		}
		mysqli_free_result($result);
		      return ($ret);
	}
	
		public function listarBoletasDeta($numeroBoleta) {
		$mysql = mysqli_connect($this->DATABASE_SERVER,$this->DATABASE_USERNAME,$this->DATABASE_PASSWORD);
		mysqli_select_db($mysql, $this->DATABASE_NAME);
		$ret = array();

		$query = "
		SELECT p.id, 
			   p.numeroBoleta, 
			   p.codProducto,
			   p.tipo,
			   p.marca,
               p.nombreProd,
               p.detalle,
               p.precioCosto,
               p.stock,
               p.cantidad,
               p.precioCostoActual,
               p.total
		FROM boletadeta p
		WHERE p.numeroBoleta = '".$numeroBoleta."'";
		$result = mysqli_query($mysql, $query);
	
		
		while ($row = mysqli_fetch_object($result)) {
			$tmp =new Factura();
			$tmp->id    		  	= $row->id;			
			$tmp->numeroBoleta    	= $row->numeroBoleta;
			$tmp->codProducto 	  	= $row->codProducto;
			$tmp->tipo     	      	= $row->tipo;
			$tmp->marca 	  	  	= $row->marca;
			$tmp->nombreProd      	= $row->nombreProd;
			$tmp->detalle 	  	  	= $row->detalle;
			$tmp->precioCosto     	= $row->precioCosto;
			$tmp->stock 	  	  	= $row->stock;
			$tmp->cantidad 	  	  	= $row->cantidad;
			$tmp->precioCostoActual = $row->precioCostoActual;
			$tmp->total 	      	= $row->total;
			array_push($ret,$tmp );
		}
		mysqli_free_result($result);
	
		      return ($ret);
	}
	
	public function expPDF($detalle){		
		$file = "grid3.html";
		$handle = fopen($file,"w");
	
		$tmpl = fread($handle, filesize($file));
	
		$grid1 = '';
					
			foreach ($detalle AS $de){					
					$grid1 .= '<tr>'.							
							'<td><div align="center" class="Estilo3">'.$de->ObservacionProd.'</div></td>'.
							'<td><div align="center" class="Estilo3">'.$de->nombreCategoria.'</div></td>'.
							'<td><div align="center" class="Estilo3">'.$de->nombreMarca.'</div></td>'.
						 '</tr>';
				
			}
		
		$tmpl = str_replace("#grid1#",$grid1,$tmpl);	
	
		$dompdf = new DOMPDF();
		$dompdf->load_html($tmpl);
		//$dompdf->set_paper("c4","portrait");
		$dompdf->render();
		//$pdfout = $dompdf->output();
		$filename = "productos.pdf";
		//$fp = fopen($filename, "a");
		//fwrite($fp,$pdfout);
		//fclose($fp);
		
		return $filename;	

	}
	
} 		
?>