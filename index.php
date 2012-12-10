<?php

$request_prefix = '/~zeke';

require('vendor/autoload.php');
use Respect\Rest\Router;

dibi::connect(array(
    'driver'   => 'sqlite3',
    'database' => './tmp/queue.sdb',
));

//dibi::query("CREATE TABLE queue (id INTEGER PRIMARY KEY, transaction_id char(32), data text)");


$r = new Router;
$r->post($request_prefix . '/pdf/render', function() {

    $request = json_decode(file_get_contents('php://input'), true);
    return fn_run_wk($request['content']);

})->accept(array(
    'application/pdf' => function($content) {
        return $content;
    }
));

$r->post($request_prefix . '/pdf/batch/add', function() {

    $request = json_decode(file_get_contents('php://input'), true);

    if (empty($request['transaction_id'])) {
        $t_id = md5(uniqid());
    } else {
        $t_id = $request['transaction_id'];
    }

    dibi::query("INSERT INTO [queue]", array(
        'transaction_id' => $t_id,
        'data' => $request['content']
    ));

    return $t_id;

})->accept(array(
    'application/json' => function($content) {
        return json_encode($content);
    }
));

$r->get($request_prefix . '/pdf/batch/render/*', function($t_id) {
    
    $result = dibi::query("SELECT data FROM [queue] WHERE transaction_id = %s", $t_id);
    $data = $result->fetchAll();

    if (!empty($data)) {
        $contents = '';
        foreach ($data as $d) {
            $contents .= $d['data'];
        }

        dibi::query("DELETE FROM [queue] WHERE transaction_id = %s", $t_id);

        return fn_run_wk($contents);
    }

    return '';

})->accept(array(
    'application/pdf' => function($content) {
        return $content;
    }
));

function fn_run_wk($html)
{
    $prefix = dirname(__FILE__);
    $contents = '';

    if (!empty($html)) {
        $descriptorspec = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'w'), // stderr
        );
        $process = proc_open($prefix . '/bin/wkhtmltopdf --encoding utf8 -q - -', $descriptorspec, $pipes);

        // Send the HTML on stdin
        fwrite($pipes[0], $html);
        fclose($pipes[0]);

        // Read the outputs
        $contents = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        $return_value = proc_close($process);
    }

    return $contents;
}
