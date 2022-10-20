---
title: Working with comments
weight: 1
---

Comments will be always be associated with Eloquent models. To allow an Eloquent model to have comments associated with it, use the `Spatie\Comments\HasComments` trait on it.

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;

class Post extends Model
{
    use HasComments;
}
```

## Creating a comment

To create a new comment, you can call the `comment` method.

```php
$post->comment("I've got a feeling");
```

Behind the scene new `Spatie\Comments\Comment` model will be saved. The comment will be associated with the `$post` and the currently logged in user. 

You can associate the comment with another user, by passing it as a second argument.

```php
$anotherUser = User::whereFirst('email', 'paul@beatles.com');

$post->comment("I've got a feeling", $anotherUser);
```

Because the `Comment` model itself uses the `HasComments` trait, you can create a nested comment like by calling `comment` on a `Comment`.

```php
$comment = $post->comment("I've got a feeling");

$nestedComment = $comment->comment("It keeps me on my toes")
```

## Handling markdown and other formats

To add support for markdown, take a look at the section on [transforming comments](https://spatie.be/docs/v1/laravel-comments/basic-usage/tranforming-comments).

## Getting all comments of a model

To get all comments of a model, use the `comments` relationship.

```php
$allComments = $post->comments; // returns a relationship with all comments
```

You can get all unnested comments like this:

```php
$unnestedComments =  $post->comments()->topLevel()->get();
```

## Updating a comment

To update the content of a comment, update the `orginal_property` attribute of a comments model.

```php
$comment->update([
    'original_text' => 'Updated comment',
]);
```

## Deleting a comment

Simply call `delete` on a `Comment` model.

```php
$comment->delete();
```

## Determining who made the comment

You can use the `commentator` relationship to determine who made a comment.

```php
$user = $comment->commentator
```

If the user that made the comment was deleted, or that comment was made anonymously, then `$comment->commentator` will return `null`. 

You can use these methods to determine why there was no commentator found.

```php
$comment->wasMadeByDeletedCommentator(); // returns a boolean
$comment->wasMadeAnonymously(); // returns a boolean
```
