'use strict';


// Declare app level module which depends on filters, and services
/*angular.module('myApp', [
    'ngRoute',
    'myApp.filters',
    'myApp.services',
    'myApp.directives',
    'myApp.controllers'
]).
    config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/type', {templateUrl: 'app/partials/type.html', controller: 'TypeCtrl'});     
		
        $routeProvider.when('/ListadoPedidos', {templateUrl: 'app/partials/ListadoPedidos.html', controller: 'controllerListadoPedido'});
        $routeProvider.when('/IngresarStock', {templateUrl: 'app/partials/IngresarStock.html', controller: 'controllerIngresarStock'});

    }]);*/


// Creación del módulo
var angularRoutingApp = angular.module('myApp', ['ngRoute','ngCookies','progress.bar']);

var versionHtml = 3;

// Configuración de las rutas
angularRoutingApp.config(function($routeProvider, $locationProvider) {

    
    $routeProvider
        .when('/ListadoPedido', {
              templateUrl: 'app/partials/ListadoPedido.html?ver=26', 
               controller: 'controllerListadoPedido'
        })
         
        .when('/IngresarStock', {
          templateUrl: 'app/partials/IngresarStock.html?ver=26', 
            controller: 'controllerIngresarStock'
        })
       .when('/Pedidos', {
             templateUrl: 'app/partials/Pedidos.html?ver=26', 
            controller: 'controllerPedido'
        })
       .when('/Productos', {
             templateUrl: 'app/partials/Productos.html?ver=26', 
            controller: 'controllerProductos'
        })
       .when('/ListadoIngresos', {
             templateUrl: 'app/partials/ListadoIngresos.html?ver=26', 
            controller: 'controllerListadoIngreso'
        })
        .when('/Cliente', {
             templateUrl: 'app/partials/Cliente.html?ver=26', 
            controller: 'controllerCliente'
        })
        .when('/Inventario', {
             templateUrl: 'app/partials/Inventario.html?ver=26', 
            controller: 'controllerInventario'
        })
        .when('/Login', {
          templateUrl: 'app/partials/Login.html?ver=26', 
            controller: 'controllerLogin'
        })
        .when('/Caja', {
          templateUrl: 'app/partials/Caja.html?ver=26', 
            controller: 'controllerCaja'
        })
        .when('/ListadoVentas', {
          templateUrl: 'app/partials/ListadoVentas.html?ver=26', 
            controller: 'controllerListadoVentas'
        })
        .when('/UsuarioInforme', {
          templateUrl: 'app/partials/UsuarioInforme.html?ver=26', 
            controller: 'controllerUsuarioInforme'
        })
            .when('/Talonario', {
          templateUrl: 'app/partials/Talonario.html?ver=26', 
            controller: 'controllerTalonario'
        })
        .when('/InformeVenta', {
          templateUrl: 'app/partials/InformeVenta.html?ver=26', 
            controller: 'controllerInformeVenta'
        })
      .when('/InformeTransporte', {
          templateUrl: 'app/partials/InformeTransporte.html?ver=26', 
            controller: 'controllerInformeTransporte'
        })
      .when('/ConsultarProducto', {
          templateUrl: 'app/partials/ConsultarProducto.html?ver=26', 
            controller: 'controllerConsultarProducto'
        })  
      .when('/InformeRutaVendedor', {
          templateUrl: 'app/partials/InformeRutaVendedor.html?ver=26', 
            controller: 'controllerInformeRutaVendedor'
        }) 
          .when('/TalonarioRecibo', {
             templateUrl: 'app/partials/TalonarioRecibo.html?ver=26', 
            controller: 'controllerTalonarioRecibo'
        })
        .when('/ListadosMantencionTransporte', {
             templateUrl: 'app/partials/ListadosMantencionTransporte.html?ver=26', 
            controller: 'controllerListadosMantencionTransporte'
        })
    
       .when('/CierresCajas', {
             templateUrl: 'app/partials/CierresCajas.html?ver=26', 
            controller: 'controllerCierresCajas'
        })

        .when('/CierreCajaCentralizada', {
          templateUrl: 'app/partials/CierreCajaCentralizada.html?ver=26', 
         controller: 'controllerCierreCajaCentralizada'
        })
        .when('/Descuento', {
            templateUrl: 'app/partials/Descuento.html',
            controller: 'descuentoController'
        })
       .when('/ListInformeTransporte', {
            templateUrl: 'app/partials/ListInformeTransporte.html',
            controller: 'controllerListInformeTransporte'
        })
        .when('/ListMarcaProductos', {
            templateUrl: 'app/partials/ListMarcaProductos.html',
            controller: 'controllerListMarcaProductos'
        })
        .otherwise({
            redirectTo: '/Login'
        });

});


