<?php

namespace Pdfc;

class Converter
{
    static protected $valid_params = array(
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

            $transaction_id = '';
            if (!empty($params['transaction_id'])) {
                $transaction_id = $params['transaction_id'];
            } else {
                $transaction_id = md5(uniqid('', true));
            }

            $html_file = APP_DIR . '/files/' . $transaction_id . '.html';
            $pdf_file = APP_DIR . '/files/' . $transaction_id . '.pdf';
            @file_put_contents($html_file, $params['content']);

            $cmd = self::getBinPath() . ' ' . self::formParams($params) . ' ' . $html_file . ' ' . $pdf_file;
            exec($cmd);

            $contents = @file_get_contents($pdf_file);
            unlink($html_file);
            unlink($pdf_file);
        }

        return $contents;
    }

    protected static function getBinPath()
    {
        global $config;
        
        return strtr($config['wk_bin'], array(
            '%APP_DIR%' => APP_DIR,
        ));
    }

    protected static function formParams($params)
    {
        global $config;

        $formed = $config['wk_params'];

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
