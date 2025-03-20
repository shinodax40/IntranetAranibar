angularRoutingApp.controller('controllerCliente', ['$scope', '$http',
    function ($scope, $http) {        
              $scope.dgCliente      = [];
              $scope.tipoBusqueda   = "1";
              document.getElementById('graficoAdmin').style.display    = 'none';       
              $scope.detPedido      = [];
              $scope.loading=false; 
              $scope.customFi=false;
               $scope.codEstadoCobro = 4;
        
        
   
        
        var weekdays = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];

        var mydate=new Date();
        var weekday = weekdays[mydate.getDay()];
        
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
        
$scope.init = function () {    
    

    
        document.getElementById('divPedido').style.display         = 'none';   
        document.getElementById('divPedidoCliente').style.display  = 'none';   

    
   document.getElementById('fIngreso').value= year+"-"+month+"-"+daym;
   document.getElementById('fIngresoHasta').value= year+"-"+month+"-"+daym;
    
    
    
    
    $scope.test = {};
       $scope.custom = true;

    
   if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
    }
    
    $scope.nombClie="";
    $scope.listarVendedores();
    $scope.listarSectores();
    

    $scope.tipoCliente = {
      availableOptions: [
          
        {id: 0, name: '--Seleccione Tipo--'},          
        {id: 1, name: 'Mascotero'},
        {id: 2, name: 'Particular'},
        {id: 3, name: 'Almacen'},  
        {id: 4, name: 'Veterinaria'},
        {id: 5, name: 'Avicola'},  		  
        {id: 6, name: 'Peluqueria'}  ,		  
        {id: 7, name: 'Otro'}  		  

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    }	
};


        
$scope.guardarObservacionCli = function(obsCLie, cli){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=guardarObsClie&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idObs:obsCLie,
                             idClie:cli.id_cliente                         
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="0"){
                
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          

          }).error(function(error){
                console.log(error);
        });
}        
        
		
$scope.selTipoObservacion = function(obsCLie, cli){
	var obSelect='';
	switch(obsCLie) {
     case 'Abastecido':
        obSelect = '1';
        break;
     case 'No le ha ido muy bien':
        obSelect = '2';
        break;
     case 'Lo llamo y no contesta':
        obSelect = '3';
        break;
	 case 'Voy y está cerrado':
        obSelect = '4';
        break;
     case 'Vacaciones':
        obSelect = '5';
        break;
     case 'Pendiente en visitar':
        obSelect = '6';
        break;	
	 case 'Compro en otro lado':
        obSelect = '7';
        break;			
	 case 'Llamar la proxima semana':
        obSelect = '8';
        break;	
	 case 'Pendiente en llamar':
        obSelect = '9';
        break;		
	 case 'Llamara cuando necesite':
        obSelect = '10';
        break;			
	 		
    default:
        ''
}
			 
	 $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=updateObservacionClie&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idObs:obSelect,
                             idClie:cli.id_cliente                         
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Obervacion guardado con exito!");                   
                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          

          }).error(function(error){
                console.log(error);
        });
	
	
	
};		
		

$scope.buscarClientes = function(){     
$scope.loading = true;
var  resp = ($scope.vendedores.selectedOption.id.toString() == "0" ? "":$scope.vendedores.selectedOption.id.toString());   
var  resp2 =  ($scope.tipoCliente.selectedOption.id.toString() == "0" ? "":$scope.tipoCliente.selectedOption.name.toString());  
    

        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarClientesEmpresa&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({nombreClie:$scope.nombClie,
                             responsable:resp,
                             tipoComp:resp2                           
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                       $scope.dgCliente = data;
                $scope.loading = false;

          }).error(function(error){
                console.log(error);
        });
};



$scope.refrescar = function(){    
    $scope.nombClie="";
    $scope.buscarClientes();
};


