<?php

declare(strict_types=1);

use ArtisanBuild\Turbulence\Providers\TurbulenceServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Thunk\Verbs\Contracts\StoresEvents;
use Thunk\Verbs\Event;

$appPath = $argv[1] ?? null;

if (! is_string($appPath) || ! is_file($appPath.'/bootstrap/app.php')) {
    throw new InvalidArgumentException('Pass the path to an installed Laravel application.');
}

require $appPath.'/vendor/autoload.php';

final class NoDevSmokeEvent extends Event
{
    public function __construct(public string $message) {}
}

try {
    $app = require $appPath.'/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    if ($app->getProvider(TurbulenceServiceProvider::class) === null) {
        throw new RuntimeException('Turbulence was not discovered.');
    }

    config()->set([
        'cache.default' => 'array',
        'database.default' => 'sqlite',
        'database.connections.sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],
        'verbs.autocommit' => false,
    ]);

    foreach (glob($appPath.'/vendor/hirethunk/verbs/database/migrations/*.php') as $migration) {
        (require $migration)->up();
    }

    NoDevSmokeEvent::commit(message: 'production install');

    $loaded = app(StoresEvents::class)->read()->first();

    if (! $loaded instanceof NoDevSmokeEvent || $loaded->message !== 'production install') {
        throw new RuntimeException('The committed Verbs event could not be loaded.');
    }

    echo "production-only Verbs smoke passed\n";
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage()."\n");
    exit(1);
}
