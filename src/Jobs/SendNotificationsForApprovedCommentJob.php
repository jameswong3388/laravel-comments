<?php

namespace Spatie\Comments\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Spatie\Comments\Enums\NotificationSubscriptionType;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Support\Config;

class SendNotificationsForApprovedCommentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Comment $comment)
    {
    }

    public function handle()
    {
        $commentable = $this->getCommentable();

        $this
            ->getSubscribers($commentable)
            ->each(function (CanComment $subscriber) {
                $notification = Config::getApprovedCommentNotification($this->comment, $subscriber);

                return $subscriber->notify($notification);
            });
    }

    protected function getCommentable(): Model
    {
        /** @var \Spatie\Comments\Models\Concerns\HasComments $commentable */
        return $this->comment->topLevel()->commentable;
    }

    protected function getSubscribers(Model $commentable): Collection
    {
        $subscribingToAll = $commentable->subscribers(NotificationSubscriptionType::All);

        $subscribingToParticipating = $commentable->subscribers(NotificationSubscriptionType::Participating);

        $participatingCommentators = $commentable->participatingCommentators()
            ->filter(fn (CanComment $participatingUser) => $subscribingToParticipating->contains($participatingUser));

        return $subscribingToAll
            ->merge($participatingCommentators)
            ->unique(fn (CanComment $comment) => $comment::class . '-' . $comment->getKey())
            ->reject(fn (CanComment $subscriber) => $subscriber->is($this->comment->commentator));
    }
}
