<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function run(): Response
    {
        $expected = config('app.deploy_run_token');

        if (! is_string($expected) || $expected === '') {
            abort(404);
        }

        $provided = (string) request()->query('token', '');

        if (! hash_equals($expected, $provided)) {
            abort(404);
        }

        $lines = ['Leyla Safari Tours — deploy tasks', str_repeat('-', 40)];

        $tasks = [
            'config:clear' => fn () => Artisan::call('config:clear'),
            'route:clear' => fn () => Artisan::call('route:clear'),
            'view:clear' => fn () => Artisan::call('view:clear'),
            'migrate --force --no-interaction' => fn () => Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]),
            'db:seed ContentEnhancementSeeder' => fn () => Artisan::call('db:seed', ['--class' => 'ContentEnhancementSeeder', '--force' => true]),
            'db:seed SiteContentSeeder' => fn () => Artisan::call('db:seed', ['--class' => 'SiteContentSeeder', '--force' => true]),
        ];

        foreach ($tasks as $label => $task) {
            $lines[] = '';
            $lines[] = '$ php artisan '.$label;

            try {
                $task();
                $output = trim(Artisan::output());
                $lines[] = $output !== '' ? $output : 'OK';
            } catch (\Throwable $exception) {
                $lines[] = 'FAILED: '.$exception->getMessage();

                return response(implode(PHP_EOL, $lines), 500)
                    ->header('Content-Type', 'text/plain; charset=UTF-8');
            }
        }

        $lines[] = '';
        $lines[] = 'Done. Remove DEPLOY_RUN_TOKEN from .env on the server.';

        return response(implode(PHP_EOL, $lines))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
