<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Main\Utils;
use Illuminate\Support\HtmlString;

class PurchaseDateMail extends Notification
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
        $mailBody = '';
        foreach($this->data as $item) {
            $mailBody .= '<tr><td style="border: 1px solid;">'. $item['subject'] .'</td><td style="border: 1px solid;"><a href="'. url($this->getLink($item['status']).$item['request_id']) .'">link</a></td></tr>';
        }
        $mail = (new MailMessage)
            ->subject('Danh sách yêu cầu chuẩn bị tới hạn.' )
            ->priority(2)
            ->line(new HtmlString('<p style="font-weight:bold;">Yêu cầu tới hạn xử lý</span>'))
            ->line(new HtmlString('<table style="border: 1px solid; width:100%"><tr><th style="border: 1px solid;">Tiêu đề</th><th style="border: 1px solid;"></th><tr/><tbody>'))
            ->line(new HtmlString($mailBody))
            ->line(new HtmlString('</tbody></table>'))
            ->markdown('emails.purchaseDateMail', [
                'subject' => 'Danh sách yêu cầu chuẩn bị tới hạn.',
                ]);
        if (isset($this->data['cc_email'])) {
            $mail->cc(explode(',', $this->data['cc_email']));
        }
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

    public function getLink($status){
        if ($status == Utils::YEU_CAU_MOI) {
            return '/administrator?tab=new-req-tab&id=';
        } else if ($status == Utils::TIEP_NHAN){
            return '/administrator?tab=new-req-tab&id=';
        } else if ($status == Utils::GIA_HAN){
            return '/administrator?tab=extend-return-req-tab&id=';
        } else if ($status == Utils::DANG_XU_LY){
            return '/moderator?tab=handle-req-tab&id=';
        } else if ($status == Utils::CHUYEN_XU_LY){
            return '/administrator?tab=extend-return-req-tab&id=';
        }
    }
}
