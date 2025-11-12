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

// Get posts using Timber (uses main query - already modified by pre_get_posts)
// الحصول على المقالات باستخدام Timber (يستخدم main query - تم تعديله بالفعل بواسطة pre_get_posts)
$posts = Timber::get_posts();

// Process posts to add language-specific content
// معالجة المقالات لإضافة محتوى خاص باللغة
$current_language = get_current_language();

foreach ($posts as $post) {
    if ($current_language === 'en') {
        // For English, use metabox fields (blog-specific)
        // للإنجليزية، استخدم حقول metabox (خاصة بـ blog)
        $post->title = get_post_meta($post->ID, 'blog_title_en', true) ?: $post->title;
        $post->content = get_post_meta($post->ID, 'blog_content_en', true) ?: $post->content;
        $post->excerpt = get_post_meta($post->ID, 'blog_excerpt_en', true) ?: $post->excerpt;
    }
    // For Arabic, use default WordPress fields (no changes needed)
    // للعربية، استخدم الحقول الافتراضية لـ WordPress (لا حاجة لتغيير)
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
