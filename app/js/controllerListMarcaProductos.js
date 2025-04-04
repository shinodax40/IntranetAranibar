angularRoutingApp.controller('controllerListMarcaProductos', ['$scope', '$http',
    function ($scope, $http) {

        $scope.listadoMarcas = [];
        $scope.listadoCategoria = [];
 /*********************MARCA**********************************/       
$scope.initMarca = function () {    
    
    $.blockUI({ css: { 
        border: 'none', 
        padding: '15px', 
        backgroundColor: '#000', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#fff' 
    } }); 




        $http({method : 'POST',
        url : 'FunctionIntranet.php?act=listarMarcasProductos&tienda='+$scope.tiendaSeleccionada,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
        }).success(function(data){

            $scope.listadoMarcas = data;

        setTimeout($.unblockUI, 1000);
        }).error(function(error){
        console.log("error Listar Marca: "+error);

        setTimeout($.unblockUI, 1000);
        });


        $http({method : 'POST',
            url : 'FunctionIntranet.php?act=tipoProducto&tienda='+$scope.tiendaSeleccionada,
           /* data:  $.param({data: {productos: arrayDeta, documentoReferenciado: refObj, id_credito: $scope.listPedidosIndex.id}}),   */                             
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
            }).success(function(data){
    


                $scope.tipos         = data;
                $scope.listCategoria = data;
                  
    
            setTimeout($.unblockUI, 1000);
            }).error(function(error){
            console.log("error Listar Tipo: "+error);
    
            setTimeout($.unblockUI, 1000);
            });    

};

$scope.confirmarActiveProd = function (selMarca, value) {
    var length = $scope.listadoMarcas.length; 
    var objProd = new Object();	
     for(var i = 0; i < length; i++) {
       if($scope.listadoMarcas[i].codMarca == selMarca.codMarca){
              $scope.listadoMarcas[i].activo  = value;	
           break;
       }             
    }
};

$scope.selTipo = function (valueObj, listMarcaSel) { 

     var arrayData = valueObj.split(','); 

     var length = $scope.listadoMarcas.length; 
     var objProd = new Object();	
      for(var i = 0; i < length; i++) {
        if($scope.listadoMarcas[i].codMarca == listMarcaSel.codMarca){
               $scope.listadoMarcas[i].codCategoria     = arrayData[0];	
               $scope.listadoMarcas[i].nombreCategoria  = arrayData[1];
            break;
        }             
     }
            
     
             
};

 $scope.updateEscalaProd = function (obj){
    
    $http({
     method : 'POST',
        url : 'FunctionIntranet.php?act=actualizarMarca&tienda='+$scope.tiendaSeleccionada,
        data:  $.param({codMarca:obj.codMarca,
                        nombreMarca:obj.nombreMarca,
                        codCategoria:obj.codCategoria,
                        activoMarca:obj.activo
                       }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               

         var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Actualizado con exito!");
                $scope.confirmarEstadoModificar(obj, 0);
                
            }else{
                alertify.error("Error al actualizar escala, favor contactar con el Administrador del Sistema.");
            }
        
      
  }).error(function(error){
        console.log(error);

});
    
    
};

$scope.confirmarInsertarMarca = function (){ 
    alertify.confirm("Esta seguro que desea insertar nueva marca ?", function (e) {
        if (e) {            
            $scope.insertarMarcaProducto();         
        }
    });        
};        

$scope.insertarMarcaProducto =  function(){
    $http({
           method : 'POST',
           url : 'FunctionIntranet.php?act=insertarMarcaProducto&tienda='+$scope.tiendaSeleccionada,           
           headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
     }).success(function(data){
           console.log(data);               
   
            var respuesta = data.charAt(data.length-1);
                       
               if(respuesta=="1"){                                  
                  alertify.success("Ingresado con exito!");   
                  $scope.initMarca();                
               }else{
               
                 alertify.error("Error al insertar marca.");
               }
           
         
     }).error(function(error){
           console.log(error);
   
   });
};

$scope.eliminarMarcaProducto =  function(obj){
    $http({
           method : 'POST',
           url : 'FunctionIntranet.php?act=eliminarMarcaProducto&tienda='+$scope.tiendaSeleccionada, 
           data:  $.param({codMarca:obj.codMarca
           }),          
           headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
     }).success(function(data){
           console.log(data);               
   
            var respuesta = data.charAt(data.length-1);
                       
               if(respuesta=="1"){                                  
                  alertify.success("Eliminado con exito!");  
                  $scope.initMarca();                  
               }else{
               
                 alertify.error("Error al eliminar marca.");
               }
           
         
     }).error(function(error){
           console.log(error);
   
   });
};

$scope.confirmarEliminarMarca = function (obj){ 
    alertify.confirm("Esta seguro que desea eliminar  marca ?", function (e) {
        if (e) {            
            $scope.eliminarMarcaProducto(obj);         
        }
    });        
};      

$scope.consultarRelacionMarca = function(obj){
    
    $http({
        method : 'POST',
           url : 'FunctionIntranet.php?act=consultarRelacionProductoMarca&tienda='+$scope.tiendaSeleccionada,
           data:  $.param({codMarca:obj.codMarca,
                           codCategoria:obj.codCategoria,
                          }),
                   headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
     }).success(function(data){
           console.log(data);               
   
            var respuesta = data.charAt(data.length-1);
                       
               if(respuesta=="1"){                
                  
                   alertify.error("Error al eliminar marca, existen producto asociados a la marca.");
                   
               }else{
                $scope.confirmarEliminarMarca(obj); 
               }
           
         
     }).error(function(error){
           console.log(error);
   
   });

};	  
   
$scope.confirmarEstadoModificar = function(prodDet, value){
    
	var length = $scope.listadoMarcas.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.listadoMarcas[i].codMarca == prodDet.codMarca){
              $scope.listadoMarcas[i].estado_mod  = value;	
           break;
       }             
    }
};	             

$scope.selProdParaMod = function(selMa){
     $scope.idMarca      = selMa.codMarca;  
     $scope.nombProdMod  = selMa.nombreMarca; 

 
};
       
$scope.guadarProductoArchivo = function(){
    var arrObj = [];
    var objProd= new Object();
    objProd.idMarca      =  $scope.idMarca;
    objProd.imagen       =  $scope.variableBase64;
    arrObj.push(objProd);
  
    var objCabe    = JSON.stringify(arrObj);   
   
  
$http({
  method: 'POST',
  url: 'FunctionIntranet.php?act=saveMarcaArchivo&tienda='+$scope.tiendaSeleccionada, 
  data: $.param({ idMarca: $scope.idMarca, imagen: $scope.variableBase64 }),
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
      
      
  },
  function(err) {
                           alertify.error("Error al subir archivo, favor contactar con el Administrador del Sistema.");

  }
);

};
   
/***********************MARCA FIN*******************************************/        
        
  
$scope.initCategoria = function () {    
    
    $.blockUI({ css: { 
        border: 'none', 
        padding: '15px', 
        backgroundColor: '#000', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#fff' 
    } }); 




        $http({method : 'POST',
        url : 'FunctionIntranet.php?act=listarCategoriasProductos&tienda='+$scope.tiendaSeleccionada,
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
        }).success(function(data){

            $scope.listadoCategoria = data;

        setTimeout($.unblockUI, 1000);
        }).error(function(error){
        console.log("error Listar Marca: "+error);

        setTimeout($.unblockUI, 1000);
        });


   

};


$scope.confirmarEstadoModificarCategoria = function(prodDet, value){
    
	var length = $scope.listadoCategoria.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
       if($scope.listadoCategoria[i].codCategoria == prodDet.codCategoria){
              $scope.listadoCategoria[i].estado_mod  = value;	
           break;
       }             
    }
};	             

