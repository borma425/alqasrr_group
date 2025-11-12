<?php

use Timber\Timber;

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if (get_query_var('s')) {
    $search_query = strip_tags((string) wp_unslash(get_query_var('s')));
    
    $context = Timber::context();
    
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
    
    // Process posts to add language-specific content
    $current_language = get_current_language();
    $processed_posts = [];
    
    foreach ($posts as $post) {
        if ($current_language === 'ar') {
            // For Arabic, use default WordPress fields (title, content, excerpt)
            // لا حاجة لتغيير شيء - الحقول الافتراضية هي للعربية
        } elseif ($current_language === 'en') {
            // For English, use metabox fields
            $post->title = get_post_meta($post->ID, 'blog_title_en', true) ?: $post->title;
            $post->content = get_post_meta($post->ID, 'blog_content_en', true) ?: $post->content;
            $post->excerpt = get_post_meta($post->ID, 'blog_excerpt_en', true) ?: $post->excerpt;
        }
        
        $processed_posts[] = $post;
    }
    
    $context['posts'] = $processed_posts;
    
    // Add search query to context
    $context['search_query'] = $search_query;
    
    // Add pagination info
    $context['current_page'] = $paged;
    $context['total_posts'] = $posts->found_posts ?? 0;
    $context['posts_per_page'] = 20; // Show 20 posts per page
    
    // Get the appropriate template based on current language
    if (is_english_version()) {
        $template = 'en/archive-blogs.twig';
    } else {
        $template = 'ar/archive-blogs.twig';
    }
    
    Timber::render($template, $context);
} else {
    // Redirect to home page if no search query
    wp_redirect(home_url());
    exit;
}