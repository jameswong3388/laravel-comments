---
title: Approving comments
weight: 5
---

By default, all comments are marked as approved. Optionally, you can let the admins of your application manually approve comments. The admins will get a notification for each comment that is posted. That notification will contain links to approve or reject the comment.

## Preparation

First, you must set the `automatically_approve_all_comments` config option to `false`.

```php
// config/comments.php

return [
    // ...
    
    /*
     * Comments need to be approved before they are shown. You can opt
     * to have all comments to be approved automatically.
     */
    'automatically_approve_all_comments' => false,
]
```

Next, you must pass a callable to `PendingCommentNotification::sendTo()`. The callable will receive the `$comment` that is pending, and it should return the users that should receive the notification to approve/reject it.

```php
// typically, in a service provider

PendingCommentNotification::sendTo(function(Comment $comment) {
    return User::where('is_admin', true)->get(); // select some users
});
```

Finally, you must register the routes used to approve/reject a comment.

```php
// in a routes file

Route::comments();
```

## Determining if a comment should be automatically approved

If you set `automatically_approve_all_comments` in the `comments` config file to `true` then all new comments will need to be approved.

Optionally, you can make this more fine-grained. Imagine that you only want to approve comments for users that are less then three months in your system.

To get started, you must use a custom `Comment` model. Create a class of your own and let it extend `Spatie\Comments\Models\Comment`.

```php
use Spatie\Comments\Models\Comment;

class CustomComment extends Comment
{
    // ...
}
```

Next, you must register that custom comment class in the `comments` config file.

```php
// config/comments.php

return [
    'models' => [
        'comment' => CustomComment::class
    ],
]
```

With that in place, you can now add a method `shouldBeAutomaticallyApproved` to the model that will contain the logic to determine if a comment should be approved.

```php
use Spatie\Comments\Models\Comment;

class CustomComment extends Comment
{
    public function shouldBeAutomaticallyApproved(): bool
    {
   
        // $this->commentator is the user that created the comment
        if ($this->commentator->created_at->diffInMonths() < 3) {
            return false;
        }

        // automatically approve the comment is the user that
        // created the comment can also approve it
        return $this->getApprovingUsers()->contains($currentUser);
    }
}
```

Let's take a look at another example. You might want to only automatically approve comments if they don't contain certain words.

```php
use Illuminate\Support\Str;
use Spatie\Comments\Models\Comment;

class CustomComment extends Comment
{
    public function shouldBeAutomaticallyApproved(): bool
    {
        if (Str::contains($this->original_text, [
            'bad-word', 
            'another-bad-word'
        ])) {
            return false;
        }
   
        return $this->getApprovingUsers()->contains($currentUser);
    }
}
```

## Sending and customizing the approval notification

When a new comment is made that should be approved, the `PendingCommentNotification` is sent to the users returned by the closure passed to `PendingCommentNotification::sendTo`. That notification will have links to approve/reject a comment.

By default, the notification will be a mail which looks like this.

![screenshot](/docs/laravel-comments/v1/images/approval-mail.png)

### Customizing the notification

The sender email and name can of the mail can be changed in the `notifications.mail` key of the `comments` config file.

If you want to customize the content of the sent mail, you can [publish the views](/docs/laravel-comments/v1/livewire-components/customising-the-views). The mail view will be published at `/resources/views/vendor/comments/mail/pendingCommentNotification.blade.php`.

To customize the notification class itself, you can create a notification of your own that extends `Spatie\Comments\Notifications\PendingCommentNotification`. You must update the `notifications.notifications.pending_comment` value of the `comments` config file, with the name of your class.

To get full control over what should happen when a new pending comment is submitted, you can override the `actions.send_notifications_for_pending_comment` key in the `comments` config file.

## Approving a comment via code

To approve a comment via code, you can simply call `approve()` on a comment.

```php
use Spatie\Comments\Models\Comment;

$comment = \Spatie\Comments\Models\Comment::find($commentId);

$comment->approve();
```

## Determining if a comment is approved

You can determine if a comment is approved with `isApproved()` and `isPending()`

```php
$comment = $post->comment('a new comment');

$comment->isPending(); // returns true
$comment->isApproved(); // returns false

$comment->approve();

$comment->isPending(); // returns false
$comment->isApproved(); // returns true
```

## Scoping on approved comments

The `Comment` model has a scope to filter `approved` and `pending` comments.

```php
use Spatie\Comments\Models\Comment;

Comment::approved()->get(); // returns all approved comments
Comment::pending()->get(); // returns all comments that haven't been approved yet
```
