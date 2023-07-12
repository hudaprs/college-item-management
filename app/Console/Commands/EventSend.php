<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Event;

class EventSend extends Command
{
    protected $signature = 'event:send';

    protected $description = 'Send daily email to all users!';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $events = Event::all();

        foreach($events as $event) :           
            $start = date('m-d-Y', strtotime($event->start));
            $before = date('m-d-Y', strtotime('-1 day', strtotime($event->start)));
            $passed = date('m-d-Y', strtotime('+1 day', strtotime($event->start)));
            $reminder = date('m-d-Y', strtotime($event->reminder_date));
            $emails = $event->event_has_user->email;

            if($start == date('m-d-Y')) {
                Mail::send('notificationEmail', ['event' => $event], function($message) use ($emails, $event) {
                    $message->from('opscmsdashboard@gmail.com', 'Ops Dashboard CMS');
                    $message->subject('Notification For ' . $event->title);
                    $message->to($emails);
                });
            }

            if($before == date('m-d-Y')) { 
                Mail::send('notificationEmail', ['event' => $event], function($message) use ($emails, $event) {
                    $message->from('opscmsdashboard@gmail.com', 'Ops Dashboard CMS');
                    $message->subject('Notification For ' . $event->title);
                    $message->to($emails);
                });
            }

            if($passed == date('m-d-Y')) {
                Mail::send('notificationEmail', ['event' => $event], function($message) use ($emails, $event) {
                    $message->from('opscmsdashboard@gmail.com', 'Ops Dashboard CMS');
                    $message->subject('Event ' . $event->title . ' Passed');
                    $message->to($emails);
                });
            }

            if($reminder == date('m-d-Y')) {
                Mail::send('notificationEmail', ['event' => $event], function($message) use ($emails, $event) {
                    $message->from('opscmsdashboard@gmail.com', 'Ops Dashboard CMS');
                    $message->subject('Event Reminder For ' . $event->title);
                    $message->to($emails);
                });
            }
        endforeach;

        $this->info('Event Sended');
    }
}
