<?php

namespace Pdfc;

use Pdfc\Converter;

class Status
{
    static private $_test = '<b>a</b>';
    static private $_search = '%PDF-';

    static public function get()
    {
        $response = Converter::convert(self::$_test);
        
        if (strpos($response, self::$_search) === 0) {
            $status = 'OK';
        } else {
            $status = 'FAIL';
        }

        return $status;
    }
}
