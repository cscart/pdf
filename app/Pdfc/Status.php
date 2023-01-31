<?php

namespace Pdfc;

use Pdfc\Converter;

class Status
{
    static private $_test = '<b>a</b>';
    static private $_search = '%PDF-';

    public static function get()
    {
        $response = Converter::convert(['content' => self::$_test]);

        if (strpos($response, self::$_search) === 0) {
            $status = 'OK';
        } else {
            $status = 'FAIL';
        }

        return $status;
    }
}
