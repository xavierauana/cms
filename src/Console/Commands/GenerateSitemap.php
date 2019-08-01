<?php

namespace Anacreation\Cms\Console\Commands;

use Anacreation\Cms\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:generate_sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap to sitemap.xml';

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
        SitemapGenerator::make();
        $filename = config('cms.sitemap_file_name');
        $msg = sprintf('New %s file is generated!', $filename);
        $this->info($msg);
    }
}