$scope.listarVendedores = function(){        
    var arrayDeta     = [];
    var objDeta= new Object();

    $http.get('FunctionIntranet.php?act=listarVendedores&tienda='+$scope.tiendaSeleccionada).success(function(data) {
            console.log(data);         
           
           $scope.listVendedores = data;
                objDeta= new Object();
                objDeta.name      = "--Seleccionar Vendedor--";
                objDeta.id        = 0; 
                arrayDeta.push(objDeta);
     
          
              for (var i = 0; i <    data.length; i++) {   
                        objDeta= new Object();
                        objDeta.name      = data[i].name;
                        objDeta.id        = data[i].id; 
                        arrayDeta.push(objDeta);
              }
           
           
           
           
           
           $scope.vendedores = {
              availableOptions: arrayDeta,                      
              selectedOption: {id: 0} 
            }
           
            }).
            error(function(data, status, headers, config) {
                console.log("error al listar vendedores: "+data);
            });
    
};



$scope.clienteMod = function(cli){       
    $scope.dirUbiGeo    = cli.ubicacion_geo; 
    $scope.dirModDeta   = cli.deta_direccion;
    
    
    $scope.custom           = true;
    $scope.giroMod          = cli.giro;
    $scope.idCliente        = cli.id_cliente;    
    $scope.rutModificar     = cli.rut;
    $scope.razonMod         = cli.nombre;
    $scope.dirMod           = cli.direccion;
    $scope.telMod           = cli.telefono;
    $scope.idUsuario        = (cli.id_usuario);
    $scope.observacion      = cli.observacion;
    $scope.test.currentVal  = parseInt(cli.activo);
    $scope.idSector        = (cli.id_sector);
   
    $scope.vendedores = {
      availableOptions: $scope.listVendedores,                      
      selectedOption: {id: $scope.idUsuario} 
    }
    
     $scope.sectoresLists = {
      availableOptions: $scope.listSectores,                      
      selectedOption: {id: $scope.idSector} 
    }


    if(cli.tipo_comprador == "Particular"){
       $scope.custom = false;
    }    
    
    
    $scope.tipoCli = {
          availableOptions: [
            {id: 'Avicola',    name: 'Avicola'},			  
            {id: 'Almacen',     name: 'Almacen'},
            {id: 'Mascotero',   name: 'Mascotero'},
            {id: 'Particular',  name: 'Particular'},			  
            {id: 'Veterinaria', name: 'Veterinaria'},
            {id: 'Peluqueria', name: 'Peluqueria'},
	        {id: 'Otro'       , name: 'Otro'}
		  
          ],
      selectedOption: {id: cli.tipo_comprador} //This sets the default value of the select in the ui
    }
};
		
		



$scope.guardarCliente = function(accionCliente){
    var objClie = new Object();
    var arrClientes= [];
    
    objClie.nombre      =   $scope.razonMod;
    objClie.direccion   =   $scope.dirMod;
    objClie.telefono    =   $scope.telMod;
    objClie.rut         =   $scope.rutModificar;
    objClie.idCliente   =   $scope.idCliente;
    objClie.idUsuario   =   $scope.vendedores.selectedOption.id;
    objClie.tipoCliente =   $scope.tipoCli.selectedOption.id;
    objClie.giro        =   $scope.giroMod;
    objClie.obserMod    =   $scope.observacion;
    objClie.accion      =   accionCliente;
    objClie.activo      =   $scope.test.currentVal;
    objClie.idSector    =   $scope.sectoresLists.selectedOption.id;

    

    
    objClie.dirUbiGeo    =   $scope.dirUbiGeo;
    objClie.dirModDeta    =   $scope.dirModDeta;

    
    

    arrClientes.push(objClie);
    
    var objClientes    = JSON.stringify(arrClientes);
    
        
      $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=guardarCliente&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({arrClie:objClientes}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
                var respuesta = data.charAt(data.length-1);

                    if(respuesta=="1"){                    
                        alertify.success("Accion realizado con exito!");
                                 $scope.nuevoCliente();
                                 $("#myModalNuevoCliente").modal("hide");

                    }else if(respuesta=="2"){
                        alertify.error("Error al modificar cliente, favor contactar con el Administrador del Sistema.");
                    }else if(respuesta=="3"){
                             alertify.error("Cliente ya se encuentra registrado.");
                    }         
                             
      }).error(function(error){
            console.log(error);
            $scope.loadingProd = false;

      });
};



