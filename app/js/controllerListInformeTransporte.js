angularRoutingApp.directive("fileInput", function($parse, $q, $timeout){  
    return{  
           link: function($scope, element, attrs){  
                element.on("change", function(event){  
                    let files = event.target.files;  
                    //console.log(files[0].name);  
                    getFileBuffer(files[0]).then(function(resp) {
                        $parse(attrs.fileInput).assign($scope, resp);
                        $timeout(function() {
                            $scope.$apply();  
                        });
                    });
                });  
           }  
    }
    function getFileBuffer(file) {
        let deferred = new $q.defer();
        let reader = new FileReader();
        reader.onloadend = function(e) {
            deferred.resolve(e.target.result);
        };
        reader.onerror = function(e) {
            deferred.reject(e.target.error);
        };
        reader.readAsDataURL(file);
        return deferred.promise;
    }
 });
 
angularRoutingApp.controller('controllerListInformeTransporte', ['$scope', '$http', '$location',
    function ($scope, $http, $location) {        
   
    $scope.totalNotaPedido        = 0;
    $scope.totalDebito            = 0;
    $scope.totalTransferencia     = 0;
    $scope.totalCajaVecina        = 0;
    $scope.validarGuardarInforme  = false;   
    $scope.validarBodegaInforme   = false;
    $scope.efectivoMod  = 0;           
    $scope.debitoMod    = 0;
    $scope.transferenciaMod = 0;
    $scope.cajavecinaMod    = 0;
    $scope.unirPedidos  = [];   
    $scope.detPedido    = [];
    $scope.pesoProd     = ""; 
    $scope.fotoUrl      = "";
    $scope.pedidoSelecNota="";
        

$scope.init = function () {     
    
    




   if($scope.tipoUsuarioLogin == 'Administrador' || $scope.tipoUsuarioLogin == 'Finanzas'){                
         $scope.validarGuardarInforme=true;
   }

    
  if($scope.tipoUsuarioLogin == 'Bodeguero'){                
         $scope.validarBodegaInforme=true;
   }    
    
    
    
     if($scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }
    
 $scope.listInformeTranspDeta = [];
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
    
document.getElementById('desdeb').value= year+"-"+month+"-"+daym;
document.getElementById('hastab').value= year+"-"+month+"-"+daym;        
    
document.getElementById('divPedido').style.display  = 'none'; 
document.getElementById('divPedidoProductos').style.display  = 'none'; 
 
     
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataTransporte&tienda='+$scope.tiendaSeleccionada,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                  $scope.listadoTransporte = [];                
                  $scope.listadoTransporte = data;
                  $scope.listadoTranp = {selected: "0"};

          }).error(function(error){
                        console.log(error);
    
      });   
    
};

  function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}


     
$scope.buscarListInforme = function (){

    
     $scope.desde  = angular.element(document.querySelector("#desdeb")).val();
     $scope.hasta  = angular.element(document.querySelector("#hastab")).val();    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listaInformeTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({desde: $scope.desde,
                     hasta: $scope.hasta,
                     id_inf:$scope.idInf}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listInformeTransp = data;
           
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  
    
    
};        
        

        
$scope.verDetalle = function($idTransp, $tranpObser, $idTranspPed, $selTransp){
    
      $scope.fotoUrl                = "";
    
    
      document.getElementById('divPedido').style.display                    = 'block';      
      document.getElementById('divInforme').style.display                   = 'none'; 
      document.getElementById('divPedidoProductos').style.display           = 'none';      

     if($idTranspPed.toString() == "1"){
        $scope.idTransporteRuta = "Transporte AZUL";
    }else if ($idTranspPed.toString() == "2"){
        $scope.idTransporteRuta = "Transporte ROJO";
    }else if ($idTranspPed.toString() == "3"){
        $scope.idTransporteRuta = "Transporte VERDE";
    }else if ($idTranspPed.toString() == "4"){
        $scope.idTransporteRuta = "Transporte AMARILLO";
    }
    
    $scope.estadoValidado = $selTransp.estado;
    
     $scope.idInformeTransp = $selTransp.id;
     $scope.obserTransp     = $tranpObser.toString();
    
     $scope.inicioKg     = parseInt($selTransp.inicio_km);
     $scope.finKg        = parseInt($selTransp.fin_km);
     $scope.cargaKg      = parseInt($selTransp.carga_combustible_km);
     $scope.valorKm      = parseInt($selTransp.valor_carga);
    
     $scope.fotoUrlInfo      = $selTransp.fotoInfTransp;

    
     $scope.listadoTranp = {selected: $selTransp.id_transporte_patente.toString()};

     $scope.listInformeTranspDeta = [];

      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleInformeTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idPedido: $idTransp}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listInformeTranspDeta = data;
          $scope.totalDetallesPedido();
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  

}




