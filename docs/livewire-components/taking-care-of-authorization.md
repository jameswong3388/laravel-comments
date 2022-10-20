---
title: Taking care of authorization
weight: 4
---

By default, only the user that has created a comment may update or delete it. This behaviour is implemented using [a policy](https://laravel.com/docs/9.x/authorization#generating-policies) that is included in the package: `Spatie\LivewireComments\Policies\CommentPolicy`

This is the default implementation.

```php
namespace Spatie\LivewireComments\Policies;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;

class CommentPolicy
{
    /**
     * @param CanComment|Model $commentator
     * @param Model $commentableModel
     *
     * @return bool
     */
    public function create(?CanComment $user): bool
    {
        return true;
    }

    /**
     * @param CanComment|Model $commentator
     * @param Model $commentableModel
     *
     * @return bool
     */
    public function update(?CanComment $user, Comment $comment): bool
    {
        if ($comment->getApprovingUsers()->contains($user)) {
            return true;
        }

        return $comment->madeBy($user);
    }

    /**
     * @param CanComment|Model $commentator
     * @param Model $commentableModel
     *
     * @return bool
     */
    public function delete(?CanComment $user, Comment $comment): bool
    {
        if ($comment->getApprovingUsers()->contains($user)) {
            return true;
        }

        return $comment->madeBy($user);
    }

    /**
     * @param CanComment|Model $commentator
     * @param Model $commentableModel
     *
     * @return bool
     */
    public function react(CanComment $user, Model $commentableModel): bool
    {
        return true;
    }

    public function see(?CanComment $user, Comment $comment): bool
    {
        if ($comment->isApproved()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($comment->madeBy($user)) {
            return true;
        }

        return $comment->getApprovingUsers()->contains($user);
    }

    public function approve(CanComment $user, Comment $comment): bool
    {
        return $comment->getApprovingUsers()->contains($user);
    }

    public function reject(CanComment $user, Comment $comment): bool
    {
        return $comment->getApprovingUsers()->contains($user);
    }
}
```

## Modifying the policy

To modify the behaviour of the policy, you should create a class the extends the default policy. Let's assume you want to allow admins of your app to be able to update and delete comments by any user.

```php
namespace App\Policies;

use Spatie\LivewireComments\Models\Policies\CommentPolicy;

class CustomCommentPolicy extends CommentPolicy
{
    public function update(Model $user, Comment $comment): bool
    {
        if ($user->admin) {
            return true;
        }
    
        return parent::update($user, $comment);
    }
    
    public function delete(Model $user, Comment $comment): bool
    {
        if ($user->admin) {
            return true;
        }
    
        return parent::update($user, $comment);
    }
}
```

Next, you should add a `policies` key to the `comments` config file and set the `comment` key inside it to the class name of your policy

```php
// copy the `policies` key to `config/comments.php`
return [
    'policies' => [
        /*
         * The class you want to use as the comment policy. It needs to be or
         * extend `Spatie\LivewireComments\Policies\CommentPolicy`.
         */
        'comment' => App\Policies\CustomCommentPolicy::class,
    ],
]
```
