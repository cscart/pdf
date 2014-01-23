<?php

use Pdfc\Batch;
use Pdfc\Converter;
use Pdfc\Response;
use Pdfc\Status;
use Respect\Rest\Router;

define('APP_WEB', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
define('APP_DIR', dirname(__FILE__) . '/app');

$classLoader = require('app/vendor/autoload.php');
$classLoader->add('Pdfc', APP_DIR);

$r = new Router(APP_WEB);

$r->post('/pdf/render', function() {
    $request = json_decode(file_get_contents('php://input'), true);
    return Converter::convert($request);
})->accept(Response::pdf());

$r->post('/pdf/batch/render/*', function() {
	$request = json_decode(file_get_contents('php://input'), true);
    return Batch::render($request);
})->accept(Response::pdf());

$r->post('/pdf/batch/add', function() {
    $request = json_decode(file_get_contents('php://input'), true);
    return Batch::add($request);
})->accept(Response::data());

$r->get('/status', function() {
    return Status::get();
})->accept(Response::status());

/* deprecated */
$r->get('/pdf/batch/render/*', function($transaction_id) {
    return Batch::render(array('transaction_id' => $transaction_id));
})->accept(Response::pdf());

