<?php

namespace App\Listeners;

use App\Events\CatalogActivation;
use App\Models\Group;
use Illuminate\Support\Facades\Mail;

class SendCatalogActivationEmail
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
     * @param  \App\Events\CatalogActivation  $event
     * @return void
     */
    public function handle(CatalogActivation $event)
    {
        $catalog = $event->catalog;

        /** @var Group $group */
        foreach ($catalog->getGroups()->get() as $group) {
            foreach ($group->getGroup()->getStudents() as $student) {
                Mail::to($student->getUser()->getEmail())->send(new \App\Mail\CatalogActivation($catalog, $student));
            }
        }
    }
}