angularRoutingApp.controller('AppCtrl', ['$scope', '$location', '$rootScope', 'LocalStorage', function ($scope, $location, $rootScope, LocalStorage) {
        $scope.isActive = function (viewLocation) {
            return viewLocation === $location.path();
        };

        $scope.title = "Sistema Control de Inventario";
        $scope.subNav1 = 0;
        $scope.img = "img/iconset-addictive-flavour-set/png/screen_aqua_glossy.png";
        $scope.showTopToggle = false;

    $scope.$watch("sesion", function () {
        console.log($rootScope.sesion);
        if ($rootScope.sesion && $rootScope.sesion.tienda && $rootScope.sesion.datos[0].id) {
            $scope.$parent.tiendaSeleccionada = $rootScope.sesion.tienda;
            $scope.configurarSesion($rootScope.sesion.datos);
        }
    });

    $scope.configurarSesion = function (_value) {
        $("#myModallogininicial").modal("hide");
        /*
        Barros arana: 192.168.1.170
        Santa Maria:  192.168.0.160
        */

        $scope.tiendaIp = "192.168.1.170";

        $scope.idUsuarioLogin = _value[0].id;
        $scope.tipoUsuarioLogin = _value[0].nombre_tipo.toString();
        $scope.idTiendaVenta = _value[0].id_tienda.toString();
        $scope.nombUsua = _value[0].nombre.toString();
        $scope.tipoIdLogin = _value[0].id_tipo.toString();
        $scope.idTransporte = _value[0].idTransporte.toString();

        document.getElementById('nombreLogin').innerHTML = _value[0].nombre;
        document.getElementById('rolLogin').innerHTML = _value[0].nombre_tipo;
        document.getElementById('idVendedor').style.display = 'none';
        document.getElementById('idSupervisorTransportes').style.display = 'none';

        if ($scope.tipoUsuarioLogin == 'Vendedor') {
            /*$scope.initGraficoUsuario();
            $scope.initGraficoUsuarioSema(); */
            $scope.customBotton = true;
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';
            document.getElementById('idVendedor').style.display = 'block';
            $scope.customInter = true;
            document.getElementById('idSupervisorTransportes').style.display = 'none';


            $('#myModalListProd').modal({
                backdrop: 'static',
                keyboard: false
            });


        } else if ($scope.tipoUsuarioLogin == 'Administrador' || $scope.tipoUsuarioLogin == 'Finanzas') {

            $scope.customBottonFinanzasAdministradorSupervisor = true;


            $scope.customBottonFinanzasAdministrador = true;
            if ($scope.tipoUsuarioLogin == 'Finanzas') {
                $scope.customBottonFinanzas = true;
            }


            $scope.customLogiCaja = true;

            // $scope.initGrafico();
            // $scope.initGraficoUsuarioGeneral();
            $scope.customBotton = true;
            //document.getElementById('graficoAdminGeneral').style.display    = 'block';
            // document.getElementById('graficoAdmin').style.display    = 'block';
            $scope.customSelCh = true;
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';
            $scope.customInter = false;
            document.getElementById('idAdministrador').style.display = 'block';

            $scope.customInterProd = false;
            $scope.customInterModifPed = false;
            $scope.customImprimirValeBodega = true;
            $scope.customInterMarcar = true;

            $('#myModalListProd').modal({
                backdrop: 'static',
                keyboard: false
            });
            document.getElementById('idSupervisorTransportes').style.display = 'none';

        } else if ($scope.tipoUsuarioLogin == 'Vendedor Tienda' || $scope.tipoUsuarioLogin == 'Supervisor Caja') {
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'block';
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';
            $scope.customInter = false;
            document.getElementById('idSupervisorTransportes').style.display = 'none';

            $scope.customBottonFinanzas = true;
            $scope.customBotton = true;

        } else if ($scope.tipoUsuarioLogin == 'Bodeguero') {
            document.getElementById('idBodega').style.display = 'block';
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';

            $scope.customInterMarcar = true;

            $scope.customBotton2 = true;

            $scope.customInter = false;
            $scope.customInterTransp = false;
            // $scope.customInterBodega = true;
            $scope.customSelCh = true;
            $scope.customInterProd = false;

            $('#myModalListProd').modal({
                backdrop: 'static',
                keyboard: false
            });
            $scope.customBottonNotaPedido = false;
            document.getElementById('idSupervisorTransportes').style.display = 'none';


        } else if ($scope.tipoUsuarioLogin == 'Transportes') {
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'block';
            document.getElementById('idSupervisor').style.display = 'none';
            document.getElementById('idSupervisorTransportes').style.display = 'none';

            $scope.customInformeTransporte = true;

            $scope.customInter = false;
            $scope.customInterTransp = true;
        } else if ($scope.tipoUsuarioLogin == 'Particular') {
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';

            $scope.customInter = false;
            $scope.customInterTransp = false;
            document.getElementById('myPrecio').style.display = 'block';
            document.getElementById('idSupervisorTransportes').style.display = 'none';

        } else if ($scope.tipoUsuarioLogin == 'Supervisor') {
            //   $scope.initGrafico();
            //$scope.initGraficoUsuarioGeneral();
            $scope.customBottonFinanzasAdministradorSupervisor = true;

            $scope.customImprimirValeBodega = true;

            /*$scope.initGraficoUsuario();
            $scope.initGraficoUsuarioSema(); */
            $scope.customBotton = true;
            $scope.customInterModifPed = false;

            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';
            document.getElementById('idVendedor').style.display = 'block';
            document.getElementById('idSupervisorTransportes').style.display = 'none';


            $('#myModalListProd').modal({
                backdrop: 'static',
                keyboard: false
            });

            $scope.customInterProd = false;


        } else if ($scope.tipoUsuarioLogin == "Supervisor Transportes") {
            $scope.customBotton = true;
            document.getElementById('idAdministrador').style.display = 'none';
            document.getElementById('idVendedorTienda').style.display = 'none';
            document.getElementById('idBodega').style.display = 'none';
            document.getElementById('idTransportes').style.display = 'none';
            document.getElementById('idSupervisor').style.display = 'none';
            document.getElementById('idVendedor').style.display = 'none';
            document.getElementById('idSupervisorTransportes').style.display = 'block';

            $scope.customInterModifPed = false;

        }

        $scope.customInterEntrar = true;
        $scope.customInterLogin = false;

        console.log($scope.$parent);
    };

    $scope.salir = function(){
        $("#myModalCrearCuenta").modal("hide");

        $('#myModallogininicial').modal({
            backdrop: 'static',
            keyboard: false
        });


        $scope.rutLog="";
        $scope.nombreLog="";
        $scope.passLog="";
        $scope.celLog="";
        $scope.passLogConf="";

        LocalStorage.remove('sesion');
    };
    
    }]);


    angularRoutingApp.controller('TypeCtrl', ['$scope', function ($scope) {
        $scope.$parent.title = "Typography";
        $scope.$parent.img = "img/iconset-addictive-flavour-set/png/cutting_pad.png";
        $scope.$parent.showTopToggle = false;
        $scope.$parent.customInter = false;
    }]);

