<?php

namespace MakeWeb\Squish;

use Silly\Application;

class CLI extends Application
{
    protected $commands = [
        \MakeWeb\Squish\Commands\ChangeDomain::class,
        \MakeWeb\Squish\Commands\ExportDatabase::class,
    ];

    public function boot()
    {
        $this->registerCommands();

        $this->run();
    }

    protected function registerCommands()
    {
        foreach ($this->commands as $className) {
            (new $className)->register($this);
        }
    }
}
