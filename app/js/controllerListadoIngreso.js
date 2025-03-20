'use strict';
angularRoutingApp.controller('controllerListadoIngreso', ['$scope', '$http',
    function ($scope, $http) {
    
         $scope.detIngresos       = [];  
         $scope.listIngresos      = [];  
         $scope.activoIngreso     = 0;
         $scope.loading        = false; 

         
$scope.init = function () {
	
$scope.tipoBusqueda ="Ingreso";
    document.getElementById('divPedido').style.display             = 'none';    
	
	
    if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }    
    
    
    $scope.tipBusq    = "Ingreso";       

    
      $http.get('FunctionIntranet.php?act=listarProvSelection&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);     
           $scope.proveedores = data;    

            }).
            error(function(data, status, headers, config) {
                console.log("error listar proveedores: "+data);
      });
};        
        
$scope.selProveedor = function () {
        $scope.prove  = ($scope.proveedor == undefined ? "": $scope.proveedor.id_proveedor);

}
         
$scope.listarIngresos = function () {
    
    var desde  = angular.element(document.querySelector("#desdeb")).val();
    var hasta  = angular.element(document.querySelector("#hastab")).val();
    
      $http.get('FunctionIntranet.php?act=listarIngresos&tienda='+$scope.tiendaSeleccionada+'&fechDesde='+desde+'&fechHasta='+hasta+'&idProv='+$scope.prove+'&tipBusq='+$scope.tipoBusqueda).success(function(data) {
           console.log(data);     
           $scope.listIngresos = data;    

            }).
            error(function(data, status, headers, config) {
                console.log("error listar Ingresos: "+data);
      });

}; 
        
        
$scope.selTipoBusqueda = function (rdo) { 
            $scope.tipBusqueda = rdo;
};
        
        
$scope.cargarIngresos = function (selIngresos) {
    
     $scope.rutMod     = selIngresos.rut;
     $scope.razonMod   = selIngresos.nombre;
    
      $http.get('FunctionIntranet.php?act=listarDetalleIngresos&tienda='+$scope.tiendaSeleccionada+'&idIngresos='+selIngresos.id).success(function(data) {
           console.log(data);     
           $scope.detIngresos = data;    

            }).
            error(function(data, status, headers, config) {
                console.log("error listar Ingresos: "+data);
      });
};    
        
        
        
        


$scope.cargarObser = function (selIngresos) {
    
document.getElementById('fechaCobro').value="00000-00-00";    
    
    
var mydate=new Date();
var year=mydate.getYear();
if (year < 1000)
year+=1900;
var day=mydate.getDay();
var month=mydate.getMonth()+1;
if (month<10)
month="0"+month;
var daym=mydate.getDate();
if (daym<10)
daym="0"+daym;


        
     $scope.codTipoCobro = selIngresos.estado_cobranza;
     $scope.codEstadoPed = selIngresos.estado_pedido;
    
    
     $scope.idIngresos     = selIngresos.id;
     $scope.observacion    = selIngresos.observacion;
    
     $scope.rutModificar     = selIngresos.rut;
     $scope.razonMod         = selIngresos.nombre;
    
     $scope.cod_documento    = selIngresos.cod_documento;
    
    
   if(selIngresos.fecha_cobro == null || selIngresos.fecha_cobro =="" ){              
      document.getElementById('fechaCobro').value= year+"-"+month+"-"+daym;       
   }else{       
       var arrayfecha = selIngresos.fecha_cobro.split('-');      
       document.getElementById('fechaCobro').value= arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];       
   }         
    
    
    
    
    
 $scope.cobros = {
  availableOptions: [
    {id: '0', name: '-- Tipo de Pago --'},  
    {id: '1', name: 'Efectivo'},
    {id: '2', name: 'Documento a Fecha'},
    {id: '3', name: 'Documento al Dia'},
    {id: '4', name: 'Pendiente'}  
  ],
  selectedOption: {id: $scope.codTipoCobro} //This sets the default value of the select in the ui
}
 
 
$scope.estadoPedido = {
  availableOptions: [
    {id: '0', name: '-- Estado Pedido --'},  
    {id: '1', name: 'Entregado'},
    {id: '2', name: 'Incompleto'},
    {id: '3', name: 'Pendiente'}  
  ],
  selectedOption: {id: $scope.codEstadoPed} //This sets the default value of the select in the ui
} 


    
};
        
        
        
        
  
