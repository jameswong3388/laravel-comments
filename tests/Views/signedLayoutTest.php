<?php

use Illuminate\Support\Facades\Blade;

it('can render the approveComment view', function () {
    $html = Blade::render('comments::signed.approval.approveComment');

    expect($html)->toBeString();
});
