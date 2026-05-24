<?php

namespace App\Listeners;

use App\Events\Auth\UserRegistered;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Support\Facades\Notification;

#[Queue('emails')]
class SendWelcomeNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Notification::send($event->user, new WelcomeNotification());
    }
}
