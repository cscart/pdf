<?php

$config = [
    'chrome_host' => 'http://localhost:3000'
];

if (file_exists(ROOT_DIR . '/local_conf.php')) {
    include ROOT_DIR . '/local_conf.php';
}

return $config;
