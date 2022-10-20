<?php

namespace Spatie\Comments\Tests\TestSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;

class User extends BaseUser implements CanComment
{
    use HasFactory;
    use Notifiable;
    use InteractsWithComments;
    use HasComments;

    public function commentableName(): string
    {
        return 'commentable name';
    }

    public function commentableUrl(): ?string
    {
        return url("user/{$this->id}");
    }

    public function commentUrl(): string
    {
        return url("user/{$this->id}");
    }
}
