<?php

$appPath = dirname(__DIR__);
$tmpPath = '/tmp/hosteleats';

foreach ([
    $tmpPath.'/framework/cache/data',
    $tmpPath.'/framework/sessions',
    $tmpPath.'/framework/views',
    $tmpPath.'/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

$defaults = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'LOG_CHANNEL' => 'stderr',
    'CACHE_STORE' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'VIEW_COMPILED_PATH' => $tmpPath.'/framework/views',
];

foreach ($defaults as $key => $value) {
    if (getenv($key) === false) {
        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

chdir($appPath);

require $appPath.'/public/index.php';
