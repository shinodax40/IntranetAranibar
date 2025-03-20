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
 
angularRoutingApp.controller('controllerProductos', ['$scope', '$http',
    function ($scope, $http) {
          $scope.variableBase64 ="";
          $scope.loading        = false; 
          $scope.tipoBusqueda   = "1";
 $scope.listProdSub= [];
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
$scope.fechaActual = daym+"/"+month+"/"+year;
$scope.horaActual  = mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();             
        
$scope.init = function () {
       $scope.codBarraSel="";
    
      document.getElementById('divProductoModificar').style.display          = 'none';      
      document.getElementById('divListadoProducto').style.display             = 'block';   
    
    
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarClasificacionProd&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                      
                    
         
          $scope.clasifProd = {
          availableOptions: data,                      
          selectedOption: {id: "0"} 
          }  
          
          
          $scope.clasifProd2 = {
          availableOptions: data,                      
          selectedOption: {id: "0"} 
          } 
          
          
          
        
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    
  $scope.bodegas = {
      availableOptions: [
        {id: '1', name: '1'},
        {id: '2', name: '2'},
        {id: '3', name: '3'},
        {id: '4', name: '4'},
        {id: '5', name: '5'},
        {id: '6', name: '6'}, 
        {id: '7', name: '7'}, 
        {id: '8', name: '8'}
      ],
      selectedOption: {id: $scope.codBodega} //This sets the default value of the select in the ui
  } 
    
    
     $scope.clasificacionProdLis = {
      availableOptions: [
        {id: '1', name: 'Mas sale'},
        {id: '2', name: 'Clasificacion'}           

      ],
      selectedOption: {id: "1"} //This sets the default value of the select in the ui
    }
    
    
    $scope.listarClasProd();
    
  if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
     }  
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=tipoProducto&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
        
               $scope.tipos  = [];
               $scope.tipos2 = [];
               $scope.tipos  = data;
               $scope.tipos2 = data;
               $scope.codProd          = "";
               $scope.nombProd         = "";
               $scope.nombreProd       = "";
               $scope.radioPreFac      = 1;
               $scope.ofertaPorcentaje = "";
               $scope.porcentaje       = "0";
               $scope.selPorcentaje = "";
               $scope.marcas2 = [];
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    
    
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
         
  $scope.selTipoNuevo = function () {
    $scope.auxTipo2 = $scope.tipo2;
     $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.auxTipo2.codCategoria).success(function(data) {
        console.log(data);
        $scope.marcas2 = [];
        $scope.marcas2 = data;
    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
            
};
        
        
        
  $scope.selTipoModificar = function () {
    $scope.auxTipo2 = $scope.tipoProd;
     $http.get('FunctionIntranet.php?act=getDataMarca&tienda='+$scope.tiendaSeleccionada+'&id='+$scope.auxTipo2.codCategoria).success(function(data) {
        console.log(data);
        $scope.marcaProdSele = [];
        $scope.marcaProdSele = data;
    }).
    error(function(data, status, headers, config) {
        console.log("error: "+data);
    });
            
};        
      
         
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

$scope.selMarcaNuevo = function () {
    $scope.listProd         = [];
    $scope.loadingProd      = true;
    $scope.loadingProdVacio = false;
    $scope.auxMarca2         = $scope.marca2;         

};              
           
$scope.selMarca = function () {
    $scope.listProd         = [];
    $scope.loadingProd      = true;
    $scope.loadingProdVacio = false;
    $scope.auxMarca         = $scope.marca;         

};       
        
        
        
$scope.buscarProductos = function () {
    var nombreProd     = angular.element(document.querySelector("#nombreProd")).val();
    var codigoProd     = angular.element(document.querySelector("#codigoProd")).val();

    
    $scope.loading=true; 
     var marcaProd           = ($scope.auxMarca == undefined ? "": $scope.auxMarca.codMarca);
     var categoriaProd       = ($scope.auxTipo  == undefined ? "": $scope.auxTipo.codCategoria);
     var idProveedor         = ($scope.prove    == undefined ? "": $scope.prove);
     var idBodega            = $scope.bodegas.selectedOption.id;
     var idClasificacion     = $scope.clasificacionProdLis.selectedOption.id;

    var idClasificacionFiltro     = $scope.clasifProd.selectedOption.id;

    
    

     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listProductosTotal&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({codProducto:idProveedor, 
                                        nombreProd:$scope.nombProd, 
                                        marcaProd:marcaProd, 
                                        categoriaProd:categoriaProd,
                                        bodega:idBodega,
                                        clasif:idClasificacion,
                                        clasiFiltro:idClasificacionFiltro,
                                        nombreProdBusq:nombreProd,
                                        codigoProdBusq:codigoProd,
                                        tipoBusqueda: $scope.tipoBusqueda}),
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

        
$scope.verDetallePedidoSalirMod = function(){
    $scope.listProdSub = [];
    $scope.listCodigoBarraProd= [];
     document.getElementById('divProductoModificar').style.display          = 'none';      
    document.getElementById('divListadoProducto').style.display             = 'block';   
    
}        
        
        
$scope.selProdParaMod = function(selProd){
       $scope.idProdSel    = selProd.id;  
        $scope.nombProdMod  = selProd.nombreProd; 

    
}


$scope.selProductosTop = function(selProd){
    
    $scope.listarCodigoBarra(selProd.id);
    
    document.getElementById('divProductoModificar').style.display          = 'block';      
    document.getElementById('divListadoProducto').style.display             = 'none';   
    
    $scope.precioPart  = selProd.precioPart; 
    $scope.nombProdMod  = selProd.nombreProd; 
    $scope.codProdMod   = selProd.codProducto;
    $scope.codProveedor = selProd.id_proveedor.toString();
    $scope.idProdSel    = selProd.id;        
    $scope.pesosProd     = selProd.pesosProd;
    $scope.bodegaProd    = selProd.bodega;
    // var formData = { id: selProd.codTipoProd};
    var postData = 'id='+parseInt(selProd.codTipoProd);
    
   // $scope.codBarra     = selProd.cod_barra;
    $scope.fotoUrl      = selProd.foto;
    $scope.grupoProd    = selProd.grupo_prod;
    
    
      $scope.nodoProd    = selProd.nodo_prod;
  
    
    
    if(selProd.fecha_vencimiento != null){
           var arrayfecha = selProd.fecha_vencimiento.split('-');     
           document.getElementById('fVencimiento').value= arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];       
     }else {    
           document.getElementById('fVencimiento').value  = '' ;
    }
    
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

    
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarClasificacionProd&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                      
                    
         
       /*   $scope.clasifProd = {
          availableOptions: data,                      
          selectedOption: {id: selProd.clasId} 
          }  */
         
            $scope.clasifProd2 = {
          availableOptions: data,                      
          selectedOption: {id: selProd.clasId} 
          }  
        
        
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    
    
    
    $http.get('FunctionIntranet.php?act=listarProvSelection&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);     
        
        
          $scope.proveedoresEditar = {
          availableOptions: data,                      
          selectedOption: {id_proveedor: selProd.id_proveedor} 
          }  
        
        

            }).
            error(function(data, status, headers, config) {
                console.log("error listar proveedores: "+data);
      });
    
    
    var tipoBusqNodo ="";
    var tipoBusqNodoId = 0;
    if(selProd.nodo_prod == "0"){
        tipoBusqNodoId = 0;       
        tipoBusqNodo = selProd.id;        
    }else{
        tipoBusqNodoId = 1;       
        tipoBusqNodo = selProd.nodo_prod;        
             $scope.listProdSub = [];
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarSubProducto&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({ nodo_prod:tipoBusqNodo, tipBusq: tipoBusqNodoId }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                      
                    
    
                  $scope.listProdSub = data;
        
          }).error(function(error){
                        console.log(error);
    
    });
    }
    

    
    
    
   $scope.tipoProd = {
          availableOptions: $scope.tipos,                      
          selectedOption: {codCategoria: selProd.codTipoProd} 
   }
    
    
  

    
    
};



$scope.selClasificacionNuevo = function () {
    $scope.auxClasificacion         = $scope.clasificacionProductos;         
};
        
$scope.selProveedorNuevo = function () {
    $scope.auxProveedor         = $scope.proveedorNuevo;         
};  
        
        
$scope.guadarProducto = function(){
      var arrObj = [];
      var objProd= new Object();
      objProd.idProducto      =  $scope.codProdMod; 
      objProd.codProducto     =  $scope.codProdMod; 
      objProd.nombreProd      =  $scope.nombProdMod;    
      objProd.marcaProd       =  $scope.marcaProdSele.selectedOption.codMarca; 
      objProd.categoriaProd   =  $scope.tipoProd.selectedOption.codCategoria;     
      objProd.idProd          =  $scope.idProdSel;       
      objProd.idProv          =  $scope.proveedoresEditar.selectedOption.id_proveedor;      
      objProd.bodegaProd      =  $scope.bodegaProd;
      objProd.pesosProd       =  $scope.pesosProd;    
      objProd.calfProd        =  $scope.clasifProd2.selectedOption.id;    
      objProd.precioPart      =  $scope.precioPart;
      objProd.cod_barra       =  $scope.codBarra;
      objProd.fVencimiento    =  angular.element(document.querySelector("#fVencimiento")).val();
      objProd.grupoProd       =  $scope.grupoProd;
      objProd.nodoProd       =  $scope.nodoProd;

    
    

      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
     
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=saveProductoMod&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objProdutos:objCabe}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                var respuesta = data.charAt(data.length-1);
                    
    
        
              if(respuesta=="1"){                
                alertify.success("Producto modificado con exito!");
               // $scope.precioPart ="";
             //   $scope.fotos="";
                    }else{
                        
                        if(respuesta=="2"){
                             alertify.error("Error al subir archivo.");
                        }else if(respuesta=="3"){
                              alertify.error("La imagen debe ser png.");
                        }else if(respuesta=="0"){
                        alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");

                        }
                        
                    }
        
        
         
        
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
};
        
        
        
        
        
        
$scope.guadarProductoArchivo = function(){
      var arrObj = [];
      var objProd= new Object();
      objProd.idProdSel      =  $scope.idProdSel; 
    
    
      objProd.imagen          =  $scope.variableBase64;

      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
     
    
   /* $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=saveProductoModArchivo&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objProdutos:objCabe}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                var respuesta = data.charAt(data.length-1);
                    
    
        
              if(respuesta=="1"){                
                alertify.success("Producto modificado con exito!");
            //    $scope.precioPart ="";
             //   $scope.fotos="";
                    }else{
                        
                        if(respuesta=="2"){
                             alertify.error("Error al subir archivo.");
                        }else if(respuesta=="3"){
                              alertify.error("La imagen debe ser png.");
                        }else if(respuesta=="0"){
                        alertify.error("Error al insertar imagen, favor contactar con el Administrador del Sistema.");

                        }
                        
                    }
        
        
         
        
        
        
          }).error(function(error){
                        console.log(error);
    
    });  
    */
    
    
    /*
    $http.post("FunctionIntranet.php?act=saveProductoModArchivo", {
            idProd: $scope.idProdSel, 
            imagen: $scope.variableBase64
        })
            .success(function(respuesta){
                console.log(respuesta);
            });
    */
  
    /*
     $http({
        url: 'FunctionIntranet.php?act=saveProductoModArchivo&tienda='+$scope.tiendaSeleccionada,
        method: "POST",
        data: { 'idProd' : $scope.idProdSel,
                'imagen' : $scope.variableBase64 }
    })
    .then(function(response) {
            // success
    }, 
    function(response) { // optional
            // failed
    });
    
    */
        
$http({
    method: 'POST',
    url: 'FunctionIntranet.php?act=saveProductoModArchivo&tienda='+$scope.tiendaSeleccionada, 
    data: $.param({ idProd: $scope.idProdSel, imagen: $scope.variableBase64 }),
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
           
        
        
        
        
        
 $scope.guadarProductoFotos = function(){
      var arrObj = [];
      var objProd= new Object();

      objProd.foto          =  $scope.fotos;
      objProd.idProd        =  $scope.idProdSel; 

    
      
      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
     
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=saveProductoModFoto&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objProdutos:objCabe}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
                var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){                
                alertify.success("Producto modificado con exito!");
                $scope.precioPart ="";
                
            }else{
                alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");
            }
        
        
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


                                         
$scope.generarReporteProductos = function () {
    

  var arrayDeta = [];
  var count=0;    

   for (var i = 0; i <    $scope.listProd.length; i++) {   
     count = count + 1   ;
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.id             =  $scope.listProd[i].id;
            objDeta.nombreProd     =  $scope.listProd[i].nombreProd;
            objDeta.stockBarros          =  $scope.listProd[i].stockTiendas[0].stock// $scope.listProd[i].countStock;
            objDeta.stockSantas          =  $scope.listProd[i].stockTiendas[1].stock// $scope.listProd[i].countStock;
             objDeta.stockTucape          =  $scope.listProd[i].stockTiendas[2].stock// $scope.listProd[i].countStock;

            objDeta.nombreProv     =  $scope.listProd[i].nombreProv;
            objDeta.fecha          =  $scope.fechaActual;
            objDeta.counta          =  count;

            if(objDeta.stockBarros > 0){
                
                 objDeta.urlImgBarros     = 'img/verde.png';

            }else if(objDeta.stockBarros == 0 ){
                
                 objDeta.urlImgBarros     = 'img/rojo.jpg';

            }
       
       
       
       
            if(objDeta.stockSantas > 0){
                
                 objDeta.urlImgSantas     = 'img/verde.png';

            }else if(objDeta.stockSantas == 0 ){
                
                 objDeta.urlImgSantas     = 'img/rojo.jpg';

            }
       
       
       
       
       
         if(objDeta.stockTucape > 0){
                
                 objDeta.urlImgTucape     = 'img/verde.png';

            }else if(objDeta.stockTucape == 0 ){
                
                 objDeta.urlImgTucape     = 'img/rojo.jpg';

            }
       
       
            
       
    arrayDeta.push(objDeta);       
   }
    

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'productos':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarProductosPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="productos_"+$scope.fechaActual+"_"+$scope.horaActual+".pdf";          
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
      

  $scope.listarPorcentajeProd = function(){
     var arrayDeta     = [];
     var objDeta       = new Object();

      $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=listarPorcentajeOferta&tienda='+$scope.tiendaSeleccionada,
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
          
                $scope.ofertaPorcentaje = data;
                objDeta= new Object();
                objDeta.porcentaje      = "- % -";
                objDeta.id        = "0"; 
                arrayDeta.push(objDeta);
          
              for (var i = 0; i <    data.length; i++) {   
                        objDeta= new Object();
                        objDeta.porcentaje      = data[i].porcentaje;
                        objDeta.id              = data[i].id; 
                        arrayDeta.push(objDeta);
              }
           
           $scope.ofertaPorcentaje = {
              availableOptions: arrayDeta,                      
              selectedOption: {id: $scope.porcentaje.toString()} 
            }
          
          
      }).error(function(error){
            console.log(error);

    });
};
        
        
        
        
        
$scope.listarCodigoBarra = function(selProd){

      $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=listarCodigoBarra&tienda='+$scope.tiendaSeleccionada,
           data:  $.param({id:selProd}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
          
                $scope.listCodigoBarraProd = data;
    
          
          
      }).error(function(error){
            console.log(error);

    });
};
        
        
 $scope.eliminarCodigoBarra = function(selProd){

     alertify.confirm("Esta seguro que desea eliminar codigo barra al producto ?", function (e) {
        if (e) {            
            
            
      $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=eliminarCodigoBarra&tienda='+$scope.tiendaSeleccionada,
           data:  $.param({id:selProd.id}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
          
                $scope.listCodigoBarraProd = data;
                $scope.listarCodigoBarra($scope.idProdSel);
      
          
      }).error(function(error){
            console.log(error);

    });
            
            
        }
    });     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
};
               
        
  $scope.agregarCodigoBarra = function(){
      

    if($scope.codBarraSel.length != '' ){  
      
         alertify.confirm("Esta seguro que desea agregar codigo barra al producto ?", function (e) {
        if (e) {            
            
            
                  $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=agregarCodigoBarra&tienda='+$scope.tiendaSeleccionada,
           data:  $.param({id:$scope.idProdSel,
                          cod_barra:$scope.codBarraSel}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data);               
          
     
     
                       if(data=="1"){
                        alertify.success("Agregado con exito!");
                           $scope.codBarraSel ="";
                    }else if (data=="0"){
                        alertify.error("Error!. Favor contactar con el Administrador del Sistema.");
                    }else if (data=="2"){
                         alertify.error("Codigo de Barra duplicado, ya existe en algunas de las tiendas.");
                    }     
                      
                      
                      $scope.listarCodigoBarra($scope.idProdSel);
          
      }).error(function(error){
            console.log(error);

    });
            
            
        }
    });     
      
      
    }else{
        
         alertify.custom("Ingresar codigo de barra para agregar a la lista.");
    }
      
      
      
};      
        
        
        
        
        
        
             
        
        
        
        
        

