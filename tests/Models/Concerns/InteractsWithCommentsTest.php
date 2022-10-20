<?php

use Spatie\Comments\Tests\TestSupport\Models\User;

it('can get the properties of a commentator', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'name' => 'John Smith',
    ]);

    expect($user->commentatorProperties())
        ->email->toBe('john@example.com')
        ->name->toBe('John Smith')
        ->avatar->toBe('https://www.gravatar.com/avatar/d4c74594d841139328695756648b6bd6');
});

it('can get the configured name of a commentator', function () {
    config()->set('comments.models.name', 'first_name');

    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'first_name' => 'Jane',
    ]);

    expect($user->commentatorProperties())
        ->email->toBe('jane@example.com')
        ->name->toBe('Jane')
        ->avatar->toBe('https://www.gravatar.com/avatar/9e26471d35a78862c17e467d87cddedf');
});
