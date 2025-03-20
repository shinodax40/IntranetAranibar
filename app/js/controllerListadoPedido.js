'use strict';
angularRoutingApp.controller('controllerListadoPedido', ['$scope', '$http', 'Table', 'tiendaOrdenService', 'tiendaDetalleService', 'descuentoService', 'resumenService', 'Util',
    function ($scope, $http, Table, tiendaOrdenService, tiendaDetalleService, descuentoService, resumenService, Util) {
        $scope.mostrarPedidosWeb = false;
        $scope.tableOrden = angular.copy(Table);
        $scope.orden = {};
        $scope.formOrdenPedido = {
            currentStep: 1,
            despacho: null,
            jornada: null,
            entrega: null,
            rutCliente: null,
            razon: null,
            ocupaPuntos: false,
            puntos: 0,
            contacto: null,
            direccion: null,
            detalleDireccion: null,
            pagoEn: null,
            observacion: null
        };
        $scope.tipoDePago = {
            availableOptions: [
                {id: 0, name: '--SELECCIONAR MODO DE PAGO--'},
                {id: 1, name: 'EFECTIVO'},
                {id: 5, name: 'DEBITO'}
            ],
        };

        $scope.listarSectores = function () {
            var arrayDeta = [];
            var objDeta = new Object();

            $http.get('FunctionIntranet.php?act=listarSector&tienda=' + $scope.tiendaSeleccionada).success(function (data) {
                console.log(data);

                $scope.listSectores = data;
                objDeta = new Object();
                objDeta.name = "--Seleccionar Sector--";
                objDeta.id = 0;
                arrayDeta.push(objDeta);


                for (var i = 0; i < data.length; i++) {
                    objDeta = new Object();
                    objDeta.name = data[i].name;
                    objDeta.id = data[i].id;
                    arrayDeta.push(objDeta);
                }


                $scope.sectoresLists = {
                    availableOptions: arrayDeta
                }

            }).error(function (data, status, headers, config) {
                console.log("error al listar sectores: " + data);
            });

        };

        $scope.listarOrdenesTienda = function () {
            if($scope.tipoIdLogin == '7' || $scope.tipoIdLogin == '1' || $scope.tipoIdLogin == '9' || $scope.idUsuarioLogin == '220') {
                $scope.mostrarPedidosWeb = true;
                tiendaOrdenService.toList().then(function (response) {
                    if (response.code === 0) {
                        angular.forEach(response.data, function (value) {
                            value.fecha = new Date(value.fecha);
                            value.fechaFormateada = Util.formatDate(value.fecha, 'dd-MM-yyyy');
                        });
                        $scope.tableOrden.init(response.data);
                    }
                });
            }
        };

        $scope.selectPedidoPagina = function (item) {
            if (item.idPedido > 0) {
                $http({
                    method: 'POST',
                    url: 'FunctionIntranet.php?act=listarPedidosGeneral&tienda=' + $scope.tiendaSeleccionada,
                    data: $.param({
                        idPedido: item.idPedido,
                        tipBusq: 6,
                        idTransp:$scope.idTransporte,
                        tipoUsuario:$scope.tipoUsuarioLogin,
                        idUsuario:$scope.idUsuarioLogin
                    }),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    if (data.length == 1) {
                        if($scope.tipoUsuarioLogin == 'Transportes'){
                            //$scope.listPedidosTransp = data;
                        }else{
                            $scope.cargarPedido(data[0], 'V');
                            $('#myModalVerPedido').modal('show');
                        }
                    }
                }).error(function (error) {
                    console.log(error);
                });
            } else {
                $scope.listarSectores();
                $scope.orden = {};
                tiendaOrdenService.obtainTo(item.id).then(function (response) {
                    if (response.code === 0) {
                        $scope.orden = response.data;
                        $scope.formOrdenPedido = {
                            id: item.id,
                            currentStep: 1,
                            descuentos: [],
                            descuento: 0,
                            resumenes: [],
                            despacho: null,
                            jornada: null,
                            entrega: null,
                            ocupaPuntos: false,
                            puntos: 0,
                            rutCliente: ($scope.orden.rutCliente ? $.Rut.formatear($scope.orden.rutCliente, '1') : null),
                            razon: $scope.orden.nombre,
                            contacto: $scope.orden.celular,
                            direccion: $scope.orden.calle + " " + $scope.orden.numero,
                            detalleDireccion: null,
                            detalles: [],
                            sector: {id: 0},
                            pagoEn: {id: 0},
                            observacion: null
                        };
                        if ($scope.orden.rutCliente) {
                            $('#rutInput').Rut({
                                on_error: function(){ alertify.alert('Rut incorrecto.'); },
                                format_on: 'keyup'
                            });
                            $scope.obtenerPuntos();
                        }
                        var idsProducto = [];
                        angular.forEach($scope.orden.detalles, function (value) {
                            $scope.formOrdenPedido.detalles.push({
                                id: value.id,
                                idProducto: value.idProducto,
                                nombreProducto: value.nombreProducto,
                                precio: value.precio,
                                cantidad: value.cantidad,
                                descuento: value.descuento,
                                precioActual: value.precioActual,
                                precioDistinto: value.precioDistinto,
                                stock: value.stock,
                                superaStock: value.superaStock,
                                idPrecioFactura: 0
                            });
                            idsProducto.push(value.idProducto);
                        });
                        if (idsProducto.length > 0) {
                            descuentoService.toList({activo: true})
                                .then(function (response2) {
                                    if (response2.code === 0) {
                                        angular.forEach(response2.data, function (descuento) {
                                            if (descuento.tipo === 'GLOBAL') {
                                                $scope.formOrdenPedido.descuentos.push(descuento);
                                            } else {
                                                angular.forEach($scope.formOrdenPedido.detalles, function (value) {
                                                    if (descuento.idProducto === value.idProducto) {
                                                        if (angular.isUndefined(value.descuentos)) {
                                                            value.descuentos = [];
                                                        }
                                                        value.descuentos.push(descuento);
                                                    }
                                                });
                                            }
                                        });

                                        if (item.idCliente != null) {
                                            resumenService.toListProducto({idCliente: item.idCliente}).then(function (response3) {
                                                if (response3.code === 0) {
                                                    angular.forEach(response3.data, function (resumen) {
                                                        angular.forEach($scope.formOrdenPedido.detalles, function (value) {
                                                            if (resumen.idProducto === value.idProducto) {
                                                                value.resumenes = resumen;
                                                            }
                                                        });
                                                    });
                                                }
                                            });
                                            resumenService.toListCliente({idCliente: item.idCliente}).then(function (response3) {
                                                if (response3.code === 0) {
                                                    $scope.formOrdenPedido.resumenes = response3.data[0];
                                                }
                                            });
                                        }
                                    }
                                });
                        }
                        $('#procesarPedidoWeb').modal('show');
                    }
                });

            }
        };

        $scope.obtenerPuntos = function () {
            if ($.Rut.validar($scope.formOrdenPedido.rutCliente)) {
                $http({
                    method: 'GET',
                    url: 'FunctionIntranet.php?act=consultarPuntos&tienda=' + $scope.tiendaSeleccionada + '&rut=' + $scope.formOrdenPedido.rutCliente
                }).success(function (response) {
                    $scope.puntos = response.data;
                }).error(function (error) {
                    console.log(error);
                });
            }
        };

        $scope.registrarPuntos = function (idPedido) {
            $http({
                method: 'POST',
                url: 'FunctionIntranet.php?act=registrarPuntos&tienda=' + $scope.tiendaSeleccionada,
                data: {
                    rut: $scope.formOrdenPedido.rutCliente,
                    valor: $scope.formOrdenPedido.puntos,
                    ocupaPuntos: $scope.formOrdenPedido.ocupaPuntos,
                    total: $scope.getTotalPedidoWeb(),
                    idPedido: idPedido
                }
            }).success(function (response) {

            }).error(function (error) {
                console.log(error);
            });
        };

        $scope.saveItemPedidoPagina = function (item) {
            tiendaDetalleService.replaceTo(item.id, item).then(function (response) {
                if (response.code === 0) {
                    $scope.selectPedidoPagina($scope.formOrdenPedido);
                }
            });
        };

        $scope.deleteItemPedidoPagina = function (item) {
            tiendaDetalleService.remove(item.id).then(function (response) {
                if (response.code === 0) {
                    $scope.selectPedidoPagina($scope.formOrdenPedido);
                }
            });
        };

        $scope.nextPedidoPagina = function () {
            var contador = 0;
            angular.forEach($scope.formOrdenPedido.detalles, function (value) {
                if (value.superaStock) {
                    contador++;
                }
            });
            if (contador > 0) {
                setTimeout($.unblockUI, 1000);
                alertify.error("Debe corregir los campos en rojo.");
            } else {
                $scope.formOrdenPedido.currentStep = 2;
            }
        };
        $scope.showItemDescuento = function (item) {
            $scope.formOrdenPedidoDetalle = item;
            $('#mostrarDescuentoDetallePedidoWeb').modal('show');
        };
        $scope.showPedidoDescuento = function () {
            $('#mostrarDescuentoPedidoWeb').modal('show');
        };

        $scope.getSubTotalPedidoWeb = function () {
            var result = 0;
            angular.forEach($scope.formOrdenPedido.detalles, function (value) {
                result += (value.cantidad * value.precio) - value.descuento;
            });
            return result;
        };

        $scope.getTotalPedidoWeb = function () {
            return $scope.getSubTotalPedidoWeb() - $scope.formOrdenPedido.descuento
                - ($scope.formOrdenPedido.ocupaPuntos ? $scope.formOrdenPedido.puntos : 0);
        };

        $scope.validateSavePedidoPagina = function () {
            if ($scope.formOrdenPedido.razon == "") {
                alertify.alert("Ingresar R.Social");
                return false;
            } else if ($scope.formOrdenPedido.direccion == "") {
                alertify.alert("Ingresar direccion.");
                return false;
            } else if (!$scope.formOrdenPedido.despacho) {
                alertify.alert("Seleccionar fecha despacho");
                return false;
            } else if (!$scope.formOrdenPedido.jornada) {
                alertify.alert("Seleccionar Jornada de despacho");
                return false;
            } else if (!$scope.formOrdenPedido.entrega) {
                alertify.alert("Seleccionar tipo de entrega");
                return false;
            } else if ($scope.formOrdenPedido.pagoEn.id == "0" || $scope.formOrdenPedido.pagoEn.id == "") {
                alertify.alert("Seleccionar modo de pago");
                return false;
            } else if($scope.formOrdenPedido.sector.id=="0" || $scope.formOrdenPedido.sector.id==""){
                alertify.alert("Seleccionar sector");
                return false;
            }
            return true;
        };

        $scope.savePedidoPagina = function () {
            var isValid = $scope.validateSavePedidoPagina();
            if (isValid) {
                var objOdenCliente = [
                    {
                        "id": "",
                        "rut": $scope.formOrdenPedido.rutCliente,
                        "nombre": $scope.formOrdenPedido.razon,
                        "direccion": $scope.formOrdenPedido.direccion,
                        "tipoComprador": "Nuevo",
                        "comuna": "",
                        "celular": $scope.formOrdenPedido.contacto,
                        "cobroDespacho": "0",
                        "tipoDePago": $scope.formOrdenPedido.pagoEn.id,
                        "entregaPed": $scope.formOrdenPedido.entrega,
                        "idSector": $scope.formOrdenPedido.sector.id,
                        "idDireccion": "",
                        "despacho": $scope.formOrdenPedido.despacho,
                        "jornada": $scope.formOrdenPedido.jornada,
                        "observacioPed": $scope.formOrdenPedido.observacion,
                        "tipoPedido": 2,
                        "descuento": ($scope.formOrdenPedido.descuento + ($scope.formOrdenPedido.ocupaPuntos ? $scope.formOrdenPedido.puntos : 0))
                    }
                ];
                var objOrdenUsuario = [
                    {
                        "idUsuario": $scope.idUsuarioLogin,
                        "idCliente": "2"
                    }
                ];
                var objOrdenDetalles = [];

                angular.forEach($scope.formOrdenPedido.detalles, function (value) {
                    var precioNeto = Math.round(value.precio / 1.19);
                    var descuentoNeto = Math.round(value.descuento / 1.19);
                    objOrdenDetalles.push({
                        id: value.idProducto,
                        precioVenta: precioNeto,
                        cantidadProd: value.cantidad,
                        descuento: descuentoNeto,
                        aumento: 0,
                        totalProd: (value.cantidad * precioNeto) - descuentoNeto,
                        idPrecioFactura: value.idPrecioFactura
                    });
                });

                var objCabe    = JSON.stringify(objOrdenUsuario);
                var objDeta    = JSON.stringify(objOrdenDetalles);
                var objCliente = JSON.stringify(objOdenCliente);

                $http({
                    method: 'POST',
                    url: 'FunctionIntranet.php?act=insertarPedido&tienda=' + $scope.tiendaSeleccionada,
                    data: $.param({
                        objCabe: objCabe,
                        objDeta: objDeta,
                        objCliente: objCliente
                    }),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    console.log(data);

                    var respuesta = data;

                    if (respuesta != "3") {
                        alertify.success("Pedido generado con exito!. Numero Pedido: " + data);
                        tiendaOrdenService.replaceTo($scope.formOrdenPedido.id, {idPedido: data}).then(function (response) {
                            if (response.code === 0) {
                                $('#procesarPedidoWeb').modal('hide');
                                $scope.listarOrdenesTienda();
                            }
                        });
                        if ($scope.formOrdenPedido.rutCliente != "") {
                            $scope.registrarPuntos(data);
                        }
                    } else if (respuesta == "3") {
                        setTimeout($.unblockUI, 1000);
                        alertify.alert("Por motivos logisticos en bodega, se encuentra bloqueado la opcion para Generar " +
                            "Pedido. Para mas informacion contactar con el Administrador del Sistema.");
                    } else if (respuesta == "0") {
                        setTimeout($.unblockUI, 1000);
                        alertify.error("Error al generar pedido, favor contactar con el Administrador del Sistema.");
                    }
                }).error(function (error) {
                    console.log(error);
                });
            }
        };

  /*      $scope.$parent.title = "Listado de Pedidos";
    $scope.$parent.img = "img/iconset-addictive-flavour-set/png/document-plaid-pen.png";
    $scope.$parent.showTopToggle = false;    
        */
    $scope.tipoPagos                = [];
    $scope.tipoBusqueda             = ""; 
    $scope.porcentajeAnterior       = []; 
    $scope.auxPorcentaje            = []; 
    $scope.dgPorcentaje             = [];   
    $scope.objDetallesT             = []; 
    $scope.despDomPart              = "0";
    $scope.bloquearTipoDoc          = "true";    
    $scope.id_informe_transporte    = "0";
    $scope.correo                   = "";     
    $scope.listProd                 = [];  
    $scope.loading                  = false; 
    $scope.idPedido                 = '';
    $scope.detPedido                = [];
    $scope.detPedidoLog             = [];
    $scope.listPedidos              =  [];  
    $scope.listPedidosRutaTransp    =  [];      
    $scope.unirPedidos              =  [];  
    $scope.codTipoCobro             = 0;    
    $scope.codEstadoPed             = 0;
    $scope.seleccionadoTotal        = 0;
    $scope.customConfig             = false;
    $scope.idProducto               = "";
    $scope.btnGenerarFactura        = false;
    $scope.idTransporteRuta         = "";
 // $scope.idTransporte = '';
    $scope.observacionVendAnular    = "";
    $scope.observacionMotivoCredito = "";
    
    $scope.allItemsSelectedNorte    = false;
    $scope.allItemsSelectedSur      = false;        
    $scope.sumaNortePeso            = 0;
    $scope.sumaSurPeso              = 0;
    $scope.tipPedGenerar            = "";
    $scope.mostBotFact              = true;
    $scope.mostBotBol               = true;
    $scope.estadoNuboxConsultar     = false;    
    $scope.productoAdd              = []; 
    $scope.lisProdOfert             = [];         

  $scope.diaSemana = {
  availableOptions: [
    {id: '1', name: 'NORTE'},
    {id: '3', name: 'CENTRO 1'},
    {id: '4', name: 'CENTRO 2'},
    {id: '2', name: 'SUR'}       
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}      
        
        
function formatNumero(numero){        
        var num = numero.replace(/\./g,'');
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
        num = num.split('').reverse().join('').replace(/^[\.]/,'');
        numero = num;      
      return numero;
}

    
     //   change="listarInformesSel()"//
        
$scope.listarInformesSel =  function(){
  $scope.id_informe_transporte =   $scope.listInfTransp.id;
}
    
$scope.listarInformesTransp =  function(){
        var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
       $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listadoInforTransporteAsig&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id_transporte:$scope.idTransporte,
                             desde:desde,
                             hasta:hasta

                            }),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);                
                $scope.listadoInfTransp = [];                
                $scope.listadoInfTransp = data;
                $scope.listInfTransp = {id: "0"};


          }).error(function(error){
                        console.log(error);
    
      });
    
}        
        
 




 $scope.selTipoBusqPedidos = function (rdo) {   
 $scope.tipoBusqueda = "";
   if(rdo=="1"){   //Fecha Ingreso
         $scope.busqueda1 = true;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda4 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;
         $scope.tipoBusqueda ="1";
    }else if(rdo=="2" ){  
         $scope.busqueda1 = false;     
         $scope.busqueda2 = true;
         $scope.busqueda3 = false;
         $scope.busqueda4 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="2";
    }else if(rdo=="3"){
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = true;
         $scope.busqueda4 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="3";
        
    }else if(rdo=="4"){   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda4 = true;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="4";
         
    }else if(rdo=="5"){
   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda4 = false;
         $scope.busqueda5 = true;
         $scope.busqueda6 = false;

         $scope.tipoBusqueda ="5";
         
    }else if(rdo=="6"){
   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = false;
         $scope.busqueda3 = false;
         $scope.busqueda4 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = true;

         $scope.tipoBusqueda ="6";
         
    }
     
     
 
};           
 




$scope.listarPedidosPaginaWeb = function(){
    
             $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listTiendaPedidoPagina&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.listPedWeb = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    
    
}