$scope.verDetalleActulizar = function(){
   
      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleInformeTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idPedido: $scope.idInformeTransp}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listInformeTranspDeta = data;
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  

}




        
 $scope.goLoginRuta = function (ruta){
     if(ruta.ubicacion_geo != ''){
    window.open('https://maps.google.com/?q='+ruta.ubicacion_geo+'&zoom=15&maptype=satellite');

  }  else{
      			   alertify.success("Sin ubicacion.");

  }
}


$scope.openImageModal = function(elem){     
 //       $("#modal-image").attr("src", $(elem).attr("src"));
    
    
    $scope.efectivoMod  = parseInt(elem.valor_total);           
    $scope.debitoMod    = parseInt(elem.debito_total);      
    $scope.transferenciaMod = parseInt(elem.transf_total);      
    $scope.cajavecinaMod    = parseInt(elem.cajavecina_total);      
    
    
    
      $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
     $scope.fotoUrl      = elem.foto;
     $scope.idInformeTransp    = elem.id;
    
        $scope.listInformeTranspDeta = [];

      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleInformeTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idPedido: elem.id}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listInformeTranspDeta = data;
          
           
          $scope.totalDetallesPedido();
           $("#myImageModal").modal('show'); 
           setTimeout($.unblockUI, 1000);   
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});     
    
} 


$scope.confirmarActivePago = function(prodDet){
	var length = $scope.listInformeTranspDeta.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.listInformeTranspDeta[i].id_pedido == prodDet.id_pedido){
           
           if($scope.listInformeTranspDeta[i].activePago == 1){
                $scope.listInformeTranspDeta[i].activePago  = 0;	
           }else if ($scope.listInformeTranspDeta[i].activePago  == 0 || $scope.listInformeTranspDeta[i].activePago == null){
                     
              $scope.listInformeTranspDeta[i].activePago  = 1;	         
           }
           break;
       }             
    }
};	
        
        
        




$scope.confirmarActiveProd = function(prodDet, value){
	var length = $scope.listInformeTranspDeta.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.listInformeTranspDeta[i].id_pedido == prodDet.id_pedido){
              $scope.listInformeTranspDeta[i].codCobro  = value;		   
           break;
       }             
    }
};	
        
        
$scope.confirmarEstadoAct = function(prodDet, value){
    
	var length = $scope.listInformeTranspDeta.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.listInformeTranspDeta[i].id_pedido == prodDet.id_pedido){
              $scope.listInformeTranspDeta[i].estado_pedido  = value;	
           break;
       }             
    }
};	        
        
        
        

$scope.getValorTotal = function(){
    var total = 0;
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
        var totalPed = $scope.listInformeTranspDeta[i];
        if(totalPed.codCobro == 1){
                total += parseInt( totalPed.total );
           }
    }
    return total;
};    
        
        
$scope.getValorTotalDebito = function(){
    var total = 0;
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
        var totalPed = $scope.listInformeTranspDeta[i];
        if(totalPed.codCobro == 5){
                total += parseInt( totalPed.total );
           }
    }
    return total;
}; 
        
        
        