$scope.nuevoCliente = function(){
    $scope.giroMod      = "";
    $scope.idCliente    = "";    
    $scope.rutModificar = "";
    $scope.razonMod     = "";
    $scope.dirMod       = "";
    $scope.telMod       = "";
    $scope.idUsuario    = 0;
    $scope.observacion  = "";    
    $scope.listarVendedores();
    $scope.listarSectores();


    $scope.vendedores = {
      availableOptions: $scope.listVendedores,                      
      selectedOption: {id: 0} 
    }
    
    
    $scope.sectoresLists = {
      availableOptions: $scope.listSectores,                      
      selectedOption: {id: 0} 
    }


     $scope.tipoCli = {
      availableOptions: [
            {id: 'Avicola',     name: 'Avicola'},			  
            {id: 'Almacen',     name: 'Almacen'},
            {id: 'Mascotero',   name: 'Mascotero'},
            {id: 'Particular',  name: 'Particular'},			  
            {id: 'Veterinaria', name: 'Veterinaria'},
            {id: 'Peluqueria',  name: 'Peluqueria'},
            {id: 'Otro',        name: 'Otro'}

          

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    }    
};


       
                                         
$scope.generarReporteClientes = function () {
  var arrayDeta = [];
  var count=0;    

  for (var i = 0; i <    $scope.dgCliente.length; i++) {  
    
    
     count = count + 1   ;
     var objDeta= new Object();           
       var arrayfecha = $scope.dgCliente[i].fecha.split('-');            
                objDeta.id_cliente     =  $scope.dgCliente[i].id_cliente;
                objDeta.nombre         =  $scope.dgCliente[i].nombre;
                objDeta.direccion      =  $scope.dgCliente[i].direccion;
                objDeta.telefono       =  $scope.dgCliente[i].telefono;
                objDeta.countFecha     =  $scope.dgCliente[i].countFecha;
                objDeta.fecha          =   arrayfecha[2]+"-"+arrayfecha[1]+"-"+arrayfecha[0];    
      
      
             /*  if($scope.dgCliente[i].tipo_comprador == "Mascotero"){      
                     if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }                
                }else if($scope.dgCliente[i].tipo_comprador == "Particular"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                }else if($scope.dgCliente[i].tipo_comprador == "Almacen"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                         objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                         objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                }else if($scope.dgCliente[i].tipo_comprador == "Veterinaria"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                } else if($scope.dgCliente[i].tipo_comprador == "Peluqueria"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                } else if($scope.dgCliente[i].tipo_comprador == "Avicola"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                }  else if($scope.dgCliente[i].tipo_comprador == "Otro"){
                    if(parseInt($scope.dgCliente[i].countFecha) == 0){
                          objDeta.urlImg     = 'img/verde.png';
                     }else{                     
                          objDeta.urlImg     = 'img/rojo.jpg';
                     }     
                }  */
      
      
      
                   if($scope.dgCliente[i].verde  == true){
                    
                    objDeta.urlImg     = 'img/verde.png';
                    
                }else if($scope.dgCliente[i].rojo == true){
                         
                     objDeta.urlImg     = 'img/rojo.jpg';    
                   
                }else if($scope.dgCliente[i].amarillo  == true ){
                    
                     objDeta.urlImg     = 'img/amarillo.jpg';     
                         
                }
      
      
      
      
               if($scope.dgCliente[i].activo=="1"){
                   objDeta.activo     = 'ON';
                }else{
                   objDeta.activo     = 'OFF';
                }

        arrayDeta.push(objDeta);    
      
}
   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'detalle':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=gerexpClientePDF&tienda='+$scope.tiendaSeleccionada,
            data: $.param(objectToSerialize),
            method: 'POST',
            responseType: 'arraybuffer',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
             }
        }
              
        $http(config).then(function successCallback(response) {              
        var linkElement = document.createElement('a');
        try {
            var blob = new Blob([response.data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            var filename ="Cliente.pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
         
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
        } catch (ex) {
            console.log(ex);
        }
            
        }, function errorCallback(response) {
            console.log("error");
        });    
}; 

        
        
$scope.listarProd = function (indexProd) {
  $scope.listProdCli= [];
  
  document.getElementById('desdeb').value="";
  document.getElementById('hastab').value="";    
  
  $scope.idClie     = indexProd.id_cliente ;
  $scope.nombreClie = indexProd.nombre ;     

};        
        
 
         
$scope.listarPedidoPendienteFact = function (indexClie){ 
     $scope.loading = true;

         $scope.estadoCobro = {
          availableOptions: [
            {id: '4', name: '-- Estado Cobro --'},  
            {id: '1', name: 'Efectivo'},
            {id: '3', name: 'Documento'},
            {id: '6', name: 'Transferencia'}

          ],
          selectedOption: {id: '4'} //This sets the default value of the select in the ui
        }
    
    document.getElementById('divPedidoListado').style.display            = 'none';   
    document.getElementById('divPedidoCliente').style.display             = 'block';    
    
     var fIngreso  = angular.element(document.querySelector("#fIngreso")).val();
      var fEntrega  = angular.element(document.querySelector("#fIngresoHasta")).val();
    
    $scope.listCliePedPend= [];
    $scope.idClie     = indexClie.id_cliente ;
    $scope.nombreClie = indexClie.nombre ; 

  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarPedClientesCobro&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idCliente:indexClie.id_cliente,
                     idTipoBusqueda:$scope.tipoBusqueda,
                     desde:fIngreso,
                     hasta:fEntrega}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
            $scope.loading = false;

            $scope.listCliePedPend = data;
           
  }).error(function(error){
        console.log(error);

});         
    
    
    $scope.listDetaCobraClie(indexClie);
    
};  
        
        
$scope.listDetaCobraClie = function(indexClie){
    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarCobranzaDetaCliente&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idCliente:indexClie.id_cliente}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
            

            $scope.cobranza_act = data[0].cobranza_act;
            $scope.limitado_act = data[0].limitado_act;
            $scope.compNetReal  = data[0].saldoPendiente;
            $scope.limtCompraNet= data[0].limite_compra;
            $scope.obserCobra   = data[0].obs_cobranza;
                  $scope.credito   = data[0].credito;

           
  }).error(function(error){
        console.log(error);

});  
    
    
}        
  
        
$scope.getNeto= function(){
    var total = 0;
    for(var i = 0; i < $scope.detPedido.length; i++){
        var product = $scope.detPedido[i];
        total += Math.round(product.cantidad * product.precio_vendido);
    }
    return Math.round(total);    
};    
        
        
$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += Math.round(product.cantidad * product.precio_vendido);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);
return Math.round(total);
};      
          
