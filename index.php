<?php


/*

    {
        params: {
    
        },
        content: {
            []
        }
    }
*/
require('vendor/autoload.php');

use Respect\Rest\Router;

$r = new Router;

$r->post('/~zeke/pdf/convert', function() {

    $request = json_decode(file_get_contents('php://input'), true);
    $prefix = dirname(__FILE__);

    if (!empty($request['content'])) {
        $descriptorspec = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'w'), // stderr
        );
        $process = proc_open($prefix . '/bin/wkhtmltopdf --encoding utf8 -q - -', $descriptorspec, $pipes);

        // Send the HTML on stdin
        fwrite($pipes[0], $request['content']);
        fclose($pipes[0]);
        
        // Read the outputs
        $contents = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        $return_value = proc_close($process);
        
        return $contents;
    }
})->accept(array(
    'application/pdf' => function($content) {
        return $content;
    }
));