$scope.porcentajeProdOfertas = function(index){
   
    if(index.porcentaje != ""){
         $scope.selPorcentaje =index.porcentaje;
    }else{
          $scope.selPorcentaje ="";
    }
    
 // $scope.ofertaPorcentaje ="";
  $scope.nombreProd = index.nombreProd;
  $scope.prodPrecio = index.precioMasc;
  $scope.codProd    = index.id;
  $scope.porcentaje = index.porcentaje;

    $scope.listarPorcentajeProd();
    
};


        
        
$scope.selPordPorcentaje = function (indexPor){
    if(indexPor.id == 0){
         $scope.selPorcentaje = "";
       }else{
         $scope.selPorcentaje = indexPor.porcentaje;   
       }
};        
        
        
 $scope.guardarPorcentaje = function(){       
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=updatePorcentaje&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idProd:$scope.codProd, 
                                        idPorcentaje:$scope.ofertaPorcentaje.selectedOption.id}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);

                 if(respuesta=="1"){                
                        alertify.success("Porcetaje modificado con exito!");
                    }else{
                        alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");
                    }
        
          }).error(function(error){
                        console.log(error);
    
    });        
        
};
            
        
        
 $scope.agregarProductoNuevo =  function(){
      var marcaProd           = ($scope.auxMarca2  == undefined ? "": $scope.auxMarca2.codMarca);
      var categoriaProd       = ($scope.auxTipo2    == undefined ? "": $scope.auxTipo2.codCategoria);
      var clasProd            = ($scope.auxClasificacion   == undefined ? "": $scope.auxClasificacion.id);
      var idProveed           = ($scope.auxProveedor   == undefined ? "": $scope.auxProveedor.id_proveedor);


  $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=insertarProductoNuevo&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({codProd:$scope.codProd, 
                                        nombProd:$scope.nombProd, 
                                        pesProd:$scope.pesProd, 
                                        bodProd:$scope.bodProd,
                                        bodega:$scope.bodProd,
                                        pesosProd:$scope.pesProd,
                                        marcProd:marcaProd,
                                        catProd:categoriaProd,
                                        claProd:clasProd,
                                        foto:$scope.fotos,
                                       idProveedor:idProveed}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);     
      
        var respuesta = data.charAt(data.length-1);

                 if(respuesta=="1"){                
                        alertify.success("Porcetaje modificado con exito!");
                        $scope.codProd  = "";
                        $scope.nombProd = "";
                        $scope.bodProd  = "";
                        $scope.pesProd  = "";
                        $scope.init();
                    }else{
                        alertify.error("Error al modificar precio, favor contactar con el Administrador del Sistema.");
                    }
        
        
          }).error(function(error){
                        console.log(error);
    
    });
 };
   
 
        
 $scope.selTipoBusq = function (rdo) {   
 
   if(rdo=="1"){   //Tipo y Marca
         $scope.busqueda1 = true;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
                        $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="1";
    }else if(rdo=="2" ){
         $scope.busqueda1 = false;     
         $scope.busqueda2 = true;
         $scope.busqueda3 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="2";
    }else if(rdo=="3"){
       $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = true;
                 $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="3";
    }else if(rdo=="4"){
   

         
    }else if(rdo=="5"){
   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda5 = true;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="5";
         
    }else if(rdo=="6"){
   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda5 = false;

         $scope.busqueda6 = true;

         $scope.tipoBusqueda ="6";
         
    }
     
     
 
};           
        
   
        
