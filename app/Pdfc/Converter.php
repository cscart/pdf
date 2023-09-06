<?php

namespace Pdfc;

class Converter
{
    public static function convert($params)
    {
        global $config;

        if (empty($params['content'])) {
            return '';
        }

        $opts = [
            'http' => [
                'method' => "POST",
                'header' => implode("\r\n", [
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                ]),
                'content' => json_encode([
                    'content' => $params['content'],
                    'options' => self::resolveOptions($params),
                ]),
                'timeout' => 15
            ]
        ];

        $context = stream_context_create($opts);

        return file_get_contents($config['chrome_host'], false, $context);
    }

    protected static function resolveOptions(array $params)
    {
        $options = [
            'landscape' => false,
            'format' => !isset($params['page_width'], $params['page_height']) ? 'A4' : null,
            'margin' => [
                'bottom' => '10mm',
                'left'   => '10mm',
                'right'  => '10mm',
                'top'    => '10mm',
            ],
            'printBackground' => true
        ];

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'page_size':
                    if (in_array($value, ['A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6'], true)) {
                        $options['format'] = $value;
                    }
                    break;
                case 'orientation':
                    $options['landscape'] = strtolower($value) === 'landscape';
                    break;
                case 'margin_left':
                    $options['margin']['left'] = $value;
                    break;
                case 'margin_right':
                    $options['margin']['right'] = $value;
                    break;
                case 'margin_top':
                    $options['margin']['top'] = $value;
                    break;
                case 'margin_bottom':
                    $options['margin']['bottom'] = $value;
                    break;
                case 'page_height':
                    $options['height'] = $value;
                    break;
                case 'page_width':
                    $options['width'] = $value;
                    break;
            }
        }

        return $options;
    }
}
