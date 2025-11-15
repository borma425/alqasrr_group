<?php

use Timber\Timber;

/**
 * Archive: Blog
 * أرشيف: المدونة
 * 
 * Posts per page is set in inc/archive-pagination.php
 * عدد المقالات لكل صفحة يتم تعيينه في inc/archive-pagination.php
 * 
 * To change the number of posts per page, edit:
 * لتغيير عدد المقالات لكل صفحة، عدّل:
 * inc/archive-pagination.php → $archive_posts_per_page['blog'] = 2;
 */

$context = Timber::context();

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
}

// Get posts using Timber (uses main query - already modified by pre_get_posts)
// الحصول على المقالات باستخدام Timber (يستخدم main query - تم تعديله بالفعل بواسطة pre_get_posts)
$posts = Timber::get_posts();

if ($is_english && !empty($posts)) {
    foreach ($posts as $post) {
        if (!is_object($post)) {
            continue;
        }

        $post_id = isset($post->ID) ? $post->ID : (isset($post->id) ? $post->id : 0);
        if (!$post_id) {
            continue;
        }

        $title_en = get_post_meta($post_id, 'blog_title_en', true);
        if (!empty($title_en)) {
            if (isset($post->post_title)) {
                $post->post_title = $title_en;
            }
            $post->title = $title_en;
        }

        $excerpt_en = get_post_meta($post_id, 'blog_excerpt_en', true);
        if (!empty($excerpt_en)) {
            if (isset($post->post_excerpt)) {
                $post->post_excerpt = $excerpt_en;
            }
            $post->excerpt = $excerpt_en;
        }

        $content_en = get_post_meta($post_id, 'blog_content_en', true);
        if (!empty($content_en)) {
            if (isset($post->post_content)) {
                $post->post_content = $content_en;
            }
            $post->content = $content_en;
        }

        if (function_exists('get_english_url')) {
            $post->link = get_english_url($post_id);
        }
    }
}

$context['posts'] = $posts;

// Get the appropriate template based on current language
// الحصول على القالب المناسب بناءً على اللغة الحالية
if (function_exists('get_language_template')) {
    $template = get_language_template('archive-blog.twig');
} else {
    $template = 'archive-blog.twig';
}

Timber::render($template, $context);
