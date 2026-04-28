<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// Routes
$router->add('GET', '/', 'HomeController@index');
$router->add('POST', '/api/analyze', 'AiController@analyze');

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
