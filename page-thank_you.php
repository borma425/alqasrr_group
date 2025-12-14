<?php

use Timber\Timber;

$context = Timber::context();

$post = Timber::get_post();

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
}

if ($is_english && is_object($post)) {
    $post_id = isset($post->ID) ? $post->ID : (isset($post->id) ? $post->id : 0);
    if ($post_id) {
        $title_en   = get_post_meta($post_id, 'page_title_en', true);
        $content_en = get_post_meta($post_id, 'page_content_en', true);

        if (!empty($title_en)) {
            if (isset($post->post_title)) {
                $post->post_title = $title_en;
            }
            $post->title = $title_en;
        }

        if (!empty($content_en)) {
            if (isset($post->post_content)) {
                $post->post_content = $content_en;
            }
            $post->content = $content_en;
        }
    }
}

$context['post'] = $post;

$templates = array('page-thank_you.twig');

if ($is_english) {
    array_unshift($templates, 'en/page-thank_you.twig');
    $templates[] = 'ar/page-thank_you.twig';
} else {
    array_unshift($templates, 'ar/page-thank_you.twig');
    $templates[] = 'en/page-thank_you.twig';
}

Timber::render($templates, $context);
