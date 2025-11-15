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

if ($is_english && is_object($post)) {
    $post_id = isset($post->ID) ? $post->ID : (isset($post->id) ? $post->id : 0);
    if ($post_id) {
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
    foreach ($related_timber_posts as $related_post) {
        if ($is_english && is_object($related_post)) {
            $related_post_id = isset($related_post->ID) ? $related_post->ID : (isset($related_post->id) ? $related_post->id : 0);
            if ($related_post_id) {
                $related_title_en = get_post_meta($related_post_id, 'blog_title_en', true);
                if (!empty($related_title_en)) {
                    if (isset($related_post->post_title)) {
                        $related_post->post_title = $related_title_en;
                    }
                    $related_post->title = $related_title_en;
                }

                $related_excerpt_en = get_post_meta($related_post_id, 'blog_excerpt_en', true);
                if (!empty($related_excerpt_en)) {
                    if (isset($related_post->post_excerpt)) {
                        $related_post->post_excerpt = $related_excerpt_en;
                    }
                    $related_post->excerpt = $related_excerpt_en;
                }

                $related_content_en = get_post_meta($related_post_id, 'blog_content_en', true);
                if (!empty($related_content_en) && isset($related_post->post_content)) {
                    $related_post->post_content = $related_content_en;
                    $related_post->content = $related_content_en;
                }

                if (function_exists('get_english_url')) {
                    $related_post->link = get_english_url($related_post_id);
                }
            }
        }
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
        if ($is_english && is_object($fallback_post)) {
            $fallback_post_id = isset($fallback_post->ID) ? $fallback_post->ID : (isset($fallback_post->id) ? $fallback_post->id : 0);
            if ($fallback_post_id) {
                $fallback_title_en = get_post_meta($fallback_post_id, 'blog_title_en', true);
                if (!empty($fallback_title_en)) {
                    if (isset($fallback_post->post_title)) {
                        $fallback_post->post_title = $fallback_title_en;
                    }
                    $fallback_post->title = $fallback_title_en;
                }

                $fallback_excerpt_en = get_post_meta($fallback_post_id, 'blog_excerpt_en', true);
                if (!empty($fallback_excerpt_en)) {
                    if (isset($fallback_post->post_excerpt)) {
                        $fallback_post->post_excerpt = $fallback_excerpt_en;
                    }
                    $fallback_post->excerpt = $fallback_excerpt_en;
                }

                $fallback_content_en = get_post_meta($fallback_post_id, 'blog_content_en', true);
                if (!empty($fallback_content_en) && isset($fallback_post->post_content)) {
                    $fallback_post->post_content = $fallback_content_en;
                    $fallback_post->content = $fallback_content_en;
                }

                if (function_exists('get_english_url')) {
                    $fallback_post->link = get_english_url($fallback_post_id);
                }
            }
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

