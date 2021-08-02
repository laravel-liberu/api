<?php

namespace LaravelEnso\Api\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class ApiCallError extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $action,
        private string $url,
        private array $payload,
        private int | string $code,
        private string $message
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
                'payload' => json_encode($this->payload),
            ]));
    }

    private function subject(): string
    {
        return __('API call for :action failed', [
            'action' => $this->action,
        ]);
    }
}
