<?php

$config = [
    'node_bin' => '/usr/local/bin/node', // Local
];

if (file_exists(ROOT_DIR . '/local_conf.php')) {
    include ROOT_DIR . '/local_conf.php';
}

return $config;
