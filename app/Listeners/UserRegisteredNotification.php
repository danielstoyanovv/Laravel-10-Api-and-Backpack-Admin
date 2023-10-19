<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserRegisteredNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $adminUsers = User::where('is_admin', 1)->get();
        if ($adminUsers->count() > 0) {
            foreach ($adminUsers as $adminUser) {
                $email = new \App\Mail\UserRegistered($event->name, $event->email);
                Mail::to($adminUser->email)->send($email);
            }
        }
    }
}