$scope.initListado = function () {
    $scope.listarOrdenesTienda();
    
     if($scope.tipoIdLogin == '2' || $scope.tipoIdLogin == '3' || $scope.tipoIdLogin == '4'){
             $scope.bloquearTipoDoc = false;
      }
    
    
   
         $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarMes&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.listMeses = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    
    
         $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarAno&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.listAnos = data;

        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    
    
    
    
    
    
   $scope.listarProductoOfertaDesc(); 
   //$scope.informacionTransporte();
    
   document.getElementById('divPedido').style.display                              = 'none';
   document.getElementById('divPedidoNC').style.display                            = 'none';      
   document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
   document.getElementById('divCreditosFacturas').style.display                    = 'none';      
   document.getElementById('divPedidoProductos').style.display                     = 'none'; 
   document.getElementById('divPedidoListado').style.display                       = 'block'; 

    
    
    
    
    
$scope.test = {};
$scope.consultarConfig(); 
    
    
$scope.listarTienda();
    
if($scope.tipoUsuarioLogin == 'Administrador'){
    $scope.numeroColumna = 6;   

}else if($scope.tipoUsuarioLogin == 'Vendedor' || $scope.tipoUsuarioLogin == 'Supervisor' || $scope.tipoUsuarioLogin == 'Supervisor Transportes'){
      $scope.numeroColumna = 5;    
      $scope.customConfig = true;
}      
    
if($scope.tipoUsuarioLogin == 'Bodeguero'){
    $scope.numeroColumna = 6;    
   
}
    
     //alertify.alert($scope.customSelCh.toString());
    
    /*
if($scope.tipoUsuarioLogin == 'Transportes'){
    $scope.customConfig = true;

    $scope.tipoBusqueda    = "Despacho";        
}else{
    $scope.tipoBusqueda    = "Ingreso";       
}        */
    
    
         $scope.tipoBusqueda    = "2";   
         $scope.busqueda1 = false;     
         $scope.busqueda2 = true;
         $scope.busqueda3 = false;
         $scope.busqueda4 = false;
         $scope.busqueda5 = false;
         $scope.busqueda6 = false;    
    
   
    
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
    
$scope.fechaActual                     = year+"-"+month+"-"+daym;    
    
    
document.getElementById('desdeb1').value= year+"-"+month+"-"+daym;
document.getElementById('hastab1').value= year+"-"+month+"-"+daym;

document.getElementById('desdeb2').value= year+"-"+month+"-"+daym;
document.getElementById('hastab2').value= year+"-"+month+"-"+daym;

document.getElementById('desdeb3').value= year+"-"+month+"-"+daym;
document.getElementById('hastab3').value= year+"-"+month+"-"+daym;

document.getElementById('desdeb4').value= year+"-"+month+"-"+daym;
document.getElementById('hastab4').value= year+"-"+month+"-"+daym;
    
    
document.getElementById('desdebOtro').value= year+"-"+month+"-"+daym;
document.getElementById('hastabOtro').value= year+"-"+month+"-"+daym;    
    
    
    
    
    

if( $scope.customInterEntrar != true){
        $scope.customInterLogin=true;
}    
    
$scope.tipoPagos = {
  availableOptions: [
    {id: '1', name: 'Efectivo'},
    {id: '2', name: 'Caja Vecina'},
    {id: '3', name: 'Documento'},
    {id: '4', name: 'Pendiente'} ,
    {id: '5', name: 'TransBank'}  ,
    {id: '6', name: 'Transferencia'}        
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}
 
    
    


$scope.cobradoVend = {
  availableOptions: [
    {id: '0', name: '--Seleccionar Tienda--'}  ,
    {id: '16', name: 'SANTA MARIA'},
    {id: '17', name: 'TUCAPEL'},
    {id: '18', name: 'ASOCAPEL'}
  
  
   
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}

};
        
    
$scope.cargarPedido = function (pedidoIndex, accion) {
    
   // alert(pedidoIndex.folio);
   
    $scope.mostGenerarBol          = pedidoIndex.folio;
    $scope.mostCredito             = pedidoIndex.credito;

    
    $scope.btnGenerarFactura = false; 
    var rutAux                 = pedidoIndex.rut;    
    var digVirif               = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido           = []; 
    $scope.btnGenerarFactura   = false;
    $scope.detPedido           = [];     
    $scope.listPedidosIndex    = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
    $scope.rutModificar =  pedidoIndex.rut;
    $scope.razonMod     =  pedidoIndex.nombre;
    $scope.giroMod      =  pedidoIndex.giro;
    $scope.direccionMod =  pedidoIndex.direccion;
    $scope.idPedido     =  pedidoIndex.id_pedido;        
    $scope.tipPedGenerar = pedidoIndex.id_tipo_ped;
    
    
    
    $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1" ){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    
    

    
    if(pedidoIndex.folio!=null){ 
       $scope.btnGenerarFactura = true;     
    }
    

     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedido = data;

                     $scope.totales = {
                         sub: 0,
                         descuento: 0,
                         neto: 0,
                         iva: 0,
                         total: 0
                     };
                     for(var i = 0; i < $scope.detPedido.length; i++){
                         var product = $scope.detPedido[i];
                         $scope.totales.sub += Math.round((product.cantidad * product.precio_vendido) /*- product.descuento*/);/*Modificacion temporal 24-04-2024 JORGE*/
                      //   $scope.totales.sub += Math.round((product.cantidad * product.precio_vendido) - product.descuento);/*Modificacion temporal 24-04-2024 JORGE*/

                     }
                     $scope.totales.descuento = pedidoIndex.descuento;
                     $scope.totales.neto = $scope.totales.sub /*- $scope.totales.descuento*/;/*Modificacion temporal 24-04-2024 JORGE*/
                  //   $scope.totales.neto = $scope.totales.sub - $scope.totales.descuento;/*Modificacion temporal 24-04-2024 JORGE*/

                     $scope.totales.iva = Math.round($scope.totales.neto * 0.19);
                     $scope.totales.total = $scope.totales.neto + $scope.totales.iva;
     }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    

};


var acc = document.getElementsByClassName("accordion");

for (var i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
        
        $scope.cargarDetalleRecibo = function (idIndex) {
    $scope.idPedido          = idIndex.id_pedido;
    $scope.nombreClie          = idIndex.nombre;
            
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarRecibosClientes&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:idIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               
               $scope.detPedidosRecibo = data;
               $scope.observacionRecibo = data[0].observacion;
        
          }).error(function(error){
            console.log("error Listar Detalle Pedido: "+data);    
    }); 
    

};
        
        

$scope.anularPedido = function () {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=anularPedidoListado&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.idPedido,
                             oberv:$scope.observacionVendAnular}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                 var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){                
                    alertify.success("Pedido anulado.");
                    $("#myModalObserPedidoAnular").modal("hide");
                  //  $scope.buscarPedidoDatos();
                    $scope.verDetallePedidoSalir();


                }else{
                    alertify.error("Error al anular pedido, favor contactar con el Administrador del Sistema.");
                }
        
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};


$scope.confirmarAnularPedido = function () { 

    if($scope.observacionVendAnular.length > 10 ){
    alertify.confirm("Esta seguro que desea anular pedido?", function (e) {
        if (e) {
            $scope.anularPedido();
            $("#myModalModificarPedido").modal("hide");
            
        } 
    });    
        
        }else{
            alertify.alert("Para poder anular debe ingresar una observacion mayor a 10 caracteres.");
        }
};


$scope.getTotalPedido = function(){
var total = 0;
var iva = 0.19;


for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += Math.round((product.cantidad * product.precio_vendido + Math.round(product.descuento)  ) - product.descuento);
}

var totalNeto = (Math.round(total));
               total = (Math.round(total) * iva) + Math.round(totalNeto);

    

if( $scope.despDomPart == "1"){
    total += 2000;
}        

    
return Math.round(total);
    


};    

        
$scope.getTotalPedidoCantidad = function(){
var can = 0;
    for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        can += Math.round(product.cantidad);
    }
return can;
};    
        
        
$scope.getNeto= function(){
    var total = 0;
    for(var i = 0; i < $scope.detPedido.length; i++){
        var product = $scope.detPedido[i];
        total += Math.round((product.cantidad * product.precio_vendido +  Math.round(product.descuento)) - product.descuento);
    }
    return Math.round(total);    
};    

        
$scope.getIva= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.detPedido.length; j++){
        var product = $scope.detPedido[j];
        total += Math.round((product.cantidad * product.precio_vendido +  Math.round(product.descuento)) - product.descuento);
    }
    total =Math.round(total) * (iva);
    return Math.round(total);
};    



        
        
        
        
        
$scope.modificarPedidoEstado = function(){
    var arrCobro = [];
    
    var objCobro= new Object();            
            objCobro.observacion     = $scope.observacion;
            objCobro.estadoPedido    = $scope.estadoPedido.selectedOption.id;    
            objCobro.pedido          = $scope.idPedido;
            objCobro.idUsuario       = $scope.idUsuarioLogin;

    arrCobro.push(objCobro);
    
 var objCob= JSON.stringify(arrCobro);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedidoEstado&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrCliente:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Estado guardado con exito!");
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
          
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });    

};            
       

 $scope.modificarPedidoCobro = function(){
    var arrCobro = [];
    
    var objCobro= new Object();            
            objCobro.cobros          = $scope.cobros.selectedOption.id;    
            objCobro.pedido          = $scope.idPedido;
            objCobro.cod_documento   = $scope.cod_documento;
            objCobro.fechaCobro      = angular.element(document.querySelector("#fechaCobro")).val();
            objCobro.observacion     = $scope.observacion;
            objCobro.idUsuario       = $scope.idUsuarioLogin;
    arrCobro.push(objCobro);
    
 var objCob= JSON.stringify(arrCobro);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedidoCobro&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrCliente:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
         $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Cobro guardado con exito!");
                    $scope.idPedido="";
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
          
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });    

};        
       
$scope.generarBoleta = function () {
    
//if($scope.conultarNuboxFolio() == true){    
    
    
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
    
    
    var rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    
    var estDepDom     = $scope.listPedidosIndex.estado_cobro_dep;
    
    
    var TpoDTE        = "39";
    var FchEmision    = daym+"-"+month+"-"+year;
    var Rut           = "55555555-5";/*res+'-'+digito;*/
    var RznSoc        = "Persona Natural"; //$scope.listPedidosIndex.nombre;
    var Giro          = $scope.listPedidosIndex.giro.substr(0,35);
    var Comuna        = "ARICA";
    var Direccion     = "Direccion Persona Natural";
    var Email         = "";
    var IndTraslado   = "";
    var ComunaDestino = "";
    var Vendedor      = "";/*$scope.listPedidosIndex.nombreUsuario; */    
    
   /* var FormaPago = 0;
    var TerminosPagos = 1;
    
    if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
         FormaPago      = 1; 
    }else{
         FormaPago      = 2; 
         TerminosPagos  = $scope.listPedidosIndex.credito; 
    }*/
    
    
    var arrayDeta     = [];
    

 //$.blockUI({ message: "Generando Factura Electronica..." });  

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    

   var iva = 0.19;
   var total = 0;

    
    

   for (var i = 0; i <    $scope.detPedido.length; i++) {   
    
    var objDeta= new Object();
    total = 0;  
    total =  $scope.detPedido[i].precio_vendido;    
    var totalNeto = (Math.round(total));
    total = (Math.round(total) * iva) + Math.round(totalNeto);              
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.detPedido[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.detPedido[i].cantidad;
            objDeta.Precio      =  Math.round(total);
            objDeta.Codigo      =  $scope.detPedido[i].codProducto;
            arrayDeta.push(objDeta);           
       
   }
    
    
    
   if(estDepDom == "1"){
      
    var objDeta= new Object();
    total = 2000;            
            objDeta.Afecto      = "SI";
            objDeta.Nombre      = "Despacho Domicilio";
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  1;
            objDeta.Precio      =  Math.round(total);
            objDeta.Codigo      =  1;
            arrayDeta.push(objDeta);      
   }    
    
    
    
    
    
   var objDetalles= JSON.stringify(arrayDeta);    
   
  
	
		
	      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:'Persona Natural', 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:'', 
                                        FormaPago:'',
                                        TerminosPagos:'',
										Detalles:objDetalles,                                        
                                        idPedido:$scope.listPedidosIndex.id_pedido,
                                        TipoServicio:'3'
										
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
              
               var arrayNubox = data.split(';;;');
              
               var respuesta = data.charAt(data.length-1);
                
                 if(arrayNubox[1].length  > 0){
                    
                  alertify.alert(RznSoc+" Factura Generada!");
                  alertify.set({ delay: 10000 });
             //     $scope.buscarPedido('');         
                  $scope.verDetallePedidoSalir();
               //   $scope.detPedido           = [];     
              //    $scope.listPedidosIndex    = [];     
                    
                    
                  var arrayNubox = data.split(';;;');
                    
                  var folio      = arrayNubox[0];
                  var tipo       = arrayNubox[1];
                    
                  $scope.recorrerListado(folio, tipo);
                  $scope.mostBotBol = false;    
                    
                    
                }else{
                    
                        alertify.error("Error al facturar.");

                    alertify.alert(data);
                }
          
               setTimeout($.unblockUI, 1000);   
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });
    
//}else{
    
  //  alertify.alert("Pedido ya se encuentra Boleteado");
//}
    
};   
        
        
$scope.generarFactura = function () {
    
//if($scope.conultarNuboxFolio() == true){
   

    
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
    
    
    var rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    var TpoDTE        = "33";
    var FchEmision    = daym+"-"+month+"-"+year;
    var Rut           = res+'-'+digito;
    var RznSoc        = $scope.listPedidosIndex.nombre;
    var Giro          = $scope.listPedidosIndex.giro.substr(0,35);
    var Comuna        = "ARICA";
    var Direccion     = $scope.listPedidosIndex.direccion.replace("-"," ");
    var Email         = "";
    var IndTraslado   = "";
    var ComunaDestino = "";
    var Vendedor      = $scope.listPedidosIndex.nombreUsuario;     
    
     var FormaPago = 0;
    var TerminosPagos = 1;
    
    if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
         FormaPago      = 1; 
    }else{
         FormaPago      = 2; 
         TerminosPagos  = $scope.listPedidosIndex.credito; 
    }
    
    
    var arrayDeta     = [];
    

 //$.blockUI({ message: "Generando Factura Electronica..." });  

	

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

   for (i = 0; i <    $scope.detPedido.length; i++) {   
       
   var objDeta= new Object();
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.detPedido[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.detPedido[i].cantidad;
            objDeta.Precio      =  $scope.detPedido[i].precio_vendido;
            objDeta.Codigo      =  $scope.detPedido[i].codProducto;

   arrayDeta.push(objDeta);
       
   }
    
 var objDetalles= JSON.stringify(arrayDeta);    
   
  
	
		
	      $http({method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:Giro, 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:Vendedor, 
                                        FormaPago:FormaPago,
                                        TerminosPagos:TerminosPagos,
										Detalles:objDetalles,                                        
                                        idPedido:$scope.listPedidosIndex.id_pedido,
                                        TipoServicio:''
										
                                       }),                                
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);

	// var arrayNubox = data.split(';;;');
        //  alertify.alert("N° Folio:"+arrayNubox[0]);
              
          
         //  if(arrayNubox[1]!=""){
          
    //       $scope.insertarFacturasNubox(arrayNubox);
         //  $scope.obtenerPDF(arrayNubox[0],arrayNubox[1]);
           //}else{
            
         //      alertify.error(arrayNubox[3] );
               
        //   }
               var arrayNubox = data.split(';;;');
              
               var respuesta = data.charAt(data.length-1);
                      //  alertify.alert(arrayNubox[1].length.toString());

                if(arrayNubox[1].length  > 0 ){
                  alertify.alert(RznSoc+" Factura Generada!");                    
                  alertify.set({ delay: 10000 });
                 
                // $scope.buscarPedido('');         
                 $scope.verDetallePedidoSalir();
             //    $scope.listPedidosIndex    = [];     
                 
                  var arrayNubox = data.split(';;;');
                    
                  var folio      = arrayNubox[0];
                  var tipo       = arrayNubox[1];
                    
                  $scope.recorrerListado(folio, tipo);
                  $scope.mostBotFact = false;
                                  $scope.detPedido           = [];     
   
                }else{
       //     var arrayNubox = data.split(';;;');
                     alertify.error("Error al facturar.");

                    alertify.alert(data);
                }
          
          setTimeout($.unblockUI, 1000);   
              
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
                               $scope.detPedido           = [];     

    
    });
	
  // }else{
   
  //     alertify.alert("Pedido ya se encuentra facturado");
       
       
  // }    
    
    
    
};
           

$scope.obtenerPDF = function (dataPed) {

         $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
   var config = {
                url: 'FunctionIntranet.php?act=getPDF&tienda='+$scope.tiendaSeleccionada,
                data:  $.param({identificador:dataPed.identificacion, 
                                        folio:dataPed.folio 
                                       }),
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
            var filename ="factura_"+dataPed.folio+".pdf";          

            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
         
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
              setTimeout($.unblockUI, 1000);   
        } catch (ex) {
            console.log(ex);
        }
            
        }, function errorCallback(response) {
            console.log("error");
        });  
};


$scope.isRowSelected = function (idPedido){    
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){
            alertify.alert("Seleccionado" + idPedido);
        }
    }     
};

 
$scope.toggleSelection = function(item){   
    
var desm = false;
   //if($scope.estadoConsulta.length == 0){
    
	

    
   //item.isRowSelected = !item.isRowSelected;  
   
    
    if(item.isRowSelected){
       $scope.seleccionadoTotal=  $scope.seleccionadoTotal + 1;
        if(item.id_sector == 1){
          for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == item.id_pedido ){	
				$scope.sumaNortePeso = (Math.round( $scope.sumaNortePeso)) + Math.round($scope.listPedidos[i].pesos);
			}		
          }
           
        }else if(item.id_sector == 2){
          for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == item.id_pedido ){	
				$scope.sumaSurPeso = (Math.round( $scope.sumaNortePeso)) + Math.round($scope.listPedidos[i].pesos);
			}		
          }
           
        }
    }else{
       $scope.seleccionadoTotal=  $scope.seleccionadoTotal - 1 ;
		desm = true;  
        
        
        if(item.id_sector == 1){
                    $scope.allItemsSelectedNorte = false;
         }else if(item.id_sector == 2){
                    $scope.allItemsSelectedSur = false;
         }
        
        
            
        if(item.id_sector == 1){
            
          for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == item.id_pedido ){	
				$scope.sumaNortePeso = (Math.round( $scope.sumaNortePeso)) - Math.round($scope.listPedidos[i].pesos);
			}		
          }
           
        }else if(item.id_sector == 2){
            
          for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == item.id_pedido ){	
				$scope.sumaSurPeso = (Math.round( $scope.sumaNortePeso)) - Math.round($scope.listPedidos[i].pesos);
			}		
          }
           
        }
        
        
        
    }

	    for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == item.id_pedido ){
				if(desm==false){
					$scope.listPedidos[i].orden_ruta = parseInt($scope.seleccionadoTotal);
				}else{
					$scope.listPedidos[i].orden_ruta = 0;
				}	
					
			}		
        }
   
};       
        
 /********************************/
        
$scope.cofGenerarRuta = function(idTransp){
    
    if(idTransp.toString() == "1"){
        $scope.idTransporteRuta = "Transporte AZUL";
    }else if (idTransp.toString() == "2"){
        $scope.idTransporteRuta = "Transporte ROJO";
    }else{
        $scope.idTransporteRuta = "Transporte VERDE";
    }
    
      alertify.confirm("Esta seguro que desea generar ruta " + $scope.idTransporteRuta + " ?", function (e) {
        if (e) {
         $scope.isAnythingSelectedRuta(idTransp);
            
            
        } else {
            // user clicked "cancel"
        }
    }); 
}        
		
