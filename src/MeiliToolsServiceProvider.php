<?php

declare(strict_types=1);

namespace Dwarf\MeiliTools;

use Dwarf\MeiliTools\Actions\DetailIndex;
use Dwarf\MeiliTools\Actions\DetailModelIndex;
use Dwarf\MeiliTools\Actions\EnsureIndexExists;
use Dwarf\MeiliTools\Actions\SynchronizeIndex;
use Dwarf\MeiliTools\Actions\SynchronizeModelIndex;
use Dwarf\MeiliTools\Actions\ValidateIndexSettings;
use Dwarf\MeiliTools\Console\Commands\IndexDetails;
use Dwarf\MeiliTools\Console\Commands\ModelDetails;
use Dwarf\MeiliTools\Contracts\Actions\DetailsIndex;
use Dwarf\MeiliTools\Contracts\Actions\DetailsModelIndex;
use Dwarf\MeiliTools\Contracts\Actions\EnsuresIndexExists;
use Dwarf\MeiliTools\Contracts\Actions\SynchronizesIndex;
use Dwarf\MeiliTools\Contracts\Actions\SynchronizesModelIndex;
use Dwarf\MeiliTools\Contracts\Actions\ValidatesIndexSettings;
use Dwarf\MeiliTools\Contracts\Rules\ArrayAssocRule;
use Dwarf\MeiliTools\Rules\ArrayAssoc;
use Illuminate\Support\ServiceProvider;

class MeiliToolsServiceProvider extends ServiceProvider
{
    /**
     * Actions to bind.
     *
     * @var array
     */
    public array $bindings = [
        ArrayAssocRule::class         => ArrayAssoc::class,
        DetailsIndex::class           => DetailIndex::class,
        DetailsModelIndex::class      => DetailModelIndex::class,
        EnsuresIndexExists::class     => EnsureIndexExists::class,
        SynchronizesIndex::class      => SynchronizeIndex::class,
        SynchronizesModelIndex::class => SynchronizeModelIndex::class,
        ValidatesIndexSettings::class => ValidateIndexSettings::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/meilitools.php', 'meilitools');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                IndexDetails::class,
                ModelDetails::class,
            ]);

            $this->publishes([__DIR__ . '/../config/meilitools.php' => $this->app['path.config'] . '/meilitools.php']);
        }
    }
}