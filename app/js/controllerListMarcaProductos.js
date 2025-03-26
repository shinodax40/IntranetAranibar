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
       /* data:  $.param({data: {productos: arrayDeta, documentoReferenciado: refObj, id_credito: $scope.listPedidosIndex.id}}),   */                             
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
        }).success(function(data){

            $scope.listadoMarcas = data;

        setTimeout($.unblockUI, 1000);
        }).error(function(error){
        console.log("error Listar Marca: "+error);

        setTimeout($.unblockUI, 1000);
        });






};

   
    
       

        
        
        

}]);