$scope.isAnythingSelectedRuta = function (idTransp) {   
          $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

    

    
	var pedido="";
    var banderaSel=false;
	var arrayDeta=[];
	$scope.rutaTransp = [];
	
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){
            banderaSel=true;
            pedido += ','+$scope.listPedidos[i].id_pedido;			
			  var objDeta= new Object();           
                 objDeta.idPedido    = $scope.listPedidos[i].id_pedido;
                 objDeta.ruta        = "";//$scope.listPedidos[i].orden_ruta;
                 objDeta.id_transp   = idTransp.toString();
              arrayDeta.push(objDeta);

        }
    }
	
	var objDetalles= JSON.stringify(arrayDeta); 
	
	 if(!banderaSel){     
        alertify.alert("Para generar una Rota, favor seleccionar pedidos");     
	 }else{    

	
		 
		$http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinRuta&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1), 
                                        arrayRuta:objDetalles, 
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               $scope.rutaTransp = data;
			   $scope.generarReporteRutaPDF();
             setTimeout($.unblockUI, 1000);  
               $scope.buscarPedido('');

        
          }).error(function(error){
                        console.log(error);
    
      });		 
	 
		 
	 }
};		
		
		
		
$scope.listarDetallePDF =function (){
    
    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinRuta&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1), 
                                        arrayRuta:objDetalles, 
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
   			  $scope.buscarPedido('');
               $scope.rutaTransp = data;
			   $scope.generarReporteRutaPDF();
              
        
          }).error(function(error){
                        console.log(error);
    
      });
    
}
		
   
        
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

   for (i = 0; i <    $scope.rutaTransp.length; i++) {          
   var objDeta= new Object();           
            objDeta.orden_ruta     =  "";
            if($scope.rutaTransp[i].tipo == "Particular"){
                objDeta.tipo           =  "P";
            }else if($scope.rutaTransp[i].tipo == "Almacen"){
                objDeta.tipo           =  "A";
            }else if($scope.rutaTransp[i].tipo == "Mascotero"){
                objDeta.tipo           =  "M";                     
            }else if($scope.rutaTransp[i].tipo == "Veterinaria"){
                objDeta.tipo           =  "V";                  
            }else if($scope.rutaTransp[i].tipo == "Avícola"){
                objDeta.tipo           =  "AGR";                     
            }else if($scope.rutaTransp[i].tipo == "Peluqueria"){
                objDeta.tipo           =  "L";                     
            }else if($scope.rutaTransp[i].tipo == "Otro"){
                objDeta.tipo           =  "O";                     
            }
       
           
          /********************************/
           if($scope.rutaTransp[i].id_sector == 1){//Norte
                objDeta.sector           =   'img/norte.jpg';
            }else if($scope.rutaTransp[i].id_sector == 3){//centro
                objDeta.sector           =   'img/centro.png';
            }else if($scope.rutaTransp[i].id_sector == 2){//sur
                objDeta.sector           =   'img/sur.jpg';                     
            }        
          /**************************/
       
       
            objDeta.nPedido        =  $scope.rutaTransp[i].id_pedido;
            objDeta.nFactura       =  $scope.rutaTransp[i].folio;
            objDeta.nombre         =  $scope.rutaTransp[i].nombre;
            objDeta.direccion      =  $scope.rutaTransp[i].direccion;
            objDeta.obsClie        =  $scope.rutaTransp[i].observacion; 
            objDeta.nTransp        =  $scope.idTransporteRuta;
            objDeta.cantPed        =  "Cantidad Pedidos: "+ $scope.rutaTransp.length;
            objDeta.fActual        =  daym+"-"+month+"-"+year;
            objDeta.pesos          =  $scope.rutaTransp[i].pesos; 
            objDeta.usua          =  $scope.rutaTransp[i].nombreUsuario; 
            objDeta.telefono          =  $scope.rutaTransp[i].telefono; 

       if($scope.rutaTransp[i].id_tipo_ped == "1" || $scope.rutaTransp[i].id_tipo_ped == "2" || $scope.rutaTransp[i].id_tipo_ped == "0"){
            objDeta.total          =  formatNumero($scope.rutaTransp[i].total.toString());
          }else{
            objDeta.total          =  "0";
          }
       

            objDeta.formaPago     =  $scope.rutaTransp[i].formaPago;
            objDeta.id_informe    =  $scope.rutaTransp[i].id_informe; 

       
       
      
       
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
		
		
		
        
$scope.getTotalPedidoJoin = function(){
     var total = 0;
     var iva = 0.19;
   for(var j = 0; j < $scope.unirPedidos.length; j++){
        var product = $scope.unirPedidos[j];
        total += Math.round(product.cantidad * product.precioVenta);
    }
    
    var totalNeto = (Math.round(total));
    total =(Math.round(total) * iva) + Math.round(totalNeto);
    return Math.round(total);
    
};  
        
$scope.getNetoJoin= function(){
  var total = 0;
    for(var i = 0; i < $scope.unirPedidos.length; i++){
        var product = $scope.unirPedidos[i];
        total += Math.round(product.cantidad * product.precioVenta);
    }
    return Math.round(total);
    
};    

        
$scope.getIvaJoin= function(){
     var total = 0;
    var iva = 0.19;
    for(var j = 0; j < $scope.unirPedidos.length; j++){
        var product = $scope.unirPedidos[j];
        total += Math.round(product.cantidad * product.precioVenta);
    }
    total =Math.round(total) * (iva);
    return Math.round(total);
};    
      
 
$scope.confirmarPedidoSalida = function () { 
    alertify.confirm("Esta seguro que desea generar nota pedido?", function (e) {
        if (e) {
                    
            $scope.generarReportePDF('1');
        }
    });    
};
        
$scope.confirmarPedidoSalidaMasivaRuta = function () { 
    alertify.confirm("Esta seguro que desea generar nota pedido?", function (e) {
        if (e) {
                    
            $scope.generarReportePDF('2');
        }
    });    
};                
        
  
        
        
$scope.generarSalidaReporte = function (){
  var arrayDeta = [];


   for (var i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
            objDeta.codProducto    =  $scope.unirPedidos[i].id_prod;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.fecha          =  daym+"/"+month+"/"+year+ "------Inicio:"+horaImprimible+"------Fin:";
            objDeta.pedidos        =  $scope.pedidoSelecNota.substr(1);
            objDeta.bodega         =  $scope.unirPedidos[i].bodega;
            objDeta.pesoProd       =  Math.round($scope.pesoProd);
            objDeta.nombre         =  $scope.unirPedidos[i].nombre;
   arrayDeta.push(objDeta);
       
   }
    
     var jsonData=angular.toJson(arrayDeta);
    
    $http({
         method : 'POST',
         url : 'FunctionIntranet.php?act=insertarNotaPedidoSalida&tienda='+$scope.tiendaSeleccionada,
         data:  $.param({objCabe:jsonData}),
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
      }).success(function(data){
            console.log(data); 
        
        
         
          }).error(function(error){
                        console.log(error);
    
    }); 
        
        
    
}        
        
        
        
        
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
            objDeta.fecha          =  daym+"/"+month+"/"+year+ "------Inicio:"+horaImprimible+"------Fin:";
            objDeta.pedidos        =  $scope.pedidoSelecNota.substr(1);
            objDeta.bodega         =  $scope.unirPedidos[i].bodega;
            objDeta.pesoProd       =  Math.round($scope.pesoProd);
            objDeta.nombre         =  $scope.unirPedidos[i].nombre;
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
        
        
    
$scope.isAnythingSelectedFctura = function () {
    
    $scope.rutModificar  =  "";
    $scope.direccionMod =   "";              
    $scope.razonMod      =  "";
    $scope.giroMod       =  "";
    
    
    $scope.pedidoSelec = "";
    var banderaPed      = true;
    $scope.pedido      = "";
    var auxPedido       = [];
    auxPedido           = $scope.listPedidos;
    var banderaUnir     = false;
    
for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){ 
                $scope.rutModificar  =  $scope.listPedidos[i].rut;
                 $scope.direccionMod =  $scope.listPedidos[i].direccion;              
                $scope.razonMod      =  $scope.listPedidos[i].nombre;
                $scope.giroMod       =  $scope.listPedidos[i].giro;
            
            
                banderaUnir = true;
                    for(var x=0; x < auxPedido.length; x++ ){  
                          if(auxPedido[x].isRowSelected == true){ 
                             if($scope.listPedidos[i].id_cliente != auxPedido[x].id_cliente){
                                      banderaPed=false;
                                 break;
                             }   
                          }
                    }
        }
}
    
if(banderaUnir){
     if(banderaPed){


          for(var i = 0; i < $scope.listPedidos.length; i++){
                if($scope.listPedidos[i].isRowSelected == true){
                     $scope.pedido      += ','+$scope.listPedidos[i].id_pedido;
                     $scope.pedidoSelec += '- '+$scope.listPedidos[i].id_pedido;
                }
          }

    
    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.pedido.substr(1)}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                        $scope.unirPedidos = data;
                        $("#myModalUnirPedido").modal();
          }).error(function(error){
                        console.log(error);    
    });
         
         
     }else{     
      alertify.alert("Para poder realizar esta accion los pedidos seleccionados tienen que ser del mismo cliente.");   
     }
}else{
      alertify.alert("Para poder realizar esta accion, seleccionar pedidos");   

}
    
  
};   


      
        
        
$scope.insertarFacturasNubox = function (arrayNubox) {
   
      var objDetalles= JSON.stringify(arrayNubox);    
          
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=insertarFacturaNubox&tienda='+$scope.tiendaSeleccionada,
                 data:  $.param({arrayFac:objDetalles, 
                                 numPedido:$scope.listPedidosIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
         
         
          }).success(function(data){
                console.log(data);
          
                 alertify.set({ delay: 10000 });
                 alertify.success("Factura Emitida con exito!");
                 $scope.buscarPedido('');         
                 $scope.verDetallePedidoSalir();
                 $scope.detPedido           = [];     
                 $scope.listPedidosIndex    = []; 
         
          }).error(function(error){
                        console.log(error);
    
    }); 
    
    
    

};
            
                
        
$scope.generarFacturaJoin = function () {
    
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
    
    
    
    var rutAux=$.Rut.quitarFormato($scope.rutModificar);
    var digito=rutAux.substr(-1);  
    var largo = rutAux.length-1;
    
    var res = rutAux.substring(0, largo);
   
    
    var TpoDTE ="33";
    var FchEmision =daym+"-"+month+"-"+year;
    var Rut = res+'-'+digito;
    var RznSoc =$scope.razonMod;
    var Giro = $scope.giroMod;
    var Comuna ="ARICA";
    var Direccion =$scope.direccionMod.replace("-"," ");
    var Email ="";
    var IndTraslado ="";
    var ComunaDestino ="";
    var Vendedor      = "";      
      if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
        var FormaPago      = 1; 
    }else{
         var FormaPago      = 2; 
    }
    
    var arrayDeta = [];
    

 $.blockUI({ message: "Generando Factura Electronica..." });     //20 productos

   for (var i = 0; i <    $scope.unirPedidos.length; i++) {   
       
   var objDeta= new Object();
            objDeta.Afecto      = "SI";
            objDeta.Nombre      =  $scope.unirPedidos[i].nombreProd.replace("+"," ");
            objDeta.Descripcion = "";
            objDeta.Cantidad    =  $scope.unirPedidos[i].cantidad;
            objDeta.Precio      =  $scope.unirPedidos[i].precioVenta;
            objDeta.Codigo      =  $scope.unirPedidos[i].codProducto;
   arrayDeta.push(objDeta);
       
   }
    
 var objDetalles= JSON.stringify(arrayDeta);    
   
		
	      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:Giro, 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:Vendedor, 
                                        FormaPago:FormaPago,
										Detalles:objDetalles 										
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);

	      var arrayNubox = data.split(';;;');
           alertify.alert("N° Folio:"+arrayNubox[0]);
          
           if(arrayNubox[1]!=""){          
               $scope.insertarFacturasNuboxJoin(arrayNubox);
             //  $scope.obtenerPDF(arrayNubox[0],arrayNubox[1]);
           }else{            
               alertify.error(arrayNubox[3] );
               
           }
          
          
            
          
          setTimeout($.unblockUI, 1000); 
          
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });    



};

        
$scope.insertarFacturasNuboxJoin = function (arrayNubox) {
      var arrayPedidos = $scope.pedido.split(',');
      var objDetalles= JSON.stringify(arrayNubox);   
      var objPedidos= JSON.stringify(arrayPedidos);    

      $http.get('FunctionIntranet.php?act=insertarFacturaNuboxJoin&arrayFac='+objDetalles+'&arrayPedido='+objPedidos+'&tienda='+$scope.tiendaSeleccionada).success(function(data, status, headers, config) {
                 alertify.set({ delay: 10000 });
                 alertify.success("Factura Emitida con exito!");
            }
            
            ).
            error(function(data, status, headers, config) {
                console.log("error al generar  factura: "+data);
      });

};

        
        
