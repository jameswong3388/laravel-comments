<?php

use Spatie\Comments\Support\CommentatorProperties;

it('stores the properties of a commentator', function () {
    $commentatorProperties = CommentatorProperties::email('john@example.com')
       ->avatar('https://avatar.com')
       ->name('John')
       ->url('/profile')
       ->add('customName', 'customValue');

    expect($commentatorProperties)
       ->email->toBe('john@example.com')
       ->avatar->toBe('https://avatar.com')
       ->name->toBe('John')
       ->url->toBe('/profile')
       ->customName->toBe('customValue');
});
