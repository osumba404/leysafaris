<?php

require '/home2/leylasaf/leysafaris/leysafaris/vendor/autoload.php';

$app = require_once '/home2/leylasaf/leysafaris/leysafaris/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('migrate', ['--force' => true]);

echo nl2br($kernel->output());