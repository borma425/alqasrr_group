<?php

use Timber\Timber;

$context = Timber::context();

// Get the current archive data
$context['posts'] = Timber::get_posts();
// $context['pagination'] = Timber::get_pagination(); // Deprecated in Timber 2.0

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('archive.twig');
} else {
    // Fallback to default template
    $template = 'archive.twig';
}

Timber::render($template, $context);
