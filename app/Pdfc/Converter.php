<?php

namespace Pdfc;

class Converter
{
    static private $cmd = '/bin/wkhtmltopdf %s --encoding utf8 -q - -';
    static private $dsc = array(
        0 => array('pipe', 'r'), // stdin
        1 => array('pipe', 'w'), // stdout
        2 => array('pipe', 'w'), // stderr
    );

    static private $valid_params = array(
        'page_size' => array(
            'values' => array('A4', 'A5', 'A6', 'A7', 'A8'),
            'param' => '--page-size',
            'default' => 'A4'
        ),
        'orientation' => array(
            'values' => array('Landscape', 'Portrait'),
            'param' => '--orientation',
            'default' => 'Portrait'
        )
    );

    static public function convert($params)
    {
        $prefix = dirname(__FILE__);
        $contents = '';

        if (!empty($params['content'])) {

            $cmd = sprintf(self::$cmd, self::formParams($params));

            $process = proc_open(APP_DIR . $cmd, self::$dsc, $pipes);

            // Send the HTML on stdin
            fwrite($pipes[0], $params['content']);
            fclose($pipes[0]);

            // Read the outputs
            $contents = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            $return_value = proc_close($process);
        }

        return $contents;
    }

    static private function formParams($params)
    {
        $formed = array();

        foreach (self::$valid_params as $param => $data) {
            if (!empty($params[$param]) && in_array($params[$param], $data['values'])) {
                $formed[] = $data['param'] . ' ' . $params[$param];
            } else {
                $formed[] = $data['param'] . ' ' . $data['default'];
            }
        }

        return implode(' ', $formed);
    }
}