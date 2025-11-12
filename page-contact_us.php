<?php

use Timber\Timber;

$context = Timber::context();

// Get contact page specific settings (not in global context)
$context['contact_map_link'] = get_option('contact_map_link', '');
$context['contact_background_image'] = get_option('contact_background_image', '');

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('page-Contact_us.twig');
} else {
    // Fallback to default template
    $template = 'page-Contact_us.twig';
}

Timber::render($template, $context);
