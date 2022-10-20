<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Reaction;
use Spatie\Comments\Tests\TestSupport\Models\User;
use Spatie\Comments\Tests\TestSupport\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        ray()->newScreen($this->getName());
    })
    ->in(__DIR__);



function login(User $user = null): User
{
    $currentUser = $user ?? User::factory()->create();

    Auth::login($currentUser);

    return $currentUser;
}

function logout(): void
{
    Auth::logout();
}

expect()->extend('isModel', function (Model $model) {
    expect($this->value)->is($model)->toBeTrue();
});

function expectNoExceptionsThrown()
{
    expect(true)->toBeTrue();
}

function latestComment(): ?Comment
{
    return Comment::orderByDesc('id')->first();
}

function latestReaction(): ?Reaction
{
    return Reaction::orderByDesc('id')->first();
}

function registerPolicies(): void
{
    test()->registerPolicies();
}
