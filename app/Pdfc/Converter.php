<?php

namespace Pdfc;

class Converter
{
    static private $_cmd = '/bin/wkhtmltopdf --encoding utf8 -q - -';
    static private $_dsc = array(
        0 => array('pipe', 'r'), // stdin
        1 => array('pipe', 'w'), // stdout
        2 => array('pipe', 'w'), // stderr
    );

    static public function convert($html)
    {
        $prefix = dirname(__FILE__);
        $contents = '';

        if (!empty($html)) {

            $process = proc_open(APP_DIR . self::$_cmd, self::$_dsc, $pipes);

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
}