<?php

namespace App\Console\Commands;

use App\Mail\NotifyMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class sendDailySummaryMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:daily_summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $details = [
            'title'=>'test title',
            'body'=>'this is body'
        ];


 
      Mail::to('cchanaka90@gmail.com')->send(new NotifyMail($details));
      $this->info('done');
    }
}
