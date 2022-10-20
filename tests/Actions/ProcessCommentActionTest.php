<?php

use Spatie\Comments\Actions\ProcessCommentAction;
use Spatie\Comments\CommentTransformers\MarkdownToHtmlTransformer;
use Spatie\Comments\Models\Comment;

beforeEach(function () {
    $this->comment = Comment::factory()->create();
});

it('will store sanitized html', function ($transformer) {
    config()->set('comments.comment_transformers', $transformer);

    $this->comment->update(['original_text' => '<img src="404" onerror="alert(\'XSS\')">']);

    (new ProcessCommentAction())->execute($this->comment);

    expect(trim($this->comment->text))->toBe('<img src="404" />');
})->with([
    null,
    MarkdownToHtmlTransformer::class,
]);
