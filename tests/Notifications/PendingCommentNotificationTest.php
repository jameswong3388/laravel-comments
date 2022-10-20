<?php

use Illuminate\Support\Facades\Notification;
use Spatie\Comments\Notifications\PendingCommentNotification;
use Spatie\Comments\Tests\TestSupport\Models\Post;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    Notification::fake();

    $this->user = User::factory()->create();

    $this->post = Post::factory()->create();
});

test('the PendingCommentNotification can be rendered to mail', function () {
    $comment = $this->post->comment('my comment', $this->user);

    expect((string)(new PendingCommentNotification($comment))->toMail()->render())->toBeString();
});
