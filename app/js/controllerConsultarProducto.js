angularRoutingApp.controller('controllerConsultarProducto', ['$scope', '$http','$cookies','$cookieStore', '$log',
    function ($scope, $http, $cookies, $cookieStore, $log) {
        
              $scope.dgProductos= [];
              $scope.customCookies           = false; 

              $scope.producto   = [];
              $scope.idProducto ="";                 

              $scope.nombreProd = "";
              $scope.idProd     = "";
              $scope.ingresos   = 0;
              $scope.salidas    = 0;
              $scope.codBodega  = 0;
        
              $scope.baseInv     = "";
              $scope.filaInv     = "";
              $scope.unidInv     = "";
        
          //   $scope.arrayDetaInv = [];
    
        
   
        
  $scope.eliminarCookieSesion = function() {
       $cookieStore.remove('detalleProductoCookiesInvt');
       $scope.customCookies        = false;
  }
  
  
  $scope.mostrarCookieSesion = function() {
        var getmycookiesback = $cookieStore.get('detalleProductoCookiesInvt');        
        $log.info(getmycookiesback); 
        $scope.dgProductos=getmycookiesback;
        $scope.customCookies        = false;
       // $cookieStore.remove('detalleProductoCookiesInvt');
        
  }
          
        
$scope.init = function () {
    
    
if($cookieStore.get('detalleProductoCookiesInvt') != null){      
     $scope.customCookies        = true;
}
    
    
     $scope.salidas         = 0;
     $scope.ingresos        = 0;        
     $scope.tipoBusqueda    = "barra";    
    
if( $scope.customInterEntrar != true){
    $scope.customInterLogin=true;
 }    
    $scope.bodegas = {
      availableOptions: [
        {id: '1', name: '1'},
        {id: '2', name: '2'},
        {id: '3', name: '3'},
        {id: '4', name: '4'},
        {id: '5', name: '5'}            

      ],
      selectedOption: {id: $scope.codBodega} //This sets the default value of the select in the ui
    } 

    $('#codProducto').focus();




    
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
    
$scope.fechaActual = daym+"-"+month+"-"+year;
$scope.cad         = mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
};




$scope.buscarProd = function(){
var bandera="false";
var length = $scope.dgProductos.length;
var objPro = [];
  

  
 
if( $scope.idProducto !=""){
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=listarProductosInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idProd:$scope.idProducto,
                     tipBusq:$scope.tipoBusqueda}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.producto = data;
        
         if(data.length != 0){
        
               $scope.nombreProd = data[0].nombreProd;
               $scope.idProd     = data[0].id;
               $scope.ingresos   = data[0].stockProd;             
               $scope.salidas    = data[0].salidasProd;
               $scope.cod_barra  = data[0].cod_barra;

               $scope.cantProdConObs     = data[0].cantProdConObs;
               $scope.cantProdPendiente  = data[0].cantProdPendiente;
               $scope.cantProdEnDesp     = data[0].cantProdEnDesp;
               $scope.cantProdEnBodega   = data[0].cantProdEnBodega;
               $scope.cantProdRechazado  = data[0].cantProdRechazado;

               $scope.precioVenta  = data[0].precioVenta;
               $scope.precioPart   = data[0].precioPart;
  
              $scope.nombreCategoria   = data[0].nombreCategoria;
              $scope.nombreMarca   = data[0].nombreMarca;

             
             
             for(var i = 0; i < length; i++) {
                 
                  objPro = $scope.dgProductos;
                    if(objPro[i].idProd ==    $scope.idProd ){
                        bandera = "true";
                    }             
                }
             
                if(bandera == "true"){    
                    alertify.alert("El producto ya existe en la lista."); 
                    
                }else{
                       $("#myModalCliente").modal();
                }        


             
         }else{
             alertify.alert("El Producto no existe."); 

         }
          $scope.loadingProd = false;
                            setTimeout($.unblockUI, 1000); 

  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});
 
 }else{
     alertify.alert("Favor ingresar codigo del producto para realizar la busqueda."); 

 }
};





