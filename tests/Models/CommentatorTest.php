<?php

use Spatie\Comments\Tests\TestSupport\Models\User;

it('can get a commentators comments', function () {
    config()->set('comments.models.commentator', User::class);

    $user = User::factory()
        ->hasCommentatorComments(3)
        ->create();

    expect($user->commentatorComments)->toHaveCount(3);
});
