<?php
require_once './libs/router.php';
require_once './app/controllers/paciente-api.controller.php';
require_once './app/controllers/admin-api.controller.php';

// crea el router
$router = new Router();

// defina la tabla de ruteo
$router->addRoute('pacientes', 'GET', 'PacienteApiController', 'getPacientes');
$router->addRoute('pacientes/:ID', 'GET', 'PacienteApiController', 'getPaciente');
$router->addRoute('pacientes/:ID', 'DELETE', 'PacienteApiController', 'deletePaciente');
$router->addRoute('pacientes', 'POST', 'PacienteApiController', 'insertPaciente'); 
$router->addRoute('pacientes/:ID', 'PUT', 'PacienteApiController', 'updatePaciente'); 
$router->addRoute('admin/token', 'GET', 'AuthApiController', 'getToken'); 

// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);