$scope.listarClasProd = function(){
    $http.post('FunctionIntranet.php?act=listarClasificacionProd&tienda='+$scope.tiendaSeleccionada).success(function(data) {
           console.log(data);  
               $scope.clasificacionProd = [];
               $scope.clasificacionProd = data;
        
            }).
            error(function(data, status, headers, config) {
                console.log("error listar proveedores: "+data);
      });
};       
        
        
        
        
 /*******************LECTOR CODIGO BARRA************************/  
 $(document).ready(function() {
    var App = {
        init: function(){
            let config = {
                inputStream: {
                    type : "LiveStream",
                    constraints: {
                        width: {min: 640},
                        height: {min: 480},
                        facingMode: "environment",
                        aspectRatio: {min: 1, max: 2}
                    }
                },
                locator: {
                    patchSize: "large",
                    halfSample: false
                },
                numOfWorkers: 2,
                frequency: 10,
                decoder: {
                    readers: [
                        {
                            format: "code_128_reader",
                            config: {}
                        },
                        {
                            format: "ean_reader",
                            config: {}
                        }
                    ],
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true,
                        boxFromPatches: {
                            showTransformed: true,
                            showTransformedBox: true,
                            showBB: true
                        }
                    }
                },
                locate: true
            };
            
            let lastResult = null;
            
            Quagga.init(config, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                App.attachListeners();
                setTimeout(()=>{ Quagga.start(); alert("Listo para escanear.");}, 1000);
            });
        },
        
        attachListeners: function() {
            var self = this;
            
            $('#barcodeModal').on('hidden.bs.modal', function (e) {
              Quagga.stop();
              App.lastResult = null;
            });
        }
    };
    
    $('#barcodeModal').on('shown.bs.modal', function (e) {
        App.init();
      
        Quagga.onProcessed(function(result) {
            var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;
            
            if (result) {
                if (result.boxes) {
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function (box) {
                        return box !== result.box;
                    }).forEach(function (box) {
                        Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                    });
                }
    
                if (result.box) {
                    Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                }
    
                if (result.codeResult && result.codeResult.code) {
                    Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                }
            }
        });
    
        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
    
            if (App.lastResult !== code) {
                App.lastResult = code;
                document.getElementById("codProducto").value = code;
                $scope.codBarra = code;
                $("#barcodeModal").modal("hide");
               
            }
        });
    });
});
        
