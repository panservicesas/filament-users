<?php

namespace Panservice\FilamentUsers\Commands;

use Illuminate\Console\Command;

class FilamentUsersCommand extends Command
{
    public $signature = 'filament-users';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