$scope.getValorTotalTransferencia = function(){
    var total = 0;
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
        var totalPed = $scope.listInformeTranspDeta[i];
        if(totalPed.codCobro == 6){
                total += parseInt( totalPed.total );
           }
    }
    return total;
};  
        
        
$scope.getValorTotalCajaVecina = function(){
    var total = 0;
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
        var totalPed = $scope.listInformeTranspDeta[i];
        if(totalPed.codCobro == 2){
                total += parseInt( totalPed.total );
           }
    }
    return total;
};   
        
        
$scope.getValorTotalDocumentos = function(){
    var total = 0;
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
        var totalPed = $scope.listInformeTranspDeta[i];
        if(totalPed.codCobro == 3){
                total += parseInt( totalPed.total );
           }
    }
    return total;
};         
         
        
        
$scope.totalDetallesPedido = function(){
    

    
    $scope.totalNotaPedido        = $scope.getValorTotal();
    $scope.totalDebito            = $scope.getValorTotalDebito();
    $scope.totalTransferencia     = $scope.getValorTotalTransferencia();
    $scope.totalCajaVecina        = $scope.getValorTotalCajaVecina();
   
    
}       
   
        
$scope.eliminarConfirmarTransInf = function ($pdDet, $pdIndex) { 
    alertify.confirm("¿Esta seguro que desea eliminar pedido de la ruta?", function (e) {
        if (e) {                     
            $scope.eliminarTransInf($pdDet);            
        }
    });    
};	     
        
        
$scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divPedidoProductos').style.display           = 'none';      
      document.getElementById('divInforme').style.display                   = 'block';         
}
        
        

$scope.verListadoProductosSalida = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divPedidoProductos').style.display           = 'block';      
      document.getElementById('divInforme').style.display                   = 'none';   
    
    
      $scope.isAnythingSelectedMasivoRuta();
}
        
        
$scope.eliminarTransInf = function($pdIndex){

      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=eliminarPedidoTrans&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idPedido: $pdIndex.id_pedido}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
                 $scope.listInformeTranspDeta = [];
//$scope.verDetalle($pdIndex.id_pedido, '');
          $scope.verDetalleActulizar();
          
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  

}


$scope.validarInforme = function(){

      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=validarRutaInforme&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idInforme: $scope.idInformeTransp}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
			   alertify.success("Actulizacion generado con exito!");
           
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  

}

$scope.consultarInforTranpR = function(){

      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=insertarInformTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idInforme:  $scope.idInformeTransp,
                    idPedido: $scope.codPedido}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
			  var respuesta = data.charAt(data.length-1);
                    if(respuesta=="1"){
                        alertify.success("Pedido traspasado con exito!");
                         $scope.verDetalleActulizar();
                    }else{
                        alertify.error("Pedido ya existe en otra ruta, favor eliminar pedido para poder agregar.");
                    }                     
           
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});  

}

        
        

$scope.confirmarIngreso = function () { 
    alertify.confirm("¿Esta seguro que desea guardar el detalle?", function (e) {
        if (e) {                     
            $scope.guardarModFact();            
        }
    });    
};	
        
        
        
        
        
$scope.confirmarIngresoBodega = function () { 
    alertify.confirm("¿Esta seguro que desea guardar el detalle estado pedido?", function (e) {
        if (e) {                     
            $scope.guardarModFactBodega();            
        }
    });    
};	        
        
        
$scope.confirmarValidarRuta = function () { 
    alertify.confirm("¿Esta seguro que desea validar Informe Ruta?", function (e) {
        if (e) {                     
            $scope.validarInforme();            
        }
    });    
};	
        

        
        
$scope.methodObservacionTransp = function(prodDet, modInpur){
	var length = $scope.listInformeTranspDeta.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.listInformeTranspDeta;
       
       if(objProd[i].id_pedido == prodDet.id_pedido){
              $scope.listInformeTranspDeta[i].obser_transp  = (modInpur);		   
           break;
       }             
    }
};	        
        
        
$scope.methodVenta = function(prodDet, modInpur){
	var length = $scope.listInformeTranspDeta.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.listInformeTranspDeta;
       
       if(objProd[i].id_pedido == prodDet.id_pedido){
              $scope.listInformeTranspDeta[i].documento  = (modInpur);		   
           break;
       }             
    }
};	
        
