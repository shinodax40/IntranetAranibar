angularRoutingApp.controller('controllerInformeRutaVendedor', ['$scope', '$http', function ($scope, $http) {
      $scope.observacionRuta ="";
    $scope.fechaMod ="";
        $scope.telefonoMod ="";

 $scope.checkboxTipoClie = {
   almacen : true,
   mascotero : true,
   peluqueria : true,
   veterinaria : true
 };
    
    
 $scope.checkboxSemana = {
   lunes : true,
   martes : true,
   miercoles : true,
   jueves : true,
   viernes : true
     
 };    
    
    

  // $scope.checkboxTipoClie.almacen = false;  
    
    
$scope.selCheckBoxSemana = function(indCli){
    
    if(indCli==1){
        
        if($scope.checkboxSemana.lunes==true){
                   $scope.checkboxSemana.lunes==false;

        }else if($scope.checkboxSemana.lunes==false){
                 
        $scope.checkboxSemana.lunes==true;
        }
        
      
        
        
    }else if(indCli==2){
                if($scope.checkboxSemana.martes==true){
                   $scope.checkboxSemana.martes==false;

        }else if($scope.checkboxSemana.martes==false){
                 
        $scope.checkboxSemana.martes==true;
        } 
    }else if(indCli==3){
                if($scope.checkboxSemana.miercoles==true){
                   $scope.checkboxSemana.miercoles==false;

        }else if($scope.checkboxSemana.miercoles==false){
                 
        $scope.checkboxSemana.miercoles==true;
        } 
    }else if(indCli==4){
                if($scope.checkboxSemana.jueves==true){
                   $scope.checkboxSemana.jueves==false;

        }else if($scope.checkboxSemana.jueves==false){
                 
        $scope.checkboxSemana.jueves==true;
        } 
    }else if(indCli==5){
                if($scope.checkboxSemana.viernes==true){
                   $scope.checkboxSemana.viernes==false;

        }else if($scope.checkboxSemana.viernes==false){
                 
        $scope.checkboxSemana.viernes==true;
        } 
    }
    
    
    
}
    
    
    
$scope.selCheckBoxTipo = function(indCli){
    
    
    //alert($scope.checkboxTipoClie.almacen);
    
         if(indCli==1){
             
                           for (var i = 0; i <    $scope.listCliente.length; i++) {  
                   var clie = $scope.listCliente[i];
                  if($scope.checkboxTipoClie.almacen == true){
                      if(clie.tipo_comprador=="Almacen"){
                          $scope.listCliente[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.almacen == false){
                           if(clie.tipo_comprador=="Almacen"){
                          $scope.listCliente[i].activo =0;
                      }
                   }
                  
                }
             
             
              for (var i = 0; i <    $scope.listClienteSemana.length; i++) {  
                   var clie = $scope.listClienteSemana[i];
                  if($scope.checkboxTipoClie.almacen == true){
                      if(clie.tipo_comprador=="Almacen"){
                          $scope.listClienteSemana[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.almacen == false){
                           if(clie.tipo_comprador=="Almacen"){
                          $scope.listClienteSemana[i].activo =0;
                      }
                   }
                  
                }
             

         }else if(indCli==2){
             
                       for (var i = 0; i <    $scope.listCliente.length; i++) {  
                   var clie = $scope.listCliente[i];
                  if($scope.checkboxTipoClie.mascotero == true){
                      if(clie.tipo_comprador=="Mascotero"){
                          $scope.listCliente[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.mascotero == false){
                           if(clie.tipo_comprador=="Mascotero"){
                          $scope.listCliente[i].activo =0;
                      }
                   }
                  
                }
            
             
             
             for (var i = 0; i <    $scope.listClienteSemana.length; i++) {  
                   var clie = $scope.listClienteSemana[i];
                  if($scope.checkboxTipoClie.mascotero == true){
                      if(clie.tipo_comprador=="Mascotero"){
                          $scope.listClienteSemana[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.mascotero == false){
                           if(clie.tipo_comprador=="Mascotero"){
                          $scope.listClienteSemana[i].activo =0;
                      }
                   }
                  
                }

        }else if(indCli==3){
            
            
                
                         for (var i = 0; i <    $scope.listCliente.length; i++) {  
                   var clie = $scope.listCliente[i];
                  if($scope.checkboxTipoClie.peluqueria == true){
                      if(clie.tipo_comprador=="Peluqueria"){
                          $scope.listCliente[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.peluqueria == false){
                           if(clie.tipo_comprador=="Peluqueria"){
                          $scope.listCliente[i].activo =0;
                      }
                   }
                  
                }
            

                for (var i = 0; i <    $scope.listClienteSemana.length; i++) {  
                   var clie = $scope.listClienteSemana[i];
                  if($scope.checkboxTipoClie.peluqueria == true){
                      if(clie.tipo_comprador=="Peluqueria"){
                          $scope.listClienteSemana[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.peluqueria == false){
                           if(clie.tipo_comprador=="Peluqueria"){
                          $scope.listClienteSemana[i].activo =0;
                      }
                   }
                  
                }

            
            
        }else if(indCli==4){
            
            
                         for (var i = 0; i <    $scope.listCliente.length; i++) {  
                   var clie = $scope.listCliente[i];
                  if($scope.checkboxTipoClie.veterinaria == true){
                      if(clie.tipo_comprador=="Veterinaria"){
                          $scope.listCliente[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.veterinaria == false){
                           if(clie.tipo_comprador=="Veterinaria"){
                          $scope.listCliente[i].activo =0;
                      }
                   }
                  
                }
            
            
            
            
                    for (var i = 0; i <    $scope.listClienteSemana.length; i++) {  
                   var clie = $scope.listClienteSemana[i];
                  if($scope.checkboxTipoClie.veterinaria == true){
                      if(clie.tipo_comprador=="Veterinaria"){
                          $scope.listClienteSemana[i].activo =1;
                      }
                  }else if($scope.checkboxTipoClie.veterinaria == false){
                           if(clie.tipo_comprador=="Veterinaria"){
                          $scope.listClienteSemana[i].activo =0;
                      }
                   }
                  
                }

        }
    

   
};  
    

$scope.diaSemanaEscusa = {
  availableOptions: [
    {id: '1', name: 'Abastecido'},
    {id: '2', name: 'No le ha ido muy bien'},
    {id: '3', name: 'Lo llamo y no contesta'},
    {id: '4', name: 'Voy y está cerrado'} ,
    {id: '5', name: 'Vacaciones'},      
    {id: '6', name: 'Pendiente en visitar'},
    {id: '7', name: 'Compro en otro lado'},        
    {id: '8', name: 'Llamar la proxima semana'},
    {id: '9', name: 'Pendiente en llamar'} ,       
    {id: '10', name: 'Llamara cuando necesite'}              
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}
    
    
    
    
    
$scope.diaSemana = {
  availableOptions: [
    {id: '1', name: 'Lunes'},
    {id: '2', name: 'Martes'},
    {id: '3', name: 'Miercoles'},
    {id: '4', name: 'Jueves'} ,
    {id: '5', name: 'Viernes'}        
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}
    
    
$scope.diaSemanaPdf = {
  availableOptions: [
    {id: '1', name: 'Lunes'},
    {id: '2', name: 'Martes'},
    {id: '3', name: 'Miercoles'},
    {id: '4', name: 'Jueves'} ,
    {id: '5', name: 'Viernes'}        
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}    
     
        
$scope.init = function () {
     if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }    
         
    $scope.InitClientes();
    $scope.InitClientesSemana();
};

    
$scope.listarClienteSel = function (indexClie) {  
  $scope.idClie            = indexClie.id_cliente ;
  $scope.nombreClie        = indexClie.nombre ;  
  $scope.fechaMod          = indexClie.fecha_obs_mod;
  $scope.fechaUltima       = indexClie.fecha;
  $scope.idObserva         = indexClie.id_observacion;
  $scope.observacionRuta   = indexClie.observacion;
  $scope.telefonoMod       = indexClie.telefono;

   
    
$scope.diaSemanaEscusa = {
  availableOptions: [
    {id: '1', name: 'Abastecido'},
    {id: '2', name: 'No le ha ido muy bien'},
    {id: '3', name: 'Lo llamo y no contesta'},
    {id: '4', name: 'Voy y está cerrado'} ,
    {id: '5', name: 'Vacaciones'},      
    {id: '6', name: 'Pendiente en visitar'},
    {id: '7', name: 'Compro en otro lado'},        
    {id: '8', name: 'Llamar la proxima semana'},
    {id: '9', name: 'Pendiente en llamar'} ,       
    {id: '10', name: 'Llamara cuando necesite'}              
  ],
  selectedOption: {id:  $scope.idObserva} //This sets the default value of the select in the ui
}    
    
    
    

};  
    
    
  
    
$scope.salirClienteSemanaRuta = function () {  
  $scope.idClie      = "";
  $scope.nombreClie  = "";
  $scope.fechaMod    = "";
  $scope.fechaUltima = "";
  $scope.idObserva   = "";
   $scope.observacionRuta   = "";
    $scope.telefonoMod ="";
   
    
$scope.diaSemanaEscusa = {
  availableOptions: [
    {id: '1', name: 'Abastecido'},
    {id: '2', name: 'No le ha ido muy bien'},
    {id: '3', name: 'Lo llamo y no contesta'},
    {id: '4', name: 'Voy y está cerrado'} ,
    {id: '5', name: 'Vacaciones'},      
    {id: '6', name: 'Pendiente en visitar'},
    {id: '7', name: 'Compro en otro lado'},        
    {id: '8', name: 'Llamar la proxima semana'},
    {id: '9', name: 'Pendiente en llamar'} ,       
    {id: '10', name: 'Llamara cuando necesite'}              
  ],
  selectedOption: {id:  0} //This sets the default value of the select in the ui
}    
    
    
    

};     
    
    
    
$scope.confEliminarClie = function(indCli){
    
  alertify.confirm(" ¿ Esta seguro que desea eliminar cliente ? ", function (e) {
        if (e) {
            $scope.eliminarClientes(indCli);            
        } 
    });    
   
};
    

    
$scope.insertarClienteSemanaRuta = function(){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=insertarClientesSemana&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsua:$scope.idUsuarioLogin,
                                       idClie:$scope.idClie,
                                       idDia:$scope.diaSemana.selectedOption.id }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	    
              var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Asignado Correctamente.");
                     $scope.InitClientes();
                     $scope.InitClientesSemana();
                 }else{
                    alertify.error("Error al asignar cliente.");
                 }
		 
          }).error(function(error){
                        console.log(error);
    }); 	
}      
    
    
$scope.eliminarClientes = function(indCli){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=eliminarClientesSemana&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({nRuta:indCli.id_ruta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	    
              var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Cliente eliminado correctamente.");
                     $scope.InitClientes();
                     $scope.InitClientesSemana();
                 }else{
                    alertify.error("Error al eliminar.");
                 }
		 
          }).error(function(error){
                        console.log(error);
    }); 	
}       
   


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
    
 
$scope.InitClientes = function(){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarClientesRuta&tienda='+$scope.tiendaSeleccionada,
                        data: $.param({idUsua:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listCliente    = []; 
             $scope.listCliente = data;
		 
          }).error(function(error){
                        console.log(error);
    }); 	
}   


$scope.InitClientesSemana = function(){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarClientesSemana&tienda='+$scope.tiendaSeleccionada,
                        data: $.param({idUsua:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.listClienteSemana    = []; 
             $scope.listClienteSemana = data;
		 
          }).error(function(error){
                        console.log(error);
    }); 	
}


 $scope.goLoginRuta = function (ruta){
     if(ruta.ubica_gps != ''){
    window.open('https://maps.google.com/?q='+ruta.ubica_gps+'&zoom=15&maptype=satellite');

  }  else{
      			   alertify.success("Sin ubicacion.");

  }
}
 
 
  $scope.goLoginRutaReportada = function (ruta){
     if(ruta.$ubicacion_geo != ''){
    window.open('https://maps.google.com/?q='+ruta.ubicacion_geo+'&zoom=15&maptype=satellite');

  }  else{
      			   alertify.success("Sin ubicacion.");

  }
}


$scope.InitClientesPDF = function(){	
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
    
    
     var arrayDeta = [];
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarClieSemanaPdf&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idUsua:parseInt($scope.idUsuarioLogin),
                                        idDia:parseInt($scope.diaSemanaPdf.selectedOption.id)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	     $scope.listClienteSemanaPDF    = []; 
             $scope.listClienteSemanaPDF = data;
         
         
         for (var i = 0; i <    $scope.listClienteSemanaPDF.length; i++) {  
            var objDeta= new Object();           
                objDeta.id_cliente     =  $scope.listClienteSemanaPDF[i].id_cliente;
                objDeta.nombre         =  $scope.listClienteSemanaPDF[i].nombre;
                objDeta.direccion      =  $scope.listClienteSemanaPDF[i].direccion;
                objDeta.fecha          =  $scope.listClienteSemanaPDF[i].fecha;
                objDeta.telefono       =  $scope.listClienteSemanaPDF[i].telefono;
                         objDeta.fecha_actual_gps       =  $scope.listClienteSemanaPDF[i].fecha_actual_gps;
                         objDeta.ubica_gps       =  $scope.listClienteSemanaPDF[i].ubica_gps;

                                      objDeta.ubicacion_geo       =  $scope.listClienteSemanaPDF[i].ubicacion_geo;

             objDeta.objDirecCant="";
               if($scope.listClienteSemanaPDF[i].cantidaddirecc > 1){
                  
                   objDeta.objDirecCant     = 'img/signoMas.png'; 
               }
             
             
             
             
             
             
             
                     
                if($scope.listClienteSemanaPDF[i].verde  == true){                    
                    objDeta.urlImg     = 'img/verde.png';                    
                }else if($scope.listClienteSemanaPDF[i].rojo == true){                         
                     objDeta.urlImg     = 'img/rojo.jpg';                       
                }else if($scope.listClienteSemanaPDF[i].amarillo  == true ){                    
                     objDeta.urlImg     = 'img/amarillo.jpg';                              
                }
             
             
             
             
                    if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Mascotero'){
                    
                    objDeta.urlImgTipoClie     = 'img/Mascotero.png';
                    
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador == 'Almacen'){
                         
                     objDeta.urlImgTipoClie     = 'img/Almacen.png';    
                   
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Particular' ){
                    
                     objDeta.urlImgTipoClie     = 'img/Particular.png';     
                         
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Veterinaria' ){
                    
                     objDeta.urlImgTipoClie     = 'img/Veterinaria.png';     
                         
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Avicola' ){
                    
                     objDeta.urlImgTipoClie     = 'img/Agricola.png';     
                         
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Peluqueria' ){
                    
                     objDeta.urlImgTipoClie     = 'img/Peluqueria.png';     
                         
                }else if($scope.listClienteSemanaPDF[i].tipo_comprador  == 'Otro' ){
                    
                     objDeta.urlImgTipoClie     = 'img/Otro.png';     
                         
                }
             
             
             
             
             
                  var diaSel = $scope.diaSemanaPdf.selectedOption.id;


                if(diaSel==1){
                    objDeta.diaSelecionado         = 'LUNES '+daym+'-'+month+'-'+year;   
                }else if(diaSel==2){
                    objDeta.diaSelecionado         = 'MARTES '+daym+'-'+month+'-'+year;   
                }else if(diaSel==3){
                     objDeta.diaSelecionado        = 'MIERCOLES '+daym+'-'+month+'-'+year;       
                }else if(diaSel==4){
                       objDeta.diaSelecionado      = 'JUEVES '+daym+'-'+month+'-'+year;     
                }else if(diaSel==5){
                        objDeta.diaSelecionado     = 'VIERNES '+daym+'-'+month+'-'+year;     
                }

             
               var diaSelObs    = $scope.listClienteSemanaPDF[i].id_observacion;
               var obsTipo      = $scope.listClienteSemanaPDF[i].observacion;
               var obsTipoFecha = $scope.listClienteSemanaPDF[i].fecha_obs_mod;

             
                      if(diaSelObs==1){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Abastecido ->'+obsTipo;   
                }else if(diaSelObs==2){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' No le ha ido muy bien ->'+obsTipo;   
                }else if(diaSelObs==3){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Lo llamo y no contesta ->'+obsTipo;   
                }else if(diaSelObs==4){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Voy y está cerrado ->'+obsTipo;   
                }else if(diaSelObs==5){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Vacaciones ->'+obsTipo;
                }else if(diaSelObs==6){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Pendiente en visitar ->'+obsTipo;  
                }else if(diaSelObs==7){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Compro en otro lado ->'+obsTipo;  
                }else if(diaSelObs==8){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Llamar la proxima semana ->'+obsTipo; 
                }else if(diaSelObs==9){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Pendiente en llama ->'+obsTipo; 
                }else if(diaSelObs==10){
                    objDeta.diaSelObs    = 'F.Mod:'+obsTipoFecha+' Llamara cuando necesite ->'+obsTipo; 
                }
             
             
             
             
             
         /*    
             
               availableOptions: [
    {id: '1', name: 'Abastecido'},
    {id: '2', name: 'No le ha ido muy bien'},
    {id: '3', name: 'Lo llamo y no contesta'},
    {id: '4', name: 'Voy y está cerrado'} ,
    {id: '5', name: 'Vacaciones'},      
    {id: '6', name: 'Pendiente en visitar'},
    {id: '7', name: 'Compro en otro lado'},        
    {id: '8', name: 'Llamar la proxima semana'},
    {id: '9', name: 'Pendiente en llamar'} ,       
    {id: '10', name: 'Llamara cuando necesite'}              
  ],*/
             
             
             /*
             if($scope.checkboxTipoClie.almacen == true){
                  
             }else if($scope.checkboxTipoClie.mascotero == true){
                       
                       
             }else if($scope.checkboxTipoClie.peluqueria == true){
                 
                  
             }else if($scope.checkboxTipoClie.veterinaria == true){
                      
             }*/
             

             if($scope.listClienteSemanaPDF[i].tipo_comprador=="Almacen"){
                 
                 if($scope.checkboxTipoClie.almacen == true){
                  arrayDeta.push(objDeta);  
                 }
                
             }else if($scope.listClienteSemanaPDF[i].tipo_comprador=="Mascotero"){
                 
                 if($scope.checkboxTipoClie.mascotero == true){
                       
                     arrayDeta.push(objDeta);    
                 }
                      
             }else if($scope.listClienteSemanaPDF[i].tipo_comprador=="Peluqueria"){
                 
                 if($scope.checkboxTipoClie.peluqueria == true){
                 
                  arrayDeta.push(objDeta);  
                 }
                      
             }else if($scope.listClienteSemanaPDF[i].tipo_comprador=="Veterinaria"){
                 if($scope.checkboxTipoClie.veterinaria == true){
                   arrayDeta.push(objDeta);     
                 }     
             }
             
             
             
             
		        
         }

       var jsonData=angular.toJson(arrayDeta);
        var objectToSerialize={'detalle':jsonData};
        
          var config = {
            url: 'FunctionIntranet.php?act=generarClientePDFRuta&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="ClienteRutaPDF.pdf";          
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
   
          }).error(function(error){
                        console.log(error);
    }); 	
}


$scope.guardarObservacionCli = function(){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=guardarObsClie&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idObs:$scope.observacionRuta,
                             idClie:$scope.idClie                           
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


		
$scope.selTipoObservacion = function(){
			 
	 $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=updateObservacionClie&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idObs:$scope.diaSemanaEscusa.selectedOption.id.toString(),
                             idClie:$scope.idClie                        
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                  //  alertify.success("Obervacion guardado con exito!");                   
                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          

          }).error(function(error){
                console.log(error);
        });
	
	
	
};		


    
    
}]);