$scope.updateEscalaProdCategoria = function (obj){
    $http({
     method : 'POST',
        url : 'FunctionIntranet.php?act=actualizarCategoria&tienda='+$scope.tiendaSeleccionada,
        data:  $.param({codCategoria:obj.codCategoria,
                        nombreCategoria:obj.nombreCategoria,
                        nombreGrupo:obj.grupo,
                        activoCategoria:obj.activo,
                        activoPagina:obj.activo_pagina
                       }),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               

         var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Actualizado con exito!");
                $scope.confirmarEstadoModificarCategoria(obj, 0);
                
            }else{
                alertify.error("Error al actualizar categoria, favor contactar con el Administrador del Sistema.");
            }
        
      
  }).error(function(error){
        console.log(error);

});
    
    
};

$scope.confirmarEstadoModificarCategoria = function(prodDet, value){    
	var length = $scope.listadoCategoria.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
       if($scope.listadoCategoria[i].codCategoria == prodDet.codCategoria){
              $scope.listadoCategoria[i].estado_mod  = value;	
           break;
       }             
    }
};	    


$scope.confirmarActiveProdCategoria = function (selMarca, value) {
    var length = $scope.listadoCategoria.length; 
    var objProd = new Object();	
     for(var i = 0; i < length; i++) {
       if($scope.listadoCategoria[i].codCategoria == selMarca.codCategoria){
              $scope.listadoCategoria[i].activo  = value;	
           break;
       }             
    }
};

$scope.confirmarActiveProdCategoriaPagina = function (selMarca, value) {
    var length = $scope.listadoCategoria.length; 
    var objProd = new Object();	
     for(var i = 0; i < length; i++) {
       if($scope.listadoCategoria[i].codCategoria == selMarca.codCategoria){
              $scope.listadoCategoria[i].activo_pagina  = value;	
           break;
       }             
    }
};

}]);