$scope.generarReporteFacturaPDF = function () {
    
    
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

var totalPedido = $scope.getTotalPedidoJoin();
var iva = $scope.getIvaJoin();
var neto = $scope.getNetoJoin();
    
    
var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
   
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

   for (var i = 0; i <    $scope.unirPedidos.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.unirPedidos[i].codProducto;
            objDeta.nombreProd     =  $scope.unirPedidos[i].nombreProd;
            objDeta.cantidad       =  $scope.unirPedidos[i].cantidad;
            objDeta.precioVenta    =  $scope.unirPedidos[i].precioVenta;
            objDeta.total          =  $scope.unirPedidos[i].precioVenta *  $scope.unirPedidos[i].cantidad;   
       
       
            objDeta.fecha          =  daym+"/"+month+"/"+year;
            objDeta.rut            =  $scope.listPedidosIndex.rut;
            objDeta.razosocial     =  $scope.listPedidosIndex.nombre;
            objDeta.direccion      =  $scope.listPedidosIndex.direccion;
            objDeta.pedido         =  "Nota Pedido N°"+$scope.pedidoSelec;
            objDeta.totalPedido    =  totalPedido;
            objDeta.iva            =  iva;
            objDeta.neto           =  neto;
       
       
           if($scope.tipPedGenerar == "2"){
                           objDeta.tipPedGen           =  "Nota de Pedido";

           }else if($scope.tipPedGenerar == "3"){
                        objDeta.tipPedGen           =  "Bonificación";

           }else if($scope.tipPedGenerar == "4"){
                                objDeta.tipPedGen           =  "Cambio de Productos";

           }
       
       
       
           objDeta.formaPago            =  $scope.listPedidosIndex.formaPago;
           objDeta.credito              =  $scope.listPedidosIndex.credito;


   arrayDeta.push(objDeta);
       
   }
    

   
    var jsonData=angular.toJson(arrayDeta);
    var objectToSerialize={'detalle':jsonData};
        
        
            var config = {
                url: 'FunctionIntranet.php?act=generarFacturaPedidoPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="nota_pedido_"+fechaActual+"_"+cad+".pdf";          
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

$scope.convertToDate = function (stringDate){
  var dateOut = new Date(stringDate);
  dateOut.setDate(dateOut.getDate() + 1);
  return dateOut;
};
        
        
        
$scope.buscarPedidoDatos = function () {
    
     $scope.listPedidosTransp = "";    
     $scope.listPedidos = "";  
    
     $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
     } }); 

    $scope.seleccionadoTotal=0;
    
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

    var totalPedido = $scope.getTotalPedido();
    var iva = $scope.getIva();
    var neto = $scope.getNeto();
    $scope.fechaActualSistema = year+"-"+month+"-"+daym;    
    
    
    $scope.loading=true; 
    //  var rut    = angular.element(document.querySelector("#rutb")).val();
      var tipoPagos =  ($scope.tipoPagos.selectedOption.id == 0 ? "": $scope.tipoPagos.selectedOption.id);
      var nombre = angular.element(document.querySelector("#nombb")).val();
      var desde  = angular.element(document.querySelector("#desdeb")).val();
      var hasta  = angular.element(document.querySelector("#hastab")).val();
      var pedido = angular.element(document.querySelector("#idPedido")).val();


      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({tipoPagos:tipoPagos, 
                                        nombre:nombre, 
                                        desde:desde, 
                                        hasta:hasta,
                                        idPedido:pedido,
                                        tipBusq:$scope.tipoBusqueda,
                                        idTransp:$scope.idTransporte,
                                        busqRap:"0",
                                        tipoUsuario:$scope.tipoUsuarioLogin,
                                        idUsuario:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            if($scope.tipoUsuarioLogin == 'Transportes'){
                $scope.listPedidosTransp = data;    
            }else{
                $scope.listPedidos = data;    
            } 
                  setTimeout($.unblockUI, 1000); 

          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    

};         
        
$scope.buscarPedido = function (valueTipo) {
    
    
     $scope.listarInformesTransp();
    
     $scope.listPedidosTransp = "";    
     $scope.listPedidos = "";  
    
    
            $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

    $scope.seleccionadoTotal=0;
    
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

    var totalPedido = $scope.getTotalPedido();
    var iva = $scope.getIva();
    var neto = $scope.getNeto();
    $scope.fechaActualSistema = year+"-"+month+"-"+daym;    
    
    
    $scope.loading=true; 
    //  var rut    = angular.element(document.querySelector("#rutb")).val();
      var tipoPagos =  "";
    
    
    /*
    1- Entregados
    3- Pendientes
    4- Anulados
    6- REchazados
    2- COn Observacion*/
    
        

    
    
    
      var nombre = angular.element(document.querySelector("#nombb")).val();
      var desde  = angular.element(document.querySelector("#desdebOtro")).val();
      var hasta  = angular.element(document.querySelector("#hastabOtro")).val();
      var pedido = angular.element(document.querySelector("#idPedido")).val();


      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({tipoPagos:tipoPagos, 
                                        nombre:nombre, 
                                        desde:desde, 
                                        hasta:hasta,
                                        idPedido:pedido,
                                        tipBusq:$scope.tipoBusqueda,
                                        idTransp:$scope.idTransporte,
                                        busqRap:valueTipo,
                                        tipoUsuario:$scope.tipoUsuarioLogin,
                                        idUsuario:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            if($scope.tipoUsuarioLogin == 'Transportes'){
                $scope.listPedidosTransp = data;    
            }else{
                $scope.listPedidos = data;    
            } 
            setTimeout($.unblockUI, 1000); 
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
    
    

};       
        

        
        
        
        
        
        
        
        
        
        
        
        
$scope.buscarPedidoGeneralFiltro = function (valueTipo) {
    $scope.listarOrdenesTienda();
    
if($scope.tipoBusqueda == "" ){    
    
    alertify.alert("Favor seleccionar tipo de busqueda.");
  
}else{
    
     
$scope.tipoPagos = {
  availableOptions: [
    {id: '1', name: 'Efectivo'},
    {id: '2', name: 'Caja Vecina'},
    {id: '3', name: 'Documento'},
    {id: '4', name: 'Pendiente'} ,
    {id: '5', name: 'TransBank'}  ,
    {id: '6', name: 'Transferencia'}        
  ],
  selectedOption: {id: 0} //This sets the default value of the select in the ui
}    
    
   // $scope.listarInformesTransp();
    $scope.listPedidosTransp = "";    
    $scope.listPedidos = "";  
    
    
            $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

    $scope.seleccionadoTotal=0;
    
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

    var totalPedido = $scope.getTotalPedido();
    var iva = $scope.getIva();
    var neto = $scope.getNeto();
    $scope.fechaActualSistema = year+"-"+month+"-"+daym;    
    
    
    $scope.loading=true; 
    //  var rut    = angular.element(document.querySelector("#rutb")).val();
      var tipoPagos =  ($scope.tipoPagos.selectedOption.id == 0 ? "": $scope.tipoPagos.selectedOption.id);
    
    
      var nombre = angular.element(document.querySelector("#nombb")).val();
      var pedido = angular.element(document.querySelector("#codigoPedido")).val();
      var folio = angular.element(document.querySelector("#folioPedido")).val();
      var desde1  = angular.element(document.querySelector("#desdeb1")).val();
      var hasta1  = angular.element(document.querySelector("#hastab1")).val();    
      var desde2  = angular.element(document.querySelector("#desdeb2")).val();
      var hasta2  = angular.element(document.querySelector("#hastab2")).val();
      var desde3  = angular.element(document.querySelector("#desdeb3")).val();
      var hasta3  = angular.element(document.querySelector("#hastab3")).val();
      var desde4  = angular.element(document.querySelector("#desdeb4")).val();
      var hasta4  = angular.element(document.querySelector("#hastab4")).val();
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarPedidosGeneral&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({tipoPagos:tipoPagos, 
                                        nombre:nombre, 
                                        
                                        idPedido:pedido,
                                        folio:folio,
                                        
                                        desde1:desde1,
                                        hasta1:hasta1,

                                        desde2:desde2,
                                        hasta2:hasta2,
                                        
                                        desde3:desde3,
                                        hasta3:hasta3,
                                        
                                        desde4:desde4,
                                        hasta4:hasta4,
                                        
                                        tipBusq:$scope.tipoBusqueda,
                                        idTransp:$scope.idTransporte,
                                        busqRap:valueTipo,
                                        tipoUsuario:$scope.tipoUsuarioLogin,
                                        idUsuario:$scope.idUsuarioLogin}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            if($scope.tipoUsuarioLogin == 'Transportes'){
                $scope.listPedidosTransp = data;    
            }else{
                $scope.listPedidos = data;    
            } 
            setTimeout($.unblockUI, 1000); 
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
          

    
}
    
    
    

};       
        
        
        
        
        
        
        
        
        
        
        
        
        
        
$scope.generarNotaPedido = function (tipoDocumentoPDF) {
 
 
    
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

var totalPedido = $scope.getTotalPedido();
var iva = $scope.getIva();
var neto = $scope.getNeto();
    
    
var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
    
   
    
  var arrayDeta = [];
  var fechaActual= daym+"/"+month+"/"+year;

    
   for (var i = 0; i <    $scope.detPedido.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.detPedido[i].codProducto;
            objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
            objDeta.cantidad       =  $scope.detPedido[i].cantidad;
            objDeta.precioVenta    =  formatNumero($scope.detPedido[i].precio_vendido);
            var totalProd          =  $scope.detPedido[i].precio_vendido *  $scope.detPedido[i].cantidad;
            objDeta.total          =  formatNumero(totalProd.toString());   
       
       
            objDeta.fecha          =  daym+"/"+month+"/"+year;
            objDeta.rut            =  $scope.listPedidosIndex.rut;
            objDeta.razosocial     =  $scope.listPedidosIndex.nombre;
            objDeta.direccion      =  $scope.listPedidosIndex.direccion;
            objDeta.pedido         =  "N° "+$scope.idPedido;

        
       
           if($scope.listPedidosIndex.estado_cobro_dep =="1"){
              var sumaDesp = parseInt(totalPedido + 2000);
               objDeta.totalPedido     =  formatNumero(sumaDesp.toString());
           }else{
                objDeta.totalPedido    =  formatNumero(totalPedido.toString());

           }
       
           
           objDeta.formaPago            =  $scope.listPedidosIndex.formaPago;
           objDeta.credito              =  $scope.listPedidosIndex.credito;
       
       
            objDeta.iva            =  formatNumero(iva.toString());
            objDeta.neto           =  formatNumero(neto.toString());
            objDeta.vendedor       =  $scope.listPedidosIndex.nombreUsuario;
       
            objDeta.cobroDespacho  =  $scope.listPedidosIndex.estado_cobro_dep;
       
     if(tipoDocumentoPDF =='c'){  
         
                                 objDeta.tipPedGen           =  "Cotización";
 }else{
       
          if($scope.tipPedGenerar == "2"){
                           objDeta.tipPedGen           = "Nota de Pedido";

           }else if($scope.tipPedGenerar == "3"){
                            objDeta.tipPedGen           =  "Bonificación";

           }else if($scope.tipPedGenerar == "4"){
                            objDeta.tipPedGen           =  "Cambio de Productos";

           }
         
    
 }      
       
       

            arrayDeta.push(objDeta);
 }
    

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'detalle':jsonData};
        
 
if(tipoDocumentoPDF =='c'){     
        var config = {
            url: 'FunctionIntranet.php?act=generarCotizacionPedidoPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="cotizacion_"+fechaActual+"_"+cad+".pdf";          
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
  
 }else{
          var config = {
            url: 'FunctionIntranet.php?act=generarFacturaPedidoPDF&tienda='+$scope.tiendaSeleccionada,
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
            var filename ="nota_pedido_"+fechaActual+"_"+cad+".pdf";          
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
     
 }

};        
        
        
        
$scope.confirmarFacturaJoin = function () { 
  if($scope.unirPedidos.length>20){        
     alertify.alert("Para poder generar Factura Electronica en el detalle del pedido no tiene que se mas de 20 productos.");
  }else{
       alertify.confirm("Esta seguro que desea generar Factura Electronica a los pedidos seleccionados?", function (e) {
        if (e) {
            $scope.generarFacturaJoin();
             $("#myModalUnirPedido").modal("hide");
        }
    });    
  }
}               
        
        
        
$scope.confirmarFactura = function () { 
  if($scope.detPedido.length > 20){        
     alertify.alert("Para poder generar Factura Electronica, maximo 20 productos.");
  }else{
       
       alertify.confirm("Esta seguro que desea generar Factura Electronica?", function (e) {
        if (e) {
          //  $scope.conultarNuboxFolioFactura();
             $scope.generarFactura();
             $("#myModalModificarPedido").modal("hide");
           }
         }      
        );     
   }
    
};
        
        
$scope.confirmarBoleta = function () { 
  if($scope.detPedido.length > 20){        
     alertify.alert("Para poder generar Boleta Electronica, maximo 20 productos.");
  }else{
       
       alertify.confirm("Esta seguro que desea generar Boleta Electronica?", function (e) {
        if (e) {
           // $scope.generarBoleta();
            $scope.generarBoletaListadoPedidos();
             $("#myModalModificarPedido").modal("hide");
           }
         }      
        );     
   }
    
};       
        
        
/***************05-10-2023***********************/		
$scope.generarBoletaListadoPedidos =function (){
    
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
                        method : 'POST',
                        /*url : 'FunctionIntranet.php?act=generarBoletaDesdeListadoPedidos&tienda='+$scope.tiendaSeleccionada,*/
  
                        url : '//192.168.1.128/Tienda/FunctionIntranet.php?act=generarBoletaDesdeListadoPedidos&tienda='+$scope.tiendaSeleccionada,

                        data:  $.param({idPedido:$scope.idPedido
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);

        
        
                if(data  != 0){
                    
                  alertify.alert(RznSoc+" Boleta Emitida Con Exito!");
                  alertify.set({ delay: 10000 });
             //     $scope.buscarPedido('');         
                  $scope.verDetallePedidoSalir();
               //   $scope.detPedido           = [];     
              //    $scope.listPedidosIndex    = [];     
                    
                    
 
                    
                  $scope.recorrerListado(data, 'Bol');
                  $scope.mostBotBol = false;    
                    
                    
                }else{
                    
                    alertify.error("Error al Emitir.");
                    alertify.alert(data);
                }
        
        
        
                 setTimeout($.unblockUI, 1000);   
        
        
          }).error(function(error){
                        console.log(error);
    
      });
    
}        
        
        

$scope.cargarPedidoCobro= function (pedidoIndex) {
    
    // alert(pedidoIndex.folio);
   
    $scope.mostGenerarBol          = pedidoIndex.folio;
     $scope.mostCredito             = pedidoIndex.credito;
   
    
    
    
   var rutAux                 = pedidoIndex.rut;    
    var digVirif               = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido           = []; 
    $scope.listPedidosIndex    = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
$scope.correo = pedidoIndex.correo;    
    
        if(pedidoIndex.id_transp.toString() == "1"){
                    $scope.idTransporteRuta = "Transporte AZUL";
                }else if (pedidoIndex.id_transp.toString() == "2"){
                    $scope.idTransporteRuta = "Transporte ROJO";
                }else{
                    $scope.idTransporteRuta = "Transporte VERDE";
        }
    
    
 $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarLogPedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedidoIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                          $scope.detPedidoLog = data;

        
          }).error(function(error){
            console.log("error Listar Log Pedido: "+data);    
    });       
    
    
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
    
if(pedidoIndex.anulada != 'S' ){
    
document.getElementById('fechaCobro').value="00000-00-00";    
    
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
var fechaActual= daym+"/"+month+"/"+year;
    
 
    $scope.rutModificar  =  pedidoIndex.rut;
    $scope.direccionMod  =  pedidoIndex.direccion;
    $scope.idPedido      =  pedidoIndex.id_pedido;   
    $scope.codTipoCobro  =  pedidoIndex.estado_cobranza;       
    $scope.cod_documento =  pedidoIndex.cod_documento;
    $scope.razonMod      =  pedidoIndex.nombre;
    $scope.observacion   =  pedidoIndex.obser_transp;
    $scope.codEstadoPed  =  pedidoIndex.id_estado;       
    $scope.creditoClie   =  pedidoIndex.credito;      
    
    
    
    $scope.tipPedGenerar = pedidoIndex.id_tipo_ped;
    
     
    
    $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1"){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    
   /* var arrayfecha = pedidoIndex.fecha_cobro.split('-');*/ 
    
 if(pedidoIndex.fecha_cobro == null || pedidoIndex.fecha_cobro =="" ){              
      document.getElementById('fechaCobro').value= year+"-"+month+"-"+daym;       
   }else{       
       var arrayfecha = pedidoIndex.fecha_cobro.split('-');      
       document.getElementById('fechaCobro').value= arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];       
   }       
    $scope.listarEstadosCobros();
    $scope.listarEstadosPedido();
    
}else{
    
   alertify.alert("Pedido Anulado");
    
}
    
    
    
};



    
$scope.listarEstadosCobros= function () {
    
$http.get('FunctionIntranet.php?act=listarCobros&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);                
                $scope.Tiposcobros = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error listarCobros: "+data);
}); 
   
$scope.cobros = {
  availableOptions: [
    {id: '0', name: '-- Tipo de Cobro --'},  
    {id: '1', name: 'Efectivo'},
    {id: '2', name: 'Caja Vecina'},
    {id: '3', name: 'Documento'},
    {id: '4', name: 'Pendiente'} ,
    {id: '5', name: 'TransBank'}  ,
    {id: '6', name: 'Transferencia'}  

  ],
  selectedOption: {id: $scope.codTipoCobro} //This sets the default value of the select in the ui
}
};
        
        
        
$scope.listarEstadosPedido= function () {
        $http.get('FunctionIntranet.php?act=listarEstadoPedido&tienda='+$scope.tiendaSeleccionada).success(function(data) {
                console.log(data);                
                $scope.estadoTipoPedido = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error listarCobros: "+data);
}); 
    
    
$scope.estadoPedido = {
  availableOptions: [
    {id: '0', name: '-- Estado Pedido --'},  
    {id: '1', name: 'Entregado'},
    {id: '2', name: 'Con Observacion'},
    {id: '3', name: 'Pendiente'},
    {id: '4', name: 'En Despacho'},
    {id: '5', name: 'En Bodega'},
    {id: '6', name: 'Rechazado'}        
      
  ],
  selectedOption: {id: $scope.codEstadoPed} //This sets the default value of the select in the ui
}
    
$scope.estadoPedidoTransp = {
  availableOptions: [
    {id: '4', name: '-- Estado Pedido --'},  
    {id: '1', name: 'Entregado'},
    {id: '2', name: 'Con Observacion'},
    {id: '3', name: 'Pendiente'},
    {id: '6', name: 'Rechazado'}        
      
  ],
  selectedOption: {id: $scope.codEstadoPed} //This sets the default value of the select in the ui
}    
    
    
};
        

$scope.selTipoBusqueda = function (rdo) { 
            $scope.tipBusqueda = rdo;
};   
        
        
        
        
$scope.enviarTienda = function(){
    var arrayDeta = [];
    var idTienda="";
   

 for(var x=0; x < $scope.lstTiendas.length  ; x++){     
     if($scope.lstTiendas[x].nombre.toString() ==  $scope.razonMod.toString()){
          idTienda = $scope.lstTiendas[x].id_tienda.toString();
           break;
        }     
 }    
    
    
    
  for (var i = 0; i < $scope.detPedido.length; i++) {         
       var objDeta= new Object();
                objDeta.cantidad    =  $scope.detPedido[i].cantidad;
                objDeta.id_prod     =  $scope.detPedido[i].id_prod;
                objDeta.tienda      =  idTienda;
                objDeta.id_ped      =  $scope.idPedido;

        arrayDeta.push(objDeta);       
  }
    
 var objDeta= JSON.stringify(arrayDeta);    

        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=traspasoPedidoTienda&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrDeta:objDeta}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);       
               var respuesta = data.charAt(data.length-1);
                    if(respuesta=="1"){
                        alertify.success("Pedido traspasado con exito!");
                    }else{
                        alertify.error("Error al traspasar pedido, favor contactar con el Administrador del Sistema.");
                    }                     
          }).error(function(error){
                        console.log(error);
    
    });
};            
        

$scope.confirmarTraspaso = function () { 
       alertify.confirm("Esta seguro que desea traspasar a tienda?", function (e) {
        if (e) {
            $scope.enviarTienda();
             $("#myModalTraspaso").modal("hide");
           }
         });        
};   
        
        
        
$scope.modificarEntregaTransp = function(){    
 if($scope.estadoPedidoTransp.selectedOption.id == "1" ){ 
     $scope.modTranpConf();  
   } if($scope.estadoPedidoTransp.selectedOption.id != "1" ){ 
       if($scope.observacion == "" ){ 
           alertify.error("Para poder realizar cambio de estado favor ingresar Observacion.");
       }else{
          $scope.modTranpConf();  

       }
    }
    
};
        
        
$scope.modTranpConf = function(){
    
      var arrCobro = [];
    var objCobro= new Object();            
            objCobro.pedido          = $scope.idPedido;
            objCobro.observacion     = $scope.observacion;
            objCobro.estadoPedido    = $scope.estadoPedidoTransp.selectedOption.id;  
            objCobro.creditoClie     = $scope.creditoClie;     
            objCobro.idUsuario       = $scope.idUsuarioLogin;
    
    
    
    arrCobro.push(objCobro);
    
    var objCob= JSON.stringify(arrCobro);    
    

    $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedidoTransp&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrEstado:objCob}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    
                    if($scope.estadoPedidoTransp.selectedOption.id != 1){
                                            $scope.testEmail();

                    }
                     alertify.success("Estado guardado con exito!");

                    $scope.buscarPedido('');
                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          
          
          $scope.loading=false; 
        
          }).error(function(error){
                        console.log(error);
    
    });
    
}        
		
		
 $scope.setTransactionJornada =function(value){
    $scope.test.currentValJornada = value;   
    
    $scope.jornadaMod      = value;  
      
  };		
        
        
$scope.selTipoEnt = function(value){
    $scope.test.entregaPed     = value;   
    $scope.tipoEntregaMod      = value;  
  };
        
$scope.entregadosTotal= function(){
  var total = 0;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        var pedido = $scope.listPedidos[i];
        if(pedido.estado_pedido == 1){
          total = total + 1;
        }
        
    }
    return parseInt(total);    
};    

        
        
$scope.PendientesTotal= function(){
  var total = 0;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        var pedido = $scope.listPedidos[i];
        if(pedido.estado_pedido == 3){
             if(pedido.anulada != "S"){
                total = total + 1;   
             }          
        }
        
    }
    return Math.round(total);    
};  
        
        
$scope.AnuladaTotal = function(){
  var total = 0;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        var pedido = $scope.listPedidos[i];
        if(pedido.anulada == "S"){
          total = total + 1;
        }
        
    }
    return Math.round(total);    
};          
 
 
        
$scope.cargarDespObs = function(pedidoIndex){
    $scope.idPedido           =  pedidoIndex.id_pedido;   
    $scope.nombreCliente      =  pedidoIndex.nombre;   
    
    var arrayfecha = pedidoIndex.fecha_entrega.split('-');      
    
  
    
    document.getElementById('fechaDespacho').value= arrayfecha[2]+"-"+arrayfecha[1]+"-"+arrayfecha[0]; 
       

    
    $scope.observacionPedido  = pedidoIndex.observacion;    
    $scope.observacionVend    =  pedidoIndex.obser_vend;   

    

    //$scope.observacionVend = pedidoIndex.observacion+""+pedidoIndex.obser_vend;
};
   