$scope.getNeto = function(){
    var total = 0;
    for(var i = 0; i < $scope.detIngresos.length; i++){
        var product = $scope.detIngresos[i];
        total += parseInt( product.valor_neto );
    }
    return total;
};    
        

                
     
        
$scope.getIva= function(){
    var total = 0;
    var   iva = 0.19;
    
    for(var j = 0; j < $scope.detIngresos.length; j++){
        var product = $scope.detIngresos[j];
             total += parseInt(product.valor_neto);
    }
    
    total = parseInt(total) * (iva);
    return parseInt(total);
};    
        
        
$scope.getTotalIngreso = function(){
     var total = 0;
     var iva = 0.19;
   for(var j = 0; j < $scope.detIngresos.length; j++){
        var product = $scope.detIngresos[j];
        total += parseInt(product.valor_neto);
    }
       var totalNeto = (parseInt(total));
               total = (parseInt(total) * iva) + parseInt(totalNeto);
    return parseInt(total);
}; 
        

        
$scope.guadarObservacion = function () {
    
      $http.get('FunctionIntranet.php?act=guardarObserFact&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.idIngresos+
                                                  '&obse='+$scope.observacion+
                                                  '&estCobro='+$scope.cobros.selectedOption.id+
                                                  '&estPedido='+$scope.estadoPedido.selectedOption.id+
                                                  '&fechCobro='+angular.element(document.querySelector("#fechaCobro")).val()+
                                                  '&numDoc='+$scope.cod_documento).success(function(data) {
           console.log(data);     
              var respuesta = data.charAt(data.length-1);

              if(respuesta=="1"){                
                   alertify.success("Modificado con exito!");
                }else{
                    alertify.error("Error al modificar, favor contactar con el Administrador del Sistema.");
                }

            }).
            error(function(data, status, headers, config) {
                console.log("error mostrar observacion Ingresos: "+data);
      });
};
		
		
$scope.listarEditar = function(indexProd){
	    document.getElementById('divPedido').style.display             = 'block';    
        document.getElementById('divPedidoListado').style.display      = 'none';
	
	 $scope.rutMod     = indexProd.rut;
     $scope.razonMod   = indexProd.nombre;
	 $scope.idIngreso  = indexProd.id;
	 $scope.activoIngreso = indexProd.activo;
 	 $scope.numFactura = indexProd.num_factura;
   
	$scope.loading=true;
	$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarDetalleIngresos&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idIngresos:indexProd.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
        $scope.detIngresos = data;    
		$scope.loading=false;
  }).error(function(error){
        console.log(error);

   });

	
};		
		
$scope.methodA = function(){
	//alertify.alert("tes1");
}



$scope.methodPreParticular = function(prodDet, modInpur){
	var length = $scope.detIngresos.length; 
	var objProd = new Object();
	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.detIngresos;       
       if(objProd[i].id_prod == prodDet.id_prod){          
              $scope.detIngresos[i].precioPart  = parseInt(modInpur);    		   
           break;
       }             
    }
}


$scope.methodB = function(prodDet, modInpur){
	var length = $scope.detIngresos.length; 
	var objProd = new Object();
	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.detIngresos;       
       if(objProd[i].id_prod == prodDet.id_prod){          
              $scope.detIngresos[i].precioVenta  = parseInt(modInpur);    		   
           break;
       }             
    }
};
		
		
$scope.methodVenta = function(prodDet, modInpur){
	var length = $scope.detIngresos.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.detIngresos;
       
       if(objProd[i].id_prod == prodDet.id_prod){
              $scope.detIngresos[i].valor_neto  = parseInt(modInpur);		   
           break;
       }             
    }
};		
        
        
$scope.methodCantidad = function(prodDet, modInpur){
	var length = $scope.detIngresos.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.detIngresos;
       
       if(objProd[i].id_prod == prodDet.id_prod){
              $scope.detIngresos[i].cantidad  = parseInt(modInpur);		   
           break;
       }             
    }
};		
	        
		
		
$scope.getNeto= function(){
    var total = 0;
    for(var i = 0; i < $scope.detIngresos.length; i++){
        var product = $scope.detIngresos[i];
        total += Math.round(product.valor_neto * product.cantidad);
    }
    return Math.round(total);    
};    

        
$scope.getIva= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.detIngresos.length; j++){
        var product = $scope.detIngresos[j];
        total += Math.round(product.valor_neto  * product.cantidad);
    }
    total =Math.round(total) * (iva);
    return Math.round(total);
};   		
		
		
$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.detIngresos.length; j++){
        var product = $scope.detIngresos[j];
        total += Math.round(product.valor_neto  * product.cantidad);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);
return Math.round(total);
};    
		

