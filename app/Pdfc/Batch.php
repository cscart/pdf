<?php

namespace Pdfc;

use Pdfc\Queue;
use Pdfc\Converter;

class Batch
{
    public static function add($request)
    {
        if (empty($request['transaction_id'])) {
            $t_id = md5(uniqid('', true));
        } else {
            $t_id = $request['transaction_id'];
        }

        Queue::instance()->put($t_id, $request['content']);

        return $t_id;
    }

    public static function render($params)
    {
        $params['content'] = Queue::instance()->get($params['transaction_id']);

        return Converter::convert($params);
    }
}
