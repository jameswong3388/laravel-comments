---
title: Installation & setup
weight: 4
---

## Getting a license

In order to install Laravel comments, you'll need to [get a license](https://spatie.be/products/laravel-comments) first.

## Installation via Composer

First, add the `satis.spatie.be` repository in your `composer.json`.

```php
"repositories": [
    {
        "type": "composer",
        "url": "https://satis.spatie.be"
    }
],
```

Next, you need to create a file called `auth.json` and place it either next to the `composer.json` file in your project, or in the composer home directory. You can determine the composer home directory on *nix machines by using this command.

```bash
composer config --list --global | grep home
```

This is the content you should put in `auth.json`:

```php
{
    "http-basic": {
        "satis.spatie.be": {
            "username": "<YOUR-SPATIE.BE-ACCOUNT-EMAIL-ADDRESS-HERE>",
            "password": "<YOUR-LICENSE-KEY-HERE>"
        }
    }
}
```

If you are using [Laravel Forge](https://forge.laravel.com), you don't need to create the `auth.json` file manually. Instead, you can set the credentials on the Composer Package Authentication screen of your server. Fill out the fields with these values:

- Repository URL: `satis.spatie.be`
- Username: Fill this field with your spatie.be account email address
- Password: Fill this field with your Laravel Comments license key

To validate if Composer can read your `auth.json` you can run this command:

```bash
composer config --list --global | grep satis.spatie.be
````

If you did everything correctly, the above command should display your credentials.

Now, you can install the package via composer:

```bash
composer require spatie/laravel-comments
```

## Publishing the config file

Optionally, you can publish the `health` config file with this command.

```bash
php artisan vendor:publish --tag="comments-config"
```

This is the content of the published config file:

```php
use Spatie\Comments\Notifications\ApprovedCommentNotification;
use Spatie\Comments\Notifications\PendingCommentNotification;
use Spatie\Comments\Actions\SendNotificationsForApprovedCommentAction;
use Spatie\Comments\Actions\RejectCommentAction;
use Spatie\Comments\Actions\ApproveCommentAction;
use Spatie\Comments\Actions\SendNotificationsForPendingCommentAction;
use Spatie\Comments\Actions\ProcessCommentAction;
use Spatie\Comments\Models\Reaction;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\CommentTransformers\MarkdownToHtmlTransformer;
use Spatie\Comments\Models\CommentNotificationSubscription;

return [
    /*
     * These are the reactions that can be made on a comment. We highly recommend
     * only enabling positive ones for getting good vibes in your community.
     */
    'allowed_reactions' => ['👍', '🥳', '👀', '😍', '💅'],

    'allow_anonymous_comments' => false,

    /*
     * A comment transformer is a class that will transform the comment text
     * for example from Markdown to HTML
     */
    'comment_transformers' => [
        MarkdownToHtmlTransformer::class,
    ],

    /*
     * Comments need to be approved before they are shown. You can opt
     * to have all comments to be approved automatically.
     */
    'automatically_approve_all_comments' => true,

    'models' => [
        /*
         * The class that will comment on other things. Typically, this
         * would be a user model.
         */
        'commentator' => null,

        /*
         * The field to use to display the name from the commentator model.
         */
        'name' => 'name',

        /*
         * The model you want to use as a Comment model. It needs to be or
         * extend the `Spatie\Comments\Models\Comment::class` model.
         */
        'comment' => Comment::class,

        /*
         * The model you want to use as a React model. It needs to be or
         * extend the `Spatie\Comments\Models\Reaction::class` model.
         */
        'reaction' => Reaction::class,

        /*
         * The model you want to use as an opt-out model. It needs to be or
         * extend the `Spatie\Comments\Models\CommentNotificationSubscription::class` model.
         */
        'comment_notification_subscription' => CommentNotificationSubscription::class,
    ],

    'notifications' => [
        /*
         * When somebody creates a comment, a notification will be sent to other persons
         * that commented on the same thing.
         */
        'enabled' => true,

        'notifications' => [
            'pending_comment' => PendingCommentNotification::class,
            'approved_comment' => ApprovedCommentNotification::class,
        ],

        /*
         * Here you can configure the notifications that will be sent via mail.
         */
        'mail' => [
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],
    ],

    'pagination' => [
        /*
         * Here you can configure the number of results to show before
         * pagination links are displayed.
         */
        'results' => 10000,

        /*
         * If you have multiple paginators on the same page, you can configure the
         * query string page name to avoid conflicts with the other paginator.
         * For example, you could set the page_name to be 'comments_page'.
         */
        'page_name' => 'page',

        /*
         * You can choose a different pagination theme like "simple-tailwind" or build
         * a custom pagination "vendor.livewire.custom-pagination" See the livewire
         * docs for more information: https://laravel-livewire.com/docs/2.x/pagination#custom-pagination-view
         */
        'theme' => 'tailwind',
    ],

    /*
     * Unless you need fine-grained customisation, you don't need to change
     * these action classes. If you do change any of them, make sure that your class
     * extends the original action class.
     */
    'actions' => [
        'process_comment' => ProcessCommentAction::class,
        'send_notifications_for_pending_comment' => SendNotificationsForPendingCommentAction::class,
        'approve_comment' => ApproveCommentAction::class,
        'reject_comment' => RejectCommentAction::class,
        'send_notifications_for_approved_comment' => SendNotificationsForApprovedCommentAction::class,
    ],
    
    'gravatar' => [
        /*
         * Here you can choose which default image to show when a user does not have a Gravatar profile.
         * See the Gravatar docs for further information https://en.gravatar.com/site/implement/images/
         */
        'default_image' => 'mp',
    ],
];
```

## Migrating the database

 To create the tables used by this package, you must create and run the migration.

```bash
php artisan vendor:publish --tag="comments-migrations"
php artisan migrate
```

## Preparing your models

Comments will be associated with Eloquent models. To allow an Eloquent model to have comments associated with it, use the `Spatie\Comments\HasComments` trait on it.

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;

class YourModel extends Model
{
    use HasComments;
}
```

By using the `HasComments` trait on your model, you are required to add these two methods on the same model.

```php
/*
 * This string will be used in notifications on what a new comment
 * was made.
 */
public function commentableName(): string
{
    //
}

/*
 * This URL will be used in notifications to let the user know
 * where the comment itself can be read.
 */
public function commentUrl(): string
{

}
```

The object that will comment on Eloquent models is called the commentator. Typically, the commentator is the `User` model in your app.

You must prepare your commentator model by using the `Spatie\Comments\Models\Concerns\HasComments` trait on it, and letting implementing `Spatie\Comments\Models\Concerns\Interfaces\CanComment`. 

Here's an example for when your user model is `App\Models\User`:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\InteractsWithComments;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;

class User extends Model implements CanComment
{
    use InteractsWithComments;
}
```

You must also set the `commentator` key in the `comments` config file to this model.

```php
// in the `comments` config file

return [
    // ...
    
    'commentator' => App\Models\User::class,
]
```

## Customising the code highlighting theme

Code highlighting of the comments is powered by [our spatie/laravel-markdown package](https://github.com/spatie/laravel-markdown), which in its turn uses [Shiki](https://github.com/shikijs/shiki) to highlight code.

You can use [any Shiki code highlighting theme](https://github.com/shikijs/shiki/blob/main/docs/themes.md#all-themes) that you desire. By default `github-light`is used.

To use your preferred theme, you must publish the config file of Laravel-markdown using this command:

```php
php artisan vendor:publish --tag="markdown-config"
```

In the config file that gets published at `config/markdown.php` you should set the name of or path to a Shiki theme in the `theme` option.

```php
// in config/markdown.php

return [
    'code_highlighting' => [
    
        /*
         * The name of or path to a Shiki theme
         *
         * More info: https://github.com/spatie/laravel-markdown#specifying-the-theme-used-for-code-highlighting
         */
        'theme' => 'github-dark',
    ],
```

## Using the Livewire components

If you want to use the Livewire components to render your comments, proceed to [the installation instruction of the component](https://spatie.be/docs/laravel-comments/v1/livewire-components/installation).


