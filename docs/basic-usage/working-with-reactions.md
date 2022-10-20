---
title: Working with reactions
weight: 2
---

A reaction is very short response, often just an emoji, on a comment. 

## Creating a reaction

To create a reaction, call the `reaction` method on the comment.

```php
$post->comment('Everybody pulled their socks up');

$comment->react('😍');
```

Behind the scenes, a `Spatie\Comments\Reaction` will be store that is associated with the comment and the currently logged in user.

To create a reaction for another user, you can pass that user as a second argument.

```php
$anotherUser = User::whereFirst('email', 'john@beatles.com');

$comment->react('😍', $anotherUser);
```

A single user may have multiple reactions on a comment, but each reaction will be unique.

```php
$post->comment('Everybody pulled their socks up');

$comment
    ->react('😍')
    ->react('👍')
    ->react('👍') // will not be stored as 👍 was already added as a reaction by the current user;
```

## Retrieving reactions

You can retrieve all reactions by using the `reactions` relation on a comment.

```php
$comment->reactions;
```

The reactions will be returned in a `Spatie\Comments\Models\Collections\ReactionCollection`. That collection has a `summary` method that will return a summary of all reactions.

```php
$summary = $comment->reactions->summary() // returns a Illuminate\Support\Collection;
```

The `$summary` will contain an item per unique reaction. Each item in `$summary` has these keys:

- `reaction`: the reaction itself, e.g. `😍`
- `count`: the number of users that gave this reaction on the comment
- `commentator_reacted`: a boolean that indicates whatever the currently logged in user gave this reaction

You can pass a `User` model to the `summary` method. The `commentator_reacted` in the return collection will be `true` when the given user has given that particular reaction.

## Deleting a reaction

To delete a particular reaction on a comment, you can call `deleteReaction`.

```
$comment->deleteReaction('😍');
```

The code above will delete the existing `😍` reaction on that `$comment` for the logged in user. If that reaction did not exist, nothing will happen.

To delete a reaction for another user, pass that user as second argument to `deleteReaction`.

```php
$comment->deleteReaction('😍', $anotherUser);
```
