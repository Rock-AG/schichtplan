<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendUnsubscribeConfirmation extends Notification
{
    use Queueable;

    private string $unsubscribeLink;
    private string $title;
    private Subscription $subscription;

    /**
     * Create a new notification instance.
     *
     */
    public function __construct(string $unsubscribeLink, Subscription $subscription)
    {
        $this->unsubscribeLink = $unsubscribeLink;
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('subscription.unsubscribeConfirmation'))
            ->greeting(__('general.mail.greeting', ['nickname' => $this->subscription->nickname]))
            ->line(__('subscription.confirmUnsubscribe'))
            ->action(__('subscription.unsubscribe'), $this->unsubscribeLink)
            ->line(__('subscription.confirmUnsubscribeShiftInfo'))
            ->line(new HtmlString('<b>' . $this->subscription->shift->buildShiftInfoForEmail() . '</b>'))
            ->line(__('subscription.confirmUnsubscribeEnd'))
            ->salutation(new HtmlString(__('general.mail.salutation')))
        ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
