/* global sourceAdminApp */

sourceAdminApp.controller('tiendaDireccionController', function ($scope, $log, loading, tiendaDireccionService, swal, CONFIG) {
    $scope.arreglo = [];
    $scope.tiendaDireccion = {};
    $scope.formTiendaDireccion = {};
    $scope.defaultTiendaDireccion = {
        id: null,
        idCuenta: null,
        tipo: null,
        calle: null,
        numero: null,
        comuna: null
    };
    const CONTROL = "TiendaDireccion";
    $scope.opcion = null;
    $scope.bloquear = null;
    $scope.agregarLog = function (_accion) {
        $log.info("Controlador:", CONTROL + ',', "accion", _accion);
    };
    $scope.iniciar = function () {
        $scope.agregarLog('iniciar');
        $scope.opcion = REVISAR;
        $scope.bloquear = true;
        $scope.formTiendaDireccion = angular.copy($scope.defaultTiendaDireccion);
        $scope.actualizar();
    };
    $scope.actualizar = function () {
        $scope.agregarLog('actualizar');
        loading.start();
        tiendaDireccionService.toList(null).then(function (response) {
            loading.finish();
            $log.info(response);
            if (response.codigo === CONFIG.CODE.OK) {
                $scope.arreglo = response.dato;
            }
        });
    };
    $scope.seleccionar = function (_id) {
        $scope.agregarLog('seleccionar');
        loading.start();
        tiendaDireccionService.obtainTo(_id).then(function (response) {
            loading.finish();
            $log.info(response);
            if (response.codigo === CONFIG.CODE.OK) {
                $scope.tiendaDireccion = response.dato;
                $scope.convetirTiendaDireccion($scope.tiendaDireccion);
            }
        });
    };
    $scope.revisar = function (_id) {
        $scope.agregarLog('revisar');
        $scope.bloquear = true;
        $scope.seleccionar(_id);
        $scope.opcion = REVISAR;
    };
    $scope.agregar = function () {
        $scope.agregarLog('agregar');
        $scope.bloquear = false;
        $scope.formTiendaDireccion = angular.copy($scope.defaultTiendaDireccion);
        $scope.tiendaDireccion = {};
        $scope.opcion = AGREGAR;
    };
    $scope.editar = function (_id) {
        $scope.agregarLog('editar');
        $scope.bloquear = false;
        $scope.seleccionar(_id);
        $scope.opcion = EDITAR;
    };
    $scope.parchar = function (_id) {
        $scope.agregarLog('parchar');
        $scope.bloquear = false;
        $scope.formTiendaDireccion = angular.copy($scope.defaultTiendaDireccion);
        $scope.tiendaDireccion= {};
        $scope.tiendaDireccionid = _id;
        $scope.opcion = PARCHAR;
    };
    $scope.resetear = function () {
        $scope.agregarLog('resetear');
        $scope.formTiendaDireccion = angular.copy($scope.defaultTiendaDireccion);
    };
    $scope.guardar = function () {
        $scope.agregarLog('guardar');
        $scope.convetirFormTiendaDireccion($scope.formTiendaDireccion);
        switch ($scope.opcion) {
            case AGREGAR:
                tiendaDireccionService.create($scope.tiendaDireccion).then(function (response) {
                    loading.finish();
                    $log.info(response);
                    if (response.codigo === CONFIG.CODE.OK) {
                        swal.success(response.dato);
                        $scope.iniciar();
                    }
                });
                break;
            case EDITAR:
                tiendaDireccionService.edit($scope.tiendaDireccion.id, $scope.tiendaDireccion).then(function (response) {
                    loading.finish();
                    $log.info(response);
                    if (response.codigo === CONFIG.CODE.OK) {
                        swal.success(response.dato);
                        $scope.iniciar();
                    }
                });
                break;
            case PARCHAR:
                tiendaDireccionService.replaceTo($scope.tiendaDireccion.id, $scope.tiendaDireccion).then(function (response) {
                    loading.finish();
                    $log.info(response);
                    if (response.codigo === CONFIG.CODE.OK) {
                        swal.success(response.dato);
                        $scope.iniciar();
                    }
                });
                break;
        }
    };
    $scope.eliminar = function (_id) {
        $scope.agregarLog('eliminar');
        loading.start();
        tiendaDireccionService.remove(_id).then(function (response) {
            loading.finish();
            $log.info(response);
            if (response.codigo === CONFIG.CODE.OK) {
                $scope.bloquear = true;
            }
        });
    };
    $scope.convetirTiendaDireccion = function (_data) {
        $scope.agregarLog('convetirTiendaDireccion');
        $scope.formTiendaDireccion = {
            idCuenta: _data.idCuenta,
            tipo: _data.tipo,
            calle: _data.calle,
            numero: _data.numero,
            comuna: _data.comuna
        };
    };
    $scope.convetirFormTiendaDireccion = function (_data) {
        $scope.agregarLog('convetirFormTiendaDireccion');
        if (_data.idCuenta !== null) {
            $scope.tienda_direccion.idCuenta = _data.idCuenta;
        }
        if (_data.tipo !== null) {
            $scope.tienda_direccion.tipo = _data.tipo;
        }
        if (_data.calle !== null) {
            $scope.tienda_direccion.calle = _data.calle;
        }
        if (_data.numero !== null) {
            $scope.tienda_direccion.numero = _data.numero;
        }
        if (_data.comuna !== null) {
            $scope.tienda_direccion.comuna = _data.comuna;
        }
    };

    $scope.iniciar();
});