<?php

use Illuminate\Support\Facades\Notification;
use Spatie\Comments\Enums\NotificationSubscriptionType;
use Spatie\Comments\Notifications\ApprovedCommentNotification;
use Spatie\Comments\Tests\TestSupport\Models\Post;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    Notification::fake();

    /** @var User firstUser */
    $this->firstUser = User::factory()->create();
    $this->secondUser = User::factory()->create();
    $this->thirdUser = User::factory()->create();

    $this->firstPost = Post::factory()->create();
    $this->secondPost = Post::factory()->create();
});

test('by default it will not send any notifications', function () {
    $this->firstPost->comment('my comment', $this->firstUser);

    Notification::assertNothingSent();
});

it('will send a notification to all persons subscribed', function () {
    $this->secondUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::All);

    $this->firstPost->comment('my comment', $this->firstUser);

    Notification::assertSentTo($this->secondUser, ApprovedCommentNotification::class);
});

it('can unsubscribe from notifications', function () {
    $this->secondUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::All);

    $this->firstPost->comment('my comment', $this->firstUser);

    Notification::assertSentToTimes($this->secondUser, ApprovedCommentNotification::class, 1);

    $this->secondUser->unsubscribeFromCommentNotifications($this->firstPost);
    $this->firstPost->comment('my comment', $this->firstUser);
    Notification::assertSentToTimes($this->secondUser, ApprovedCommentNotification::class, 1);
});

it('will not send a notification to the author of the comment', function () {
    $this->firstUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::All);
    $this->secondUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::All);

    $this->firstPost->comment('my comment', $this->firstUser);

    Notification::assertNotSentTo($this->firstUser, ApprovedCommentNotification::class);
    Notification::assertSentTo($this->secondUser, ApprovedCommentNotification::class);
});

it('will can only send a notification to participators', function () {
    $this->secondUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::Participating);

    $this->firstPost->comment('my comment', $this->firstUser);
    Notification::assertNotSentTo($this->secondUser, ApprovedCommentNotification::class);

    $this->firstPost->comment('my comment', $this->secondUser);
    Notification::assertNotSentTo($this->secondUser, ApprovedCommentNotification::class);

    $this->firstPost->comment('my comment', $this->firstUser);
    Notification::assertSentTo($this->secondUser, ApprovedCommentNotification::class);
});

it('can determine the type of the notification subscription', function () {
    expect($this->firstUser->notificationSubscriptionType($this->firstPost))->toBeNull();

    $this->firstUser->subscribeToCommentNotifications($this->firstPost, NotificationSubscriptionType::Participating);

    expect($this->firstUser->notificationSubscriptionType($this->firstPost))->toBe(NotificationSubscriptionType::Participating);

    $this->firstUser->unsubscribeFromCommentNotifications($this->firstPost);

    expect($this->firstUser->notificationSubscriptionType($this->firstPost))->toBe(NotificationSubscriptionType::None);
});

test('the ApprovedCommentNotification can be rendered to mail', function () {
    $comment = $this->firstPost->comment('my comment', $this->firstUser);

    expect((string)(new ApprovedCommentNotification($comment, $this->firstUser))->toMail()->render())->toBeString();
});
