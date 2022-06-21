<?php

namespace LaravelEnso\Api\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApiCallError extends Notification implements ShouldQueue
{
    use Queueable;

    private ?User $user;

    public function __construct(
        private string $action,
        private string $url,
        private string|array $payload,
        private int|string $code,
        private string $message,
    ) {
        $this->user = Auth::user();
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
            ]))->when($this->user !== null, fn ($message) => $message
                ->line(__('Triggered by user id: :id ( :email )', [
                    'id' => $this->user->id,
                    'email' => $this->user->email,
                ])));
    }

    private function subject(): string
    {
        return __('API call for :action failed', [
            'action' => $this->action,
        ]);
    }
}
