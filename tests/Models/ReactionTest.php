<?php

use Spatie\Comments\Models\Comment;
use Spatie\Comments\Tests\TestSupport\Models\User;

beforeEach(function () {
    $this->currentUser = login();

    $this->comment = Comment::factory()->create();
});

it('can add a reaction to a comment', function () {
    $this->comment->react('👍');

    expect($this->comment->reactions)->toHaveCount(1);

    expect($this->comment->reactions->first())
        ->commentator->isModel($this->currentUser)
        ->reaction->toBe('👍');
});

it('can add multiple different reactions to a comment', function () {
    $this->comment->react('👍');
    $this->comment->react('🥳');

    expect($this->comment->reactions)->toHaveCount(2);
    expect($this->comment->reactions->pluck('reaction')->toArray())->toBe(['👍', '🥳']);
});

it('will make sure reactions are unique for a user', function () {
    $this->comment->react('👍');
    $this->comment->react('👍');

    expect($this->comment->reactions)->toHaveCount(1);

    $this->comment->react('🥳');
    expect($this->comment->fresh()->reactions)->toHaveCount(2);

    $anotherUser = User::factory()->create();
    $this->comment->react('👍', $anotherUser);

    expect($this->comment->fresh()->reactions)->toHaveCount(3);
});

it('can remove a reaction', function () {
    $this->comment->react('👍');

    $this->comment->deleteReaction('👍');
    expect($this->comment->fresh()->reactions)->toHaveCount(0);
});

it('will not complain when trying to remove a non-existing reaction', function () {
    $this->comment->deleteReaction('👍');

    expectNoExceptionsThrown();
});

it('will remove the reaction of a specific user', function () {
    $anotherUser = User::factory()->create();
    $this->comment->react('👍');
    $this->comment->react('👍', $anotherUser);

    $this->comment->deleteReaction('👍', $anotherUser);

    expect($this->comment->reactions)->toHaveCount(1);

    expect($this->comment->reactions->first()->commentator)->isModel($this->currentUser);
});

it('can get the reaction counts', function () {
    $this->comment->react('👍');
    $this->comment->react('🥳');

    $anotherUser = User::factory()->create();
    $this->comment->react('👍', $anotherUser);

    expect($this->comment->reactionCounts())->toBe([
        ['reaction' => '👍', 'count' => 2],
        ['reaction' => '🥳', 'count' => 1],
    ]);
});

it('will sort the reaction count using the allowed reaction counts', function () {
    config()->set('comments.allowed_reactions', ['🥳', '👍']);

    $this->comment->react('👍');
    $this->comment->react('🥳');

    $anotherUser = User::factory()->create();
    $this->comment->react('👍', $anotherUser);

    expect($this->comment->reactionCounts())->toBe([
        ['reaction' => '🥳', 'count' => 1],
        ['reaction' => '👍', 'count' => 2],
    ]);
});

it('can summarize a reaction collection for the current user', function () {
    $this->comment->react('👍');
    $this->comment->react('🥳');

    $anotherUser = User::factory()->create();
    $this->comment->react('👍', $anotherUser);
    $this->comment->react('😍', $anotherUser);

    expect($this->comment->reactions->summary()->toArray())->toBe([
        ['reaction' => '👍', 'count' => 2, 'commentator_reacted' => true],
        ['reaction' => '🥳', 'count' => 1, 'commentator_reacted' => true],
        ['reaction' => '😍', 'count' => 1, 'commentator_reacted' => false],
    ]);
});

it('can summarize a reaction collection for another user', function () {
    $this->comment->react('👍');
    $this->comment->react('🥳');

    $anotherUser = User::factory()->create();
    $this->comment->react('👍', $anotherUser);
    $this->comment->react('😍', $anotherUser);

    expect($this->comment->reactions->summary($anotherUser)->toArray())->toBe([
        ['reaction' => '👍', 'count' => 2, 'commentator_reacted' => true],
        ['reaction' => '🥳', 'count' => 1, 'commentator_reacted' => false],
        ['reaction' => '😍', 'count' => 1, 'commentator_reacted' => true],
    ]);
});

it('can find a reaction on a comment', function () {
    $this->comment->react('👍');
    $this->comment->react('🥳');

    expect($this->comment->refresh()->findReaction('🥳'))->isModel(latestReaction());
    expect($this->comment->findReaction('❌'))->toBeNull();
});

it('can find a reaction for a specific user', function () {
    $this->comment->react('👍');

    $anotherUser = User::factory()->create();
    $this->comment->react('😍', $anotherUser);

    expect($this->comment->refresh()->findReaction('😍', $anotherUser))->isModel(latestReaction());
    expect($this->comment->findReaction('👍', $anotherUser))->toBeNull();
});