$scope.modDespachoObser = function(){ 
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=modObsDesp&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({obs:$scope.observacionVend, 
                                        fecha:angular.element(document.querySelector("#fechaDespacho")).val(),
                                        id:$scope.idPedido,
										jornada:$scope.jornadaMod,
                                        tipoEntrega:$scope.tipoEntregaMod

                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                         var respuesta = data.charAt(data.length-1);

              if(respuesta=="1"){
                    alertify.success("GUARDADO CON EXITO!");
                    $scope.buscarPedido('');
                }else{
                    alertify.error("Error al guardar estado, favor contactar con el Administrador del Sistema.");
                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });
    
};
    
        
 
$scope.guardarCobrado = function(){ 
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cobradoVendedor&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({cobrado:$scope.cobradoVend.selectedOption.id,
                                        id:$scope.idPedido
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                         var respuesta = data.charAt(data.length-1);

              if(respuesta=="1"){
                    alertify.success("GUARDADO CON EXITO!");
                    $scope.buscarPedidoDatos();
                }else{
                    alertify.error("Error al guardar cobro, favor contactar con el Administrador del Sistema.");
                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });
    
};  
        
/*

116 Asocapec
386 Santa Maria
6400 Tucapel


{id: '0', name: '--Seleccionar Tienda--'}  ,
    {id: '16', name: 'SANTA MARIA'},
    {id: '17', name: 'TUCAPEL'},
    {id: '18', name: 'ASOCAPEC'}
  

*/
        
        
$scope.generarTraspasoProductoTienda = function(){ 
    
var tiendaId = 0;
    
    
switch($scope.idCliente) {
        
  case "116":
    tiendaId = 18;
    break;
  case "386":
    tiendaId = 16;
    break;

  case "6400":
    tiendaId = 17;
    break;        
        
}    
    
    
 //  if($scope.cobradoVend.selectedOption.id != 0) {
       
       // if($scope.idCliente ==  116) {
       
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=traspasoProductosTienda&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido,
                                       /* id_tienda:$scope.cobradoVend.selectedOption.id*/
                                         id_tienda:tiendaId
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                         var respuesta = data.charAt(data.length-1);

              if(respuesta=="0"){
                    alertify.error("Pedido ya se realizo traspaso a tienda, favor intentar con otro.");
                    }else{
                    alertify.success("Pedido traspasado con exito.");
                        $scope.idCliente = 0;

                }
          
        
          }).error(function(error){
                        console.log(error);
    
    });
    

       
       /*
       
  }else{
      alertify.error("Seleccionar tienda para poder realizar traspaso.");
  } */ 
    
    
};          
        
        
$scope.cargarDespObsVendedor = function(pedidoIndex){

 // alert(pedidoIndex.folio);
   
    $scope.mostGenerarBol          = pedidoIndex.folio;
        $scope.mostCredito             = pedidoIndex.credito;

    
$scope.test.currentValJornada="";
$scope.test.entregaPed="";

    
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
    
var arrayfecha = pedidoIndex.fecha_entrega.split('-');     

var arrayfechaIngreso = pedidoIndex.fecha.split('-');  
    
var diaStri = parseInt(arrayfechaIngreso[2]) + 2;    
    
if($scope.tipoUsuarioLogin=="Supervisor Transportes"){
   var diaStri = parseInt(arrayfechaIngreso[2]) + 30;
}    
    

    
    
    
      if(diaStri < 10){
          diaStri = "0"+diaStri;
      }
        
    
    
    if(pedidoIndex.fecha_entrega == null || pedidoIndex.fecha_entrega =="" ){              
      document.getElementById('fechaDespacho').value= year+"-"+month+"-"+daym;       
    }else{       
         var arrayfecha = pedidoIndex.fecha_entrega.split('-');      
         document.getElementById('fechaDespacho').value = arrayfecha[0]+"-"+arrayfecha[1]+"-"+arrayfecha[2];     
         document.getElementById('fechaDespacho').min   = arrayfechaIngreso[0]+"-"+arrayfechaIngreso[1]+"-"+arrayfechaIngreso[2];       
         document.getElementById('fechaDespacho').max   = arrayfechaIngreso[0]+"-"+arrayfechaIngreso[1]+"-"+diaStri;       
    }    
        
$scope.idPedido                  =  pedidoIndex.id_pedido;   
$scope.nombreCliente             =  pedidoIndex.nombre;      
$scope.observacionPedido         =  pedidoIndex.observacion;
$scope.observacionVend           =  pedidoIndex.obser_vend+""+pedidoIndex.observacion;   
$scope.test.currentValJornada    =  pedidoIndex.jornada;
$scope.jornadaMod	             =  pedidoIndex.jornada;
    
$scope.test.entregaPed           =  pedidoIndex.entregaPed;
$scope.tipoEntregaMod	             =  pedidoIndex.entregaPed;

$scope.tipPedGenerar             =  pedidoIndex.id_tipo_ped;
    
   
     $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1"){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    
    
};        
        
        
        
        
$scope.cargarObservTransp = function(ped){
    $scope.idPedido     = ped.id_pedido;
    $scope.observacion = ped.obser_transp;    
};
    
$scope.cargarObservVend = function(ped){
    $scope.idPedido     = ped.id_pedido;
    $scope.observacion = ped.obser_vend +""+ped.observacion;    
};
/*
$scope.cargarObserv = function(ped){
    $scope.idPedido     = ped.id_pedido;
    $scope.observacion = ped.observacion;    
};*/
        

$scope.cargarCobroVend = function(ped){
    
    
        $scope.idPedido           = ped.id_pedido;
        $scope.nombreCliente      = ped.nombre;   
        $scope.idCliente          = ped.id_cliente;   

    

$scope.cobradoVend = {
  availableOptions: [
    {id: '0', name: '--Seleccionar Tienda--'}  ,
    {id: '16', name: 'SANTA MARIA'},
    {id: '17', name: 'TUCAPEL'},
    {id: '18', name: 'ASOCAPEC'}
  
  
   
  ],
  selectedOption: {id: ped.cobrado_vend} //This sets the default value of the select in the ui
}    
    
    
};        
  

        
                                         
$scope.generarReporteClientes = function () {
  var arrayDeta = [];
  var count=0;    

   for (var i = 0; i <    $scope.listPedidos.length; i++) {   
     count = count + 1   ;
   var objDeta= new Object();           
       
           var arrayfechaDesp = $scope.listPedidos[i].fecha_entrega.split('-');     
           var arrayfechaCobr = $scope.listPedidos[i].fecha_cobro.split('-');  
       
            objDeta.pedido         = '';
            objDeta.folio          =  $scope.listPedidos[i].folio;
            objDeta.id_pedido      =  $scope.listPedidos[i].id_pedido;
            objDeta.fecha_entrega  =    arrayfechaDesp[2]+"-"+arrayfechaDesp[1]+"-"+arrayfechaDesp[0];    
            objDeta.fecha_cobro    =    arrayfechaCobr[2]+"-"+arrayfechaCobr[1]+"-"+arrayfechaCobr[0];    
            objDeta.estado_cobranza =  $scope.listPedidos[i].estado_cobranza;       
            objDeta.totalPedido     =  formatNumero($scope.listPedidos[i].totalPedido.toString());       
            objDeta.fecha           =  $scope.fechaActual;
            objDeta.nombre          =  $scope.listPedidos[i].nombre;
            objDeta.counta          =  count;

           if(objDeta.estado_cobranza == '3' || objDeta.estado_cobranza == '2' || objDeta.estado_cobranza  == '1'  ){                
                 objDeta.nombreEstado     = 'Pagado';
            }else if(objDeta.estado_cobranza  == '4' ){
                 objDeta.nombreEstado     = 'Pendiente';
            }
       
    arrayDeta.push(objDeta);       
   }
    

   
var jsonData=angular.toJson(arrayDeta);
var objectToSerialize={'productos':jsonData};
        
        
        var config = {
            url: 'FunctionIntranet.php?act=generarCobroPDF&tienda='+$scope.tiendaSeleccionada,
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
        
        
        
        
$scope.listarTienda = function(){
 
      $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=tiendas&tienda='+$scope.tiendaSeleccionada,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                   $scope.lstTiendas = data;         
          }).error(function(error){
                        console.log(error);
    
    });
    
};          
        
        
$scope.consultarConfig = function(){
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consulEstadoConfig&tienda='+$scope.tiendaSeleccionada,
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            
                $scope.test.currentVal=data.charAt(data.length-1);    
        
          }).error(function(error){
                        console.log(error);
    
    });   

};     
        
        
$scope.listDetallePaginaWb = function(detalle){
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listTiendaDetallePagina&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id_orden:detalle.id }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            
                $scope.detalleWeb=data;    
        
          }).error(function(error){
                        console.log(error);
    
    });   

};           
              
        
        
        
  $scope.setTransactionDebit=function(value){
  
       $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=updateConf&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({id:"1",
                            activo:value
                                       }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
          
           $scope.test.currentVal=value;  
        
          }).error(function(error){
                        console.log(error);
    
    });   
  };


        
$scope.verDetallePedido = function(pedidoIndex){
    
   // alert(pedidoIndex.folio);
   
    $scope.mostGenerarBol          = pedidoIndex.folio;
        $scope.mostCredito             = pedidoIndex.credito;

    
$scope.btnGenerarFactura = false;    
    $scope.limpiarProdList();
    
    $scope.idProducto="";
    
    if(pedidoIndex.folio!=null){ 
       $scope.btnGenerarFactura = true;     
    }
    if($scope.tipoUsuarioLogin == 'Vendedor'){
       $scope.btnGenerarFactura = true;     
    }  
    
    
    $scope.stadoPed = false;
    $scope.stadoPedEntreg = false;
    
    if(pedidoIndex.estado_pedido == "4" || pedidoIndex.estado_pedido == "2" || pedidoIndex.estado_pedido == "1" || pedidoIndex.estado_pedido == "5"){       
        $scope.stadoPed = true;
    }
    
    if(pedidoIndex.estado_pedido == "4"){       
        $scope.stadoPedEntreg = true;
    }
    
    
    
    
    document.getElementById('divPedidoListado').style.display      = 'none';   
    document.getElementById('divPedido').style.display             = 'block';    
    
    var rutAux                 = pedidoIndex.rut;    
    var digVirif               = rutAux.charAt(rutAux.length-1);   
    $scope.detPedido           = []; 
    $scope.listPedidosIndex    = [];  
    $scope.listPedidosIndex     = pedidoIndex;    
    $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
    
    $scope.rutModificar =  pedidoIndex.rut;
    $scope.razonMod     =  pedidoIndex.nombre;
    $scope.giroMod      =  pedidoIndex.giro;
    $scope.direccionMod =  pedidoIndex.direccion;
    $scope.idPedido     =  pedidoIndex.id_pedido;    
    

    $scope.tipPedGenerar             =  pedidoIndex.id_tipo_ped;
    
    
    $scope.mostBotFact          = true;
    $scope.mostBotBol           = true;
    
      if($scope.tipPedGenerar == "1"){
         $scope.mostBotFact = true;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "2"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = true;
      }else if($scope.tipPedGenerar == "3"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }else if($scope.tipPedGenerar == "4"){
         $scope.mostBotFact = false;
         $scope.mostBotBol  = false;
      }
    
    
  /*  $http.get('FunctionIntranet.php?act=listarDetallePedido&idPedido='+pedidoIndex.id_pedido).success(function(data) {
                console.log(data);                
                $scope.detPedido = data;
            }).
            error(function(data, status, headers, config) {
                console.log("error Listar Detalle Pedido: "+data);
    }); */
    
    
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:pedidoIndex.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
   			  
                $scope.detPedido = data;
        
          }).error(function(error){
                        console.log("error Listar Detalle Pedido: "+error);
    
      });
    

}


$scope.verDetallePedSalidaProd = function(stdo, idEstado){
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
     method : 'POST',
     url : 'FunctionIntranet.php?act=verDetalleObsPed&tienda='+$scope.tiendaSeleccionada,
     data:  $.param({idEstado:idEstado,
                     idProd:stdo.id_prod}),
                headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
  }).success(function(data){
        console.log(data);               
        $scope.listInformeTranspDeta = data;
         $("#myModalJoinPedidoMasivoProductos").modal();
                        setTimeout($.unblockUI, 1000);   

  }).error(function(error){
        console.log(error);

});
    
    
    
}


$scope.verDetallePedidoSalir = function(){
      
        
   document.getElementById('divPedido').style.display                              = 'none';
   document.getElementById('divPedidoNC').style.display                            = 'none';      
   document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
   document.getElementById('divCreditosFacturas').style.display                    = 'none';      
   document.getElementById('divPedidoProductos').style.display                     = 'none'; 
   document.getElementById('divPedidoListado').style.display                       = 'block'; 
    

    
    
      $scope.mostBotFact  = true;
}



/*

$scope.consultarEstadoPedList = function(){
var estadoPed = true;
    if($scope.test.currentVal == 1){    
    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consultarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.idPedido.toString()}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               var respuesta = data.charAt(data.length-1);
                if(respuesta!="0"){
                          if($scope.estPedListar.toString() == respuesta.toString()){
                               estadoPed = true;
                          }else{
                               estadoPed = false;
                          }
                }else{
                    alertify.error("Error al ver el detalle del pedido, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   

}else{    
   estadoPed = false;
}
    
return estadoPed;
    
}*/

$scope.ventaPrecioDifPart = function(){
    
if($scope.precioDifPr == "" || $scope.precioDifPr ==0 ){    
       
    alertify.alert("Debe seleccionar un producto para poder realizar esta acción.");
    
} else {
    
     alertify.confirm("Desea aumentar el precio particular a la venta?", function (e) {
        if (e) {
             $scope.prodPrecioAumt = $scope.precioDifPr;
            document.getElementById("prodPrecioAumt").value = $scope.precioDifPr ;
        } 
    }); 
}   
}    


$scope.buscarProd = function(){
        $scope.limpiarProdList();  
    
var estadoPed = false;

    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consultarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.idPedido.toString()}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               var respuesta = data.charAt(data.length-1);
                if(respuesta!="0"){
                          if($scope.estPedListar.toString() == respuesta.toString()){
                               estadoPed = false;
                          }else{
                               estadoPed = true;
                          }
                }else{
                    alertify.error("Error al ver el detalle del pedido, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   

   
 

if(estadoPed == true){
       
       alertify.alert("No se pudo realizar la accion, favor listar pedido");
   } else{   
    
$scope.limpiarProdList();

$scope.accionProdLis  ="A";
    
    

var length = $scope.detPedido.length;
var objProd = new Object();
var banderaSel = "false";

for(var i = 0; i < length; i++) {
        objProd = $scope.detPedido;
        if(objProd[i].id_prod == $scope.idProducto){
             banderaSel ="true";
           break;
        }             
}
    
    
    
$scope.auxSelProd       = [];
 
    
    
if($scope.idProducto !=""){

    if(banderaSel=="false"){

       $http({
                 method : 'POST',
                 url : 'FunctionIntranet.php?act=listarProductosCajas&tienda='+$scope.tiendaSeleccionada,
                 data:  $.param({idProd:$scope.idProducto}),
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
              }).success(function(data){
                    console.log(data);                

                        $scope.productoAdd = data;

                     if(data.length != 0){

                         $scope.prodIdPrec       = data[0].id;
                         $scope.prodNombPrec     = data[0].nombreProd;
                         $scope.prodPrecioCost   = parseInt(data[0].precioVenta);
                        // $scope.selPorcentaje    = parseInt(data[0].porcentaje);
                        $scope.precioPart    = parseInt(data[0].precioPart);
                        $scope.precioDifPr    = parseInt(data[0].precioDifPr);


                       $("#myModalModProd").modal();

                     }else{
                         $scope.idProducto="";
                         alertify.alert("El Producto no existe."); 
                     }




              }).error(function(error){
                            console.log(error);

        });

    }else if(banderaSel=="true"){
         alertify.alert("El producto que desea agregar ya se encuentra en la lista."); 
    }
    
    
}else{
     alertify.alert("Favor ingresar codigo del producto para agregar a la lista."); 

 }
 }   
};
     

        

        
        
$scope.deleteProdListadoConf = function(prod, prodIndex){
   // alert(print_r(prod));    
  //$scope.conultarNuboxNumeroFolio();  
    
  
    
    var estadoPed = false;
     
    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consultarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.idPedido.toString()}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               var respuesta = data.charAt(data.length-1);
                if(respuesta!="0"){
                          if($scope.estPedListar.toString() == respuesta.toString()){
                               estadoPed = false;
                          }else{
                               estadoPed = true;
                          }
                }else{
                    alertify.error("Error al ver el detalle del pedido, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   

    



    
          if(estadoPed == true){
                       alertify.alert("No se pudo realizar la accion, favor volver a listar los pedidos.");
          }else{
            alertify.confirm("Esta seguro que desea eliminar el producto "+prod.nombreProd+"?", function (e) {
            if (e) {
                  $scope.deleteProdListado(prod, prodIndex);    
            } 
            }); 
          }
    
       
    
};
        

$scope.deleteProdListado = function(prod, prodIndex){    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=eliminarProdList&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:$scope.idPedido, producto:prod.id_prod}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
             $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                
                     $scope.detPedido.splice(prodIndex, 1);
                    alertify.success("Producto eliminado con exito");
                    
                }else{
                    alertify.error("Error al eliminar producto, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
};

        
$scope.addProdListadoConf = function(){
var mensaje="";    
if($scope.prodPrecioCant > 0){
    
if($scope.accionProdLis=="M"){
    mensaje="Esta seguro que desea modificar el producto "+$scope.prodNombPrec+"?";   
}else{
     mensaje="Esta seguro que desea agregar el producto "+$scope.prodNombPrec+"?";   
}    
    
    
     alertify.confirm(mensaje, function (e) {
        if (e) {
          $scope.addProdListado();
        } 
    });  

}else{
    
    alertify.alert("Favor ingresar cantidad para poder realizar la accion.");
}        

}
        
$scope.addProdListado = function(accion){

     var arrayClieDet = [];

                var objDeta = new Object();
                 objDeta.id_ped         = $scope.idPedido;
                 objDeta.id             = parseInt($scope.prodIdPrec);
                 objDeta.cantidadProd   = $scope.prodPrecioCant; 
                 objDeta.precioVenta    = parseInt($scope.prodPrecioCost) ; /*- (parseInt($scope.prodPrecioCost)  * ($scope.selPorcentaje/100));*/
    
                 objDeta.descuento      = ($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc);
                 objDeta.aumento        = ($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt); 
    
    objDeta.totalProd      = (parseInt((parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))); /*- (parseInt((parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) * ($scope.selPorcentaje/100);*/
    
    objDeta.precio_vendido = (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt )));/*- 
                     
                     (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt ))) * ($scope.selPorcentaje/100);*/
    
    arrayClieDet.push(objDeta);

      var objDetaPed= JSON.stringify(arrayClieDet);   
    
             $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=agregarProdListado&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrayClieDet:objDetaPed, accionProd:$scope.accionProdLis}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    
                    if($scope.accionProdLis=="M"){
                       
                       $scope.guardarPrecMod();
                       alertify.success("Producto modificado correctamente.");    
                       
                    }else if($scope.accionProdLis=="A"){
                    
                      $scope.auxSelProd.nombreProd     =  $scope.productoAdd[0].nombreProd;
                      $scope.auxSelProd.id_prod        =  $scope.productoAdd[0].id;
                      $scope.auxSelProd.precio_vendido =  (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? 0: $scope.prodPrecioDesc) +  parseInt($scope.prodPrecioAumt == undefined ? 0: $scope.prodPrecioAumt)));
                     /* - (parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? 0: $scope.prodPrecioDesc) +  parseInt($scope.prodPrecioAumt == undefined ? 0: $scope.prodPrecioAumt)))    * ($scope.selPorcentaje/100)*/
                        ;
                      $scope.auxSelProd.aumento        =  ($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt) 
                      $scope.auxSelProd.descuento      =  ($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) 
                      $scope.auxSelProd.cantidad       =  $scope.prodPrecioCant;
                     
                    $scope.auxSelProd.total          =  (parseInt(parseInt($scope.prodPrecioCost - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) ; /*- 
                      (parseInt(parseInt($scope.prodPrecioCost - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant))) *($scope.selPorcentaje/100) ;*/
                      $scope.auxSelProd.precioVenta     = $scope.prodPrecioCost; /*- ($scope.prodPrecioCost  * ($scope.selPorcentaje/100));*/
                        
                        
                      $scope.detPedido.push($scope.auxSelProd);                      
                      $scope.auxSelProd       = [];
                      alertify.success("Producto agregado correctamente.");
                    
                    }
                     $("#myModalModProd").modal("hide");   
                    $scope.limpiarProdList();  
                    $scope.accionProdLis="";
                }else if(respuesta=="0"){
                    alertify.error("Error al agregar producto, favor contactar con el Administrador del Sistema.");
                }else {
                    
                 alertify.alert("Los siguientes productos se encuentran sin Stock o la cantidad ingresada es mayor al Stock existente:"+data+ "<br/>Favor volver a seleccionar los productos señalados, ingresando nuevamente el Tipo y la Marca.");
                    
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
    
    
}        
        
        
$scope.modificarProdPrecio = function(prod){
    $scope.prodNombPrec    = prod.nombreProd;
    
     $scope.accionProdLis  ="M";
        
     $scope.prodIdPrec      = (prod.id_prod);

     $scope.prodPrecioCost  = parseInt(parseInt(prod.precio_vendido) + parseInt(prod.descuento == undefined ? 0: prod.descuento) - parseInt(prod.aumento == undefined ? 0: prod.aumento));
   //  $scope.prodPrecioVent  = prod.precio_vendido;
     $scope.prodPrecioAumt  = parseInt(prod.aumento);
     $scope.prodPrecioDesc  = parseInt(prod.descuento);
     $scope.prodPrecioCant  = parseInt(prod.cantidad);
     $scope.prodPrecioTotal = parseInt(prod.total);
    
};
        
        
$scope.modificarPedido = function(){
    var arrayClie = [];
    var arrayClieDet = [];
    
    
    
    
    var objClie= new Object();
            objClie.idCliente = $scope.listPedidosIndex.id_cliente;
            objClie.rut       = $.Rut.quitarFormato($scope.rutModificar);
            objClie.nombre    =  $scope.razonMod;
            objClie.direccion =  $scope.direccionMod;
            objClie.giro      =  $scope.giroMod;
   arrayClie.push(objClie);
    
 var objClie= JSON.stringify(arrayClie);    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=modificarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({arrCliente:objClie}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
             $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Pedido guardado con exito!");
                    $scope.initListado ();
                }else{
                    alertify.error("Error al guardar pedido, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   

};    
        
        
        
        
        
        
        
$scope.guardarPrecMod= function(){
var length = $scope.detPedido.length;
var objProd = new Object();
    
   for(var i = 0; i < length; i++) {
      objPro = $scope.detPedido;
       
       if(objPro[i].id_prod == $scope.prodIdPrec   ){
    
           
           $scope.detPedido[i].precio_vendido = parseInt(parseInt($scope.prodPrecioCost) - parseInt($scope.prodPrecioDesc == undefined ? 0: $scope.prodPrecioDesc) +  parseInt($scope.prodPrecioAumt == undefined ? 0: $scope.prodPrecioAumt));
           $scope.detPedido[i].aumento        = ($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt); 
           $scope.detPedido[i].descuento      = ($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) ;
           $scope.detPedido[i].cantidad       = $scope.prodPrecioCant;
           $scope.detPedido[i].total          = parseInt(parseInt($scope.prodPrecioCost - parseInt($scope.prodPrecioDesc == undefined ? "0": $scope.prodPrecioDesc) + parseInt($scope.prodPrecioAumt == undefined ? "0": $scope.prodPrecioAumt)) * parseInt($scope.prodPrecioCant));
           $scope.detPedido[i]. precioVenta     = $scope.prodPrecioCost;      
           
          
           break;
        
         
       
       }             
    }
    
    
};        


$scope.limpiarProdList= function(){
     $scope.prodNombPrec    = "";
     $scope.prodIdPrec      = "";
     $scope.prodPrecioCost  = "";
     $scope.prodPrecioVent  = "";
     $scope.prodPrecioAumt  = 0;
     $scope.prodPrecioDesc  = 0;
     $scope.prodPrecioCant  = "";
     $scope.prodPrecioTotal = "0";
    
};
        
    /*    
$scope.updateDespEst = function(){
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=updateEstadoDepacho&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:objClie}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
             $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Estado realizado con éxito!");
                }else{
                    alertify.error("Error al cambiar estado, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
};
*/

$scope.updateDespEst = function(){
    var pedido="";
    var pedidoNota="";
    var banderaSel=false;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){
            banderaSel=true;
            pedido += ','+$scope.listPedidos[i].id_pedido;
            pedidoNota += ',  '+$scope.listPedidos[i].id_pedido;
        }
    }
    
    $scope.pedidoSelecNota= pedidoNota;
    
    
        $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=updateEstadoDepacho&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:pedido.substr(1)}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
             $scope.listPedidosIndex =[]; 
                
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Estado realizado con éxito!");
                }else{
                    alertify.error("Error al cambiar estado, favor contactar con el Administrador del Sistema.");
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   
};


