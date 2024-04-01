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
        $hasCustomPageSize = isset($params['page_width'], $params['page_height']);
        $options = [
            'landscape' => false,
            'format' => !$hasCustomPageSize ? 'A4' : null,
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
                    if (!$hasCustomPageSize && in_array($value, ['A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6'], true)) {
                        $options['format'] = $value;
                    }
                    break;
                case 'orientation':
                    $options['landscape'] = strtolower($value) === 'landscape';
                    break;
                case 'margin_left':
                    $options['margin']['left'] = self::transformSize($value);
                    break;
                case 'margin_right':
                    $options['margin']['right'] = self::transformSize($value);
                    break;
                case 'margin_top':
                    $options['margin']['top'] = self::transformSize($value);
                    break;
                case 'margin_bottom':
                    $options['margin']['bottom'] = self::transformSize($value);
                    break;
                case 'page_height':
                    $options['height'] = self::transformSize($value);
                    break;
                case 'page_width':
                    $options['width'] = self::transformSize($value);
                    break;
            }
        }

        return $options;
    }

    protected static function transformSize($value)
    {
        return preg_replace_callback('/([.\d]+)(.*)/', function (array $matches) {
            $matches[2] = strtolower($matches[2]);

            return $matches[1] . (in_array($matches[2], ['mm', 'cm'], true) ? $matches[2] : 'mm');
        }, $value);
    }
}
