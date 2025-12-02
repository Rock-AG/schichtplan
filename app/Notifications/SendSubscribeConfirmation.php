<?php

namespace App\Notifications;

use App\Http\Controllers\PlanController;
use App\Models\Shift;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class SendSubscribeConfirmation extends Notification
{
    use Queueable;

    private Shift $shift;

    public function __construct(Shift $shift)
    {
        $this->shift = $shift;
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
        $shiftInfo = $this->shift->title;
        $shiftInfo .= '<br/>';
        $shiftInfo .= PlanController::buildDateString($this->shift->start, $this->shift->end, true);

        $message = (new MailMessage)
            ->greeting(__('general.mail.greeting'))
            ->subject(__('subscription.subscribeConfirmation'))
            ->line(__('subscription.subscribeConfirmationText'))
            ->line(new HtmlString($shiftInfo));
        
        // Add contact info if the shift has it
        if ($this->shift->hasContactInfo()) {
            if ($this->shift->contact_name) {
                $message->line(__('subscription.subscribeConfirmationContactInfo_withName', ['contact_info' => $this->shift->getContactInfo()]));
            } else {
                $message->line(__('subscription.subscribeConfirmationContactInfo_withoutName', ['contact_info' => $this->shift->getContactInfo()]));
            }
        }

        return $message;
    }
}
