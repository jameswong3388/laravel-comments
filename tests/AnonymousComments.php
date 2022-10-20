<?php

use Spatie\Comments\Exceptions\CannotCreateComment;
use Spatie\Comments\Tests\TestSupport\Models\Post;

beforeEach(function () {
    $this->post = Post::factory()->create();
});

it('will not allow anonymous comments by default', function () {
    $this->post->comment('test');
})->throws(CannotCreateComment::class);

it('can allow anonymous comments', function () {
    config()->set('comments.allow_anonymous_comments', true);

    $comment = $this->post->comment('test');

    expect($comment->commentator)->toBeNull();
});
