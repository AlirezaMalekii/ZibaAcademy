<?php

namespace App\Notifications;

use App\Models\Announcement;
use App\Models\KavenegarTemplate;
use App\Notifications\Messages\KavenegarMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyUserOfAnnouncement extends Notification implements ShouldQueue
{
    use Queueable;


    public function viaQueues(): array
    {
        return [
//            'mail' => 'mail-queue',
            'database' => 'database-queue',
            KavenegarChannel::class => 'kavenegar-queue',
        ];
    }


    protected $announcement;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        foreach (json_decode($this->announcement->drivers) as $driver) {
            if ($driver === "kavenegar") {
                $drivers[] = KavenegarChannel::class;
            }
            if ($driver === "local") {
                $drivers[] = 'database';
            }
        }

        return $drivers;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'message' => $this->announcement->message,
        ];
    }

    public function toKavenegar(object $notifiable): KavenegarMessage
    {
        $kavenegar_data = json_decode($this->announcement->kavenegar_data, false, 512, JSON_THROW_ON_ERROR);
        $template = KavenegarTemplate::where('id', $kavenegar_data->template_id)->firstOrFail();
        return (new KavenegarMessage(
            $notifiable->phone,
            $template->name,
            $kavenegar_data->token1,
            $kavenegar_data->token2,
            $kavenegar_data->token3,
            $kavenegar_data->kavenegar_send_method
        ));
    }


}
