<?php

namespace Spatie\Comments\Tests\TestSupport\Policies;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Policies\CommentPolicy;

class DenyCommentCreationPolicy extends CommentPolicy
{
    public function create(Model $user, Model $commentableModel): bool
    {
        return false;
    }
}
