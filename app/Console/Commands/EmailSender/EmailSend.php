<?php

namespace App\Console\Commands\EmailSender;

use App\Services\SubscriberServices;
use Illuminate\Console\Command;
use Newsletter;


class EmailSend extends Command
{
    protected $signature = 'emailsender:send';
    protected $description = 'Send all email to mailchimp list';

    protected $subscribersService;

    public function __construct()
    {
        $this->subscribersService = new SubscriberServices();

        parent::__construct();
    }


    public function handle()
    {
        $this->line('Start process');

        try{
            $this->subscribersService->addSubscribersToList(
                $this->subscribersService->getSubscribers(),
                'subscribers'
            );

            $this->line('Emails sent successfully');
        }catch(\Exception $e){
            $this->error('Something went wrong!');
        }

    }
}
