<?php

namespace App\Listeners;

use App\Events\SendAnnouncementNotifications;
use App\Models\Announcement;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use App\Models\Workshop;
use App\Notifications\NotifyUserOfAnnouncement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PublishAnnouncement implements ShouldQueue
{

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    protected $queue = 'announcements-queue';
    public function viaQueue(): string
    {
        return $this->queue;
    }
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendAnnouncementNotifications  $event
     * @return void
     */
    public function handle(SendAnnouncementNotifications $event)
    {
        $announcement = Announcement::where('id' , $event->announcement_id)->first();
        $announcement->update(['status' => 'sending']);
        if (isset($announcement->users)){
            foreach (json_decode($announcement->users, false, 512, JSON_THROW_ON_ERROR) as $user_id){
                $user = User::where('id' , $user_id)->first();
                if (isset($user->id)){
                    $user->notify(new NotifyUserOfAnnouncement($announcement));
                }
            }
            $announcement->update(['status' => 'sent']);
        }elseif (isset($announcement->workshop_id)){
            $workshop = Workshop::where('id' , $announcement->workshop_id)->first();
            //Log::info($workshop->id);
            foreach ($workshop->members() as $ticket){
                $user = User::where('id' , $ticket->user_id)->first();
                $user->notify(new NotifyUserOfAnnouncement($announcement));
            }
            $announcement->update(['status' => 'sent']);
        }
       /* elseif (isset($announcement->classroom_id)){
            $classroom = Classroom::where('id' , $announcement->classroom_id)->first();
            foreach ($classroom->users()->get() as $user){
                $user->notify(new NotifyUserOfAnnouncement($announcement));
            }
            $announcement->update(['status' => 'sent']);
        }*/
    }
}