$scope.guardarModFact = function(){
     var idTransporte  = ($scope.listadoTranp == undefined ? "": $scope.listadoTranp.selected); 
	 var arrayProd    = [];
	       for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
                    var objDeta = new Object();
                      objDeta.codCobro      = $scope.listInformeTranspDeta[i].codCobro;
                      objDeta.documento     = $scope.listInformeTranspDeta[i].documento;
                      objDeta.id_pedido     = $scope.listInformeTranspDeta[i].id_pedido;
                      objDeta.obsTransp     = $scope.obserTransp;
               
                      objDeta.obsInfor    = $scope.listInformeTranspDeta[i].obser_transp;
               
                      objDeta.estPedido     = $scope.listInformeTranspDeta[i].estado_pedido;

               
                      objDeta.idInforme     = $scope.idInformeTransp; 
                      objDeta.idUsuario     = $scope.idUsuarioLogin; 
               
               
                        objDeta.inicioKg      = $scope.inicioKg; 
                        objDeta.finKg         = $scope.finKg; 
                        objDeta.idTransporte  = idTransporte; 
                        objDeta.cargaKg       = $scope.cargaKg; 
                        objDeta.valorKm       = $scope.valorKm; 
                    objDeta.dataMonto     = "";/*$scope.rendicionMontos;*/ 

                      arrayProd.push(objDeta);
           }
	
	 var objProd   = JSON.stringify(arrayProd);
	
	$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=saveInformeTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrPed:objProd,
                    arrData:""/*$scope.rendicionMontos*/}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
   
		 var respuesta = data.charAt(data.length-1);
		
		
		  if(respuesta=="1"){
		//	  $scope.refreshDetalleProd();
			   alertify.success("Actulizacion generado con exito!");
		   }else{
			   alertify.error("Error al actualizar registro, favor contactar con el administrador!");
		   }
		
  }).error(function(error){
        console.log(error);

});
}



$scope.guardarModFactBodega = function(){
     var idTransporte  = ($scope.listadoTranp == undefined ? "": $scope.listadoTranp.selected); 
	 var arrayProd    = [];
	       for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
                    var objDeta = new Object();
                      objDeta.codCobro      = $scope.listInformeTranspDeta[i].codCobro;
                      objDeta.documento     = $scope.listInformeTranspDeta[i].documento;
                      objDeta.id_pedido     = $scope.listInformeTranspDeta[i].id_pedido;
                      objDeta.obsTransp     = $scope.obserTransp;
               
                      objDeta.obsInfor    = $scope.listInformeTranspDeta[i].obser_transp;
               
                      objDeta.estPedido     = $scope.listInformeTranspDeta[i].estado_pedido;

               
                      objDeta.idInforme     = $scope.idInformeTransp; 
                      objDeta.idUsuario     = $scope.idUsuarioLogin; 
               
               
                        objDeta.inicioKg      = $scope.inicioKg; 
                        objDeta.finKg         = $scope.finKg; 
                        objDeta.idTransporte  = idTransporte; 
                        objDeta.cargaKg       = $scope.cargaKg; 
                        objDeta.valorKm       = $scope.valorKm; 
               

                      arrayProd.push(objDeta);
           }
	
	 var objProd   = JSON.stringify(arrayProd);
	
	$http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=saveInformeTranspBodega&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrPed:objProd}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
   
		 var respuesta = data.charAt(data.length-1);
		
		
		  if(respuesta=="1"){
		//	  $scope.refreshDetalleProd();
			   alertify.success("Actulizacion generado con exito!");
		   }else{
			   alertify.error("Error al actualizar registro, favor contactar con el administrador!");
		   }
		
  }).error(function(error){
        console.log(error);

});
}



        
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
        });}        
        
		
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
                objDeta.name      = "--Seleccionar Vendedor";
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
            {id: 'Agricola',    name: 'Agricola'},			  
            {id: 'Almacen',     name: 'Almacen'},
            {id: 'Mascotero',   name: 'Mascotero'},
            {id: 'Particular',  name: 'Particular'},			  
            {id: 'Veterinaria', name: 'Veterinaria'},
            {id: 'Peluqueria', name: 'Peluqueria'}
			  
          ],
      selectedOption: {id: cli.tipo_comprador} //This sets the default value of the select in the ui
    }
};
		
		




        
 

