<?php

namespace Anacreation\Cms\Console\Commands;

use Illuminate\Console\Command;

class UpdateDefaultAppConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:cms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add to app.php file';

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
        $path = base_path('config/app.php');

        $content = file_get_contents($path);


        if ($start_index = strpos($content, "'providers'")) {

            $end_index = strpos($content, "]", $start_index);

            $firstPart = substr($content, 0, $end_index - 1);

            $secondPart = substr($content, $end_index);

            $string = "\Anacreation\Cms\CmsRoutesServiceProvider::class";

            $firstPart .= "\n\t\t{$string}, \n\t";

            $appConfigFile = fopen($path, "w");

            fwrite($appConfigFile, $firstPart . $secondPart);

            fclose($appConfigFile);

            var_dump('done');
        }

    }
}