///*************************************************************/       
        
 $scope.mostrarImagen = function(prodData){     
         $("#modal-image").attr("src", '');
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=mostrarImagen&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idProd:prodData.id}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          $("#modal-image").attr("src", data[0].foto);
              
          }).error(function(error){
                        console.log(error);
    
    });        
        
};
        
$scope.confirmarActiveProd = function(prodDet, value){    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=updateEstadoProductoAct&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd: prodDet.id,
                     activo:value}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
   var respuesta = data.charAt(data.length-1);

                 if(respuesta=="1"){                
                        alertify.success("Estado modificado con exito!");
                               }else{
                        alertify.error("Error al modificar estado, favor contactar con el Administrador del Sistema.");
                    }
		
  }).error(function(error){
        console.log(error);

   });
    
};
        
        
$scope.confirmarActiveTiendaProd = function(prodDet, value){    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=updateEstadoProductoActTienda&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd: prodDet.id,
                     activo:value}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      
   var respuesta = data.charAt(data.length-1);

                 if(respuesta=="1"){                
                        alertify.success("Estado modificado con exito!");
                               }else{
                        alertify.error("Error al modificar estado, favor contactar con el Administrador del Sistema.");
                    }
		
  }).error(function(error){
        console.log(error);

   });
    
};




$scope.listarProdGrup = function (objProd) {  
    $scope.cantidadProfGrup="";
     for(var i = 0; i < $scope.listProd.length; i++){
         if($scope.listProd[i].id  == objProd.nodo_prod){
             $scope.grupo_prod          = $scope.listProd[i].grupo_prod;
             
             $scope.idProd              = objProd.id;
             
             $scope.idProdDesc          = $scope.listProd[i].id;
             $scope.nombreProd          = $scope.listProd[i].nombreProd;
             $scope.stockProd           = $scope.listProd[i].stockTiendas[0].stock;//$scope.listProd[i].stockProd - $scope.listProd[i].salidasProd;
             $scope.nombreProdPrincipal = objProd.nombreProd;
             break;
         }         
     }    
     $("#myProducoAgrupar").modal();  
}

