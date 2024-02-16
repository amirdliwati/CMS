<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon;

class LegalDocs extends Notification
{
    use Queueable;

    protected $notify;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notify)
    {
        $this->notify = $notify;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            "type"=>'LegalDoc',
            "date"=>Carbon::parse($this->notify->end_valid)->isoFormat('D MMM YY'),
            "notify_color"=>'warning',
            "message"=>'Document for'.' '.$this->notify->employee->first_name.' '.$this->notify->employee->last_name,
            "action"=>'expire',
            "action_color"=>'danger'
        ];
    }
}
