<?php

namespace App\Notifications;

use App\Http\Controllers\PlanController;
use App\Models\Shift;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class SendSubscribeConfirmation extends Notification
{
    use Queueable;

    private Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
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
        $message = (new MailMessage)
            ->subject(__('subscription.subscribeConfirmation'))
            ->greeting(__('general.mail.greeting', ['nickname' => $this->subscription->nickname]))
            ->line(__('subscription.subscribeConfirmationText'))
            ->line(new HtmlString('<b>' . $this->subscription->shift->buildShiftInfoForEmail() . '</b>'))
            ->salutation(new HtmlString(__('general.mail.salutation')))
        ;
        
        // Add contact info if the shift has it
        if ($this->subscription->shift->hasContactInfo()) {
            if ($this->subscription->shift->contact_name) {
                $message->line(__('subscription.subscribeConfirmationContactInfo_withName', ['contact_info' => $this->subscription->shift->getContactInfo()]));
            } else {
                $message->line(__('subscription.subscribeConfirmationContactInfo_withoutName', ['contact_info' => $this->subscription->shift->getContactInfo()]));
            }
        }

        return $message;
    }
}
