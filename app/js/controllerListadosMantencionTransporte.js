angularRoutingApp.controller('controllerListadosMantencionTransporte', ['$scope', '$http',
    function ($scope, $http) {

         $scope.customFi          = false;     
         $scope.customFe          = false;
         $scope.customFolio       = false;
         $scope.nombreClie        = false;
         $scope.tipoBusqueda      = "1";
         $scope.tipoAccionSol     ="";        
         $scope.customInterLogin  = false;
         $scope.auxSelProd         = [];
         $scope.dgVentas           = [];
         $scope.listadoTransporte  = [];
              $scope.listadoTipoTransporte  = [];
   
        $scope.tetPrueba  = true;

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
        

        
        $scope.loading=false;  
        
        
$scope.iniciarDataTransportes =  function(){     
    
          $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataMantencionTransporte&tienda='+$scope.tiendaSeleccionada,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                  $scope.listadoEstadoTransporte = [];                
                  $scope.listadoEstadoTransporte = data;
                  $scope.listadoEstadoTranp = {selected: "0"};

          }).error(function(error){
                        console.log(error);
    
      });
    
    
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
    
    
    
      $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getDataTipoMantenciones&tienda='+$scope.tiendaSeleccionada,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.listadoTipoMantencion = [];                
                $scope.listadoTipoMantencion = data;

          }).error(function(error){
                        console.log(error);
    
      });
    
    
    
       $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=getTipoSolicitudMantenciones&tienda='+$scope.tiendaSeleccionada,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.listadoTipoSolicitudMantencion = [];                
                $scope.listadoTipoSolicitudMantencion = data;
                 $scope.listadoTipoSolicitudMant = {selected: "0"};


          }).error(function(error){
                        console.log(error);
    
      });
    
    
    
}        
        
/*
$scope.selTipoMantencion = function(listadoTipoMant, cli){
	var length = $scope.dgVentas.length; 

	 for(var i = 0; i < length; i++) {
      objProd = $scope.dgVentas;       
       if(objProd[i].idValidar == listadoTipoMant.idValidar){
              $scope.dgVentas[i].itemsDetalleSelMant  = cli;		   
           break;
       }             
    }
    
    alert(listadoTipoMant.idValidar);

}*/

        
 $scope.selTipoComprador = function (rdo) {   
 
   if(rdo=="FechaIngreso"){   
         $scope.customFi      = true;     
         $scope.customFe      = false;
         $scope.customFolio   = false;
         $scope.nombreClie    = false;
         $scope.customPatente = false;
         $scope.tipoBusqueda  ="1";

         
    }else if(rdo=="FechaEntrega" ){
         $scope.customFi = false;     
         $scope.customFe = true;
         $scope.customFolio = false;
         $scope.nombreClie = false;
         $scope.customPatente = false;        
         $scope.tipoBusqueda ="2";

    }else if(rdo=="Folio"){
         $scope.customFi     = false;     
         $scope.customFe     = false;
         $scope.customFolio  = true;
         $scope.nombreClie   = false;
         $scope.customPatente = false;
         $scope.tipoBusqueda = "3";

         
    }else if(rdo=="Cliente"){
          $scope.customFi     = false;     
          $scope.customFe     = false;
          $scope.customFolio  = false;
          $scope.nombreClie   = true;
         $scope.customPatente = false;        
          $scope.tipoBusqueda = "4";
         
    }else if(rdo=="Patente"){
          $scope.customFi     = false;     
          $scope.customFe     = false;
          $scope.customFolio  = false;
          $scope.nombreClie   = false;
          $scope.customPatente = true;        
          $scope.tipoBusqueda = "5";
         
    }
 
};
 

             
 /*
$scope.methodVenta = function(prodDet, modInpur){
	var length = $scope.dgVentas.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
      objProd = $scope.dgVentas;       
       if(objProd[i].idValidar == prodDet.idValidar){
              $scope.dgVentas[i].itemsDetalleProdAdic  = (modInpur);		   
           break;
       }             
    }
};	
         */
        
