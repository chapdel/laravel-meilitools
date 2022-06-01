<?php

declare(strict_types=1);

namespace Dwarf\MeiliTools\Tests\Feature\Commands;

use Dwarf\MeiliTools\Contracts\Actions\SynchronizesModel;
use Dwarf\MeiliTools\Helpers;
use Dwarf\MeiliTools\Tests\Models\MeiliMovie;
use Dwarf\MeiliTools\Tests\Models\Movie;
use Dwarf\MeiliTools\Tests\TestCase;

/**
 * @internal
 */
class ModelDetailsTest extends TestCase
{
    /**
     * Test `meili:model:details` command with default settings.
     *
     * @return void
     */
    public function testWithDefaultSettings(): void
    {
        try {
            $values = Helpers::convertIndexSettingsToTable(Helpers::defaultSettings());

            $this->artisan('meili:model:details')
                ->expectsQuestion('What is the model class?', Movie::class)
                ->expectsTable(['Setting', 'Value'], $values)
                ->assertSuccessful()
            ;

            $this->artisan('meili:model:details', ['model' => Movie::class])
                ->expectsTable(['Setting', 'Value'], $values)
                ->assertSuccessful()
            ;
        } finally {
            $this->deleteIndex((new Movie())->searchableAs());
        }
    }

    /**
     * Test `meili:model:details` command with advanced settings.
     *
     * @return void
     */
    public function testWithAdvancedSettings(): void
    {
        try {
            $settings = MeiliMovie::meiliSettings();

            $changes = $this->app->make(SynchronizesModel::class)(MeiliMovie::class);
            $this->assertNotEmpty($changes);

            $values = Helpers::convertIndexSettingsToTable($settings);

            $this->artisan('meili:model:details', ['model' => MeiliMovie::class])
                ->expectsTable(['Setting', 'Value'], $values)
                ->assertSuccessful()
            ;
        } finally {
            $this->deleteIndex((new MeiliMovie())->searchableAs());
        }
    }
}