$scope.getNeto= function(){
    var total = 0;
    for(var i = 0; i < $scope.detPedido.length; i++){
        var product = $scope.detPedido[i];
        total += Math.round(product.cantidad * product.precio_vendido);
    }
    return Math.round(total);    
};   
        
$scope.cargarPedido = function (idIndex) {
$scope.idPedido = idIndex.id;
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:idIndex.id}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedido = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    

};
        
        
        
$scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedidoCliente').style.display             = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block';     
       $scope.tipoBusqueda ="1";
    $scope.customFi=false;
      $scope.mostBotFact  = true;
}        
        
$scope.buscarListProd = function (){
     $scope.desde  = angular.element(document.querySelector("#desdeb")).val();
     $scope.hasta  = angular.element(document.querySelector("#hastab")).val();    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarClientesProd&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id:  $scope.idClie,
                     desde: $scope.desde,
                     hasta: $scope.hasta}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listProdCli = data;
           
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  
    
    
};
        
        
  $scope.setTransactionDebit=function(value){
        $scope.test.currentVal=    value;
  };
        
        
        
$scope.initGraficoActual  = function(indexClie){   
     $scope.idClie     = indexClie.id_cliente ;
    $scope.nombreClie = indexClie.nombre ; 
    document.getElementById('graficoAdmin').style.display         = 'none'; 

    
	$scope.totalActual = [];
	var banderaMes = false;	
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesClienteActual&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idCliente:$scope.idClie}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
        /******************************/ 	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalActual.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalActual.push(0);

			  }
	      } 
		/************************/
        
        $scope.initGraficoAnterior();

          }).error(function(error){
                        console.log(error);
    
    }); 
}


        
$scope.initGraficoAnterior  = function(){   
	$scope.totalAnterior = [];
	var banderaMes = false;	
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=sumaTotalPorMesClienteAnterior&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idCliente:$scope.idClie}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data.total);
        
        /******************************/ 	
		for(var x=1; x < 13 ; x++){
			  banderaMes = false;
			  for (var i = 0; i < data.length; i++) {  
			     if(data[i].mes == x){
                       $scope.totalAnterior.push(parseInt(data[i].total));      
					   banderaMes  = true;
					 break;
				
				 }
			  }
			  
			  if(banderaMes == false){
					  $scope.totalAnterior.push(0);

			  }
	      } 
		/************************/
        $scope.grafico();

          }).error(function(error){
                        console.log(error);
    
    }); 
        document.getElementById('graficoAdmin').style.display         = 'block'; 

}