$scope.methodVentaAct = function(prodDet){
    var length = $scope.dgVentas.length; 
	var objProd = new Object();	
    
      if(prodDet){
      	 for(var i = 0; i < length; i++) {
              objProd = $scope.dgVentas;

               if(objProd[i].idValidar == prodDet.idValidar){
                      $scope.dgVentas[i].itemsDetalleAct  = "1";		   
                   break;
               }             
            }
    }else{
                 for(var i = 0; i < length; i++) {
                        objProd = $scope.dgVentas;

                       if(objProd[i].idValidar == prodDet.idValidar){
                              $scope.dgVentas[i].itemsDetalleAct  = "0";		   
                           break;
                       }             
            }
    }
    
	

};	        
        
        
        
        
$scope.agregarItems = function(){
    var length = $scope.dgVentas.length; 
    
               $scope.estaDetProd = false;
               $scope.auxSelProd                      = [];   
               $scope.auxSelProd.idValidar            = length +1;
               $scope.auxSelProd.itemsDetalleSelMant  = "";
               $scope.auxSelProd.itemsDetalleProdAdic = "";
               $scope.auxSelProd.itemsDetalleAct      = "0";
        //   $scope.auxSelProd.itemsDetalleProd       = $scope.detNombProd;
        //   $scope.auxSelProd.itemsNetoProd          = parseInt($scope.detPrecioVenta);
               $scope.auxSelProd.itemsDetalleProd     = "";
               $scope.auxSelProd.itemsNetoProd        = "";
               $scope.auxSelProd.selected             = "0";

    
    

    
                console.log($scope.auxSelProd);
    
               $scope.dgVentas.push($scope.auxSelProd);
    
               console.log($scope.dgVentas);
             
              $scope.detNombProd = "";
              $scope.detPrecioVenta = "";
            //  $scope.detCantidad = "";
    
 $scope.tetPrueba == false;
}
         
$scope.initListado = function () {
    
    $scope.iniciarDataTransportes();
    
   $scope.tipoDeSolEstado = {
      availableOptions: [          
        {id: 0, name: '--Seleccionar Estado--'},          
        {id: 1, name: 'Anulado'},
        {id: 2, name: 'Pendiente'},
        {id: 2, name: 'Entregado'}
      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
}
    
      if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }

         $scope.customFi = true;     
         $scope.customFe = false;
         $scope.customFolio = false;
         $scope.nombreClie = false;
    
    

    
    var diaDesp  = 300;
    var diaXDesp = parseInt(daym) + diaDesp;
    
    
      if(diaXDesp < 10){
          diaXDesp = "0"+diaXDesp;
      }
    
       

    
    document.getElementById('insEntrega').value= year+"-"+month+"-"+daym;    
    document.getElementById('insEntrega').max  = year+"-"+month+"-"+diaXDesp;
    document.getElementById('insEntrega').min  = year+"-"+month+"-"+daym;
  
    
   document.getElementById('fIngreso').value= year+"-"+month+"-"+daym;
   document.getElementById('fIngresoHasta').value= year+"-"+month+"-"+daym;
    
    
   document.getElementById('fEntrega').value= year+"-"+month+"-"+daym;
   document.getElementById('fEntregaHasta').value= year+"-"+month+"-"+daym;

  
    
    $scope.listarSol();
  };
        
$scope.confirmarSolicitud = function () { 
    
    
if($scope.tipoAccionSol == "Modificar"){
          alertify.confirm("Esta seguro que desea modificar solicitud ?", function (e) {
           
         if (e) {                     
              $scope.modificarSolicitud();            
        }
    });  
}else{
       alertify.confirm("Esta seguro que desea ingresar solicitud ?", function (e) {
           
         if (e) {                     
              $scope.insertarSolicituds();            
        }
    });    
   
}    

   
};

        
  

