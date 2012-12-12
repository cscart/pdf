<?php

namespace Pdfc;

class Queue
{
    static private $_instance = null;

    public function put($transaction_id, $data)
    {
        \dibi::query("INSERT INTO [queue]", array(
            'transaction_id' => $transaction_id,
            'data' => $data
        ));
    }

    public function get($transaction_id)    
    {
        $result = \dibi::query("SELECT data FROM [queue] WHERE transaction_id = %s ORDER BY id ASC", $transaction_id);
        $data = $result->fetchAll();

        if (!empty($data)) {
            $contents = '';
            foreach ($data as $d) {
                $contents .= $d['data'];
            }

            \dibi::query("DELETE FROM [queue] WHERE transaction_id = %s", $transaction_id);

            return $contents;
        }

        return false;
    }

    static public function instance()
    {
        if (empty(self::$_instance)) {
            $class = '\\Pdfc\\Queue';
            self::$_instance = new $class();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        if (!file_exists(APP_DIR . '/db/queue.sdb')) {
            $create = true;
        }

        \dibi::connect(array(
            'driver'   => 'sqlite3',
            'database' => APP_DIR . '/db/queue.sdb',
        ));

        if (!empty($create)) {
            \dibi::query("CREATE TABLE queue (id INTEGER PRIMARY KEY, transaction_id char(32), data text)");
        }
    }
}
