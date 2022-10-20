<?php

use Illuminate\Support\Facades\Notification;
use Spatie\Comments\Notifications\PendingCommentNotification;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    Notification::fake();

    $this->user = User::factory()->create();

    $this->commentator = User::factory()->create();
});

test('the PendingCommentNotification can be rendered to mail', function () {
    $comment = $this->commentator->comment('my comment', $this->user);

    expect((string)(new PendingCommentNotification($comment))->toMail()->render())->toBeString();
});