angularRoutingApp.run(function ($rootScope, LocalStorage) {
    $rootScope.sesion = LocalStorage.get('sesion');
}).factory('LocalStorage', function () {
    let storage = window.localStorage;
    return {
        get: function (_name) {
            return angular.fromJson(storage.getItem(_name));
        },
        set: function (_name, value) {
            storage.setItem(_name, angular.toJson(value));
        },
        remove: function (_name) {
            storage.removeItem(_name);
        }
    };
}).factory('Table', function ($filter) {
    var table = {
        count: 0,
        options: [10, 20, 30, 40, 50],
        quantity: 10,
        current: 1,
        final: 1,
        search: '',
        orderBy: {column: 'default', asc: true},
        sorting: false,
        countFilter: 0,
        data: [],
        page: []
    };
    table.init = function (_array) {
        this.data = _array;
        this.viewFilterArray();
    };
    table.activeSorting = function () {
        this.sorting = true;
    };
    table.viewFilterArray = function () {
        var begin = ((this.current - 1) * this.quantity);
        var end = begin + this.quantity;
        var auxData = $filter('filter')(this.data, this.search);
        this.countFilter = auxData.length;
        this.final = Math.ceil(auxData.length / this.quantity);
        this.page = auxData.slice(begin, end);
        this.count = this.page.length;
    };
    table.quantityPage = function () {
        this.current = 1;
        this.viewFilterArray();
    };
    table.prevPage = function () {
        if (this.current > 1) {
            this.current--;
            this.viewFilterArray();
        }
    };
    table.nextPage = function () {
        if (this.current < this.final) {
            this.current++;
            this.viewFilterArray();
        }
    };
    table.orderByPage = function (_column) {
        if (this.isColumnSorting(_column)) {
            this.orderBy.asc = !this.orderBy.asc;
        } else {
            this.orderBy.column = _column;
            this.orderBy.asc = false;
        }
        this.data = $filter('orderBy')(this.data, this.orderBy.column, this.orderBy.asc);
        this.viewFilterArray();
    };
    table.searchPage = function () {
        this.current = 1;
        this.viewFilterArray();
    };
    table.isColumnSorting = function (_column) {
        return this.orderBy.column === _column;
    };
    table.isColumnSortingAsc = function (_column) {
        return (this.isColumnSorting(_column) ? this.orderBy.asc : false);
    };
    table.isColumnSortingDesc = function (_column) {
        return (this.isColumnSorting(_column) ? !this.orderBy.asc : false);
    };
    return table;
}).service("service", function ($http, $q) {
    return {
        rest: function (header) {
            var deferred = $q.defer();
            $http(header).then(function (result) {
                deferred.resolve(result.data);
            }).catch(function (error) {
                console.log(error);
                deferred.reject(false);
            });
            return deferred.promise;
        }
    };
}).filter('sprintf', function () {
    function parse(str, args) {
        if (angular.isUndefined(str)) {
            return "";
        } else {
            var i = 0;
            return str.replace(/%s/g, function () {
                var result = '';
                if (!angular.isUndefined(args[i]) && args[i] !== null) {
                    result = args[i];
                }
                i++;
                return result;
            });
        }
    }

    return function () {
        return parse(Array.prototype.slice.call(arguments, 0, 1)[0], Array.prototype.slice.call(arguments, 1));
    };
}).constant('MAPPING', {
    PRODUCTO: "productos",
    DESCUENTO: "descuentos",
    TIENDACLIENTE: "clientes",
    TIENDADETALLE: "detalles",
    TIENDADIRECCION: "direcciones",
    TIENDAORDEN: "ordenes",
    TDARESUMEN: "resumenes"
}).factory('Util', function ($filter) {
    var utilidades = {};
    utilidades.formatEndpoint = function (url, _mapping) {
        return $filter('sprintf')('%s/%s%s', url, _mapping);
    };
    utilidades.convertToDate = function (_value) {
        return new Date(_value);
    };
    utilidades.formatDate = function (_value, format) {
        if (_value === null || _value === undefined) {
            return "";
        } else {
            return $filter('date')(_value, format);
        }
    };
    return utilidades;
}).factory('Url', function (Util) {
    const REST_PATH = "../tienda-aranibar/admin/rest";
    var obj = {};
    obj.getService = function (_mapping) {
        return Util.formatEndpoint(REST_PATH, _mapping);
    };
    return obj;
}).service("tiendaOrdenService", function (service, Url, MAPPING) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDAORDEN),
                params: _filter
            });
        },
        obtainTo: function (_id) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDAORDEN) + "/" + _id
            });
        },
        create: function (_data) {
            return service.rest({
                method: 'POST',
                url: Url.getService(MAPPING.TIENDAORDEN),
                data: _data
            });
        },
        edit: function (_id, _data) {
            return service.rest({
                method: 'PUT',
                url: Url.getService(MAPPING.TIENDAORDEN) + "/" + _id,
                data: _data
            });
        },
        replaceTo: function (_id, _data) {
            return service.rest({
                method: 'PATCH',
                url: Url.getService(MAPPING.TIENDAORDEN) + "/" + _id,
                data: _data
            });
        }
    };
}).service("tiendaDetalleService", function (service, Url, MAPPING) {
    return {
        replaceTo: function (_id, _data) {
            return service.rest({
                method: 'PATCH',
                url: Url.getService(MAPPING.TIENDADETALLE) + "/" + _id,
                data: _data
            });
        },
        remove: function (_id) {
            return service.rest({
                method: 'DELETE',
                url: Url.getService(MAPPING.TIENDADETALLE) + "/" + _id
            });
        }
    };
}).service("tiendaDireccionService", function (service, Url, MAPPING) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDADIRECCION),
                params: _filter
            });
        },
        obtainTo: function (_id) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDADIRECCION) + "/" + _id
            });
        }
    };
}).service("tiendaClienteService", function (service, Url, MAPPING) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDACLIENTE),
                params: _filter
            });
        },
        obtainTo: function (_id) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TIENDACLIENTE) + "/" + _id
            });
        }
    };
});