$scope.generarReporteRutaPDF = function () {   
    
    
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
    
    
  var arrayDeta = [];

          
            

 
       for(var i=0; i < $scope.listInformeTranspDeta.length; i++){
               var objDeta= new Object();           

            objDeta.orden_ruta     =  "";
            if($scope.listInformeTranspDeta[i].tipo == "Particular"){
                objDeta.tipo           =  "P";
            }else if($scope.listInformeTranspDeta[i].tipo == "Almacen"){
                objDeta.tipo           =  "A";
            }else if($scope.listInformeTranspDeta[i].tipo == "Mascotero"){
                objDeta.tipo           =  "M";                     
            }else if($scope.listInformeTranspDeta[i].tipo == "Veterinaria"){
                objDeta.tipo           =  "V";                  
            }else if($scope.listInformeTranspDeta[i].tipo == "Avícola"){
                objDeta.tipo           =  "AV";                     
            }else if($scope.listInformeTranspDeta[i].tipo == "Peluqueria"){
                objDeta.tipo           =  "L";                     
            }else if($scope.listInformeTranspDeta[i].tipo == "Otro"){
                objDeta.tipo           =  "O";                     
            }
           
           
           
           
               /********************************/
           if($scope.listInformeTranspDeta[i].id_sector == 1){//Norte
                objDeta.sector           =   'img/norte.jpg';
            }else if($scope.listInformeTranspDeta[i].id_sector == 3){//centro
                objDeta.sector           =   'img/centro.png';
            }else if($scope.listInformeTranspDeta[i].id_sector == 2){//sur
                objDeta.sector           =   'img/sur.jpg';                     
            }        
          /**************************/
           
            objDeta.nPedido        =  $scope.listInformeTranspDeta[i].id_pedido;
            objDeta.nombre         =  $scope.listInformeTranspDeta[i].nombre;
            objDeta.direccion      = $scope.listInformeTranspDeta[i].direccion;
            objDeta.obsClie        =  $scope.listInformeTranspDeta[i].observacion; 
            objDeta.nTransp        =  $scope.idTransporteRuta;
            objDeta.cantPed        =  "Cantidad Pedidos: "+ $scope.listInformeTranspDeta.length;
            objDeta.fActual        =  daym+"-"+month+"-"+year;
            objDeta.pesos          =  $scope.listInformeTranspDeta[i].pesos; 
            objDeta.usua          =  $scope.listInformeTranspDeta[i].nombreUsuario; 
            objDeta.telefono          =  $scope.listInformeTranspDeta[i].telefono; 

           
       if($scope.listInformeTranspDeta[i].id_tipo_ped == "1" || $scope.listInformeTranspDeta[i].id_tipo_ped == "2" ||                              $scope.listInformeTranspDeta[i].id_tipo_ped == "0"){
            objDeta.total          =  formatNumero($scope.listInformeTranspDeta[i].total.toString());
          }else{
            objDeta.total          =  "0";
          }
           
           
           
            objDeta.formaPago    =  $scope.listInformeTranspDeta[i].formaPago;
            objDeta.id_informe     =  $scope.idInformeTransp; 
           
              arrayDeta.push(objDeta);

          
       }
       
      
         
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=gerexpRutaPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="ruta.pdf";          
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
		


 $scope.confirmarAnular = function () { 

    alertify.confirm("¿Esta seguro que desea anular Informe Transportes?", function (e) {
        if (e) {
            $scope.anularPedido();
         //   $("#myModalModificarPedido").modal("hide");
            
        } 
    });    
        
       
};       
        

 
$scope.anularPedido = function () {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=anularPedidoInformeVenta&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idInforme:$scope.idInformeTransp}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                 var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                
                    alertify.success("Informe Transporte anulado.");
                  
                    /*$("#myModalObserPedidoAnular").modal("hide");
                    $scope.buscarPedidoDatos();*/


                }else{
                    alertify.error("Error al anular pedido, favor contactar con el Administrador del Sistema.");
                }
        
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};       


 $scope.mostrarImagenTransfAdj = function(prodDet){   
     
   $scope.idPedido     =  prodDet.id_pedido; 
   $scope.nombPedido   =  prodDet.nombre; 
     
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
     
         $("#modal-image-detalle").attr("src", '');
     
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=mostrarImagenTransf&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPed:prodDet.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);  
         
            setTimeout($.unblockUI, 1000);
         
          $("#modal-image-detalle").attr("src", data[0].img_transf);
              
          }).error(function(error){
                        console.log(error);
    
    });        
        
};
        
        
   
