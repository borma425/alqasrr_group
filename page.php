<?php

use Timber\Timber;

$context = Timber::context();

// Get the current page/post data
$context['post'] = Timber::get_post();

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('page.twig');
} else {
    // Fallback to default template
    $template = 'page.twig';
}

Timber::render($template, $context);