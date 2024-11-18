<?php

namespace SextaNet\LaravelOneclick\Commands;

use Illuminate\Console\Command;

class LaravelOneclickCommand extends Command
{
    public $signature = 'laravel-oneclick';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