$scope.anularPedido2  = function (){
    $("#myModalObserPedidoAnular").modal();
}

        
$scope.consultarEstadoPedido = function(prodIndex){  
    
             
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#74B6F7', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    $scope.detPedido                = [];
    $scope.idPedido="";

    
    $scope.consultarConfig(); 
    $scope.estPedListar     =  prodIndex.estado_pedido;    
    
    
if($scope.test.currentVal == 1){    
    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=consultarPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({pedido:prodIndex.id_pedido.toString()}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
            
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta!="0"){
                
                          if(prodIndex.estado_pedido == respuesta){
                              $scope.verDetallePedido(prodIndex);
                          }else{
                              alertify.alert("Favor volver a listar los pedidos.");
                          }
                      setTimeout($.unblockUI, 1000);  
                    
                }else{
                    alertify.error("Error al ver el detalle del pedido, favor contactar con el Administrador del Sistema.");
                    $scope.detPedido                = [];
                      setTimeout($.unblockUI, 1000);  
                }
              
        
          }).error(function(error){
                        console.log(error);
    
    });   

}else{    
    alertify.alert("Por temas logísticos en bodega no se puede realizar esta acción.");
      setTimeout($.unblockUI, 1000);  
}
};        
        
        
        
        
        
        

function print_r(arr,level) {
var dumped_text = "";
if(!level) level = 0;
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { 
    for(var item in arr) {
        var value = arr[item];

        if(typeof(value) == 'object') { 
            dumped_text += level_padding + "'" + item + "' ...\n";
            dumped_text += print_r(value,level+1);
        } else {
            dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    }
} else { 
    dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
}         
        

$scope.informacionTransporte = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=transporteEstado&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          $scope.transporteData    = []; 
       	  $scope.transporteData = data;
		 
       // $scope.listarPorcentajeAnterior();

          }).error(function(error){
                        console.log(error);
    }); 
    
    
	
}


$scope.listarUnirPorcentaje  = function(){	

for(var i = 0; i < $scope.porcentajeAnterior.length; i++){
    
    
   for(var j = 0; j < $scope.porcentajeActual.length; j++){
        if($scope.porcentajeAnterior[i].id == $scope.porcentajeActual[j].id ){
            
            alertify.alert($scope.porcentajeAnterior[i].id);
            
               $scope.auxPorcentaje.nombre         = $scope.porcentajeAnterior[i].nombre;
               $scope.auxPorcentaje.apellido       = $scope.porcentajeAnterior[i].apellido;
               $scope.auxPorcentaje.nombre_tipo    = $scope.porcentajeAnterior[i].nombre_tipo;
        
            
            
            $scope.auxPorcentaje.total           = Math.round(((Math.round($scope.porcentajeActual[j].totalActual) / Math.round($scope.porcentajeAnterior[i].totalAnterior)) - 1  ) * 100);
               $scope.dgPorcentaje.push($scope.auxPorcentaje);
       }
   }
}
        
}


$scope.listarPorcentajeAnterior = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarAnteriorPorcentaje&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);

       	  $scope.dgPorcentaje = data;
		// $scope.listarPorcentajeActual();
					
          }).error(function(error){
                        console.log(error);
    }); 
	
}

$scope.listarPorcentajeActual = function(){	
	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarActualPorcentaje&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
       	           $scope.porcentajeActual    = []; 

         $scope.porcentajeActual = data;
		 
					$scope.listarUnirPorcentaje();
          }).error(function(error){
                        console.log(error);
    }); 	
}




 $scope.logSistema=function(ValueAccion){
       $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=insertarLog&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({id_pedido:$scope.idPedido,
                            accion:ValueAccion,
                            idUsuario:$scope.idUsuarioLogin
                                       }),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
          
        
          }).error(function(error){
                        console.log(error);
    
    });   
  };
        

$scope.marcarNorte = function(){   

        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 1 ){//Norte
                          if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                        $scope.listPedidos[i].isRowSelected = true;
                                    $scope.sumaNortePeso = (Math.round( $scope.sumaNortePeso)) + Math.round($scope.listPedidos[i].pesos);

                             }
                      }
             }
        }
  
};  
        
$scope.desmarcarNorte = function(){   
        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 1 ){//Norte
                          if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                        $scope.listPedidos[i].isRowSelected = false;
                                    $scope.sumaNortePeso = (Math.round( $scope.sumaNortePeso)) - Math.round($scope.listPedidos[i].pesos);

                             }
                      }
             }
        }

  
};        
        
        
        
        
        

        
        
$scope.marcarSur = function(){  
        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 2 ){//Sur
                            if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                    $scope.listPedidos[i].isRowSelected = true;
                                    $scope.sumaSurPeso = (Math.round( $scope.sumaSurPeso)) + Math.round($scope.listPedidos[i].pesos);
                             }
                      }
             }
        }
};  
    
        
$scope.desmarcarSur = function(){   
        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 2 ){//Sur
                          if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                $scope.listPedidos[i].isRowSelected = false;
                                $scope.sumaSurPeso = (Math.round( $scope.sumaSurPeso)) - Math.round($scope.listPedidos[i].pesos);
                             }
                      }
             }
        }
}; 
        
        

$scope.marcarCentro = function(){  
        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 3 ){//Sur
                            if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                    $scope.listPedidos[i].isRowSelected = true;
                                    $scope.sumaSurPeso = (Math.round( $scope.sumaSurPeso)) + Math.round($scope.listPedidos[i].pesos);
                             }
                      }
             }
        }
};  
    
        
$scope.desmarcarCentro = function(){   
        for(var i = 0; i < $scope.listPedidos.length; i++){
            if($scope.listPedidos[i].entregaPed == 1 ){
                      if($scope.listPedidos[i].id_sector == 3 ){//Sur
                          if($scope.listPedidos[i].id_transp == "" || $scope.listPedidos[i].id_transp == 0 ){
                                $scope.listPedidos[i].isRowSelected = false;
                                $scope.sumaSurPeso = (Math.round( $scope.sumaSurPeso)) - Math.round($scope.listPedidos[i].pesos);
                             }
                      }
             }
        }
};         
        

$scope.testEmail = function(){


    
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

var totalPedido = $scope.getTotalPedido();
var iva = $scope.getIva();
var neto = $scope.getNeto();
var cad=mydate.getHours()+":"+mydate.getMinutes()+":"+mydate.getSeconds();     
var arrayDeta = [];
var fechaActual= daym+"/"+month+"/"+year;
    
    
    
    
for (var i = 0; i <    $scope.detPedido.length; i++) {          
   var objDeta= new Object();           
       objDeta.pedido         = '';
            objDeta.codProducto    =  $scope.detPedido[i].codProducto;
            objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
            objDeta.cantidad       =  $scope.detPedido[i].cantidad;
            objDeta.precioVenta    =  formatNumero($scope.detPedido[i].precio_vendido);
            var totalProd          =  $scope.detPedido[i].precio_vendido *  $scope.detPedido[i].cantidad;
            objDeta.total          =  formatNumero(totalProd.toString());   
       
       
            objDeta.fecha          =  daym+"/"+month+"/"+year;
            objDeta.rut            =  $scope.listPedidosIndex.rut;
            objDeta.razosocial     =  $scope.listPedidosIndex.nombre;
            objDeta.direccion      =  $scope.listPedidosIndex.direccion;
            objDeta.pedido         =  $scope.idPedido;

           
           if($scope.listPedidosIndex.estado_cobro_dep =="1"){
               var sumaDesp = parseInt(totalPedido + 2000);
               objDeta.totalPedido     =  formatNumero(sumaDesp.toString());
           }else{
                objDeta.totalPedido    =  formatNumero(totalPedido.toString());

           }
       
            objDeta.iva            =  formatNumero(iva.toString());
            objDeta.neto           =  formatNumero(neto.toString());
            objDeta.vendedor       =  $scope.listPedidosIndex.nombreUsuario;
            objDeta.cobroDespacho  =  $scope.listPedidosIndex.estado_cobro_dep;         
            objDeta.tipPedGen      =  "Transporte";       
    
    
          
    

            arrayDeta.push(objDeta);
 }
    
var jsonData=angular.toJson(arrayDeta);
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=enviarCorreoPedido&tienda='+$scope.tiendaSeleccionada,
                 data:  $.param({correoUsua:$scope.correo, 
                                        detalle:jsonData,
                                transp:$scope.idTransporteRuta,
                                transpMsj:$scope.observacion,
                                transpEst:$scope.estadoPedidoTransp.selectedOption.name}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
          
        
          }).error(function(error){
                        console.log(error);
    
    }); 
    
}

            var TpoDTE        = "";
            var FchEmision    = ""
            var Rut           = ""
            var RznSoc        = ""
            var Giro          = ""
            var Comuna        = ""
            var Direccion     = ""
            var Email         = ""
            var IndTraslado   = ""
            var ComunaDestino = ""
            var Vendedor      = ""
            var idPedidoMs    = ""
       

$scope.generarFacturaMasivo = function(){
     var arrayDeta     = [];
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
    
         
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#74B6F7', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
  
   var Temp = 0; 
    for(var i=0; i < $scope.listPedidos.length; i++){  
        var arrayDeta     = [];
            var rutAux=$.Rut.quitarFormato($scope.listPedidos[i].rut);
            var digito=rutAux.substr(-1);  
            var largo = rutAux.length-1;
            var res = rutAux.substring(0, largo);


                TpoDTE        = "33";
                FchEmision    = daym+"-"+month+"-"+year;
                Rut           = res+'-'+digito;
                RznSoc        = $scope.listPedidos[i].nombre;
                Giro          = $scope.listPedidos[i].giro.substr(0,35);
                Comuna        = "ARICA";
                Direccion     = $scope.listPedidos[i].direccion.replace("-"," ");
                Email         = "";
                IndTraslado   = "";
                ComunaDestino = "";
                Vendedor      = $scope.listPedidos[i].nombreUsuario;   
                idPedidoMs    = $scope.listPedidos[i].id_pedido;   
       
        
        if($scope.listPedidos[i].id_tipo_ped == "1" ){
                 if($scope.listPedidos[i].folio == "" || $scope.listPedidos[i].folio == null){
                                 if($scope.listPedidos[i].id_informe != null){
    



           
        for (var x = 0; x <    $scope.listPedidos[i].detalle.length; x++) {   
       
                       var objDeta= new Object();
                                objDeta.Afecto      = "SI";
                                objDeta.Nombre      =  $scope.listPedidos[i].detalle[x].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
                                objDeta.Descripcion = "";
                                objDeta.Cantidad    =  $scope.listPedidos[i].detalle[x].cantidad;
                                objDeta.Precio      =  $scope.listPedidos[i].detalle[x].precio_vendido;
                                objDeta.Codigo      =  $scope.listPedidos[i].detalle[x].codProducto;

                       arrayDeta.push(objDeta);

                }
    
         $scope.objDetallesT= JSON.stringify(arrayDeta);     
        
                     
                     
           // window.setInterval("$scope.nuboxInsert()",600000);

             // $scope.nuboxInsert();       
          //  setTimeout( $scope.nuboxInsert(), 11000);         
                                     
          var ac = $scope.nuboxInsert();
                                     
              if(ac == 1){
                 
                 continue;
                 
                 }
                 
                 
                 
                                     
        
        
        } 
      }
    }     
 }
    
    
   // $scope.buscarPedido('');         
     setTimeout($.unblockUI, 1000);  
    
}



$scope.nuboxInsert = function (){
    
      $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=testDTE&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({TpoDTE:TpoDTE, 
                                        FchEmision:FchEmision, 
										Rut:Rut, 
										RznSoc:RznSoc, 
										Giro:Giro, 
										Comuna:Comuna, 
										Direccion:Direccion, 
										Email:Email, 
										IndTraslado:IndTraslado, 
										ComunaDestino:ComunaDestino, 
										Vendedor:Vendedor, 
										Detalles:$scope.objDetallesT, 
										idPedido:idPedidoMs
                                       }),
             
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
                        body: 'a=1&b=2'    ,
                       cache: 'no-cache'
          }).success(function(data){
                console.log(data);

	             /*    var arrayNubox = data.split(';;;');
                     alertify.success(RznSoc+" N° Folio: "+arrayNubox[0]);
          
                   if(arrayNubox[1]!=""){          
                           $scope.insertarFacturasNuboxMasivo(arrayNubox);
                        // $scope.obtenerPDF(arrayNubox[0],arrayNubox[1]);
                   }else{            
                           alertify.error(arrayNubox[3] ); 
                   }*/
          
           var arrayNubox = data.split(';;;');
          var respuesta = data.charAt(data.length-1);
          
         // alertify.alert(arrayNubox[0].toString());
                
                if(arrayNubox[1].length  > 0){
                       alertify.success(RznSoc+" Factura Generada!");
                alertify.set({ delay: 10000 });
                 
              //   $scope.verDetallePedidoSalir();
                 $scope.detPedido           = [];     
                 $scope.listPedidosIndex    = [];     
                    
                    
                }else{
                        alertify.error("Error al facturar.");

                    alertify.alert(data);
                }
          
          
          
           $scope.objDetallesT =[];          

          
        
          }).error(function(error){
                         console.log("error al generar  factura: "+data);
    
    });     
    
    

       return 1;
};

      
    
        
$scope.insertarFacturasNuboxMasivo = function (arrayNubox) {
   
      var objDetalles= JSON.stringify(arrayNubox);    
     
        
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=insertarFacturaNubox&tienda='+$scope.tiendaSeleccionada,
                 data:  $.param({arrayFac:objDetalles, 
                                 numPedido:$scope.listPedidosIndex.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
         
         
          }).success(function(data){
                console.log(data);
          
        lertify.set({ delay: 10000 });
                 alertify.success("Factura Emitida con exito!");       
         
          }).error(function(error){
                        console.log(error);
    
    }); 
    
    
    

}; 
        
        
$scope.verListadoCreditoFactura = function () {
    
    
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarCreditosPendienteFactura&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                   $scope.listadoCreditoFacturas = data;
        //           $("#myModalJoinPedido").modal();
            
                        

                       document.getElementById('divPedido').style.display                              = 'none';
                       document.getElementById('divPedidoNC').style.display                            = 'none';      
                       document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
                       document.getElementById('divCreditosFacturas').style.display                    = 'block';      
                       document.getElementById('divPedidoProductos').style.display                     = 'none'; 
                       document.getElementById('divPedidoListado').style.display                       = 'none'; 
    
            
            
            
      
               setTimeout($.unblockUI, 1000);  
       
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	
    
    
}



