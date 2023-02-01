<?php

$config = [
    'node_bin' => '/usr/local/bin/node', // Local
    'browser_ws_endpoint' => 'ws://chrome:3000',
];

if (file_exists(ROOT_DIR . '/local_conf.php')) {
    include ROOT_DIR . '/local_conf.php';
}

return $config;
