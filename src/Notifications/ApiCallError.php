<?php

namespace LaravelEnso\Api\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApiCallError extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $action,
        private readonly string $url,
        private readonly string|array $payload,
        private readonly int|string $code,
        private readonly string $message,
    ) {
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $app = Config::get('app.name');

        return (new MailMessage())
            ->subject("[ {$app} ] {$this->subject()}")
            ->greeting(__('Hi :name,', [
                'name' => $notifiable->person->appellative(),
            ]))->line(__('The action :action failed on :url with the following error code: :code', [
                'action' => $this->action,
                'url' => $this->url,
                'code' => $this->code,
            ]))->line(__('Reported error message: :message', [
                'message' => $this->message,
            ]))->line(__('Request payload: :payload', [
                'payload' => json_encode($this->payload, JSON_THROW_ON_ERROR),
            ]))->when(Auth::check(), fn ($message) => $message
                ->line(__('Triggered by user id: :id ( :email )', [
                    'id' => Auth::id(),
                    'email' => Auth::user()->email,
                ])));
    }

    private function subject(): string
    {
        return __('API call for :action failed', [
            'action' => $this->action,
        ]);
    }
}
