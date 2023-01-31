<?php

namespace Pdfc;

class Converter
{
    static protected $valid_params = [
        'page_size'     => [
            'values'  => ['A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10'],
            'param'   => '--page-size',
            'default' => 'A4'
        ],
        'orientation'   => [
            'values'  => ['Landscape', 'Portrait'],
            'param'   => '--orientation',
            'default' => 'Portrait'
        ],
        'margin_left'   => [
            'param' => '--margin-left'
        ],
        'margin_right'  => [
            'param' => '--margin-right'
        ],
        'margin_top'    => [
            'param' => '--margin-top'
        ],
        'margin_bottom' => [
            'param' => '--margin-bottom'
        ],
        'page_height'   => [
            'param' => '--page-height'
        ],
        'page_width'    => [
            'param' => '--page-width'
        ]
    ];

    public static function convert($params)
    {
        if (empty($params['content'])) {
            return '';
        }

        if (!empty($params['transaction_id'])) {
            $transaction_id = $params['transaction_id'];
        } else {
            $transaction_id = md5(uniqid('', true));
        }

        $html_file = APP_DIR . '/files/' . $transaction_id . '.html';
        $pdf_file = APP_DIR . '/files/' . $transaction_id . '.pdf';
        @file_put_contents($html_file, $params['content']);

        $cmd = self::getBinPath() . ' ' . APP_DIR . '/htmltopdf.js' . ' ' . $html_file . ' ' . $pdf_file . ' ' . self::formParams($params);
        exec($cmd);

        $contents = @file_get_contents($pdf_file);
        unlink($html_file);
        unlink($pdf_file);

        return $contents;
    }

    protected static function getBinPath()
    {
        global $config;

        return strtr($config['node_bin'], [
            '%APP_DIR%' => APP_DIR,
        ]);
    }

    protected static function formParams($params)
    {
        $formed = [];

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
