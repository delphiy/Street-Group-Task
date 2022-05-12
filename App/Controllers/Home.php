<?php

namespace App\Controllers;

use App\Utils\CSVUtils;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function index($request, $response, $service)
    {
        $utils = new CSVUtils();
        $peoples = $utils->loadPeoplesFromCSV();

        View::renderTemplate('Home/index.html', [
            'peoples'    => $peoples
        ]);
    }
}