$scope.insertarSolicituds =  function(){
    var idTransporte  = ($scope.listadoTranp == undefined ? "": $scope.listadoTranp.selected);    
    var idTransporteTipoMant  = ($scope.listadoTipoSolicitudMant == undefined ? "": $scope.listadoTipoSolicitudMant.selected);   
     
     var arrayProd    = [];
     


        for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.itemsDetalleProd      = $scope.dgVentas[i].itemsDetalleProd;
                     objDeta.itemsDetallePrecio    = $scope.dgVentas[i].itemsNetoProd;
                     objDeta.itemsDetalleAct       = $scope.dgVentas[i].itemsDetalleAct;  
                     objDeta.itemsDetalleProdAdic  = $scope.dgVentas[i].itemsDetalleProdAdic;  
                     objDeta.itemsDetalleTipoMant  = $scope.dgVentas[i].selected;  

                     arrayProd.push(objDeta);
           }     
                 var objDeta    = JSON.stringify(arrayProd);
     

  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=insertarSolicitudMantencion&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({nombre:$scope.insNombre, 
                                        observacion:$scope.insObservacion, 
                                        tipo_solicitud:idTransporteTipoMant,
                                        f_entrega:angular.element(document.querySelector("#insEntrega")).val(),
                                        telefono:$scope.insContacto,
                                        idTransp:idTransporte,
                                        kilometraje:$scope.insKilometraje,
                                        objDeta:objDeta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
      
        var respuesta = data;

                 if(respuesta!=""){                
                        alertify.success("Solicitud generado con exito!, ID: "+respuesta);
                         $scope.limpiarDatas();
         
                    }else{
                        alertify.error("Error al insertar solicitud!, favor contactar con el administrador del Sistema.");
                    }
        
        
          }).error(function(error){
                        console.log(error);
    
    });
 };
   
        
        
$scope.listarSol = function(){
    
      document.getElementById('divPedido').style.display           = 'none';    
      document.getElementById('divPedidoModificar').style.display  = 'none';    
   
      var nombre    = angular.element(document.querySelector("#nombreCliente")).val();
      var folio     = angular.element(document.querySelector("#folio")).val();
      var fIngreso  = angular.element(document.querySelector("#fIngreso")).val();
      var fEntrega  = angular.element(document.querySelector("#fEntrega")).val();
    
      var fIngresoHasta  = angular.element(document.querySelector("#fIngresoHasta")).val();
      var fEntregaHasta  = angular.element(document.querySelector("#fEntregaHasta")).val();
    
      var fPatente  = angular.element(document.querySelector("#patente")).val();

    
      $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarSolicitudesMantencion&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({tipBusq: $scope.tipoBusqueda,
                     folio:   folio,
                     nombre:  nombre,
                     fIngreso: fIngreso,
                     fEntrega: fEntrega,
                     fIngresoHasta: fIngresoHasta,
                     fEntregaHasta: fEntregaHasta,
                     patente:fPatente
                    }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
           
            $scope.listSolicitud = data;
           
  }).error(function(error){
        console.log(error);
      

});  
    
    
    
};
        
        

