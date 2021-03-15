<?php

namespace LaravelEnso\Api\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Throwable;

class ApiCallError extends Notification implements ShouldQueue
{
    use Queueable;

    private string $action;
    private array $payload;
    private int $code;
    private string $message;

    public function __construct(string $action, array $payload, Throwable $exception)
    {
        $this->action = $action;
        $this->payload = $payload;
        $this->code = $exception->getCode();
        $this->message = $exception->getMessage();
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__('[ :app ] API call error', [
                'app' => Config::get('app.name'),
            ]))->greeting(__('Hi :name,', [
                'name' => $notifiable->person->appellative(),
            ]))->line(__('The action :action failed with the following error code: :code', [
                'action' => $this->action,
                'code' => $this->code,
            ]))->line(__('Reported error message: :message', [
                'message' => $this->message,
            ]))->line(__('Request payload: :payload', [
                'payload' => json_encode($this->payload),
            ]));
    }
}
