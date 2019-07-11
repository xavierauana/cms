<?php

namespace Anacreation\Cms\Console\Commands;

use Illuminate\Console\Command;

class ReloadPhpFpm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:reload_php_fpm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload php fpm cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if ($phpVersion = config('cms.reload_php_fpm', null)) {
            sprintf('echo "" | sudo -S service php%s-fpm reload', $phpVersion);

            $msg = sprintf("%s-fpm has been reload!", $phpVersion);
            $this->info($msg);
        }
    }
}
