---
title: Transforming comments
weight: 3
---

When a comments get created, the package can transform that comment for you. This can be handy when you're allowing the user to use markdown to render comments, but you want to display them as HTML.

A comment can be created like this:

```php
$blogPost->comment('Everybody saw the sunshine');
```

The text passed to comment used as the value of the `original_text` attribute of the `Comment` model.

In the `comments` config, you can specify comment transformer classes that can transform the comment. The transformed comment will be put in the `text` attribute of the `Comment` model. If you're building a UI, you should use `text` to display the comment, and the `original_text` when the user is editing the comment.

## Transforming Markdown to HTML

The package ships with a transformer `Spatie\Comments\CommentProcessors\MarkdownToHtmlProcessor` that can transform markdown to HTML. Any code snippets will be highlighted too.

To use this transformer you should install the `spatie/laravel-markdown` package.

```bash
composer require spatie/laravel-markdown
```

Make sure to install Shiki as well, this is mentioned in [the installation instruction of laravel-markdown](https://spatie.be/docs/laravel-markdown/v1/installation-setup).

Next, specify the `MarkdownToHtmlProcessor::class` in the `transformers` key of the `comments` config file.

```php
// in `config/comments.php`

'comment_transformers' => [
    Spatie\Comments\CommentProcessors\MarkdownToHtmlProcessor::class,
],
```

After that, when you create a comment like this...

```php
$blogPost->comment('## Title')
```

... will create a new `Comment` model with these attributes:

- `original_text`: '## Title'
- `text`: `<h2 id="my-title">My title</h2>`

## Creating your own transformer class

You can create a transformer by letting any class implement the `Spatie\Comments\CommentTransformers\CommentTransformer` interface.

Here's how that interface looks like:

```php
namespace Spatie\Comments\CommentTransformers;

use Spatie\Comments\Models\Comment;

interface CommentTransformer
{
    public function handle(Comment $comment): void;
}
```

Inside the `handle` method you should set the `text` property of the given `$comment`.

For an example, take a look at [the sourcecode of `MarkdownToHtmlProcessor`](https://github.com/spatie/laravel-comments/blob/af3dc7a415f3022fc0213b0eeff5f540d139fe89/src/CommentTransformers/MarkdownToHtmlTransformer.php).

