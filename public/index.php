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
$router->add('POST', '/api/visions', 'VisionController@save');
$router->add('GET', '/v/([a-zA-Z0-9]+)', 'VisionController@view');
$router->add('GET', '/gallery', 'VisionController@gallery');

// Boss Panel
$router->add('GET', '/boss', 'AdminController@index');
$router->add('POST', '/boss/login', 'AdminController@login');
$router->add('POST', '/boss/logout', 'AdminController@logout');
$router->add('POST', '/boss/settings', 'AdminController@updateSettings');
$router->add('POST', '/boss/visions/delete', 'AdminController@deleteVisions');

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
