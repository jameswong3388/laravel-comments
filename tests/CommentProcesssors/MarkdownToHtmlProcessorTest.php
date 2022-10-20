<?php

use Spatie\Comments\CommentTransformers\MarkdownToHtmlTransformer;
use Spatie\Comments\Tests\TestSupport\Models\Post;

beforeEach(function () {
    login();
});

it('can convert markdown to html', function () {
    config()->set('comments.comment_transformers', [
        MarkdownToHtmlTransformer::class,
    ]);

    $markdown = '## My title';

    Post::factory()->create()->comment($markdown);

    $comment = latestComment();

    expect($comment->original_text)->toBe($markdown);
    expect(trim($comment->text))->toBe('<h2 id="my-title">My title</h2>');
});
