<?php

namespace Pdfc;

use dibi;

class Queue
{
    const TRANSACTION_TTL = 3600; // 1 hour
    static private $_instance = null;

    public function put($transaction_id, $data)
    {
        dibi::query("INSERT INTO [queue]", array(
            'transaction_id' => $transaction_id,
            'data' => $data,
            'ttl' => time() + self::TRANSACTION_TTL
        ));
    }

    public function get($transaction_id)    
    {
        $result = dibi::query("SELECT data FROM [queue] WHERE transaction_id = %s ORDER BY id ASC", $transaction_id);
        $data = $result->fetchAll();

        if (!empty($data)) {
            $contents = '';
            foreach ($data as $d) {
                $contents .= $d['data'];
            }

            dibi::query("DELETE FROM [queue] WHERE transaction_id = %s OR ttl < %s", $transaction_id, time());
            dibi::query("VACUUM");
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

        dibi::connect(array(
            'driver'   => 'sqlite3',
            'database' => APP_DIR . '/db/queue.sdb',
        ));

        if (!empty($create)) {
            dibi::query("CREATE TABLE queue (id INTEGER PRIMARY KEY, transaction_id char(32), data text, ttl INTEGER)");
            dibi::query("CREATE INDEX ttl ON queue (ttl)");
        }
    }
}