$scope.validarCliente = function (accion) {

    if(accion ==  "Crear"){
        
     var idTransporte  = ($scope.listadoTranp == undefined ? "": $scope.listadoTranp.selected);    
     var idTransporteTipoMant  = ($scope.listadoTipoSolicitudMant == undefined ? "": $scope.listadoTipoSolicitudMant.selected); 
        
          if(document.formCliente.insNombre.value==""){
                alertify.alert("Ingresar Nombre"); 
                document.formCliente.insNombre.focus();
                       return 0;
          }else if(document.formCliente.insContacto.value==""){
               alertify.alert("Ingresar Numero Contacto"); 
                document.formCliente.insContacto.focus();
                      return 0;
 
          }else if( idTransporte=="0"    || idTransporte=="" ){
               alertify.alert("Seleccionar transportes"); 
                       return 0;      
              

          }else if( idTransporteTipoMant=="0"    || idTransporteTipoMant=="" ){
               alertify.alert("Seleccionar tipo trabajo"); 
                       return 0;
          }else if(document.formCliente.insObservacion.value==""){
               alertify.alert("Ingresar Observacion"); 
                document.formCliente.insObservacion.focus();
                       return 0;
              
          }else if($scope.dgVentas.length == 0){
               alertify.alert("Ingresar Items a la solicitud"); 
                       return 0;
              
          }else if(document.formCliente.insKilometraje.value==""){
               alertify.alert("Ingresar Kilometraje"); 
                document.formCliente.insKilometraje.focus();
                       return 0;
              
          }
        
        
      }else  if(accion ==  "Modificar"){
     var idTransporteMod          = ($scope.listadoTranpMod == undefined ? "": $scope.listadoTranpMod.selected);    
     var idTransporteTipoMantMod  = ($scope.listadoTipoSolicitudMantMod == undefined ? "": $scope.listadoTipoSolicitudMantMod.selected);   
     
          if(document.formClienteMod.modNombre.value==""){
                alertify.alert("Ingresar Nombre"); 
                document.formClienteMod.modNombre.focus();
                       return 0;
          }else if(document.formClienteMod.modContacto.value==""){
               alertify.alert("Ingresar Numero Contacto"); 
                document.formClienteMod.modContacto.focus();
              
           }else if( idTransporteMod=="0"    || idTransporteMod=="" ){
               alertify.alert("Seleccionar transportes"); 
                       return 0;      
              

          }else if( idTransporteTipoMantMod=="0"    || idTransporteTipoMantMod=="" ){
               alertify.alert("Seleccionar tipo trabajo"); 
                       return 0;
          }else if(document.formClienteMod.modObservacion.value==""){
               alertify.alert("Ingresar Observacion"); 
                document.formClienteMod.modObservacion.focus();
                       return 0;
              
          }else if($scope.dgVentas.length == 0){
               alertify.alert("Ingresar Items a la solicitud"); 
                       return 0;
              
          }else if(document.formClienteMod.modKilometraje.value==""){
               alertify.alert("Ingresar Kilometraje"); 
                document.formClienteMod.modKilometraje.focus();
                       return 0;
              
          }
      }
 
    
       $scope.confirmarSolicitud();
      
}


