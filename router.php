<?php
require_once 'app/libs/router.php';
require_once 'app/controllers/api.controller.php';

$router = new Router();

$router->addRoute('shirts', 'GET', 'Apicontroller', 'getAllShirts');
$router->addRoute('shirts/:id', 'GET', 'Apicontroller', 'getShirt');
$router->addRoute('shirts/:id', 'PUT', 'Apicontroller', 'updateShirt');

$router->route($_REQUEST['resource'], $_SERVER['REQUEST_METHOD']);