$scope.cargarPedido = function (pedidoIndex) {
    $scope.detPedido = [];
        $scope.razonMod     =  pedidoIndex.nombre;

     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedido = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
};      
        
        /**/
        
$scope.agregarObservacion = function (pedidoIndex) {
    $scope.detPedido = [];
    $scope.razonMod     =  pedidoIndex.nombre;

     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                    var textoDetalle ="";
      
                  textoDetalle = "*"+$scope.razonMod+"\n";
         
                  for(var i = 0; i < data.length; i++){
                      
                  textoDetalle+=   data[i].cantidad+" - " + data[i].nombreProd+"\n";
                      
                    
                    
                }
                             
         
         
         
            $scope.obserTransp += textoDetalle;

         
         
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
};        
        
 
$scope.selInforme = function(){
    $scope.idInforme = $scope.idInformeTransp;
}



 
$scope.selInformeRuta = function(clie){
    $scope.idInforme = clie.id;
    $scope.urlImagenMercaderia = clie.fotoInfTransp;
}
        
        
$scope.guadarArchivoInforme = function(){
 
      $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
             } });    
        
$http({
    method: 'POST',
    url: 'FunctionIntranet.php?act=subirArchivoRuta&tienda='+$scope.tiendaSeleccionada, 
    data: $.param({ idInforme: $scope.idInforme
                   , imagen: $scope.variableBase64 }),
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
}).success(
    function(res) {
        
        
          var respuesta = res.charAt(res.length-1);
      
                 if(respuesta=="1"){                
                       alertify.success("Archivo subido con exito!");
                    }else{
                        
                        if(respuesta=="2"){
                             alertify.error("Error al subir archivo.");
                        }else if(respuesta=="3"){
                              alertify.error("La imagen debe ser png.");
                        }else if(respuesta=="0"){
                        alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

                        }
                        
                    }
    setTimeout($.unblockUI, 1000); 
        
        
    },
    function(err) {
          setTimeout($.unblockUI, 1000); 
                             alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

    }
);

};
        
        
        
        
$scope.guadarImagenInformeTransp = function(){
 
      $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
             } });    
        
$http({
    method: 'POST',
    url: 'FunctionIntranet.php?act=subirArchivoCamion&tienda='+$scope.tiendaSeleccionada, 
    data: $.param({ idInforme: $scope.idInforme
                   , imagen: $scope.variableBase64 }),
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
}).success(
    function(res) {
        
        
          var respuesta = res.charAt(res.length-1);
      
                 if(respuesta=="1"){                
                       alertify.success("Archivo subido con exito!");
                    }else{
                        
                        if(respuesta=="2"){
                             alertify.error("Error al subir archivo.");
                        }else if(respuesta=="3"){
                              alertify.error("La imagen debe ser png.");
                        }else if(respuesta=="0"){
                        alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

                        }
                        
                    }
    setTimeout($.unblockUI, 1000); 
        
        
    },
    function(err) {
          setTimeout($.unblockUI, 1000); 
                             alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

    }
);

};        
           
          
   
        
     