$scope.grafico = function (){    
    

 
    


    var mydate=new Date();
    var year=mydate.getYear();
    if (year < 1000)
    year+=1900;
    
 var pieChartContent = document.getElementById("pieChartContent"); 
pieChartContent.innerHTML = "&nbsp;"; 
$("#pieChartContent").append("<canvas id=pieChart width=300 height=300><canvas>"); 
    

/*var speedCanvas = document.getElementById("speedChart");*/

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 18;


    
var dataFirst = {
  label: year,
  data: $scope.totalActual,
  lineTension: 0.3,
     backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(172,53,47,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(172,53,47,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(172,53,47,1)",
                        pointHoverBorderColor: "rgba(172,53,47,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                            spanGaps: true
};
   
var dataSecond = {
   label: parseInt(year-1),
                        fill: true,
                        lineTension: 0.1,
                        backgroundColor: "rgba(255,172,53,0.4)",
                        borderColor: "rgba(255, 172, 53,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(255, 172, 53,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalAnterior,
                        spanGaps: true,
};
   
var speedData = {
    responsive: true,
  labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
  datasets: [dataFirst, dataSecond]
};    
    
    
 $scope.chartOptions = {
  legend: {
    display: true,
    position: 'top',
    labels: {
      boxWidth: 80,
      fontColor: 'black'
    }
  }
};

    
 $scope.configP = {
  type: 'line',
  data: speedData,
  options: $scope.chartOptions
}    
   

 // var pieChart = new Chart(pieChartContent, $scope.configP); 

  var  ctx = $("#pieChart").get(0).getContext("2d");   
  var  pieChart = new Chart(ctx, $scope.configP); 
    

   /*
           const CHART = document.getElementById("lineChart").getContext('2d');

    
           let lineChart = new Chart(CHART,{
                type:'line',
                options : {responsive: true},
                data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                datasets: [
                    {
                        label: year,
                        fill: true,
                        lineTension: 0.1,
                        backgroundColor: "rgba(172,53,47,0.4)",
                        borderColor: "rgba(172,53,47,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(172,53,47,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(172,53,47,1)",
                        pointHoverBorderColor: "rgba(172,53,47,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalActual,
                        spanGaps: true,
                    }
					,
					 {
                        label: parseInt(year-1),
                        fill: true,
                        lineTension: 0.1,
                        backgroundColor: "rgba(255,172,53,0.4)",
                        borderColor: "rgba(255, 172, 53,1)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "rgba(255, 172, 53,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderColor: "rgba(255, 172, 53,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: $scope.totalAnterior,
                        spanGaps: true,
                    }
                
                
                
                
                ]
               }


            })*/
    

};        
 

$scope.listarSectores = function(){        
    var arrayDeta     = [];
    var objDeta= new Object();

    $http.get('FunctionIntranet.php?act=listarSector&tienda='+$scope.tiendaSeleccionada).success(function(data) {
            console.log(data);         
           
           $scope.listSectores = data;
                objDeta= new Object();
                objDeta.name      = "--Seleccionar Sector--";
                objDeta.id        = 0; 
                arrayDeta.push(objDeta);
     
          
              for (var i = 0; i <    data.length; i++) {   
                        objDeta= new Object();
                        objDeta.name      = data[i].name;
                        objDeta.id        = data[i].id; 
                        arrayDeta.push(objDeta);
              }
           
           
           
           
           
           $scope.sectoresLists = {
              availableOptions: arrayDeta,                      
              selectedOption: {id: 0} 
            }
           
            }).
            error(function(data, status, headers, config) {
                console.log("error al listar sectores: "+data);
            });
    
};


$scope.verClienteRojo = function(){
  document.getElementById('divPedidoListado').style.display      = 'none';   
    document.getElementById('divPedido').style.display             = 'block';  
    
    
    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarClientesRojo&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idUsua:''                        
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                
           $scope.clientsRojo = data;
          

          }).error(function(error){
                console.log(error);
        });
    
    
}




