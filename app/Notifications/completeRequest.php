<?php

namespace App\Notifications;

use App\Main\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class completeRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $data;

    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $link)
    {
        $this->data = $data;
        $this->link = $link;
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
        $mail = (new MailMessage)
            ->subject('['.$this->data['status'] == 'X' ? "Từ chối" : "Hoàn thành] ".$this->data['subject'])
            ->priority(2)
            ->action('Chi tiết', url(config('app.url').$this->link))
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
