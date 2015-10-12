<?php

$config = array(
    'wk_bin' => '%APP_DIR%/bin/wkhtmltopdf', // Local
    'wk_params' => array(
        '--encoding utf8',
        '-q',
    ),
);


if (file_exists(ROOT_DIR . '/local_conf.php')) {
    include ROOT_DIR . '/local_conf.php';
}

return $config;
