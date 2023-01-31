<?php

namespace Pdfc;

class Response
{
    public static function pdf()
    {
        return [
            'application/pdf'  => function ($content) {
                return $content;
            },
            'application/json' => function ($content) {
                return json_encode([
                    'content' => base64_encode($content)
                ]);
            }
        ];
    }

    public static function data()
    {
        return [
            'application/json' => function ($content) {
                return json_encode($content);
            }
        ];
    }

    public static function status()
    {
        return [
            'text/html' => function ($content) {
                return $content;
            }
        ];
    }
}
