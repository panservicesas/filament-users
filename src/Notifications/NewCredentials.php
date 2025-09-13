<?php

namespace  Panservice\FilamentUsers\Notifications;

use Filament\Facades\Filament;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCredentials extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'mail',
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');

        return (new MailMessage())
            ->subject(
                __('filament-users::filament-users.new_credentials.subject', [
                    'app_name' => $appName
                ])
            )
            ->view('filament-users::emails.new_password', [
                'content' =>
                    __('filament-users::filament-users.new_credentials.greeting', [
                        'name' => $this->data['name']
                    ]) . '<br/><br/>' .
                    __('filament-users::filament-users.new_credentials.new_password') . '<br/>' .
                    $this->data['password'] . '<br/><br/>' .
                    __('filament-users::filament-users.new_credentials.login_action', [
                        'action' => '<a href="' . Filament::getLoginUrl() . '" target="_blank">' .
                            __('filament-users::filament-users.new_credentials.here') .
                            '</a>'
                    ])
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

