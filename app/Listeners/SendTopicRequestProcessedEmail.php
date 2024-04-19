<?php

namespace App\Listeners;

use App\Events\TopicRequestProcessed;
use Illuminate\Support\Facades\Mail;

class SendTopicRequestProcessedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @param  \App\Events\TopicRequestProcessed  $event
     * @return void
     */
    public function handle(TopicRequestProcessed $event)
    {
        $topicRequest = $event->topicRequest;

        $student = $topicRequest->getStudent();

        Mail::to($student->getUser()->getEmail())->send(new \App\Mail\TopicRequestProcessed($topicRequest, $student));
    }
}