$scope.limpiarDatas = function () {
        var diaDesp  = 300;
        var diaXDesp = parseInt(daym) + diaDesp;
    
    
      if(diaXDesp < 10){
          diaXDesp = "0"+diaXDesp;
      }
    document.getElementById('insEntrega').value= year+"-"+month+"-"+daym;    
    document.getElementById('insEntrega').max  = year+"-"+month+"-"+diaXDesp;
    document.getElementById('insEntrega').min  = year+"-"+month+"-"+daym;
    
$scope.insNombre        = "";
$scope.insDireccion     = "";
$scope.insObservacion   = "";
$scope.insContacto      = "";
$scope.dgVentas         = [];

    
$scope.modNombre        = "";
$scope.modDireccion     = "";
$scope.modObservacion   = "";
$scope.modContacto      = "";
    /*
$scope.tipoDeSol = {
      availableOptions: [          
            {id: 0, name: '--Seleccionar Tipo--'},          
            {id: 1, name: 'Cotizacion'},
            {id: 2, name: 'Mantenimiento correctivo'},
            {id: 3, name: 'Mantenimiento preventivo'}

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
}*/
    
    
/*
    
$scope.tipoDeSolY = {
      availableOptions: [
          
        {id: 0, name: '--Seleccionar Tipo--'},          
        {id: 1, name: 'Cotizacion'},
        {id: 2, name: 'Orden de Trabajo'}

      ],
      selectedOption: {id: 0} //This sets the default value of the select in the ui
    }   */ 
    
   document.getElementById('divPedido').style.display           = 'none';    
   document.getElementById('divPedidoModificar').style.display  = 'none';     
   document.getElementById('divPedidoListado').style.display    = 'block';    
    
   $scope.listarSol();
    
};
        
        
$scope.preCrearSolicitud = function (){ 
  $scope.listadoTranp = {selected: "0"};
  $scope.listadoTipoSolicitudMant = {selected: "0"};


    document.getElementById('divPedidoListado').style.display   = 'none';    
    document.getElementById('divPedido').style.display          = 'block';    
    
    
   
    $scope.tipoAccionSol        = "Crear";
    $scope.insNombre            = "";
    $scope.insDireccion         = "";
    $scope.insObservacion       = "";
    $scope.insContacto          = "";
    $scope.dgVentas             = [];
    $scope.modNombre            = "";
    $scope.modDireccion         = "";
    $scope.modObservacion       = "";
    $scope.modContacto          = "";
            
}
        
 $scope.preModificarSolicitud = function(sol){
     
     document.getElementById('divPedidoListado').style.display               = 'none';    
     document.getElementById('divPedidoModificar').style.display             = 'block';    
     
     $scope.idCliente                             = sol.idClie;
     $scope.idSoli                                = sol.id;
     $scope.tipoAccionSol                         = "Modificar";
     $scope.modNombre                             = sol.nombre_mecanico;
     $scope.modObservacion                        = sol.observacion;
     $scope.modContacto                           = parseInt(sol.telefono);
   
     $scope.modKilometraje                           = parseInt(sol.kilometraje);

     document.getElementById('modEntrega').value  = sol.fecha_entrega;
    // $scope.listadoTransporte.id                  = sol.id_camion;
   //  $scope.listadoTipoSolicitudMant            = {selected: sol.tipo_solicitud.toString()};

        $scope.listadoTranpMod = {selected: sol.id_camion.toString()};
     $scope.listadoTipoSolicitudMantMod = {selected: sol.tipo_solicitud.toString()};

   //  $scope.iniciarDataTransportes();
     /*
     
     $scope.tipoDeSolMod = {
      availableOptions: [          
        {id: 0, name: '--Seleccionar Tipo--'},          
            {id: 1, name: 'Cotizacion'},
            {id: 2, name: 'Mantenimiento correctivo'},
            {id: 3, name: 'Mantenimiento preventivo'}

      ],
      selectedOption: {id: parseInt(sol.tipo_solicitud)} //This sets the default value of the select in the ui
    }*/
     
     $scope.listarDetalle($scope.idSoli);
     
    // $("#myModalNuevoCliente").modal();  
     
 }
    
 /*
            case 'modificarSolicitudMantencion': echo json_encode(modificarSolicitudMantencion($_REQUEST['nombre'], 
                                                                   $_REQUEST['observacion'],
                                                                   $_REQUEST['tipo_solicitud'],
                                                                   $_REQUEST['f_entrega'],
                                                                   $_REQUEST['telefono'],
                                                                   $_REQUEST['idTransp'],                            
                                                                     json_decode($_REQUEST["objDeta"])
                                                                  )); break;*/
        
 $scope.modificarSolicitud =  function(){

     var idTransporteMod          = ($scope.listadoTranpMod == undefined ? "": $scope.listadoTranpMod.selected);    
     var idTransporteTipoMantMod  = ($scope.listadoTipoSolicitudMantMod == undefined ? "": $scope.listadoTipoSolicitudMantMod.selected);
     var arrayProd    = [];
     


        for(var i = 0; i < $scope.dgVentas.length; i++){
                    var objDeta = new Object();
                     objDeta.itemsDetalleProd      = $scope.dgVentas[i].itemsDetalleProd;
                     objDeta.itemsDetallePrecio    = $scope.dgVentas[i].precio;
                     objDeta.itemsDetalleAct       = $scope.dgVentas[i].itemsDetalleAct;  
                     objDeta.itemsDetalleProdAdic  = $scope.dgVentas[i].itemsDetalleProdAdic;  
                     objDeta.itemsDetalleTipoMant  = $scope.dgVentas[i].selected;  

                     arrayProd.push(objDeta);
           }     
                 var objDeta    = JSON.stringify(arrayProd);

     
  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=modificarSolicitudMantencion&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({nombre:$scope.modNombre, 
                                        observacion:$scope.modObservacion, 
                                        tipo_solicitud:idTransporteTipoMantMod, 
                                        f_entrega:angular.element(document.querySelector("#modEntrega")).val(),
                                        telefono:$scope.modContacto,
                                        idTransp:idTransporteMod,
                                        idPed:$scope.idSoli,
                                        kilometraje:$scope.modKilometraje,
                                        objDeta:objDeta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
      
        var respuesta = data;

                 if(respuesta!=""){    
                     $("#myModalNuevoCliente").modal("hide");
                        alertify.success("Solicitud modificado con exito!");
                         $scope.limpiarDatas();
         
                    }else{
                        alertify.error("Error al modificar solicitud!, favor contactar con el administrador del Sistema.");
                    }
        
        
          }).error(function(error){
                        console.log(error);
    
    });
 };        
        
 $scope.preVerSolicitud = function(sol){
     $scope.insNombre       = sol.nombre_mecanico;
     $scope.insDireccion    = sol.direccion;
     $scope.insObservacion  = sol.observacion;
     $scope.insContacto     = parseInt(sol.telefono);
    
 }
 


