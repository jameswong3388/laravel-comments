---
title: Sending notifications
weight: 4
---

Whenever somebody creates a comments, this package can send a notification when comments are made. Out of the box such a notification is always an email.

## Subscribing to notifications

You can let a user subscribe to notifications like this:

```php
use \Spatie\Comments\Enums\NotificationSubscriptionType;

$user->subscribeToCommentNotifications($model, NotificationSubscriptionType::All);
```

This will send a notification to `$user` whenever a comment is posted by anyone to the given `$model`.

You can also only send a notification to `$user` when a new comment is posted on things that `$user` already commented on. For the behaviour, use ` NotificationSubscriptionType::Participating`

```php
use \Spatie\Comments\Enums\NotificationSubscriptionType;

$user->subscribeToCommentNotifications($model, NotificationSubscriptionType::Participating);
```

## Unsubscribing from notifications

Here's how you can unsubscribe a user from getting notified when a new comment is posted

```php
$user->unsubscribeFromCommentNotifications($model);
```

To let a user unsubscribe from all notifications for all models, you can use this method:

```php
$user->unsubscribeFromAllCommentNotifications();
```

## Customizing notifications

Out of the box, this package will send notifications as emails.

You can customize the content of the mail by publishing the views:

```bash
php artisan vendor:publish --tag="comments-views"
```

You'll find the Blade views to customize in `resources/vendor/comments/`.

To customize the notifications itself (to for instance add new channels) you create your own notification class that extends either `Spatie\Comments\Notifications\PendingCommentNotification` or `Spatie\Comments\Notifications\ApprovedCommentNotification`. You should specify your class name in the `pending_comment` or `approved_comment` key of the `comments` config file.
