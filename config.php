<?php

$config = [
    'chrome_host'   => 'http://localhost:3000',
    'stop_words'    => [],
    'ip_stop_words' => [],
    'allowed_ips'   => [],
    'denied_ips'    => [],
];

if (file_exists(ROOT_DIR . '/local_conf.php')) {
    include ROOT_DIR . '/local_conf.php';
}

return $config;
