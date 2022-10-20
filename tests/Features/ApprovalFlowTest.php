<?php

use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

use Spatie\Comments\Models\Comment;
use Spatie\Comments\Notifications\PendingCommentNotification;
use Spatie\Comments\Tests\TestSupport\Models\Post;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->currentUser = login();

    /** @var Post post */
    $this->post = Post::factory()->create();

    /** @var Comment comment */
    $this->comment = $this->post->comment('hello');
});

it('by default all comments will be approved', function () {
    expect($this->comment->isApproved())->toBeTrue();
});

test('auto approval can be turned off', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    $comment = $this->post->comment('hello');

    expect($comment->isPending())->toBeTrue();
    expect($comment->isApproved())->toBeFalse();
});

it('will automatically approve comments made by users that can approve comments', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    PendingCommentNotification::sendTo(fn () => $this->currentUser);

    $comment = $this->post->comment('hello');

    expect($comment->isApproved())->toBeTrue();
});

it('can explicitly approve a comment', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    $comment = $this->post->comment('hello');

    $comment->approve();

    expect($comment->isPending())->toBeFalse();
    expect($comment->isApproved())->toBeTrue();
});

it('will delete a rejected comment', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    $comment = $this->post->comment('hello');

    $comment->reject();

    expect(Comment::find($comment->id))->toBeNull();
});

it('can generate urls to approve and reject urls', function () {
    expect($this->comment->approveUrl())->toBeString();
    expect($this->comment->rejectUrl())->toBeString();
});

test('visiting the approve url of a commit will approve the comment', function () {
    $approveUrl = $this->comment->approveUrl();

    get($approveUrl)
        ->assertSuccessful()
        ->assertSee('Do you want to approve');

    post($approveUrl)
         ->assertSuccessful()
         ->assertSee('approved');

    expect($this->comment->refresh()->isApproved())->toBeTrue();
});

test('visiting the reject url of a commit will reject the comment', function () {
    $rejectUrl = $this->comment->rejectUrl();

    $this->get($rejectUrl)
        ->assertSuccessful()
        ->assertSee('Do you want to reject');

    $this->post($rejectUrl)
        ->assertSuccessful()
        ->assertSee('rejected');

    expect(Comment::count())->toBe(0);
});

it('will send notifications to a single user when a new comment should be approved', function () {
    Notification::fake();

    $user = User::factory()->create();

    config()->set('comments.automatically_approve_all_comments', false);

    PendingCommentNotification::sendTo(fn (Comment $comment) => $user);

    $this->post->comment('hello');

    Notification::assertSentTo($user, PendingCommentNotification::class);
});

it('will send notifications to multiple users when a new comment should be approved', function () {
    Notification::fake();

    $users = User::factory()->count(2)->create();

    config()->set('comments.automatically_approve_all_comments', false);

    PendingCommentNotification::sendTo(fn (Comment $comment) => $users);

    $this->post->comment('hello');

    foreach ($users as $user) {
        Notification::assertSentTo($user, PendingCommentNotification::class);
    }
});
