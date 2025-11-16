<?php

use Timber\Timber;

$context = Timber::context();

// Get the current page/post data
$post = Timber::get_post();

// Localize for English version using custom English fields
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

		if (function_exists('get_english_url')) {
			$post->link = get_english_url($post_id);
		}

		if (function_exists('format_date_english')) {
			$post->english_date = format_date_english($post->date('Y-m-d'), 'j F Y');
		} else {
			$post->english_date = $post->date('j F Y');
		}
	}
}

$context['post'] = $post;

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('page.twig');
} else {
    // Fallback to default template
    $template = 'page.twig';
}

Timber::render($template, $context);