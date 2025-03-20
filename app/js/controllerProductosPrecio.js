angularRoutingApp.controller('controllerProductosPrecio', ['$scope', '$http',
    function ($scope, $http) {
    
          $scope.loading        = false; 

        
$scope.init = function () {
  if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }  
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=tipoProductoPrecio&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
               $scope.tipos  = [];
               $scope.tipos2 = [];
               $scope.tipos  = data;
               $scope.tipos2 = data;
               $scope.codProd    = "";
               $scope.nombProd   = "";
               $scope.nombreProd = "";
               $scope.radioPreFac=1;

          }).error(function(error){
                        console.log(error);
    
    });
    


};

        
$scope.selProveedor = function () {
        $scope.prove  = ($scope.proveedor == undefined ? "": $scope.proveedor.id_proveedor);

}
         
        
         
$scope.selTipo = function () {
    $scope.auxTipo = $scope.tipo;
     $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.auxTipo.codCategoria).success(function(data) {
        console.log(data);
        $scope.marcas = [];
        $scope.marcas = data;
    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
            
};

        
           
$scope.selMarca = function () {

    $scope.listProd         = [];
    $scope.loadingProd      = true;
    $scope.loadingProdVacio = false;
    $scope.auxMarca         = $scope.marca;         

};       
        
        
        
$scope.buscarProductos = function () {
    $scope.loading=true; 

     var marcaProd           = ($scope.auxMarca == undefined ? "": $scope.auxMarca.codMarca);
     var categoriaProd       = ($scope.auxTipo == undefined ? "": $scope.auxTipo.codCategoria);
     var idProveedor         = ($scope.prove == undefined ? "": $scope.prove);
    

     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProductosTotal&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({codProducto:idProveedor, 
                                        nombreProd:$scope.nombProd, 
                                        marcaProd:marcaProd, 
                                        categoriaProd:categoriaProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                $scope.listProd = data;
                $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    
    
    
    
};

$scope.buscarPreciosFact = function (objProd) {  
     $scope.nombreProd    = objProd.nombreProd;
     $scope.precioVenta   = "";
     $scope.descuento     = "";
     $scope.valorNeto     = "";
     $scope.cantidadStock = "";
    
     $http.get('FunctionIntranet.php?act=listPreciosFactura&tienda='+$scope.tiendaSeleccionada+'&idProducto='+objProd.id).success(function(data) {
        console.log(data);               
        $scope.listPrecioFact = data;
            
        for(var i = 0; i < $scope.listPrecioFact.length; i++){
                if($scope.listPrecioFact[i].activo == "true"){
                    $scope.idFac         = parseInt($scope.listPrecioFact[i].idFactura);   
                    $scope.radioPreFac   = parseInt($scope.listPrecioFact[i].idFactura);                    
                    $scope.precioVenta   = parseInt($scope.listPrecioFact[i].precio_venta * 1.19);
                    $scope.descuento     = parseInt($scope.listPrecioFact[i].descuento);
                    $scope.valorNeto     = parseInt($scope.listPrecioFact[i].valor_neto);
                    $scope.cantidadStock = parseInt($scope.listPrecioFact[i].cantidad);
                    $scope.idProd        = parseInt($scope.listPrecioFact[i].idProd);   

                    
                    break;
                }
          }         
            
    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);

    });
}


    

$scope.selPrecio = function(item){   
    $scope.idFac         = parseInt(item.idFactura);
    $scope.precioVenta   = parseInt(item.precio_venta * 1.19);
    $scope.descuento     = parseInt(item.descuento);
    $scope.valorNeto     = parseInt(item.valor_neto);
    $scope.cantidadStock = parseInt(item.cantidad);   
    $scope.idProd        = parseInt(item.idProd);   
};       
       
        
$scope.guardarPrecioFact = function (){
     var arrayProd    = [];    
     var objPre       = new Object();
    
     objPre.precVenta = parseInt($scope.precioVenta / 1.19);
     objPre.desc      = $scope.descuento;
     objPre.valorNet  = $scope.valorNeto; 
     objPre.canti     = $scope.cantidadStock;
     objPre.idFac     = $scope.idFac; 
     objPre.idProd    = $scope.idProd;  
     
    arrayProd.push(objPre);
    
    var obj    = JSON.stringify(arrayProd);
    
    $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=guardarPrecioFact&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({objPrcioFac:obj}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
              var respuesta = data.charAt(data.length-1);

                 if(respuesta=="1"){                
                        alertify.success("Precio modificado con exito!");
                    }else{
                        alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");
                    }
      }).error(function(error){
            console.log(error);
            $scope.loadingProd = false;

    });
    
};



$scope.listarProveedores = function(codProve){
    

    $http.post('FunctionIntranet.php?act=listarProvSelection&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);     
        
               
           $scope.proveedores = {
                  availableOptions: data,                      
                  selectedOption: {id_proveedor: codProve} 
           }
        
        

            }).
            error(function(data, status, headers, config) {
                console.log("error listar proveedores: "+data);
      });
};

        
$scope.selProductosTop = function(selProd){
    
    $scope.nombProdMod  = selProd.nombreProd; 
    $scope.codProdMod   = selProd.codProducto;
    $scope.codProveedor = selProd.cod_proveedor;
    $scope.idProdSel    = selProd.id;
        
    
    // var formData = { id: selProd.codTipoProd};
    var postData = 'id='+parseInt(selProd.codTipoProd);
    
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada,
                        data: postData,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                      
                       $scope.marcaProdSele = {
                              availableOptions: data,                      
                              selectedOption: {codMarca: selProd.codMarcaProd} 
                       }
        
        
          }).error(function(error){
                        console.log(error);
    
    });

    
    
    
    
   $scope.tipoProd = {
          availableOptions: $scope.tipos,                      
          selectedOption: {codCategoria: selProd.codTipoProd} 
   }

    
    $scope.listarProveedores($scope.codProveedor);
    
};




$scope.guadarProducto = function(){
      var arrObj = [];
      var objProd= new Object();
      objProd.idProducto    =  $scope.codProdMod; 
      objProd.codProducto   =  $scope.codProdMod; 
      objProd.nombreProd    =  $scope.nombProdMod;
      objProd.marcaProd     =  $scope.marcaProdSele.selectedOption.codMarca; 
      objProd.categoriaProd =  $scope.tipoProd.selectedOption.codCategoria; 
      objProd.idProd        =  $scope.idProdSel; 
      
      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=saveProductoMod&tienda='+$scope.tiendaSeleccionada,
                        data: objCabe,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                      
              
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
};
        
        
        
$scope.listarSalProd = function(index){
     
     $scope.selNombProd = index.nombreProd;
    
      $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=listarSalidasProductos&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({idProd:index.id}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
          
        $scope.listProdSal= data;  
          
          
      }).error(function(error){
            console.log(error);
            $scope.loadingProd = false;

    });
    
    
};

        
}]);