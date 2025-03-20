angularRoutingApp.service("productosService", function (service, Url, MAPPING) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.PRODUCTO),
                params: _filter
            });
        }
    };
}).service("resumenService", function (service, Url, MAPPING) {
    return {
        toListProducto: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TDARESUMEN + "-productos"),
                params: _filter
            });
        },
        toListCliente: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.TDARESUMEN + "-clientes"),
                params: _filter
            });
        }
    };
}).service("descuentoService", function (service, Url, MAPPING) {
    return {
        toList: function (_filter) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.DESCUENTO),
                params: _filter
            });
        },
        obtainTo: function (_id) {
            return service.rest({
                method: 'GET',
                url: Url.getService(MAPPING.DESCUENTO) + "/" + _id
            });
        },
        create: function (_data) {
            return service.rest({
                method: 'POST',
                url: Url.getService(MAPPING.DESCUENTO),
                data: _data
            });
        },
        edit: function (_id, _data) {
            return service.rest({
                method: 'PUT',
                url: Url.getService(MAPPING.DESCUENTO) + "/" + _id,
                data: _data
            });
        }
    };
}).controller('descuentoController', function ($scope, $log, Table, productosService, descuentoService) {
    $scope.loading = false;
    $scope.productos = [];
    $scope.tableDescuento = angular.copy(Table);
    $scope.descuento = {};
    $scope.formDescuento = {};
    $scope.defaultDescuento = {
        id: null,
        nombre: null,
        tipo: null,
        descripcion: null,
        activo: null,
        valor: null,
        idProducto: null,
        cantidad: null
    };
    const CONTROL = "Descuento";
    const AGREGAR = 1;
    const EDITAR = 2;
    $scope.opcion = null;
    $scope.agregarLog = function (_accion) {
        $log.info("Controlador:", CONTROL + ',', "accion", _accion);
    };
    $scope.iniciar = function () {
        $scope.agregarLog('iniciar');
        $scope.formDescuento = angular.copy($scope.defaultDescuento);
        productosService.toList(null).then(function (response) {
            $scope.loading = false;
            $log.info(response);
            if (response.code === 0) {
                $scope.productos = response.data.productos;
                var nombreProductos = [];
                angular.forEach($scope.productos, function (value) {
                    nombreProductos.push(value.nombreProd);
                });
                $('#nombreProductoDivPart .typeahead').typeahead({
                    local: nombreProductos
                }).on('typeahead:selected', function (event, selection) {
                    $scope.formDescuento.idProducto = selection.value;
                });
                $scope.actualizar();
            }
        });
    };
    $scope.actualizar = function () {
        $scope.agregarLog('actualizar');
        $scope.loading = true;
        descuentoService.toList(null).then(function (response) {
            $scope.loading = false;
            $log.info(response);
            if (response.code === 0) {
                var datos = [];
                angular.forEach(response.data, function (item) {
                    angular.forEach($scope.productos, function (producto) {
                        if (producto.id === item.idProducto) {
                            item.nombreProducto = producto.nombreProd;
                            console.log(item);
                        }
                    });
                    datos.push(item);
                });
                $scope.tableDescuento.init(datos);
            }
        });
    };
    $scope.seleccionar = function (_id) {
        $scope.agregarLog('seleccionar');
        $scope.loading = true;
        descuentoService.obtainTo(_id).then(function (response) {
            $scope.loading = false;
            $log.info(response);
            if (response.code === 0) {
                $scope.descuento = response.data;
                $scope.convetirDescuento($scope.descuento);
            }
        });
    };
    $scope.agregar = function () {
        $scope.agregarLog('agregar');
        $scope.formDescuento = angular.copy($scope.defaultDescuento);
        $scope.descuento = {};
        $scope.opcion = AGREGAR;
    };
    $scope.editar = function (_id) {
        $scope.agregarLog('editar');
        $scope.seleccionar(_id);
        $scope.opcion = EDITAR;
    };
    $scope.guardar = function () {
        $scope.agregarLog('guardar');
        $scope.convetirFormDescuento($scope.formDescuento);
        $scope.loading = true;
        switch ($scope.opcion) {
            case AGREGAR:
                descuentoService.create($scope.descuento).then(function (response) {
                    $scope.loading = false;
                    $log.info(response);
                    if (response.code === 0) {
                        alertify.success(response.data);
                        $scope.iniciar();
                    }
                });
                break;
            case EDITAR:
                descuentoService.edit($scope.formDescuento.id, $scope.descuento).then(function (response) {
                    $scope.loading = false;
                    $log.info(response);
                    if (response.code === 0) {
                        alertify.success(response.data);
                        $scope.iniciar();
                    }
                });
                break;
        }
    };
    $scope.convetirDescuento = function (_data) {
        $scope.agregarLog('convetirDescuento');
        var idProducto = 0;
        angular.forEach($scope.productos, function (value) {
            if (value.id === _data.idProducto) {
                idProducto = value.nombreProd;
            }
        });
        $scope.formDescuento = {
            id: _data.id,
            nombre: _data.nombre,
            tipo: _data.tipo,
            descripcion: _data.descripcion,
            activo: _data.activo,
            valor: _data.valor,
            idProducto: idProducto,
            cantidad: _data.cantidad
        };
    };
    $scope.convetirFormDescuento = function (_data) {
        $scope.agregarLog('convetirFormDescuento');
        var idProducto = 0;
        angular.forEach($scope.productos, function (value) {
            if (value.nombreProd === _data.idProducto) {
                idProducto = value.id;
            }
        });
        $scope.descuento = {
            nombre: _data.nombre,
            tipo: _data.tipo,
            descripcion: _data.descripcion,
            activo: _data.activo,
            valor: _data.valor,
            idProducto: idProducto,
            cantidad: _data.cantidad
        };
    };

    $scope.iniciar();
});