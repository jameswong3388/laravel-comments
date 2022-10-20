<?php

use Spatie\Comments\Events\CommentApprovedEvent;
use Spatie\Comments\Events\CommentRejectedEvent;
use Spatie\Comments\Models\Comment;

beforeEach(function () {
    $this->comment = Comment::factory()->make();
});

it('can access comment from the event', function () {
    $event = new CommentApprovedEvent($this->comment);
    expect($event->comment)->isModel($this->comment);

    $event = new CommentRejectedEvent($this->comment);
    expect($event->comment)->isModel($this->comment);
});
