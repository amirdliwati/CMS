<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Delete:Files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Temp Files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = new Filesystem;
        $file->cleanDirectory(public_path('uploads/Temp'));

        $this->info('Files have been deleted successfully');
    }
}