$scope.agregarSelProductos = function () {  
    

var objAddProd = new Object(); 

var cantidadInvetario = parseInt(($scope.baseInv * $scope.filaInv) + $scope.unidInv) ; 
var stockInv          = parseInt($scope.ingresos - $scope.salidas);
    
var stockInventario  =  parseInt(isNaN(stockInv) ? 0 : stockInv);
    
    
  
         if($scope.idProd==""){
            $('#codProducto').focus();
            alertify.alert("Favor realizar una busqueda para agregar a la lista."); 
            return 0 ;                    
        }else if(cantidadInvetario==0 || cantidadInvetario=="null"){
            $('#codProducto').focus();
            alertify.alert("Favor ingresar cantidad para agregar a la lista."); 
            return 0 ;                    

        }

       objAddProd.realStock      = parseInt(stockInventario  -  cantidadInvetario) ;
       objAddProd.idProd         = $scope.idProd;
       objAddProd.nombProd       = $scope.nombreProd;
       objAddProd.cantIng        = cantidadInvetario;

    
    
    
    
    
    
      if(objAddProd.realStock < 0){
           objAddProd.estadoInventario = "Ingresar";
           objAddProd.realStock      = parseInt(cantidadInvetario - stockInventario)  ;
      }else if(objAddProd.realStock == 0 ){
            objAddProd.estadoInventario = "Cuadrado"; 
      }else if(objAddProd.realStock > 0 ){
            objAddProd.estadoInventario = "Salida"; 
      }


       $scope.dgProductos.push(objAddProd);
      
       $cookieStore.put('detalleProductoCookiesInvt',  $scope.dgProductos);

    
    
       $scope.nombreProd ="";
       $scope.idProd     ="";
       $scope.ingresos   = 0;
       $scope.salidas    = 0;
       $scope.realProd   ="";
       $scope.idProducto ="";               
       $scope.baseInv     = "";
       $scope.filaInv     = "";
       $scope.unidInv     = "";

       $('#codProducto').focus();

};




$scope.eliminarProduto = function (productIndex) {     
    $scope.dgProductos.splice(productIndex, 1);         
    $('#codProducto').focus();
    $cookieStore.put('detalleProductoCookiesInvt',  $scope.dgProductos);
}


$scope.ingresarInventario = function (){
    var arrSalida  = [];
    var arrIngreso = [];

   for (var x=0; x < $scope.dgProductos.length; x++){
        var objProd = new Object();
        
            objProd.idProd   = $scope.dgProductos[x].idProd;
            objProd.cantidad = $scope.dgProductos[x].realStock;
            objProd.codBodega= $scope.bodegas.selectedOption.id;
        
        if($scope.dgProductos[x].estadoInventario=="Ingresar"){
            arrIngreso.push(objProd);
        }else if($scope.dgProductos[x].estadoInventario=="Salida"){
            arrSalida.push(objProd);
        }        
    }
    
    var arrSal    = JSON.stringify(arrSalida);
    var arrIng    = JSON.stringify(arrIngreso);

    
    $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=ingresarInventario&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({arrSal:arrSal,
                     arrIng:arrIng}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
                  var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    $('#codProducto').focus();
                  //  $scope.dgProductos    = []; 
                    alertify.success("Inventario generado con exito!");
                    $cookieStore.remove('detalleProductoCookiesInvt');

                }else{
                   $('#codProducto').focus();
                    alertify.error("Error al ingresar inventario, favor contactar con el Administrador del Sistema.");
                }
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});

};


$scope.confirmarInvetario = function () { 

if($scope.dgProductos.length!=0){    
    if($scope.bodegas.selectedOption.id != 0){        
        alertify.confirm("¿Esta seguro que desea generar inventario?", function (e) {
        if (e) {
            $scope.ingresarInventario();
        }
    });    

        }else{      
                alertify.alert("Favor seleccionar numero bodega para ingresar inventario.");
        }
}else{
    
    alertify.alert("Para ingresar inventario favor agregar producto a la lista.");

}
};


