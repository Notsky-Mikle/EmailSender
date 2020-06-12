<?php

namespace App\Console\Commands\EmailSender;

use App\Services\SubscriberServices;
use Illuminate\Console\Command;
use Spatie\Newsletter\Newsletter;

class EmailSync extends Command
{
    protected $signature = 'emailsender:sync';
    protected $description = 'Sync all subscription';

    protected $subscribersService;


    public function __construct()
    {
        $this->subscribersService = new SubscriberServices();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Start process');

        try{
            $this->subscribersService->syncSubscriptionStatus(
                $this->subscribersService->getSubscribers(),
                'subscribers'
            );

            $this->line('Subscribers status sync successfully');
        }catch(\Exception $e){
            $this->error('Something went wrong!');
        }

    }
}