$scope.actualizarTotalesInforme = function (){

  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=actualizarTotalInfoTransp&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({ modEfectivo: $scope.efectivoMod,
                      modDebito: $scope.debitoMod,
                      modTransferencia:$scope.transferenciaMod,
                      modCajaVecina:$scope.cajavecinaMod,
                      idTransporte:$scope.idInformeTransp}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            var respuesta = data.trim();
                
                if(respuesta == "1"){
                   alertify.success("Valores modificados.");
                }else{
                    alertify.error("Error al modificar");
                }
           
  }).error(function(error){
        console.log(error);

});  
    
    
};
        
        
 
$scope.pesosProductos = function(pedido){
	
	$http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=pesoProductos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		
                 $scope.pesoProd = data[0].pesos;
        
          }).error(function(error){
                        console.log(error);
    
      });
	
};	        
        
        
        
        
$scope.confirmarPedidoSalidaMasivaRuta = function () { 
    alertify.confirm("Esta seguro que desea generar nota pedido con las salidas de productos ?", function (e) {
        if (e) {                    
            $scope.generarReportePDF('2');
        }
    });    
};                
        
      
        

$scope.generarReportePDF = function (tipoNotaPedido) {
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
    
    var hora    = mydate.getHours() 
    var minuto  = mydate.getMinutes() 
    var segundo = mydate.getSeconds() 
    var horaImprimible = hora + " : " + minuto;

var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (var i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
            objDeta.codProducto    =  $scope.unirPedidos[i].id_prod;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.fecha          =  daym+"/"+month+"/"+year+ " - Inicio:"+horaImprimible+" - Fin:_________  - ";
            objDeta.pedidos        =  $scope.pedidoSelecNota.substr(1);
            objDeta.bodega         =  $scope.unirPedidos[i].bodega;
            objDeta.pesoProd       =  Math.round($scope.pesoProd);
            objDeta.nombre         =  $scope.unirPedidos[i].nombre;
            objDeta.idInforme      =  "ID INFORME TRANSPORTE: "+$scope.idInformeTransp;

   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=gerexpPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="bodega_"+fechaActual+"_"+cad+".pdf";          
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
            
            if($scope.tipoUsuarioLogin == 'Administrador'){                
               if(tipoNotaPedido =='1'){
                                 $scope.updateDespEst();
                  }                    
             }
            
            
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
  
        
$scope.verDetallePed = function(stdo, idEstado){
    
     $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleObsPed&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idEstado:idEstado,
                     idProd:stdo.id_prod}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.listInformeTranspDeta = data;
         $("#myModalJoinPedidoMasivo").modal();
  }).error(function(error){
        console.log(error);

});
    
    
    
}        
        
        
        
$scope.isAnythingSelectedMasivoRuta = function () {
    var pedido="";
    var pedidoNota="";
    $scope.unirPedidos = [];
    
    for(var i = 0; i < $scope.listInformeTranspDeta.length; i++){
         var product = $scope.listInformeTranspDeta[i];   
        
            pedido += ','+product.id_pedido;
            pedidoNota += ',  '+product.id_pedido;
        
    }
    
    $scope.pedidoSelecNota= pedidoNota;
    

    
	
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidosProductoSalidas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		           $scope.pesosProductos(pedido);
                   $scope.unirPedidos = data;
                  // $("#myModalJoinPedidoMasivo").modal();
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 
	 
   
};     
        
        

$scope.imprimirTermicaListadoProductosSalida = function(){
      var arrayDeta     = []; 
            
                        var objDeta= new Object();           
                        objDeta.observacion    =  $scope.obserTransp;
                        arrayDeta.push(objDeta);
                   

                var jsonData=angular.toJson(arrayDeta);    
                var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         //url : '//'+$scope.tiendaIp+'/tienda/FunctionIntranet.php?act=imprimirTermicaObservacionInformeTransporte&tienda='+$scope.tiendaSeleccionada,      
                         url : '//192.168.1.128/Tienda/FunctionIntranet.php?act=imprimirTermicaObservacionInformeTransporte&tienda='+$scope.tiendaSeleccionada,
                    
                         data:  $.param(objectToSerialize),
                                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });
                

    

       
}


$('#listSalidaProd').on('click', 'tbody tr', function(event) {
  $(this).addClass('highlight').siblings().removeClass('highlight');
});
   
        
        

}]);