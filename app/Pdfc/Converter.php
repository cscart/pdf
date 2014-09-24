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
            'values' => array('A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10'),
            'param' => '--page-size',
            'default' => 'A4'
        ),
        'orientation' => array(
            'values' => array('Landscape', 'Portrait'),
            'param' => '--orientation',
            'default' => 'Portrait'
        ),
        'margin_left' => array(
            'param' => '--margin-left'
        ),
        'margin_right' => array(
            'param' => '--margin-right'
        ),
        'margin_top' => array(
            'param' => '--margin-top'
        ),
        'margin_bottom' => array(
            'param' => '--margin-bottom'
        ),
        'page_height' => array(
            'param' => '--page-height'
        ),
        'page_width' => array(
            'param' => '--page-width'
        ),        
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
            if (!empty($params[$param])) {
                if (!empty($data['values'])) {
                    if (in_array($params[$param], $data['values'])) {
                        $formed[] = $data['param'] . ' ' . $params[$param];
                    } else {
                        $formed[] = $data['param'] . ' ' . $data['default'];
                    }
                } else {
                    $formed[] = $data['param'] . ' ' . escapeshellarg($params[$param]);
                }
            }
        }

        return implode(' ', $formed);
    }
}