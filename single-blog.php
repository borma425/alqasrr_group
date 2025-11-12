<?php

use Timber\Timber;

$context = Timber::context();

// Get the current post
$post = Timber::get_post();

// Process post to add language-specific content
$current_language = get_current_language();

if ($current_language === 'ar') {
    // For Arabic, use default WordPress fields (title, content, excerpt)
    // لا حاجة لتغيير شيء - الحقول الافتراضية هي للعربية
} elseif ($current_language === 'en') {
    // For English, use metabox fields
    $post->title = get_post_meta($post->ID, 'blog_title_en', true) ?: $post->title;
    $post->content = get_post_meta($post->ID, 'blog_content_en', true) ?: $post->content;
    $post->excerpt = get_post_meta($post->ID, 'blog_excerpt_en', true) ?: $post->excerpt;
}

$context['post'] = $post;

// Get related posts (same language, same category or tags, exclude current post)
// الحصول على المقالات المشابهة (نفس اللغة، نفس الفئة أو العلامات، استثناء المقال الحالي)
$related_posts = [];
$categories = wp_get_post_categories($post->ID);
$tags = wp_get_post_tags($post->ID, array('fields' => 'ids'));

if (!empty($categories) || !empty($tags)) {
    $tax_query = array('relation' => 'OR');
    
    if (!empty($categories)) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $categories
        );
    }
    
    if (!empty($tags)) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'term_id',
            'terms' => $tags
        );
    }

    $related_args = array(
        'post_type' => 'blog',
        'posts_per_page' => 6,
        'post__not_in' => array($post->ID),
        'post_status' => 'publish',
        'orderby' => 'rand',
        'tax_query' => $tax_query
    );

    $related_timber_posts = Timber::get_posts($related_args);
    
    // Process related posts to add language-specific content
    foreach ($related_timber_posts as $related_post) {
        if ($current_language === 'en') {
            // For English, use metabox fields
            $related_post->title = get_post_meta($related_post->ID, 'blog_title_en', true) ?: $related_post->title;
            $related_post->content = get_post_meta($related_post->ID, 'blog_content_en', true) ?: $related_post->content;
            $related_post->excerpt = get_post_meta($related_post->ID, 'blog_excerpt_en', true) ?: $related_post->excerpt;
        }
        // For Arabic, use default WordPress fields (no changes needed)
        $related_posts[] = $related_post;
    }
}

// If no related posts found by category/tags, get latest posts
if (empty($related_posts)) {
    $fallback_args = array(
        'post_type' => 'blog',
        'posts_per_page' => 6,
        'post__not_in' => array($post->ID),
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $fallback_posts = Timber::get_posts($fallback_args);
    
    foreach ($fallback_posts as $fallback_post) {
        if ($current_language === 'en') {
            $fallback_post->title = get_post_meta($fallback_post->ID, 'blog_title_en', true) ?: $fallback_post->title;
            $fallback_post->content = get_post_meta($fallback_post->ID, 'blog_content_en', true) ?: $fallback_post->content;
            $fallback_post->excerpt = get_post_meta($fallback_post->ID, 'blog_excerpt_en', true) ?: $fallback_post->excerpt;
        }
        $related_posts[] = $fallback_post;
    }
}

$context['related_posts'] = $related_posts;

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('single-blog.twig');
} else {
    // Fallback to default template
    $template = 'single-blog.twig';
}

Timber::render($template, $context);