$(function(){
  $('.lectorCodigoBarra').keypress(function(e){
    if(e.which == 13) {
      $scope.buscarProd();
    }
  })
});
 
        
$scope.selTipoBusqueda = function (rdo) { 
      $scope.tipBusqueda = rdo;
      $('#codProducto').focus();
};        
        
        
$scope.salir = function () { 
     $scope.nombreProd ="";
       $scope.idProd     ="";
       $scope.ingresos   = 0;
       $scope.salidas    = 0;
       $scope.realProd   ="";
       $scope.idProducto ="";               
       $scope.baseInv     = "";
       $scope.filaInv     = "";
       $scope.unidInv     = "";

       $('#codProducto').focus();
};           
        
        

                                         
$scope.generarReporteInventario = function () {
    

  var arrayDeta = [];
  var count=0;    

   for (var i = 0; i <    $scope.dgProductos.length; i++) {   
     count = count + 1   ;
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.idProd             =  $scope.dgProductos[i].idProd;
            objDeta.nombProd           =  $scope.dgProductos[i].nombProd;
            objDeta.cantIng            =  $scope.dgProductos[i].cantIng;

            objDeta.diferencia         =  $scope.dgProductos[i].realStock;
       
      
            objDeta.fecha           =  $scope.fechaActual +" - "+ $scope.cad;
            objDeta.counta          =  count;

            if($scope.dgProductos[i].estadoInventario == "Ingresar"){
                
                 objDeta.urlImg     = 'img/flechaArriba-min.png';

            }else if($scope.dgProductos[i].estadoInventario == "Cuadrado" ){
                
                 objDeta.urlImg     = 'img/correcto-min.png';

            }else if($scope.dgProductos[i].estadoInventario == "Salida" ){
                
                 objDeta.urlImg     = 'img/flechaAbajo-min.png';

            }
            
       
    arrayDeta.push(objDeta);       
   }
    

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'productos':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarInventarioPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="inventario_"+$scope.fechaActual+"_"+$scope.cad+".pdf";          
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
        
        
        
$scope.generarReporteInventarioIng = function () {
    var idProd       = "";
    var arrayDetaInv = [];
    var count        = 0;
    
    for (var i = 0; i <    $scope.dgProductos.length; i++) {   
       idProd += ','+$scope.dgProductos[i].idProd;
    }  
    
 $http({
     method : 'POST',
     url : 'FunctionIntranet.php?act=consultarInventarioIng&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({id_prod:idProd.substr(1)}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
             
         
  var prodConsulIng = data;
     
        
 for (var i = 0; i <    $scope.dgProductos.length; i++) {   
  count = count + 1   ;
   var objDeta= new Object();           
            objDeta.idProd             =  $scope.dgProductos[i].idProd;
            objDeta.nombProd           =  $scope.dgProductos[i].nombProd;         
             for(var x = 0 ; x < prodConsulIng.length ; x ++ ){
                 if(objDeta.idProd  == prodConsulIng[x].id){                     
                        objDeta.stock  = prodConsulIng[x].stockProd;
                     break;
                   }                  
             }         
            objDeta.fecha           =  $scope.fechaActual +" - "+ $scope.cad;
            objDeta.counta          =  count;
    arrayDetaInv.push(objDeta);       
   }     
   $scope.generarInventarioIngPDF(arrayDetaInv);  
     
  }).error(function(error){
        console.log(error);
        $scope.loadingProd = false;

});     
};

        
        

        
$scope.generarInventarioIngPDF = function(arrayDetaInv){    
    
var jsonData=angular.toJson(arrayDetaInv);
var objectToSerialize={'detalle':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarInventarioValidarPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="inventario_ingresos"+$scope.fechaActual+"_"+$scope.cad+".pdf";          
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
        
        
        
  
        
  /*******************LECTOR CODIGO BARRA*************************/      
 /*$(document).ready(function() {
     
       
     
        $("#lector").on("change", function(e) {
            if (e.target.files && e.target.files.length) {
                    
                
            $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
                
                decode(URL.createObjectURL(e.target.files[0]));
            }
        });
        
        Quagga.onDetected(function(result) {
            
            //alert(result[0]);
            
           // console.log(result);
            let code = result[0].codeResult.code;
    
            document.getElementById("codProducto").value = code;
            $scope.idProducto = code;
            $scope.buscarProd();
           

            $("#lector").val('');
        });
    });
    
    function decode(src){
        let config = {
            inputStream: {
                size: 800,
                singleChannel: false
            },
            locator: {
                patchSize: "large",
                halfSample: false
            },
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
                multiple: true
            },
            locate: true,
            src: src
        };
        
        Quagga.decodeSingle(config, function(result) {
            if(result == undefined){
                                            setTimeout($.unblockUI, 1000); 
                
                alertify.alert("ERROR al leer código de barra, para tener un mejor resultado, centrar codigo en la camara, luego focalizar lo mas claro posible.");
                
                              


               }
             console.log(result);
        });
    }       
        */
///*************************************************************/
        
        
  /*******************LECTOR CODIGO BARRA************************/  
 /*$(document).ready(function() {
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
                $("#barcodeModal").modal("hide");
                document.getElementById("codProducto").value = code;
                $scope.idProducto = code;
                $scope.buscarProd();
               
            }
        });
    });
});*/
        
///*************************************************************/   
        $(function() {
    var resultCollector = Quagga.ResultCollector.create({
        capture: true,
        capacity: 20,
        blacklist: [{
            code: "WIWV8ETQZ1", format: "code_93"
        }, {
            code: "EH3C-%GU23RK3", format: "code_93"
        }, {
            code: "O308SIHQOXN5SA/PJ", format: "code_93"
        }, {
            code: "DG7Q$TV8JQ/EN", format: "code_93"
        }, {
            code: "VOFD1DB5A.1F6QU", format: "code_93"
        }, {
            code: "4SO64P4X8 U4YUU1T-", format: "code_93"
        }],
        filter: function(codeResult) {
            // only store results which match this constraint
            // e.g.: codeResult
            return true;
        }
    });
    var App = {
        init: function() {
            var self = this;

            Quagga.init(this.state, function(err) {
                if (err) {
                    return self.handleError(err);
                }
                //Quagga.registerResultCollector(resultCollector);
                App.attachListeners();
                App.checkCapabilities();
                Quagga.start();
                setTimeout(()=>{ Quagga.start(); alert("Listo para escanear v2.");}, 1000);
            });
        },
        handleError: function(err) {
            console.log(err);
        },
        checkCapabilities: function() {
            var track = Quagga.CameraAccess.getActiveTrack();
            var capabilities = {};
            if (typeof track.getCapabilities === 'function') {
                capabilities = track.getCapabilities();
            }
            this.applySettingsVisibility('zoom', capabilities.zoom);
            this.applySettingsVisibility('torch', capabilities.torch);
        },
        updateOptionsForMediaRange: function(node, range) {
            console.log('updateOptionsForMediaRange', node, range);
            var NUM_STEPS = 6;
            var stepSize = (range.max - range.min) / NUM_STEPS;
            var option;
            var value;
            while (node.firstChild) {
                node.removeChild(node.firstChild);
            }
            for (var i = 0; i <= NUM_STEPS; i++) {
                value = range.min + (stepSize * i);
                option = document.createElement('option');
                option.value = value;
                option.innerHTML = value;
                node.appendChild(option);
            }
        },
        applySettingsVisibility: function(setting, capability) {
            // depending on type of capability
            if (typeof capability === 'boolean') {
                var node = document.querySelector('input[name="settings_' + setting + '"]');
                if (node) {
                    node.parentNode.style.display = capability ? 'block' : 'none';
                }
                return;
            }
            if (window.MediaSettingsRange && capability instanceof window.MediaSettingsRange) {
                var node = document.querySelector('select[name="settings_' + setting + '"]');
                if (node) {
                    this.updateOptionsForMediaRange(node, capability);
                    node.parentNode.style.display = 'block';
                }
                return;
            }
        },
        initCameraSelection: function(){
            var streamLabel = Quagga.CameraAccess.getActiveStreamLabel();

            return Quagga.CameraAccess.enumerateVideoDevices()
            .then(function(devices) {
                function pruneText(text) {
                    return text.length > 30 ? text.substr(0, 30) : text;
                }
                var $deviceSelection = document.getElementById("deviceSelection");
                while ($deviceSelection.firstChild) {
                    $deviceSelection.removeChild($deviceSelection.firstChild);
                }
                devices.forEach(function(device) {
                    var $option = document.createElement("option");
                    $option.value = device.deviceId || device.id;
                    $option.appendChild(document.createTextNode(pruneText(device.label || device.deviceId || device.id)));
                    $option.selected = streamLabel === device.label;
                    $deviceSelection.appendChild($option);
                });
            });
        },
        attachListeners: function() {
            var self = this;

            self.initCameraSelection();
            $(".controls").on("click", "button.stop", function(e) {
                e.preventDefault();
                Quagga.stop();
                self._printCollectedResults();
            });

            $(".controls .reader-config-group").on("change", "input, select", function(e) {
                e.preventDefault();
                var $target = $(e.target),
                    value = $target.attr("type") === "checkbox" ? $target.prop("checked") : $target.val(),
                    name = $target.attr("name"),
                    state = self._convertNameToState(name);

                console.log("Value of "+ state + " changed to " + value);
                self.setState(state, value);
            });
        },
        _printCollectedResults: function() {
            var results = resultCollector.getResults(),
                $ul = $("#result_strip ul.collector");

            results.forEach(function(result) {
                var $li = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');

                $li.find("img").attr("src", result.frame);
                $li.find("h4.code").html(result.codeResult.code + " (" + result.codeResult.format + ")");
                $ul.prepend($li);
            });
        },
        _accessByPath: function(obj, path, val) {
            var parts = path.split('.'),
                depth = parts.length,
                setter = (typeof val !== "undefined") ? true : false;

            return parts.reduce(function(o, key, i) {
                if (setter && (i + 1) === depth) {
                    if (typeof o[key] === "object" && typeof val === "object") {
                        Object.assign(o[key], val);
                    } else {
                        o[key] = val;
                    }
                }
                return key in o ? o[key] : {};
            }, obj);
        },
        _convertNameToState: function(name) {
            return name.replace("_", ".").split("-").reduce(function(result, value) {
                return result + value.charAt(0).toUpperCase() + value.substring(1);
            });
        },
        detachListeners: function() {
            $(".controls").off("click", "button.stop");
            $(".controls .reader-config-group").off("change", "input, select");
        },
        applySetting: function(setting, value) {
            var track = Quagga.CameraAccess.getActiveTrack();
            if (track && typeof track.getCapabilities === 'function') {
                switch (setting) {
                case 'zoom':
                    return track.applyConstraints({advanced: [{zoom: parseFloat(value)}]});
                case 'torch':
                    return track.applyConstraints({advanced: [{torch: !!value}]});
                }
            }
        },
        setState: function(path, value) {
            var self = this;

            if (typeof self._accessByPath(self.inputMapper, path) === "function") {
                value = self._accessByPath(self.inputMapper, path)(value);
            }

            if (path.startsWith('settings.')) {
                var setting = path.substring(9);
                return self.applySetting(setting, value);
            }
            self._accessByPath(self.state, path, value);

            console.log(JSON.stringify(self.state));
            App.detachListeners();
            Quagga.stop();
            App.init();
        },
        inputMapper: {
            inputStream: {
                constraints: function(value){
                    if (/^(\d+)x(\d+)$/.test(value)) {
                        var values = value.split('x');
                        return {
                            width: {min: parseInt(values[0])},
                            height: {min: parseInt(values[1])}
                        };
                    }
                    return {
                        deviceId: value
                    };
                }
            },
            numOfWorkers: function(value) {
                return parseInt(value);
            },
            decoder: {
                readers: function(value) {
                    if (value === 'ean_extended') {
                        return [{
                            format: "ean_reader",
                            config: {
                                supplements: [
                                    'ean_5_reader', 'ean_2_reader'
                                ]
                            }
                        }];
                    }
                    return [{
                        format: value + "_reader",
                        config: {}
                    }];
                }
            }
        },
        state: {
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
                patchSize: "medium",
                halfSample: true
            },
            numOfWorkers: 2,
            frequency: 10,
            decoder: {
                readers :[{
                    format: "code_128_reader",
                    config: {}
                }]
            },
            locate: true
        },
        lastResult : null
    };

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
            var $node = null, canvas = Quagga.canvas.dom.image;

            $node = $('<li><div class="thumbnail"><div class="imgWrapper"><img /></div><div class="caption"><h4 class="code"></h4></div></div></li>');
            $node.find("img").attr("src", canvas.toDataURL());
            $node.find("h4.code").html(code);
            $("#result_strip ul.thumbnails").prepend($node);
        }
           /* var code = result.codeResult.code;
    
            if (App.lastResult !== code) {
                App.lastResult = code;
                $("#barcodeModal").modal("hide");
                document.getElementById("codProducto").value = code;
                $scope.idProducto = code;
                $scope.buscarProd();
               
            }*/
    });
  

});

}]);