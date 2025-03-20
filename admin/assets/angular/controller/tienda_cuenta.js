/* global sourceAdminApp */

sourceAdminApp.controller('tiendaCuentaController', function ($scope, $log, loading, tiendaCuentaService, swal, CONFIG) {
    $scope.arreglo = [];
    $scope.tiendaCuenta = {};
    $scope.formTiendaCuenta = {};
    $scope.defaultTiendaCuenta = {
        id: null,
        id: null,
        correo: null,
        contrasena: null,
        nombre: null,
        token: null,
        fechaIngreso: null,
        codigo: null
    };
    const CONTROL = "TiendaCuenta";
    $scope.opcion = null;
    $scope.bloquear = null;
    $scope.agregarLog = function (_accion) {
        $log.info("Controlador:", CONTROL + ',', "accion", _accion);
    };
    $scope.iniciar = function () {
        $scope.agregarLog('iniciar');
        $scope.opcion = REVISAR;
        $scope.bloquear = true;
        $scope.formTiendaCuenta = angular.copy($scope.defaultTiendaCuenta);
        $scope.actualizar();
    };
    $scope.actualizar = function () {
        $scope.agregarLog('actualizar');
        loading.start();
        tiendaCuentaService.toList(null).then(function (response) {
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
        tiendaCuentaService.obtainTo(_id).then(function (response) {
            loading.finish();
            $log.info(response);
            if (response.codigo === CONFIG.CODE.OK) {
                $scope.tiendaCuenta = response.dato;
                $scope.convetirTiendaCuenta($scope.tiendaCuenta);
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
        $scope.formTiendaCuenta = angular.copy($scope.defaultTiendaCuenta);
        $scope.tiendaCuenta = {};
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
        $scope.formTiendaCuenta = angular.copy($scope.defaultTiendaCuenta);
        $scope.tiendaCuenta= {};
        $scope.tiendaCuentaid = _id;
        $scope.opcion = PARCHAR;
    };
    $scope.resetear = function () {
        $scope.agregarLog('resetear');
        $scope.formTiendaCuenta = angular.copy($scope.defaultTiendaCuenta);
    };
    $scope.guardar = function () {
        $scope.agregarLog('guardar');
        $scope.convetirFormTiendaCuenta($scope.formTiendaCuenta);
        switch ($scope.opcion) {
            case AGREGAR:
                tiendaCuentaService.create($scope.tiendaCuenta).then(function (response) {
                    loading.finish();
                    $log.info(response);
                    if (response.codigo === CONFIG.CODE.OK) {
                        swal.success(response.dato);
                        $scope.iniciar();
                    }
                });
                break;
            case EDITAR:
                tiendaCuentaService.edit($scope.tiendaCuenta.id, $scope.tiendaCuenta).then(function (response) {
                    loading.finish();
                    $log.info(response);
                    if (response.codigo === CONFIG.CODE.OK) {
                        swal.success(response.dato);
                        $scope.iniciar();
                    }
                });
                break;
            case PARCHAR:
                tiendaCuentaService.replaceTo($scope.tiendaCuenta.id, $scope.tiendaCuenta).then(function (response) {
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
        tiendaCuentaService.remove(_id).then(function (response) {
            loading.finish();
            $log.info(response);
            if (response.codigo === CONFIG.CODE.OK) {
                $scope.bloquear = true;
            }
        });
    };
    $scope.convetirTiendaCuenta = function (_data) {
        $scope.agregarLog('convetirTiendaCuenta');
        $scope.formTiendaCuenta = {
            id: _data.id,
            correo: _data.correo,
            contrasena: _data.contrasena,
            nombre: _data.nombre,
            token: _data.token,
            fechaIngreso: _data.fechaIngreso,
            codigo: _data.codigo
        };
    };
    $scope.convetirFormTiendaCuenta = function (_data) {
        $scope.agregarLog('convetirFormTiendaCuenta');
        if (_data.correo !== null) {
            $scope.tienda_cuenta.correo = _data.correo;
        }
        if (_data.contrasena !== null) {
            $scope.tienda_cuenta.contrasena = _data.contrasena;
        }
        if (_data.nombre !== null) {
            $scope.tienda_cuenta.nombre = _data.nombre;
        }
        if (_data.token !== null) {
            $scope.tienda_cuenta.token = _data.token;
        }
        if (_data.fechaIngreso !== null) {
            $scope.tienda_cuenta.fechaIngreso = _data.fechaIngreso;
        }
        if (_data.codigo !== null) {
            $scope.tienda_cuenta.codigo = _data.codigo;
        }
    };

    $scope.iniciar();
});