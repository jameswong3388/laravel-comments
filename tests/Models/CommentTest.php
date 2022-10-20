<?php

use Spatie\Comments\CommentTransformers\MarkdownToHtmlTransformer;
use Spatie\Comments\Exceptions\CannotCreateComment;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Support\CommentatorProperties;
use Spatie\Comments\Tests\TestSupport\Models\Post;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->currentUser = login();

    $this->post = Post::factory()->create();
});

it('can add comments', function ($transformer, $text) {
    config()->set('comments.comment_transformers', $transformer);

    $this->post->comment('my comment');

    expect($this->post->comments)->toHaveCount(1);

    expect($this->post->comments->first())
        ->commentator->toBeInstanceOf(User::class)
        ->text->toContain($text);
})->with([
    [null, 'my comment'],
    [MarkdownToHtmlTransformer::class, '<p>my comment</p>'],
]);

it('can update a comment', function ($transformer, $text) {
    config()->set('comments.comment_transformers', $transformer);

    $this->post->comment('my comment');

    $comment = $this->post->comments->first();

    $comment->update([
        'original_text' => 'updated comment',
    ]);

    expect(trim($comment->fresh()->text))->toBe($text);
})->with([
    [null, 'updated comment'],
    [MarkdownToHtmlTransformer::class, '<p>updated comment</p>'],
]);

it('can add multiple comments', function () {
    $this->post->comment('comment 1');
    $this->post->comment('comment 2');
    $this->post->comment('comment 3');

    expect($this->post->comments)->toHaveCount(3);
});

it('can nest comments', function () {
    $comment = $this->post->comment('comment 1');
    $comment->comment('comment 2');

    expect($this->post->comments)->toHaveCount(1);
    expect($this->post->comments->first()->comments)->toHaveCount(1);
});

it('will not create a comment if no one is logged in', function () {
    logout();

    $this->post->comment('my comment');
})->throws(CannotCreateComment::class);

it('can create a comment for a specific user', function () {
    $anotherUser = User::factory()->create();

    $this->post->comment('my comment', $anotherUser);

    expect($this->post->comments->first()->commentator)->isModel($anotherUser);
});

it('can create a nested comment', function () {
    $this->post->comment('top level comment');
    /** @var Comment $topLevelComment */
    $topLevelComment = Comment::first();

    $topLevelComment->comment('nested comment');
    $nestedComment = Comment::find(2);

    expect($topLevelComment->isTopLevel())->toBeTrue();
    expect($nestedComment->isTopLevel())->toBeFalse();
});

it('has a relation to get nested comments', function () {
    $this->post->comment('top level comment');
    /** @var Comment $topLevelComment */
    $topLevelComment = Comment::first();

    $topLevelComment->comment('nested comment');
    $nestedComment = Comment::find(2);

    expect($topLevelComment->nestedComments)->toHaveCount(1);
    expect($topLevelComment->nestedComments->first())->isModel($nestedComment);
    expect($nestedComment->nestedComments)->toHaveCount(0);
});

it('can get the commentator properties', function () {
    $comment = $this->post->comment('comment');

    expect($comment->commentatorProperties())->toBeInstanceOf(CommentatorProperties::class);
    expect($comment->commentatorProperties()->email)->toBe(auth()->user()->email);
});

it('can determine that a comment was made by a certain commentator', function () {
    $commentByCurrentUser = $this->post->comment('comment');
    $anotherUser = User::factory()->create();

    expect($commentByCurrentUser->madeBy($this->currentUser))->toBeTrue();
    expect($commentByCurrentUser->madeBy($anotherUser))->toBeFalse();

    $commentByAnotherUser = $this->post->comment('comment', $anotherUser);
    expect($commentByAnotherUser->madeBy($this->currentUser))->toBeFalse();
    expect($commentByAnotherUser->madeBy($anotherUser))->toBeTrue();
});

it('can determine that the commentator of a comment was deleted', function () {
    $comment = $this->post->comment('comment');
    expect($comment->commentator)->not()->toBeNull();
    expect($comment->wasMadeByDeletedCommentator())->toBeFalse();

    $this->currentUser->delete();
    $comment = $comment->refresh();

    expect($comment->commentator)->toBeNull();
    expect($comment->commentatorProperties())->toBeNull();
    expect($comment->wasMadeByDeletedCommentator())->toBeTrue();
    expect($comment->wasMadeByDeletedCommentator())->toBeTrue();
});

it('will determine that an anonymous comment was not made by a deleted user', function () {
    auth()->logout();
    config()->set('comments.allow_anonymous_comments', true);

    $comment = $this->post->comment('my comment', null);
    expect($comment->commentator)->toBeNull();

    expect($comment->wasMadeAnonymously())->toBeTrue();
    expect($comment->wasMadeByDeletedCommentator())->toBeFalse();
});

it('will determine that a comment by a deleted user was not made by an anonymous user', function () {
    $comment = $this->post->comment('comment by current user');

    $this->currentUser->delete();
    expect($comment->wasMadeAnonymously())->toBeFalse();
});

it('has a scope to only get approved comments', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    $comment = $this->post->comment('comment by current user');
    expect(Comment::approved()->get())->toHaveCount(0);

    $comment->approve();
    expect(Comment::approved()->get())->toHaveCount(1);
});

it('has a scope to only get pending comments', function () {
    config()->set('comments.automatically_approve_all_comments', false);

    $comment = $this->post->comment('comment by current user');
    expect(Comment::pending()->get())->toHaveCount(1);

    $comment->approve();
    expect(Comment::pending()->get())->toHaveCount(0);
});
