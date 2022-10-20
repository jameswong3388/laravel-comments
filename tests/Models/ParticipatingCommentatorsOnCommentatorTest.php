<?php

use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->firstUser = User::factory()->create();
    $this->secondUser = User::factory()->create();
    $this->thirdUser = User::factory()->create();

    $this->firstCommentator = User::factory()->create();
});

it('will not return the original user of a comment when called on a comment', function () {
    $comment = $this->firstCommentator->comment('my comment', $this->firstUser);

    expect($comment->participatingCommentators())->toHaveCount(0);
});

it('will return other users that have commented on the same thing when called on a comment', function () {
    $firstComment = $this->firstCommentator->comment('my comment', $this->firstUser);
    $secondComment = $this->firstCommentator->comment('my comment', $this->secondUser);

    expect($firstComment->refresh()->participatingCommentators())
        ->toHaveCount(1)
        ->first()->id->toBe($this->secondUser->id);

    expect($secondComment->refresh()->participatingCommentators())
        ->toHaveCount(1)
        ->first()->id->toBe($this->firstUser->id);
});

it('will not consider an anonymous commentator as a participating commentator', function () {
    config()->set('comments.allow_anonymous_comments', true);

    $comment = $this->firstCommentator->comment('my comment', $this->firstUser);
    $anonymousComment = $this->firstCommentator->comment('my comment');

    expect($comment->refresh()->participatingCommentators())->toHaveCount(0);
    expect($anonymousComment->refresh()->participatingCommentators())->toHaveCount(1);
});

it('will return unique users when called on a comment', function () {
    $firstComment = $this->firstCommentator->comment('my comment', $this->firstUser);
    $secondComment = $this->firstCommentator->comment('my comment', $this->secondUser);
    $this->firstCommentator->comment('my comment', $this->firstUser);
    $this->firstCommentator->comment('my comment', $this->secondUser);

    expect($firstComment->refresh()->participatingCommentators())
        ->toHaveCount(1)
        ->first()->id->toBe($this->secondUser->id);

    expect($secondComment->refresh()->participatingCommentators())
        ->toHaveCount(1)
        ->first()->id->toBe($this->firstUser->id);
});

it('will return all users when called on a commentable', function () {
    $this->firstCommentator->comment('my comment', $this->firstUser);
    $this->firstCommentator->comment('my comment', $this->secondUser);

    expect($this->firstCommentator->participatingCommentators())
        ->toHaveCount(2)
        ->pluck('id')->toArray()->toBe([$this->firstUser->id, $this->secondUser->id]);
});

it('will return unique users when called on a commentable', function () {
    $this->firstCommentator->comment('my comment', $this->firstUser);
    $this->firstCommentator->comment('my comment', $this->secondUser);
    $this->firstCommentator->comment('my comment', $this->firstUser);
    $this->firstCommentator->comment('my comment', $this->secondUser);

    expect($this->firstCommentator->participatingCommentators())
        ->toHaveCount(2)
        ->pluck('id')->toArray()->toBe([$this->firstUser->id, $this->secondUser->id]);
});