$scope.cargarDetalleRecibo = function (idIndex) {
    
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarRecibosClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:idIndex.id}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               
               $scope.detPedidosRecibo = data;
               $scope.observacionRecibo = data[0].observacion;
        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    

};
        
        
 $scope.selTipoComprador = function (rdo) {   
   if(rdo=="Pendientes"){   
        $scope.listCliePedPend= [];
        $scope.customFi = false;     
        $scope.tipoBusqueda ="1";
        $scope.listarCrobroFiltro();
    }else if(rdo=="Pagados" ){
        $scope.listCliePedPend= [];
         $scope.customFi = true; 
         $scope.tipoBusqueda ="2";
    }
 
};        
        
        
        
        
$scope.listarDirecciones = function (cliente){     
  $scope.listDirecciones = [];

  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarDireccionesCliente&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_cliente: cliente.id_cliente}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listDirecciones = data;
      
  }).error(function(error){
        console.log(error);

});         
    
};            
        
        
        
        
$scope.listarCrobroFiltro = function (){     
 $scope.loading = true;    
 var fIngreso  = angular.element(document.querySelector("#fIngreso")).val();
 var fEntrega  = angular.element(document.querySelector("#fIngresoHasta")).val();
    
 

  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarPedClientesCobro&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idCliente: $scope.idClie ,
                     idTipoBusqueda:$scope.tipoBusqueda,
                     desde:fIngreso,
                     hasta:fEntrega}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listCliePedPend = data;
           $scope.loading = false;
  }).error(function(error){
        console.log(error);

});         
    
};           
        
    
$scope.cargarPedidoCobro = function(ped){
    $scope.idPedido = ped.id;
    $("#myModalCobro").modal();                  
 
}        
        
 $scope.modificarPedidoCobro = function(){
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarEstadoClienteCobro&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idPedido,
                            documento:$scope.cod_documento,
                            estadoCobro:$scope.estadoCobro.selectedOption.id}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Cobro guardado con exito!");
                    $scope.idPedido="";
                    $scope.listarCrobroFiltro();
                   $("#myModalCobro").modal("hide");

                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });    

};        
        

        

$scope.confirmarBloqueoCobra = function(value){
	$scope.cobranza_act = value;
};	
                
        
$scope.confirmarSinLimite = function(value){
	$scope.limitado_act = value;
};	
              
        

        
$scope.guardarDetalleCobranza = function(){

            $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=actualizarDetalleCobranza&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idCli:$scope.idClie,
                            actCobra:$scope.cobranza_act,
                            actLimit:$scope.limitado_act,
                            obsCobra:$scope.obserCobra,
                            limtCompra:$scope.limtCompraNet
                      
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Detalle Cobranza guardado con exito!");
   

                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });    
    

};	        
        
        
        
        
        
        
        
        
        
        

}]);