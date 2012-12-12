<?php

use Pdfc\Batch;
use Pdfc\Converter;
use Pdfc\Response;
use Respect\Rest\Router;

define('APP_WEB', dirname($_SERVER['SCRIPT_NAME']));
define('APP_DIR', dirname(__FILE__) . '/app');

$classLoader = require('app/vendor/autoload.php');
$classLoader->add('Pdfc', APP_DIR);

$r = new Router(APP_WEB);

$r->post('/pdf/render', function() {
    $request = json_decode(file_get_contents('php://input'), true);
    return Converter::convert($request['content']);
})->accept(Response::pdf());

$r->get('/pdf/batch/render/*', function($transaction_id) {
    return Batch::render($transaction_id);
})->accept(Response::pdf());

$r->post('/pdf/batch/add', function() {
    $request = json_decode(file_get_contents('php://input'), true);
    return Batch::add($request);
})->accept(Response::data());