$scope.guardarModFact = function(){
	 var arrayProd    = [];
	       for(var i = 0; i < $scope.detIngresos.length; i++){
                    var objDeta = new Object();
                     objDeta.id_prod     = $scope.detIngresos[i].id_prod;
                   //  objDeta.valor_neto  = Math.round($scope.detIngresos[i].valor_neto * $scope.detIngresos[i].cantidad);
                     objDeta.valor_neto  = Math.round($scope.detIngresos[i].valor_neto);//Valor precio costo con IVA			         
                     objDeta.precioVenta = Math.round(($scope.detIngresos[i].precioVenta)/(1.19));
                     objDeta.difPrecio   = Math.round($scope.detIngresos[i].precioVenta - $scope.detIngresos[i].precioVentaAnt); 
                     objDeta.idIng       = $scope.idIngreso;
                     objDeta.activo      = $scope.detIngresos[i].activo;
                     objDeta.precioPart  = $scope.detIngresos[i].precioPart;
                     objDeta.cantidad    = $scope.detIngresos[i].cantidad;


                     arrayProd.push(objDeta);
           }
	
	 var objProd   = JSON.stringify(arrayProd);
	
	
	$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=updatePrecioFactura&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrProd:objProd}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
   
		 var respuesta = data.charAt(data.length-1);
		
		
		  if(respuesta=="1"){
			  $scope.refreshDetalleProd();
			   alertify.success("Actulizacion generado con exito!");
		   }else{
			   alertify.error("Error al actulizar registro, favor contactar con el administrador!");
		   }
		
  }).error(function(error){
        console.log(error);

});
}
	
	
$scope.confirmarIngreso = function () { 
    alertify.confirm("¿Esta seguro que desea guardar el detalle?", function (e) {
        if (e) {                     
            $scope.guardarModFact();            
        }
    });    
};	

	
$scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block';      
      $scope.detIngresos ="";
  
	
};		
		
		
$scope.confirmarActive = function (value) { 
	var texto = '';
	if(value =='1'){
	   texto ='Activar';
	 }else{
	   texto ='Desactivar';
     }
	
	if($scope.activoIngreso != value){	
		alertify.confirm("¿Esta seguro que desea "+texto+" esta factura?", function (e) {
			if (e) {                     
				$scope.setActiveIng(value);            
			}else{
				
				if(value =='1'){
				   $scope.activoIngreso='0';
				 }else{
				   $scope.activoIngreso='1';
				 }
			}
		});    
	}else{
	  		alertify.alert("La factura ya se encuetra "+ texto);
		
	}
};			
		
		
  $scope.setActiveIng=function(value){
	  $scope.activoIngreso = value;
 
      $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=activarDesactivarFact&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({idIng:$scope.idIngreso,
                            activo:value
                                       }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		  
		 var respuesta = data.charAt(data.length-1);
		  	  if(respuesta=="1"){
			   alertify.success("Factura activa con exito!");
		   }else{
			   alertify.error("Error al actulizar registro, favor contactar con el administrador!");
		   }
          
        
          }).error(function(error){
                        console.log(error);
    
    });  
  };		
		
		
$scope.confirmarActiveProd = function(prodDet, value){
	var length = $scope.detIngresos.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.detIngresos;
       
       if(objProd[i].id_prod == prodDet.id_prod){
              $scope.detIngresos[i].activo  = value;		   
           break;
       }             
    }
}		

$scope.refreshDetalleProd = function(){
  $scope.loading=true;
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarDetalleIngresos&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idIngresos: $scope.idIngreso }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
        $scope.detIngresos = data;    
	    $scope.loading=false;
		
  }).error(function(error){
        console.log(error);

   });
	
	
};
        
         
$scope.consultarProductoNodo = function(prodDea){
    
    $scope.nombreProdHijo = prodDea.nombreProd;
    
    
    
    
        $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarProductoHijo&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_prod:prodDea.id_prod}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
    }).success(function(data){
            
         $scope.detallePadre = data;  
            
    }).error(function(error){
        console.log(error);

    });
    
    
    
}        
               
        

//NUEVAS FUNCIONES CONEXION NUBOX

$scope.buscarEstadoSII = function(indexProd){

    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=updateEstadoCompraSII&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({folio:indexProd.num_factura, rut: indexProd.rut, id_factura: indexProd.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
    }).success(function(data){
        indexProd.estadoSII = data;     
    }).error(function(error){
        console.log(error);

    });
};	

$scope.generarPDFSII = function(indexProd){

    $http({
     method : 'POST',
     responseType: 'blob',
     url : 'FunctionIntranet.php?act=obtienePDFCompraSII&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({folio:indexProd.num_factura, rut: indexProd.rut, id_factura: indexProd.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
    }).success(function(data){
        var blob = new Blob([data], { type: 'application/pdf' });
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'DOCUMENTO-'+indexProd.num_factura+'.pdf'; // You can also get filename from Content-Disposition header in the response
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }).error(function(error){
        console.log(error);

    });
};
		
  
}]);