$scope.verListadoCreditoPorPedido = function (ped) {
    
    
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarCreditosPorPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:ped.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                   $scope.listadoCreditoFacturas = data;
        //           $("#myModalJoinPedido").modal();
            
            
                             

                       document.getElementById('divPedido').style.display                              = 'none';
                       document.getElementById('divPedidoNC').style.display                            = 'none';      
                       document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
                       document.getElementById('divCreditosFacturas').style.display                    = 'block';      
                       document.getElementById('divPedidoProductos').style.display                     = 'none'; 
                       document.getElementById('divPedidoListado').style.display                       = 'none'; 
            
            
      
               setTimeout($.unblockUI, 1000);  
       
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	
    
    
}

        
        
$scope.isAnythingSelected = function () {
    
          $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
    
    var pedido="";
    var pedidoNota="";
    var banderaSel=false;
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].isRowSelected == true){
            banderaSel=true;
            pedido += ','+$scope.listPedidos[i].id_pedido;
            pedidoNota += ',  '+$scope.listPedidos[i].id_pedido;
        }
    }
    
    $scope.pedidoSelecNota= pedidoNota;
    
 if(!banderaSel){     
     alertify.alert("Para generar el listado de productos, favor seleccionar pedidos");     
 }else{    
    
	
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidosProductoSalidas&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		           $scope.pesosProductos(pedido);
                   $scope.unirPedidos = data;
        //           $("#myModalJoinPedido").modal();

            
            
                       document.getElementById('divPedido').style.display                              = 'none';
                       document.getElementById('divPedidoNC').style.display                            = 'none';      
                       document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
                       document.getElementById('divCreditosFacturas').style.display                    = 'none';      
                       document.getElementById('divPedidoProductos').style.display                     = 'block'; 
                       document.getElementById('divPedidoListado').style.display                       = 'none'; 
            
        
               setTimeout($.unblockUI, 1000);  
       
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 
	 
	 
	 
	 
	 
 }
};          
        

        
$scope.verFolioEliminarFolio =  function(ped){
    
    $scope.observacionMotivoCredito = ped.obser_transp;
    $scope.nombreMotivoCredito      = ped.nombre;
    $scope.folioCredito             = ped.folio;
    $scope.pedidoCredito            = ped.id_pedido;

    
    $("#myModalObserPedidoAnularDespacho").modal();

  //  $("#myModalObserPedidoAnularDespacho").modal("hide");
    
}        
        
        
        
        
$scope.confirmarEliminarFolio = function () { 
    
if($scope.observacionMotivoCredito !=""){

    alertify.confirm("Esta seguro que desea eliminar FOLIO:"+$scope.folioCredito+" del pedido.?", function (e) {
        if (e) {
            $scope.eliminarFolioSistema();            
        } 
    });
    
}else{
    
    alertify.alert("Ingresar observacion para proceder a eliminar folio.");
}
    
};      
        
        
$scope.eliminarFolioSistema = function(ped){    
           $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=eliminarFolioPedido&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:$scope.pedidoCredito,
                             idFolio:$scope.folioCredito,
                             observacion: $scope.observacionMotivoCredito, 
                             id_usuario:$scope.idUsuarioLogin }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
           
               var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                
                    // $scope.detPedido.splice(prodIndex, 1);
                    alertify.success("Folio eliminado con exito");
                    $scope.verDetallePedidoSalir();
                    
                }else{
                    alertify.error("Error al eliminar folio, favor contactar con el Administrador del Sistema.");
                }
           
          }).error(function(error){
                        console.log(error);
    });   
};
        
  
        
        
$scope.guardarObservacionCredito = function(obsCLie, cli){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=guardarObsCredito&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idCred:cli.id,
                             obsCredito:obsCLie                       
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
        
        
        
$scope.confirmarTransportePedido = function () { 

    alertify.confirm("Esta seguro que desea cambiar estado a Entregados?", function (e) {
        if (e) {
           
            $scope.isAnythingSelectedEntregado();
            
        } 
    });    
        
       
};   
        
        
 $scope.precioUsuaDesc = function(){
$http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=precioUsuaDesc&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idUsua:$scope.idUsuarioLogin                       
                            }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);               
                var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="0"){
                    alertify.custom = alertify.extend("custom");
                    alertify.custom("Para poder realizar descuento, favor contactar con supervisora de Ventas.");
                    $scope.prodPrecioDesc = 0;
                }
          

          }).error(function(error){
                console.log(error);
        });
}          
        
        
$scope.isAnythingSelectedEntregado = function () {
    var pedido="";
    var pedidoNota="";
    var banderaSel=false;
    
    
    var arrCobro = [];
           
    
    
    for(var i = 0; i < $scope.listPedidosTransp.length; i++){
        if($scope.listPedidosTransp[i].isRowSelected == true){
            banderaSel=true;
            var objCobro= new Object();            
            objCobro.pedido          =$scope.listPedidosTransp[i].id_pedido;
            objCobro.creditoClie     = $scope.listPedidosTransp[i].credito; 
            objCobro.creditoClie     = $scope.listPedidosTransp[i].credito; 
            objCobro.idUsuario       = $scope.idUsuarioLogin;         
            arrCobro.push(objCobro);               
        }
    }
    
    var objCob= JSON.stringify(arrCobro);    
    //$scope.pedidoSelecNota= pedidoNota;
    
 if(!banderaSel){     
     alertify.alert("Para poder realizar el cambio de estado, por favor seleccionar pedidos.");     
 }else{    
    
	
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=modificarEstadoMasivoTransp&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({arrEstado:objCob
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		         $scope.buscarPedidoDatos();
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 
	 
	 
	 
	 
	 
 }
};       

 
  $scope.borrarImagenTransf = function(ped){     
         $("#modal-image").attr("src", '');
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=borrarImagenTransf&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPed:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          var respuesta = data.charAt(data.length-1);
                
                if(respuesta=="1"){
                    alertify.success("Imagen Borrado con exito!");
                    $scope.estImgTranf  = 0;
                    $scope.actulizarDatoAdjunto('no');
                }else{
                    alertify.error("Error al borrar imagen transferencia, favor contactar con el Administrador del Sistema.");
                }
          
              
          }).error(function(error){
                        console.log(error);
    
    });        
        
};
        
        
        
        
        
        
        

        
$scope.imprimirBoletaElect = function(pedSel){
    
  $scope.despDomPart    = pedSel.estado_cobro_dep;
    
   var arrayDeta     = []; 
   var iva           = 0.19;
   var total         = 0;
   var obSelect      = "";
   var banderaOferta = false;
  
   $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.detPedido = data;
      
      /**********VALIDAR PRODUCTO OFERTAR PARA DESCUENTO **************/
       
             for(var t=0; t < $scope.lisProdOfert.length; t++){
                for (var p=0; p < $scope.detPedido.length; p++){
                    if($scope.lisProdOfert[t].id_prod == $scope.detPedido[p].id_prod ){
                           banderaOferta = true;
                        break;
                       }
                    
                    
                }
             }
       
    /******************************/    
                       
                  for (var i = 0; i <    $scope.detPedido.length; i++) {          
                         var objDeta= new Object();   
                        objDeta.banderaOferta  =  banderaOferta;
                        objDeta.pedido         =  '';
                        objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
                        objDeta.cantidad       =  $scope.detPedido[i].cantidad;
                        objDeta.idPedido       =  pedSel.id_pedido;
                      
                      
                       if(pedSel.folio!=null){
                            objDeta.folio      =  pedSel.folio;
                        }else{
                            objDeta.folio      =  pedSel.id_pedido;
                        }
                      
                      
       
                      
                        total               = 0;  
                        total               =  Math.round($scope.detPedido[i].precio_vendido * $scope.detPedido[i].cantidad);    
                        var totalNeto       = (Math.round(total));
                        total               = Math.round((Math.round(total) * iva) + Math.round(totalNeto));       
                        objDeta.Precio      = formatNumero((total.toString()));
                        objDeta.TotalBol    = formatNumero($scope.getTotalPedido().toString());
                        $scope.despDomPart  = "0";
                      
                     
                        
                          
                          switch(pedSel.estado_cobranza) {
                             case '6'://Transferencia
                                obSelect = 'Transferencia';
                                break;
                             case '1'://Efectivo
                                obSelect = 'Efectivo';
                                break;
                             case '5'://transBank
                                obSelect = 'Transbank';
                                break;
                            default:
                                  ''
                          }
                          
                        objDeta.FormaPago    = obSelect;
                      
                      
                        arrayDeta.push(objDeta);
                     }
      
      
      
            if(pedSel.estado_cobro_dep == "1"){
                
                
                        var objDeta= new Object();           
                        objDeta.pedido         = '';
                        objDeta.nombreProd     =  "Despacho a Domicilio";
                        objDeta.cantidad       =  1;
                        objDeta.idPedido       =  1;
                        objDeta.folio          =  1;                      
                        total               = 2000; 
                        objDeta.Precio      = formatNumero((total.toString()));
                        objDeta.TotalBol    = 2000;
              
                          
                        objDeta.FormaPago    = '';
                
                   
                        arrayDeta.push(objDeta);
            }
      

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         url : '//192.168.1.128/admin/FunctionIntranet.php?act=imprimirTermicaBoletaElect&tienda='+$scope.tiendaSeleccionada,
                    
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
       
}        
        
$scope.imprimirTermicaBodega = function(pedSel){
   var arrayDeta     = [];   
  $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
              data:  $.param({idPedido:pedSel.id_pedido}),
             headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
                $scope.detPedido = data;
                  for (var i = 0; i <    $scope.detPedido.length; i++) {          
                  var objDeta= new Object();           
                   objDeta.pedido         = '';
                        objDeta.nombreProd     =  $scope.detPedido[i].nombreProd;
                        objDeta.cantidad       =  $scope.detPedido[i].cantidad;
                        objDeta.bodega         =  $scope.detPedido[i].bodega;

                      
                        objDeta.idPedido       =  pedSel.id_pedido;
                        arrayDeta.push(objDeta);
             }

            var jsonData=angular.toJson(arrayDeta);    
            var objectToSerialize={'detalle':jsonData};

               $http({
                         method : 'POST',
                         url : '//192.168.1.128/admin/FunctionIntranet.php?act=imprimirTermica&tienda='+$scope.tiendaSeleccionada,
                    
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
       
} 


$scope.imprimirComprobante= function(dataPed){
    
    if(dataPed.tipo_folio == "Fac"){
       
        alertify.confirm("Esta seguro que desea descargar factura, luego imprimir ?", function (e) {
        if (e) {
         $scope.obtenerPDF(dataPed);  
        }
         }); 
        
    }else{
       
        alertify.confirm("Esta seguro que desea imprimir boleta ?", function (e) {
        if (e) {
         //$scope.imprimirBoletaElect(dataPed);  
                     $scope.imprimirBoletaDeVentas(dataPed);  

            
        }
            
       }); 
       
    }
   
}



/*******08-10-2023***********/

$scope.imprimirBoletaDeVentas = function (dataPed) {
    
     $http({
             method : 'POST',
           //  url : 'FunctionIntranet.php?act=boletaTiendaVentasPDF',
              url : '//192.168.1.128/Tienda/FunctionIntranet.php?act=boletaTiendaVentasPDF&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:dataPed.id_pedido, imprimir:'SupervisorTransporte'}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
     







$scope.imprimirBolBodega= function(dataPed){    
       
        alertify.confirm("Esta seguro que desea imprimir tickets retiro bodega ?", function (e) {
        if (e) {
       //  $scope.imprimirTermicaBodega(dataPed);  
            
             $scope.imprimirBoletaBodega(dataPed); 
        }
        
     }); 
   
}


$scope.imprimirBoletaBodega = function (dataPed) {
    
     $http({
             method : 'POST',
             url : 'FunctionIntranet.php?act=ticketsRetiroBodega&tienda='+$scope.tiendaSeleccionada,
             data:  $.param({idPedido:dataPed.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
       
          }).error(function(error){
                        console.log("error al Anular Pedido: "+data);
    
    }); 

};        
    





$scope.guadarProductoFotos = function(){
    
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    
      var arrObj = [];
      var objProd= new Object();

      objProd.foto          =  $scope.fotos;
      objProd.idPed         =  $scope.idPedido; 

    
      
      arrObj.push(objProd);
    
      var objCabe    = JSON.stringify(arrObj);   
     
    
    $http({
            method : 'POST',
            url : 'FunctionIntranet.php?act=saveProductoModFotoTransf&tienda='+$scope.tiendaSeleccionada,
            data:  $.param({objProdutos:objCabe}),
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          var respuesta = data.charAt(data.length-1);
                    
            if(respuesta=="1"){            
                 setTimeout($.unblockUI, 1000);
                alertify.success("Imagen adjuntado sin ningun problema!");
                $scope.precioPart ="";
                document.getElementById("fotos").value  = "";
                $scope.estImgTranf  = 1;
                $scope.actulizarDatoAdjunto('si');
                 
                
            }else{
                alertify.error("Error al adjuntar imagen, favor contactar con el Administrador del Sistema.");
            }
        
        
          }).error(function(error){
                        console.log(error);
    
    });   
};
          
$scope.verTransfAdj= function(idPedidoSel){
   $scope.idPedido     =  idPedidoSel.id_pedido; 
   $scope.estImgTranf  =  idPedidoSel.estadoFotoTransf;  
   $scope.nombPedido   =  idPedidoSel.nombre;  

}

 $scope.mostrarImagenTransfAdj = function(){     

     
           $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
     
         $("#modal-image").attr("src", '');
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=mostrarImagenTransf&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPed:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          $("#modal-image").attr("src", data[0].img_transf);
                   setTimeout($.unblockUI, 1000); 
          }).error(function(error){
                        console.log(error);
    
    });        
        
};


  $scope.mostrarImagenTransf = function(ped){   
  $scope.idPedido     =  ped.id_pedido; 
  $scope.nombPedido  =  ped.nombre; 
      
      
              $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
         $("#modal-image").attr("src", '');
     $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=mostrarImagenTransf&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPed:ped.id_pedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          $("#modal-image").attr("src", data[0].img_transf);
                setTimeout($.unblockUI, 1000); 
          }).error(function(error){
                        console.log(error);
    
    });        
        
};
        

        
$scope.confEliminarClie = function(indCli){
    
  alertify.confirm("Esta seguro que desea eliminar "+indCli.nombre+" de la ruta ? ", function (e) {
        if (e) {
            $scope.eliminarClienteRuta(indCli);            
        } 
    });    
   
};
            
        
        
$scope.listarPedidoSel = function(selProd){
    $scope.nombreClie = selProd.nombre;
    $scope.idPedSeleccionar = selProd.id_pedido;

}

        
$scope.eliminarClienteRuta = function(){    
  
       for(var i = 0; i < $scope.listPedidosRutaTransp.length; i++){
        if($scope.listPedidosRutaTransp[i].id_pedido == $scope.idPedSeleccionar){
                   $scope.listPedidosRutaTransp[i].id_sector = 0;
             
            break;
        }
    }
}          
        
        
$scope.actulizarDatoAdjunto = function(actionT){    
    for(var i = 0; i < $scope.listPedidos.length; i++){
        if($scope.listPedidos[i].id_pedido == $scope.idPedido){
              if(actionT == 'si'){
                   $scope.listPedidos[i].estadoFotoTransf = 1;
               }else{
                    $scope.listPedidos[i].estadoFotoTransf = 0;
               }
           
            break;
        }
    }        
};
        
        
        
$scope.guardarPedidoSel = function(){    
    var sectorSel = parseInt($scope.diaSemana.selectedOption.id);
       for(var i = 0; i < $scope.listPedidosRutaTransp.length; i++){
        if($scope.listPedidosRutaTransp[i].id_pedido == $scope.idPedSeleccionar){
                   $scope.listPedidosRutaTransp[i].id_sector = sectorSel;
             
            break;
        }
    }
}  


$scope.listarPedidoSel = function(selProd){
    $scope.nombreClie = selProd.nombre;
    $scope.idPedSeleccionar = selProd.id_pedido;

}
        
       
$scope.verRutaTransporte = function(){
    
    if($scope.listPedidos.length!=0){
        
      $scope.listPedidosRutaTransp                                                 =  $scope.listPedidos;     
      document.getElementById('divPedidoListado').style.display                    = 'none';      
      document.getElementById('divPedidoTransporteRuta').style.display             = 'block';   
       
      }else{
               alertify.alert("Para generar una Ruta, favor listar pedidos");   
      }
    
  

}
        

$scope.getTotalPesosRutasSur = function(){
var total = 0;

for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];
    
       if(product.id_sector == '2' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += Math.round(product.pesos);
          }
    
       
}

var total = (Math.round(total));
return Math.round(total);
};    

        
$scope.getTotalPesosRutasNorte = function(){
var total = 0;

for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];
    
       if(product.id_sector == '1' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += Math.round(product.pesos);
          }
    
       
}

var total = (Math.round(total));
return Math.round(total);
};        
        
        
$scope.getTotalPesosRutasCentro = function(){
var total = 0;

for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];
    
       if(product.id_sector == '3' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += Math.round(product.pesos);
          }
}

var total = (Math.round(total));
return Math.round(total);
};     
        
        
$scope.getTotalPesosRutasCentro2 = function(){
var total = 0;

for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];
    
       if(product.id_sector == '4' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += Math.round(product.pesos);
          }
}

var total = (Math.round(total));
return Math.round(total);
};         
   
$scope.getTotalCantidadRutasCentro2 = function(){
var total = 0;
for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];    
       if(product.id_sector == '4' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += 1;
          }
}
var total = (Math.round(total));
return Math.round(total);
};                
        
        
$scope.getTotalCantidadRutasCentro = function(){
var total = 0;
for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];    
       if(product.id_sector == '3' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += 1;
          }
}
var total = (Math.round(total));
return Math.round(total);
};        
        
        
$scope.getTotalCantidadRutasNorte = function(){
var total = 0;
for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];    
       if(product.id_sector == '1' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += 1;
          }
}
var total = (Math.round(total));
return Math.round(total);
};          
        
