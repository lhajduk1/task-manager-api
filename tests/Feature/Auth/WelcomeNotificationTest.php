<?php

use App\Models\User;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('welcome notification can be sent', function (): void {
    Notification::fake();

    $this->postJson(route('auth.register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo(
        [$user],
        WelcomeNotification::class,
        function (WelcomeNotification $notification, array $channels) use ($user): bool {
            expect($channels)->toContain('mail');

            $mail = $notification->toMail($user);

            expect($mail)->toBeInstanceOf(MailMessage::class);

            expect($mail->subject)->toBe('Welcome to our application');
            expect($mail->greeting)->toBe($user->name . ' welcome to our application!');
            expect($mail->introLines)->toContain('Your account has been created successfully.');
            expect($mail->actionText)->toBe('Visit our website');
            expect($mail->actionUrl)->toBe(url('/'));
            expect($mail->outroLines)->toContain('Thank you for using our application!');

            return true;
        }
    );
});
