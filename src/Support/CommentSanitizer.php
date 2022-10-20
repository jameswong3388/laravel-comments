<?php

namespace Spatie\Comments\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class CommentSanitizer
{
    public function sanitize(string $text): string
    {
        $config = (new HtmlSanitizerConfig())
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            ->allowSafeElements()
            ->allowAttribute('style', '*');

        $sanitizer = new HtmlSanitizer($config);

        return $sanitizer->sanitize($text);
    }
}