$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;

for(var j = 0; j < $scope.dgVentas.length; j++){
        var product = $scope.dgVentas[j];
        total += Math.round(product.itemCantiadProd * product.itemsNetoProd);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);
return Math.round(total);
};    

        
$scope.verDetallePedidoSalir = function(){
      document.getElementById('divPedido').style.display                    = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block'; 
      $scope.listarSol();
      $scope.dgVentas         = [];
      $scope.detNombProd      = ""; 
      $scope.detPrecioVenta   = "";  
      $scope.detCantidad      = "";  
};
        
        
$scope.verDetallePedidoSalirMod = function(){
      document.getElementById('divPedidoModificar').style.display           = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block'; 
      $scope.dgVentas         = [];
      $scope.detNombProd      = ""; 
      $scope.detPrecioVenta   = "";  
      $scope.detCantidad      = "";  
    $scope.listarSol();
};
        
        
$scope.eliminarProduto = function (productIndex) {     
    $scope.dgVentas.splice(productIndex, 1);  

};
        
        
        
$scope.listarDetalle = function(pedSel){
   $scope.dgVentas       = []; 
       var arrayDeta     = []; 
        var length       = 0; 
    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedidoMantencion&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.dgVentasAux = data;
                  for (var i = 0; i <    $scope.dgVentasAux.length; i++) {          
                  var objDeta= new Object();       
                        objDeta.idValidar             =  length +1;
                        objDeta.itemsDetalleId        =  $scope.dgVentasAux[i].id;
                        objDeta.itemsDetalleProd      =  $scope.dgVentasAux[i].nombreDeta;
                        objDeta.precio                =  parseInt($scope.dgVentasAux[i].precio);
                        objDeta.selected              =  $scope.dgVentasAux[i].selected; 
                        objDeta.nombreMant              =  $scope.dgVentasAux[i].nombreMant; 

                      
                      
                      if($scope.dgVentasAux[i].activo_deta.toString()=="1"){
                            objDeta.itemsDetalleAct         = true;
                         }else{
                            objDeta.itemsDetalleAct         =  false;
                         }

                        objDeta.itemsDetalleProdAdic       =  $scope.dgVentasAux[i].observacion_extra;  
                        $scope.dgVentas.push(objDeta);
                     } 
        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
       
}


$scope.deleteProdListadoConf = function(prod, prodIndex){
    alertify.confirm("Esta seguro que desea eliminar el item "+prod.itemsDetalleProd+"?", function (e) {
            if (e) {
                  $scope.deleteProdListado(prod, prodIndex);    
            } 
    }); 
};
        
        
$scope.deleteProdListado = function(prod, prodIndex){    
    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=eliminarProdList&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id:prod.itemsDetalleId, 
                             idSol:$scope.idSoli}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                var respuesta = data.charAt(data.length-1);                
                if(respuesta=="1"){                
                     $scope.dgVentas.splice(prodIndex, 1);
                     alertify.success("Items eliminado con exito");                    
                }else{
                    alertify.error("Error al eliminar items, favor contactar con el Administrador del Sistema.");
                }                  
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
        
        
$scope.confirmarSolicitudPDF = function (sol) { 
     $scope.listarDetalle(sol.id);    
     alertify.confirm("Esta seguro que desea generar PDF ?", function (e) {           
         if(e) {                     
              $scope.generarNotaPedido(sol);            
          }
    });      
};       
        

        
        
