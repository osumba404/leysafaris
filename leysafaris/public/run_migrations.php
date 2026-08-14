<?php

/**
 * One-off migration runner for cPanel (no SSH).
 * Visit once, then DELETE this file from public_html.
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);

$laravelRoot = null;

foreach ([__DIR__, __DIR__.'/..', __DIR__.'/../leysafaris/leysafaris'] as $base) {
    if (is_file($base.'/vendor/autoload.php')) {
        $laravelRoot = $base;
        break;
    }
}

if ($laravelRoot === null) {
    http_response_code(500);
    exit('Could not find Laravel (vendor/autoload.php). Checked: '.__DIR__.', '.dirname(__DIR__));
}

require $laravelRoot.'/vendor/autoload.php';

$app = require_once $laravelRoot.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$status = $kernel->call('migrate', [
    '--force' => true,
    '--no-interaction' => true,
]);

$output = trim($kernel->output());

header('Content-Type: text/html; charset=UTF-8');

echo '<pre>';
echo htmlspecialchars($output !== '' ? $output : ($status === 0 ? 'Nothing to migrate.' : 'No output.'));
echo "\n\nExit status: {$status}";
echo '</pre>';
