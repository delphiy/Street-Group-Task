<?php

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->respondWithController('GET', '/', 'Home@index');

// Dispatch the router
$router->dispatch();