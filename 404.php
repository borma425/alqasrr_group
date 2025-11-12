<?php

use Timber\Timber;

$context = Timber::context();

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('404.twig');
} else {
    // Fallback to default template
    $template = '404.twig';
}

Timber::render($template, $context);