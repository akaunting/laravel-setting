<?php

namespace Akaunting\Setting;

use Akaunting\Setting\Drivers\Database;
use Akaunting\Setting\Drivers\Json;
use Akaunting\Setting\Drivers\Memory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Normalized Laravel Version.
     *
     * @var string
     */
    protected $version;

    /**
     * True when this is a Lumen application.
     *
     * @var bool
     */
    protected $is_lumen = false;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }

        parent::__construct($app);

        $this->version = $app->version();
        $this->is_lumen = str_contains($this->version, 'Lumen');
    }

    public function getDefaultDriver()
    {
        return config('setting.driver');
    }

    public function createJsonDriver()
    {
        $path = config('setting.json.path');

        return new Json($this->app['files'], $path);
    }

    public function createDatabaseDriver()
    {
        $connection = $this->app['db']->connection(config('setting.database.connection'));
        $table = config('setting.database.table');
        $key = config('setting.database.key');
        $value = config('setting.database.value');

        return new Database($connection, $table, $key, $value);
    }

    public function createMemoryDriver()
    {
        return new Memory();
    }

    public function createArrayDriver()
    {
        return $this->createMemoryDriver();
    }
}
