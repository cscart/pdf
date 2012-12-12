<?php

namespace Pdfc;

class Response
{
    static public function pdf()
    {
        return array(
            'application/pdf' => function($content) {
                return $content;
            },
            'application/json' => function($content) {
                return json_encode(array(
                    'content' => base64_encode($content)
                ));
            }
        );
    }

    static public function data()
    {
        return array(
            'application/json' => function($content) {
                return json_encode($content);
            }            
        );
    }
}
