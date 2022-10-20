<?php

namespace Spatie\Comments\Tests\TestSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;

class Post extends Model
{
    use HasComments;
    use HasFactory;

    protected $guarded = [];

    public function commentableName(): string
    {
        return 'commentable name';
    }

    public function commentableUrl(): ?string
    {
        return url("post/{$this->id}");
    }

    public function commentUrl(): string
    {
        return url("post/{$this->id}");
    }
}
