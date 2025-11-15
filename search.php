<?php

use Timber\Timber;

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if (get_query_var('s')) {
    $search_query = strip_tags((string) wp_unslash(get_query_var('s')));
    
    $context = Timber::context();
    $is_english = false;
    if (function_exists('is_english_version') && is_english_version()) {
        $is_english = true;
    } elseif (function_exists('get_current_language') && get_current_language() === 'en') {
        $is_english = true;
    }

    // Build query arguments
    $query_args = array(
        'post_type' => 'blog',
        'posts_per_page' => 20, // Show 20 posts per page
        'paged' => $paged,
        'post_status' => 'publish'
    );
    
    // Add search functionality
    if (!empty($search_query)) {
        $current_language = get_current_language();
        
        // First, get posts with basic search
        $basic_posts = get_posts(array_merge($query_args, array('s' => $search_query)));
        $basic_post_ids = wp_list_pluck($basic_posts, 'ID');
        
        // Search in language-specific metabox fields
        $meta_fields = array();
        if ($current_language === 'ar') {
            // For Arabic, search in default WordPress fields (title, content, excerpt)
            // البحث في الحقول الافتراضية يتم تلقائياً في $basic_posts
            // لا حاجة للبحث في meta fields للعربية
        } elseif ($current_language === 'en') {
            // Search in English fields
            $meta_fields = array(
                array(
                    'key' => 'blog_title_en',
                    'value' => $search_query,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'blog_content_en',
                    'value' => $search_query,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'blog_excerpt_en',
                    'value' => $search_query,
                    'compare' => 'LIKE'
                )
            );
        }
        
        $language_post_ids = array();
        if (!empty($meta_fields)) {
            $meta_query = array(
                'relation' => 'OR'
            );
            $meta_query = array_merge($meta_query, $meta_fields);
            
            $language_posts = get_posts(array(
                'post_type' => 'blog',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => $meta_query
            ));
            $language_post_ids = wp_list_pluck($language_posts, 'ID');
        }
        
        // Combine and remove duplicates
        $all_post_ids = array_unique(array_merge($basic_post_ids, $language_post_ids));
        
        if (!empty($all_post_ids)) {
            $query_args['post__in'] = $all_post_ids;
            $query_args['orderby'] = 'post__in';
        } else {
            // If no results found, return empty
            $query_args['post__in'] = array(0);
        }
    }
    
    // Get posts
    $posts = Timber::get_posts($query_args);
    
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
    
    // Add search query to context
    $context['search_query'] = $search_query;
    
    // Add pagination info
    $context['current_page'] = $paged;
    $context['total_posts'] = $posts->found_posts ?? 0;
    $context['posts_per_page'] = 20; // Show 20 posts per page
    
    // Get the appropriate template based on current language
    if (function_exists('get_language_template')) {
        $template = get_language_template('search/results.twig');
    } else {
        $template = 'search/results.twig';
    }
    
    Timber::render($template, $context);
} else {
    // Redirect to home page if no search query
    wp_redirect(home_url());
    exit;
}