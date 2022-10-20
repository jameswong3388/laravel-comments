---
title: Introduction
weight: 1
---

Using this package, you can create comments and associate them with Eloquent models. It comes with batteries included:

- A beautiful Livewire component to display comments
- markdown submission is supported, we'll render it as html
- code snippets that appear in comments will automatically be highlighted
- users can react to comments (👍, ❤️, or any emoji you want)
- optionally, you enable a comment approval flow
- sane API for creating your own commenting UI
- Livewire components out of the box

This is what the component looks like:

![screenshot](/docs/laravel-comments/v1/images/full.png)

Here's how you can create a comment for the currently logged in user. 

```php
$yourModel->comment('This my comment');
```

Here's how to retrieve them all:

```php
$comments = $yourModel->comments
$comment->text // returns "This is my comment"
```

You can also react to a comment:

```php
$comment->react('👍');
```

Using the Livewire components, you can quickly add comments to the UI of your app.

![screenshot](/docs/laravel-comments/v1/images/full.png)

If you use the optional approval flow, then users will see this when they submit a new comment.

![screenshot](/docs/laravel-comments/v1/images/to-be-approved.png)

Admins can approve them inline.

![screenshot](/docs/laravel-comments/v1/images/inline-approval.png)
