<?php
require_once './libs/router.php';
require_once './app/controllers/paciente-api.controller.php';
require_once './app/controllers/os-api.controller.php';
require_once './app/controllers/historiaclinica-api.controller.php';
require_once './app/controllers/admin-api.controller.php';
require_once './app/controllers/defaultRespond-api.controller.php';

// crea el router
$router = new Router();

// defina la tabla de ruteo
$router->addRoute('pacientes', 'GET', 'PacienteApiController', 'getPacientes');
$router->addRoute('pacientes/:ID', 'GET', 'PacienteApiController', 'getPaciente');
$router->addRoute('pacientes/:ID', 'DELETE', 'PacienteApiController', 'deletePaciente');
$router->addRoute('pacientes', 'POST', 'PacienteApiController', 'insertPaciente'); 
$router->addRoute('pacientes/:ID', 'PUT', 'PacienteApiController', 'updatePaciente'); 

$router->addRoute('obrasocial', 'GET', 'OsApiController', 'getOs');
$router->addRoute('obrasocial/:ID', 'GET', 'OsApiController', 'getObra');
$router->addRoute('obrasocial/:ID', 'DELETE', 'OsApiController', 'deleteObra');
$router->addRoute('obrasocial', 'POST', 'OsApiController', 'insertObra'); 
$router->addRoute('obrasocial/:ID', 'PUT', 'OsApiController', 'updateObra'); 

$router->addRoute('historiaclinica', 'GET', 'HcApiController', 'getHistoriasClinicas');
$router->addRoute('historiaclinica/:ID', 'GET', 'HcApiController', 'getHc');
$router->addRoute('historiaclinica/:ID', 'DELETE', 'HcApiController', 'deleteHc');
$router->addRoute('historiaclinica', 'POST', 'HcApiController', 'insertHc'); 
$router->addRoute('historiaclinica/:ID', 'PUT', 'HcApiController', 'updateHc'); 

$router->addRoute('admin/token', 'GET', 'AuthApiController', 'getToken'); 

$router->setDefaultRoute('defaultRoute', 'default');
// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);