$scope.getTotalCantidadRutasSur = function(){
var total = 0;
for(var j = 0; j < $scope.listPedidosRutaTransp.length; j++){
        var product = $scope.listPedidosRutaTransp[j];    
       if(product.id_sector == '2' && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
           total += 1;
          }
}
var total = (Math.round(total));
return Math.round(total);
};             
        
        
        
        
 /*******************Generar Ruta en GENERAL*************************************/      
        
$scope.cofGenerarRutaMasivo = function(idTransp, idSector){
    
    if(idTransp.toString() == "1"){
        $scope.idTransporteRuta = "Transporte AZUL";
    }else if (idTransp.toString() == "2"){
        $scope.idTransporteRuta = "Transporte ROJO";
    }else if (idTransp.toString() == "3"){
        $scope.idTransporteRuta = "Transporte VERDE";
    }else if (idTransp.toString() == "4"){
        $scope.idTransporteRuta = "Transporte AMARILLO";
    }
    
      alertify.confirm("Esta seguro que desea generar ruta " + $scope.idTransporteRuta + " ?", function (e) {
        if (e) {
         $scope.isAnythingSelectedRutaMasivo(idTransp, idSector);
            
            
        } else {
            // user clicked "cancel"
        }
    }); 
}        
		




$scope.isAnythingSelectedRutaMasivo = function (idTransp, idSector) { 
    
if(idSector==1){    
      angular.element(document.getElementById('btnTransp1Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp1Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp1Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp1Amarillo'))[0].disabled = true;

    
}else if(idSector==3){       
      angular.element(document.getElementById('btnTransp2Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Amarillo'))[0].disabled = true;

} else if(idSector==2){  
    angular.element(document.getElementById('btnTransp3Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Amarillo'))[0].disabled = true;
    
}else if(idSector==4){  
      angular.element(document.getElementById('btnTransp4Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Amarillo'))[0].disabled = true;

}
    
    
    
if(idTransp==1){    
      angular.element(document.getElementById('btnTransp1Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Azul'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Azul'))[0].disabled = true;

    
}else if(idTransp==2){       
      angular.element(document.getElementById('btnTransp1Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Rojo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Rojo'))[0].disabled = true;
    
} else if(idTransp==3){      
      angular.element(document.getElementById('btnTransp1Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Verde'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Verde'))[0].disabled = true;

}else if(idTransp==4){      
      angular.element(document.getElementById('btnTransp1Amarillo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp2Amarillo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp3Amarillo'))[0].disabled = true;
      angular.element(document.getElementById('btnTransp4Amarillo'))[0].disabled = true;

}
    
    
    
    
    
    
    
    
          $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 

    

    
	var pedido="";
    var banderaSel=false;
	var arrayDeta=[];
	$scope.rutaTransp = [];
	
    for(var i = 0; i < $scope.listPedidosRutaTransp.length; i++){
        var product = $scope.listPedidosRutaTransp[i];   
        if(product.id_sector == idSector && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
            banderaSel=true;
            pedido += ','+product.id_pedido;			
			  var objDeta= new Object();           
                 objDeta.idPedido    = product.id_pedido;
                 objDeta.ruta        = "";//$scope.listPedidos[i].orden_ruta;
                 objDeta.id_transp   = idTransp.toString();
              arrayDeta.push(objDeta);

        }
    }
	
	var objDetalles= JSON.stringify(arrayDeta); 
	
	 if(!banderaSel){     
        alertify.alert("Para generar una Rota, favor seleccionar pedidos");     
	 }else{    

	
		 
		$http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinRuta&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1), 
                                        arrayRuta:objDetalles, 
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
               $scope.rutaTransp = data;
			   $scope.generarReporteRutaPDF();
               setTimeout($.unblockUI, 1000);  
               //$scope.buscarPedido('');

        
          }).error(function(error){
                        console.log(error);
    
      });		 
	 
		 
	 }
};		
		        
        
        
        
              
$scope.isAnythingSelectedMasivoRuta = function (idSector) {
    var pedido="";
    var pedidoNota="";
    var banderaSel=false;
    for(var i = 0; i < $scope.listPedidosRutaTransp.length; i++){
         var product = $scope.listPedidosRutaTransp[i];   
        
       if(product.id_sector == idSector && product.estado_pedido == '3' && product.entregaPed == '1' && product.id_transp=='0'){
            banderaSel=true;
            pedido += ','+product.id_pedido;
            pedidoNota += ',  '+product.id_pedido;
        }
    }
    
    $scope.pedidoSelecNota= pedidoNota;
    
 if(!banderaSel){     
     alertify.alert("Para generar el listado de productos, favor seleccionar pedidos");     
 }else{    
    
	
	    $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=joinPedidos&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({pedido:pedido.substr(1)
                                       }),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);
		           $scope.pesosProductos(pedido);
                   $scope.unirPedidos = data;
                   $("#myModalJoinPedidoMasivo").modal();
        
          }).error(function(error){
                        console.log("error al unir Pedidos: "+data);
    
      });	 
	 
	 
	 
	 
	 
 }
};            
        
        

$scope.verDetallePedidoSalirMasivo = function(){    
      angular.element(document.getElementById('btnTransp1Azul'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp1Rojo'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp1Verde'))[0].disabled = false;    
      angular.element(document.getElementById('btnTransp1Amarillo'))[0].disabled = false;    
    
      angular.element(document.getElementById('btnTransp2Azul'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp2Rojo'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp2Verde'))[0].disabled = false;     
    angular.element(document.getElementById('btnTransp2Amarillo'))[0].disabled = false;    

    
    
      angular.element(document.getElementById('btnTransp3Azul'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp3Rojo'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp3Verde'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp3Amarillo'))[0].disabled = false;    
    
    
      angular.element(document.getElementById('btnTransp4Azul'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp4Rojo'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp4Verde'))[0].disabled = false;
      angular.element(document.getElementById('btnTransp4Amarillo'))[0].disabled = false;  
    
      document.getElementById('divPedidoTransporteRuta').style.display      = 'none';      
      document.getElementById('divPedidoListado').style.display             = 'block';     
       $scope.buscarPedido('');  
}



$scope.recorrerListado = function(pedido, tipo){
    var ti="";
    if(tipo == "33"){
       ti="Fac";
    }else{
       ti="Bol"; 
    }
    
    
     for(var i = 0; i < $scope.listPedidos.length; i++){
			if($scope.listPedidos[i].id_pedido == $scope.idPedido ){	
                
                $scope.listPedidos[i].folio      = pedido.replace(/\s/g, "");
                $scope.listPedidos[i].tipo_folio = ti;
                $scope.listPedidos[i].identificacion = tipo; 
			}		
          }
 }
        
   
$scope.conultarNuboxFolioFactura = function(){     
    $scope.estadoNuboxConsultar = false;
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarNuboxPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
        //  var respuesta = data.charAt(data.length-1);
               var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                    
                    alertify.alert("Pedido ya se encuentra con numero Folio, favor contactar con el administrador.");
                   
                }else{
                   // $scope.generarFactura();   
                    
                    $scope.confirmarFactura();
                }     
          }).error(function(error){
                        console.log(error);
    
    });  
       
   // return $scope.estadoNuboxConsultar;   
        
};   
        
        

        
$scope.confirmarActivePago = function(prodDet){
    
    
	var length = $scope.listPedidos.length; 
	var objProd = new Object();	
	 for(var i = 0; i < length; i++) {
     /* objProd = $scope.listInformeTranspDeta;*/
       if($scope.listPedidos[i].id_pedido == prodDet.id_pedido){
           
           if($scope.listPedidos[i].folio == "" || $scope.listPedidos[i].folio == null){
                $scope.listPedidos[i].tipoDocAct  = 1;	
           }else{
               alertify.alert("No se puede realizar accion, porque tiene folio asociado.");
           }
           
           break;
       }             
    }

    
};	        
        
        
        
$scope.confirmarActiveProd = function(prodDet, selcProd){
	
            $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=cambiaEstadoTipoDoc&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:prodDet.id_pedido,
                                        idTipoDoc: selcProd}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                   
                     alertify.alert("Se modifica tipo de documento.");
                    
                }  else { 

                    
                    
                }   
              
          }).error(function(error){
                        console.log(error);
    
    });  
    
    
};	        
        


$scope.conultarNuboxFolioBoleta = function(){     
    $scope.estadoNuboxConsultar = false;
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarNuboxPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                     alertify.alert("Pedido ya se encuentra numero folio, favor contactar con el administrador.");
                    //$scope.estadoNuboxConsultar = true;
                }else{
                    $scope.confirmarBoleta();
                 //   $scope.generarBoleta();
                }          
              
          }).error(function(error){
                        console.log(error);
    
    });  
       
   // return $scope.estadoNuboxConsultar;   
        
};      
       
        
        
$scope.conultarNuboxNumeroFolio = function(prod, prodIndex){     
    $scope.estadoNuboxConsultar = false;
         $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=consultarNuboxPedido&tienda='+$scope.tiendaSeleccionada,
                        data:  $.param({idPedido:$scope.idPedido}),
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
          }).success(function(data){
                console.log(data);   
         
          var respuesta = data.trim();
                if(respuesta.toString()=="1"){  
                   
                     alertify.alert("Pedido ya se encuentra numero folio, favor contactar con el administrador.");
                    
                }  else { 
                
                $scope.deleteProdListadoConf (prod, prodIndex);
                }   
              
          }).error(function(error){
                        console.log(error);
    
    });  
       
   // return $scope.estadoNuboxConsultar;   
        
};         
        
        
$scope.listarProductoOfertaDesc = function(){	

	 $http({
                        method : 'POST',
                        url : 'FunctionIntranet.php?act=listarOfertasProductosDescuento&tienda='+$scope.tiendaSeleccionada,
                        headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

          }).success(function(data){
                        console.log(data);
          $scope.lisProdOfert    = []; 
       	  $scope.lisProdOfert    = data;		 

          }).error(function(error){
                        console.log(error);
    });   	
};      
        
        
$('#divPedidoProductos').on('click', 'tbody tr', function(event) {
  $(this).addClass('highlight').siblings().removeClass('highlight');
});
        
        
        
     
  
              
        
        
        
$scope.verDetallePedidoParaNC = function(pedidoIndex){
 // alert(pedidoIndex.folio);
   
    $scope.mostGenerarBol          = pedidoIndex.folio;
      $scope.mostCredito             = pedidoIndex.credito;
  
    
    $scope.btnGenerarFactura = false;    
    $scope.limpiarProdList();
        
    $scope.idProducto="";
        
    $scope.stadoPed = false;
    $scope.stadoPedEntreg = false;
    
    if(pedidoIndex.estado_pedido == "4" || pedidoIndex.estado_pedido == "2" || pedidoIndex.estado_pedido == "1" || pedidoIndex.estado_pedido == "5"){       
        $scope.stadoPed = true;
    }
    
    if(pedidoIndex.estado_pedido == "4"){       
        $scope.stadoPedEntreg = true;
    }
        
        
        
        
        document.getElementById('divCreditosFacturas').style.display      = 'none';   
        document.getElementById('divPedidoNC').style.display           = 'block';    
        
        var rutAux                 = pedidoIndex.rut;    
        var digVirif               = rutAux? rutAux.charAt(rutAux.length-1): '';    
        $scope.detPedido           = []; 
        $scope.listPedidosIndex    = [];  
        $scope.listPedidosIndex     = pedidoIndex;    
        $scope.listPedidosIndex.rut = $.Rut.formatear($scope.listPedidosIndex.rut,digVirif);
        
        $scope.rutModificar =  pedidoIndex.rut;
        $scope.razonMod     =  pedidoIndex.nombre;
        $scope.giroMod      =  pedidoIndex.giro;
        $scope.direccionMod =  pedidoIndex.direccion;
        $scope.idPedido     =  pedidoIndex.id_pedido;    
        
    
        $scope.tipPedGenerar             =  pedidoIndex.id_tipo_ped;
        
        
        $scope.mostBotFact          = true;
        $scope.mostBotBol           = true;
        
        if($scope.tipPedGenerar == "1"){
            $scope.mostBotFact = true;
            $scope.mostBotBol  = false;
        }else if($scope.tipPedGenerar == "2"){
            $scope.mostBotFact = false;
            $scope.mostBotBol  = true;
        }else if($scope.tipPedGenerar == "3"){
            $scope.mostBotFact = false;
            $scope.mostBotBol  = false;
        }else if($scope.tipPedGenerar == "4"){
            $scope.mostBotFact = false;
            $scope.mostBotBol  = false;
        }
        
        
    /*  $http.get('Funciones.php?act=listarDetallePedido&idPedido='+pedidoIndex.id_pedido).success(function(data) {
                    console.log(data);                
                    $scope.detPedido = data;
                }).
                error(function(data, status, headers, config) {
                    console.log("error Listar Detalle Pedido: "+data);
        }); */
        
        
        
        $http({
                            method : 'POST',
                            url : 'FunctionIntranet.php?act=listarDetallePedido&tienda='+$scope.tiendaSeleccionada,
                            data:  $.param({idPedido:pedidoIndex.id_pedido}),
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
            }).success(function(data){
                    
                    $scope.detPedido = data;
            
            }).error(function(error){
                            console.log("error Listar Detalle Pedido: "+error);
        
        });
        
    
    }
    
    $scope.deleteProdListadoNC = function(prodIndex){    
        $scope.detPedido.splice(prodIndex, 1);
        alertify.success("Producto eliminado con exito");
    };
       
    $scope.generarNotaCredito = function(){
    
        let mydate=new Date();
        let year=mydate.getYear();
        if (year < 1000)
        year+=1900;
        let day=mydate.getDay();
        let month=mydate.getMonth()+1;
        if (month<10)
        month="0"+month;
        let daym=mydate.getDate();
        if (daym<10)
        daym="0"+daym;
            
            
        let rutAux=$.Rut.quitarFormato($scope.listPedidosIndex.rut);
        let digito=rutAux.substr(-1);  
        let largo = rutAux.length-1;
        
        let res = rutAux.substring(0, largo);
        
        
        let TpoDTE        = "61";
        let FchEmision    = year+"-"+month+"-"+daym+"T00:00:00.000Z";
        let Rut           = res+'-'+digito;
        let RznSoc        = $scope.listPedidosIndex.nombre;
        let Giro          = $scope.listPedidosIndex.giro.substr(0,35);
        let Comuna        = "ARICA";
        let Direccion     = $scope.listPedidosIndex.direccion.replace("-"," ");
        let Email         = "";
        let IndTraslado   = "";
        let ComunaDestino = "";
        let Vendedor      = $scope.listPedidosIndex.nombreUsuario;     
            
        let FormaPago = 0;
        let TerminosPagos = 1;
            
        if( $scope.listPedidosIndex.credito == '0' || $scope.listPedidosIndex.credito == '1' ){
                FormaPago      = 1; 
        } else {
                FormaPago      = 2; 
                TerminosPagos  = $scope.listPedidosIndex.credito; 
        }
            
            
        let arrayDeta     = [];
                 
            
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
            
        for (i = 0; i <    $scope.detPedido.length; i++) {   
                   
            let objDeta = {
                rutContraparte: Rut.length > 4? Rut: '55555555-5', //PARA BOLETA
                razonSocialContraparte: RznSoc,
                giroContraparte: Giro  || 'Particular', //PARA BOLETA
                comunaContraparte: Comuna,
                direccionContraparte: Direccion,
                emailContraparte: Email,
                tipo: TpoDTE, 
                folio: 1,
                secuencia: i + 1,
                fecha: FchEmision,
                afecto: "SI",
                vendedor: Vendedor,
            };
            objDeta.producto        =  $scope.detPedido[i].nombreProd.substr(0,35).replace("+"," ");  //$scope.detPedido[i].nombreProd;
            objDeta.descripcion     =  "";
            objDeta.cantidad        =  $scope.detPedido[i].cantidad;
            objDeta.valor           =  $scope.detPedido[i].precio_vendido;
            objDeta.codigoItem      =  $scope.detPedido[i].codProducto;
            
            arrayDeta.push(objDeta);
                   
        }

        let refObj = {
            tipo: TpoDTE, 
            folio: 1,
            secuencia: 1,
            tipoDocumentoReferenciado: $scope.listPedidosIndex.id_folio > 50000? 33: 39,
            folioDocumentoReferenciado: $scope.listPedidosIndex.id_folio,
            fechaDocumentoReferenciado: $scope.listPedidosIndex.f_anulacion,
            motivoReferencia: 1,
            glosa: "ANULA DOCUMENTO"
        };
                   
               
              
                
                    
        $http({method : 'POST',
                    url : 'Funciones.php?act=generarNotaCreditoSII',
                    data:  $.param({data: {productos: arrayDeta, documentoReferenciado: refObj, id_credito: $scope.listPedidosIndex.id}}),                                
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'}  
        }).success(function(data){
            
            if(data == "OK"){
                alertify.set({ delay: 10000 });
                 alertify.success("Nota de Cr&eacute;dito emitida con exito!");
                 $scope.buscarPedido('');         
                 $scope.verDetallePedidoNCSalir();
                 $scope.detPedido           = [];     
                 $scope.listPedidosIndex    = [];
                 $scope.verListadoCreditoFactura(); 
            } else {
                alertify.set({ delay: 10000 });
                alertify.error("No se pudo emitir la Nota de Cr&eacute;dito!<br>: "+data);
            }

            setTimeout($.unblockUI, 1000);
        }).error(function(error){
            console.log("error Listar Detalle Pedido: "+error);

            setTimeout($.unblockUI, 1000);
        });
    }
    
    $scope.verDetallePedidoNCSalir = function(){

        
                    
            
                       document.getElementById('divPedido').style.display                              = 'none';
                       document.getElementById('divPedidoNC').style.display                            = 'none';      
                       document.getElementById('divPedidoTransporteRuta').style.display                = 'none';      
                       document.getElementById('divCreditosFacturas').style.display                    = 'block';      
                       document.getElementById('divPedidoProductos').style.display                     = 'none'; 
                       document.getElementById('divPedidoListado').style.display                       = 'none'; 
            
        
        
        
        
        
        $scope.mostBotFact  = true;
    }

    $scope.obtenerPDFNC = function (pedidoDet) {

        $.blockUI({ css: { 
           border: 'none', 
           padding: '15px', 
           backgroundColor: '#000', 
           '-webkit-border-radius': '10px', 
           '-moz-border-radius': '10px', 
           opacity: .5, 
           color: '#fff' 
       } }); 
   
   
  var config = {
               url: 'Funciones.php?act=getPDFNC',
               data:  $.param({folio:pedidoDet.folio_nota}),
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
           var filename ="notacredito_"+pedidoDet.folio_nota+".pdf";          

           linkElement.setAttribute('href', url);
           linkElement.setAttribute("download", filename);
        
           var clickEvent = new MouseEvent("click", {
               "view": window,
               "bubbles": true,
               "cancelable": false
           });
           linkElement.dispatchEvent(clickEvent);
             setTimeout($.unblockUI, 1000);   
       } catch (ex) {
           console.log(ex);
       }
           
       }, function errorCallback(response) {
           console.log("error");
       });  
};
        
    
}]);