$scope.generarNotaPedido = function (sol) {
var arrayDeta     = []; 

var fecha         = "";
var idSol         = "";

                                                                 
   for (var i = 0; i <    $scope.dgVentas.length; i++) {          
   var objDeta= new Object();           
            objDeta.detalleMant             =  $scope.dgVentas[i].itemsDetalleProdAdic;
            objDeta.valor                   =  formatNumero($scope.dgVentas[i].precio.toString());
            objDeta.nombreMant              =  $scope.dgVentas[i].nombreMant;
 
            objDeta.nombreMec      =  sol.nombre_mecanico;
            objDeta.tipoSol        =  sol.tipoSolicitud;
            objDeta.contacto       =  sol.telefono;
            objDeta.idMant         =  sol.id;

        var arrayfecha = sol.fecha_entrega.split('-');    
       
         objDeta.fechaEntrega     =  arrayfecha[2]+"/"+arrayfecha[1]+"/"+arrayfecha[0]; 
       
         fecha                 =  arrayfecha[2]+"_"+arrayfecha[1]+"_"+arrayfecha[0]; 
         objDeta.id            =  sol.id;
         idSol                 =  sol.id;
            arrayDeta.push(objDeta);
 }
    

   var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=generarMantencionPDF&tienda='+$scope.tiendaSeleccionada,
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
             var filename ="mantencion_"+fecha+"_"+idSol+".pdf";          
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
        

        
 $scope.insertSolicitud =  function(){
  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=insertarDetalleSolicitud&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({nombreDetalle:$scope.detNombProd, 
                                        precioDetalle:$scope.detPrecioVenta, 
                                        cantidadDetalle:$scope.detCantidad, 
                                        idSolicitud:$scope.idSoli}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
      
             var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                        $scope.listarDetalle($scope.idSoli);
                }else{
                    alertify.error("Error al insertar items, favor contactar con el Administrador del Sistema.");
                }
        
        
          }).error(function(error){
                        console.log(error);
    
    });
 };            
       
        
$scope.imprimirTicketTrabajo = function(pedSel){
    var arrayDeta     = [];    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel.id}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.dgVentasAux = data;
             for(var i = 0; i <    $scope.dgVentasAux.length; i++) {          
                  var objDeta= new Object();           
                        objDeta.nombreProd     =  $scope.dgVentasAux[i].nombreDeta;
                        objDeta.cantidad       =  $scope.dgVentasAux[i].cantidadDeta;
                        objDeta.idPedido       =  pedSel.id;
                        objDeta.nombClie       =  pedSel.nombreClie;
                  arrayDeta.push(objDeta);
             }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         url: '//192.168.1.128/barrosaranas/FunctionIntranet.php?act=imprimirTermicaTicket&tienda='+$scope.tiendaSeleccionada,                    
                         data:  $.param(objectToSerialize),
                         headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data); 

                      }).error(function(error){
                            console.log(error);
                            $scope.loadingProd = false;

                    });

          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
};        
         
$scope.initEstadoSel = function(pedSel){
     $scope.idSoli          = pedSel.id;
     $scope.modNombre       = pedSel.nombre_mecanico;   
  
};

$scope.modificarEstadoSel = function(){    
    var idEstado  = ($scope.listadoEstadoTranp == undefined ? "": $scope.listadoEstadoTranp.selected);  
    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarEstadoTransporte&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idEstado:idEstado, 
                             idSol:$scope.idSoli}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                var respuesta = data.charAt(data.length-1);                
                if(respuesta=="1"){                
                    alertify.success("Estado asignado con exito");
                    $scope.listarSol();
                }else{
                    alertify.error("Error al eliminar items, favor contactar con el Administrador del Sistema.");
                } 
          }).error(function(error){
                        console.log(error);
    
    });   
};
    
}]);