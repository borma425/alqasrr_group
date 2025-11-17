<?php

use Timber\Timber;

$context = Timber::context();

// Get the current post
$post = Timber::get_post();

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
}

$localize_single_blog_post = function($post_object) use ($is_english) {
    if (!$is_english || !is_object($post_object)) {
        return $post_object;
    }

    $post_id = isset($post_object->ID) ? $post_object->ID : (isset($post_object->id) ? $post_object->id : 0);
    if (!$post_id) {
        return $post_object;
    }

    $title_en = get_post_meta($post_id, 'blog_title_en', true);
    if (!empty($title_en)) {
        if (isset($post_object->post_title)) {
            $post_object->post_title = $title_en;
        }
        $post_object->title = $title_en;
    }

    $excerpt_en = get_post_meta($post_id, 'blog_excerpt_en', true);
    $content_en = get_post_meta($post_id, 'blog_content_en', true);

    if (!empty($excerpt_en)) {
        $post_object->post_excerpt = $excerpt_en;
    } elseif (!empty($content_en)) {
        $post_object->post_excerpt = wp_trim_words(wp_strip_all_tags($content_en), 32, '...');
    }

    if (!empty($content_en)) {
        if (isset($post_object->post_content)) {
            $post_object->post_content = $content_en;
        }
        $post_object->content = $content_en;
    }

    if (function_exists('get_english_url')) {
        $post_object->link = get_english_url($post_id);
    }

    if (function_exists('format_date_english')) {
        $post_object->english_date = format_date_english($post_object->date('Y-m-d'), 'j F Y');
    } else {
        $post_object->english_date = $post_object->date('j F Y');
    }

    // Localize project_type for English version
    if ($is_english && function_exists('get_taxonomy_name')) {
        $project_type_terms = $post_object->terms('project_type');
        if (!empty($project_type_terms)) {
            foreach ($project_type_terms as $term) {
                if (isset($term->term_id)) {
                    $term->name = get_taxonomy_name($term->term_id, 'en');
                }
            }
        }
    }

    return $post_object;
};

$post = $localize_single_blog_post($post);

$context['post'] = $post;

// Get related posts (same language, same project_type or tags, exclude current post)
// الحصول على المقالات المشابهة (نفس اللغة، نفس project_type أو العلامات، استثناء المقال الحالي)
$related_posts = [];
$project_types = wp_get_object_terms($post->ID, 'project_type', array('fields' => 'ids'));
$tags = wp_get_post_tags($post->ID, array('fields' => 'ids'));

if (!empty($project_types) || !empty($tags)) {
    $tax_query = array('relation' => 'OR');
    
    if (!empty($project_types) && !is_wp_error($project_types)) {
        $tax_query[] = array(
            'taxonomy' => 'project_type',
            'field' => 'term_id',
            'terms' => $project_types
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
    foreach ($related_timber_posts as $related_post) {
        $related_posts[] = $localize_single_blog_post($related_post);
    }
}

// If no related posts found by project_type/tags, get latest posts
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
        $related_posts[] = $localize_single_blog_post($fallback_post);
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

