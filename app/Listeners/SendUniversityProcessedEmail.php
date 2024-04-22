<?php

namespace App\Listeners;

use App\Events\UniversityProcessed;
use Illuminate\Support\Facades\Mail;

class SendUniversityProcessedEmail
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
     * @param  \App\Events\UniversityProcessed  $event
     * @return void
     */
    public function handle(UniversityProcessed $event)
    {
        $admin = $event->university->getAdmin();

        Mail::to($admin->getEmail())->send(new \App\Mail\UniversityProcessed($event->university));
    }
}
