<?php

namespace App\Notifications;

use App\Models\Question;
use App\Models\QuestionReply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QestionReplyNotification extends Notification
{
    use Queueable;

    protected $reply;
    protected $question;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\QuestionReply $reply
     */
    public function __construct(QuestionReply $reply ,Question  $question)
    {
        $this->reply = $reply;
        $this->question = $question;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Only use database for notifications
        return ['database'];
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
            'reply' => $this->reply->reply,
            'question_id' => $this->reply->question_id,
            'question_comment' => $this->question->comment,
            'admin_id' => $this->reply->admin_id,
            'admin_name' => $this->reply->admin->name, // Add admin name if needed
        ];
    }
}
