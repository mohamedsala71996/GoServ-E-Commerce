<?php

namespace App\Notifications;

use App\Models\ProductReview;
use App\Models\ReviewReply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewReplyNotification extends Notification
{
    use Queueable;

    protected $reply;
    protected $review;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\ReviewReply $reply
     */
    public function __construct(ReviewReply $reply ,ProductReview  $review)
    {
        $this->reply = $reply;
        $this->review = $review;
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
            'review_id' => $this->reply->review_id,
            'review_comment' => $this->review->comment,
            'admin_id' => $this->reply->admin_id,
            'admin_name' => $this->reply->admin->name, // Add admin name if needed
        ];
    }
}