$scope.validarStockProd = function(){
    var arrayProd    = [];

    var objDeta = new Object();
             objDeta.id           = $scope.idProdDesc;
             objDeta.cantidadProd = $scope.cantidadProfGrup;
             arrayProd.push(objDeta);     
        
     var arrProd = JSON.stringify(arrayProd);
    
              
                $http({
                         method : 'POST',
                         url : 'FunctionIntranet.php?act=consultarProdEnStock&tienda='+$scope.tiendaSeleccionada,
                         data:  $.param({arrProd:arrProd}),
                                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
                      }).success(function(data){
                            console.log(data);               
                    var respuesta = data.charAt(data.length-1);
                    
                    if(respuesta=="1"){
                        $scope.confirmarPedidoDesagrupar();
                                      
                     }else{
                         alertify.alert("El producto se encuentra sin Stock:"+data+ ", favor contactar con el administrador.");
                     }

                    }).error(function(error){
                                        console.log(error);

                    });  
};        
        
        
        
$scope.litModificarProducto = function(){
    
     document.getElementById('divPedido').style.display  = 'none';    
     document.getElementById('divPedidoModificar').style.display  = 'none';    
   
      var nombre    = angular.element(document.querySelector("#apellidoAlumno")).val();
      var id        = angular.element(document.querySelector("#idAlumno")).val();
      var rut       = angular.element(document.querySelector("#rutAlumno")).val();
    
    
      /* $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarAlumnos&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({tipBusq: $scope.tipoBusqueda,
                             id:   id,
                             apellido:  nombre,
                             rut: rut                    
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               

                    $scope.listSolicitud = data;

          }).error(function(error){
                console.log(error);


        });  */
    
    
    
};        
 

        
$scope.insertarProductoGrupo = function(){    
  $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=insertarGrupoProd&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd:    $scope.idProd,
                     cantIng:   parseInt($scope.cantidadProfGrup*$scope.grupo_prod),
                     cantDesc:  $scope.cantidadProfGrup,
                     idProdDes: $scope.idProdDesc}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);      

                 if(data=="true"){                
                        alertify.success("Producto desagrupado con exito!");
                     $("#myProducoAgrupar").modal("hide");
                               }else{
                        alertify.error("Error al desagrupar, favor contactar con el Administrador del Sistema.");
                    }
		
  }).error(function(error){
        console.log(error);

   });
    
};        
           

$scope.confirmarPedidoDesagrupar = function (){ 
    alertify.confirm("Esta seguro que desea desagrupar producto?", function (e) {
        if (e) {            
            $scope.insertarProductoGrupo();         
        }
    });        
};        
        
        
}])
.directive('fileModel', function ($parse, $timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            element.bind('change', function () {
                scope.$apply(function () {
                    var file = element[0].files[0];
                    var reader = new FileReader();
                    reader.onload = function (readerEvt) {
                        $timeout(function () {
                            var binaryString = readerEvt.target.result;
                            modelSetter(scope, btoa(binaryString));
                        });
                    };
                    reader.readAsBinaryString(file);
                });
            });
        }
    };
});