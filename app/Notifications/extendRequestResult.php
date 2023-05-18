<?php

namespace App\Notifications;

use App\Main\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class extendRequestResult extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $mail = (new MailMessage)
            ->subject(($this->data['extend'] == "A" ? "[Đồng ý chuyển xử lý] " : "[Từ chối chuyển xử lý] "). $this->data['subject'])
            ->priority(2)
            ->action('Chi tiết', url(config('app.url').'/moderator?tab=extend-return-req-tab&id='.$this->data['request_id']))
            ->line('<span style="font-weight:bold;">Họ tên: </span>'.$this->data['requester']['name'] ." - ".$this->data['department']['department_name'])
            ->line('<span style="font-weight:bold;">Ngày tạo: </span>'.$this->data['created_at'])
            ->line('<span style="font-weight:bold;">Nội dung yêu cầu</span>')
            ->line($this->data['content']);
            if ($this->data['assign_content']){
               $mail->line('<span style="font-weight:bold;">Giao việc: </span>'.$this->data['assign']['name'])
               ->line($this->data['assign_content']);
            }
            $mail->line('<span style="font-weight:bold;">Xử lý chính: </span>'.$this->data['handler']['name'])
            ->line($this->data['handle_content'])
            ->markdown('emails.requestMail', [
                'status' => $this->data['status'],
                'subject' => $this->data['subject'],
                'statusList' => Utils::STATUS_LIST,
                'statusColor' => Utils::STATUS_COLOR,
                'sender' => $this->data['requester']['name'],
                'photo' => $this->data['requester']['photo'],
                ]);
        return $mail;
    }
}
