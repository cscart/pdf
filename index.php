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
        foreach ($request['content'] as $content) {
            $file = $prefix . '/tmp/' . uniqid(time()) . '.html';
            file_put_contents($file, $content);
            $files[] = $file;
        }

        $output = $prefix . '/tmp/' . uniqid(time()) . '.pdf';

        exec($prefix . '/bin/wkhtmltopdf ' . implode(' ', $files) . ' --encoding utf8 ' . $output);

        $pdf = file_get_contents($output);

        foreach ($files as $file) {
            unlink($file);
        }
        unlink($output);

        return $pdf;
    }
})->accept(array(
    'application/pdf' => function($content) {
        return $content;
    }
));
