angularRoutingApp.controller('controllerListMarcaProductos', ['$scope', '$http',
    function ($scope, $http) {

        $scope.listadoMarcas = [];
   
        
$scope.init = function () {    
    
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





$scope.selTipo = function (value, listMarcaSel) {

     var length = $scope.listadoMarcas.length; 
     var objProd = new Object();	
      for(var i = 0; i < length; i++) {
        if($scope.listadoMarcas[i].codMarca == listMarcaSel.codMarca){
               $scope.listadoMarcas[i].codCategoria  = value;	
            break;
        }             
     }
            
     
             
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
           
       

        
        
        

}]);