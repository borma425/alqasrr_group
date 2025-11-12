
<?php

use Timber\Timber;

$context = Timber::context();

$context['is_front_page'] = true;






$context['projects'] = Timber::get_posts([
    'post_type'      => 'projects',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);


$context['news'] = Timber::get_posts([
    'post_type'      => 'blog',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);


// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('index-home.twig');
} else {
    // Fallback to default template
    $template = 'index-home.twig';
}

Timber::render($template, $context);




