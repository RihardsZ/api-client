<?php

namespace CubeSystems\SoapClient\Commands;

use Illuminate\Console\Command;

class SoapClientCommand extends Command
{
    public $signature = 'soap-client';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
