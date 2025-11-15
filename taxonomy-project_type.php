<?php

use Timber\Timber;

if (!function_exists('alqasr_decode_url_param')) {
    /**
     * Decode and sanitize URL parameters
     *
     * @param string $param
     * @return string
     */
    function alqasr_decode_url_param($param)
    {
        if (empty($param)) {
            return '';
        }

        $decoded = urldecode($param);

        // Handle double encoding
        if (strpos($decoded, '%') !== false) {
            $decoded = urldecode($decoded);
        }

        return trim(sanitize_text_field($decoded));
    }
}

$context = Timber::context();

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
}

$current_term = get_queried_object();
if ($current_term instanceof WP_Term) {
    if ($is_english) {
        if (function_exists('get_taxonomy_name')) {
            $localized_name = get_taxonomy_name($current_term->term_id, 'en');
            if (!empty($localized_name)) {
                $current_term->name = $localized_name;
            }
        }
        if (function_exists('get_taxonomy_description')) {
            $localized_description = get_taxonomy_description($current_term->term_id, 'en');
            if (!empty($localized_description)) {
                $current_term->description = $localized_description;
            }
        }
    }
    $context['current_term'] = $current_term;
}

// Selected filters (default to current term)
$selected_project_type = ($current_term instanceof WP_Term) ? $current_term->slug : '';
$selected_city = isset($_GET['city']) ? alqasr_decode_url_param($_GET['city']) : '';
$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Allow overriding project type via query param (fallback to current term)
$query_project_type = isset($_GET['project_type']) ? alqasr_decode_url_param($_GET['project_type']) : '';
if (!empty($query_project_type)) {
    $selected_project_type = $query_project_type;
}

// Get taxonomies for filters
$project_types = get_terms([
    'taxonomy' => 'project_type',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

if ($is_english && function_exists('get_taxonomy_name')) {
    foreach ($project_types as $index => $term) {
        if ($term && isset($term->term_id)) {
            $project_types[$index]->name = get_taxonomy_name($term->term_id, 'en');
        }
    }
}

$cities = get_terms([
    'taxonomy' => 'city',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

if ($is_english && function_exists('get_taxonomy_name')) {
    foreach ($cities as $index => $term) {
        if ($term && isset($term->term_id)) {
            $cities[$index]->name = get_taxonomy_name($term->term_id, 'en');
        }
    }
}

// Normalize selected project type slug
if (!empty($selected_project_type)) {
    $selected_project_type = trim($selected_project_type);
    $term = get_term_by('slug', $selected_project_type, 'project_type');

    if (!$term || is_wp_error($term)) {
        $all_terms = get_terms([
            'taxonomy' => 'project_type',
            'hide_empty' => false,
        ]);

        if (!is_wp_error($all_terms)) {
            foreach ($all_terms as $t) {
                if (strtolower($t->slug) === strtolower($selected_project_type)) {
                    $selected_project_type = $t->slug;
                    break;
                }
            }
        }
    } else {
        $selected_project_type = $term->slug;
    }
}

// Normalize selected city slug
if (!empty($selected_city)) {
    $selected_city = trim($selected_city);
    $term = get_term_by('slug', $selected_city, 'city');

    if (!$term || is_wp_error($term)) {
        $all_terms = get_terms([
            'taxonomy' => 'city',
            'hide_empty' => false,
        ]);

        if (!is_wp_error($all_terms)) {
            foreach ($all_terms as $t) {
                if (strtolower($t->slug) === strtolower($selected_city)) {
                    $selected_city = $t->slug;
                    break;
                }
            }
        }
    } else {
        $selected_city = $term->slug;
    }
}

// Build common tax query (always constrain by current term)
$tax_query = [];

if (!empty($selected_project_type)) {
    $term = get_term_by('slug', $selected_project_type, 'project_type');
    if ($term && !is_wp_error($term)) {
        $tax_query[] = [
            'taxonomy' => 'project_type',
            'field' => 'slug',
            'terms' => $selected_project_type,
        ];
    }
} elseif ($current_term instanceof WP_Term) {
    $tax_query[] = [
        'taxonomy' => 'project_type',
        'field' => 'term_id',
        'terms' => $current_term->term_id,
    ];
}

if (!empty($selected_city)) {
    $term = get_term_by('slug', $selected_city, 'city');
    if ($term && !is_wp_error($term)) {
        $tax_query[] = [
            'taxonomy' => 'city',
            'field' => 'slug',
            'terms' => $selected_city,
        ];
    }
}

if (count($tax_query) > 1) {
    $tax_query['relation'] = 'AND';
}

// Featured project (most recent in this taxonomy)
$featured_query_args = [
    'post_type' => 'projects',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
];

if (!empty($tax_query)) {
    $featured_query_args['tax_query'] = $tax_query;
}

$featured_query = new WP_Query($featured_query_args);
$featured_post = null;

$localize_project = function ($project) use ($is_english) {
    if (!$is_english || !is_object($project)) {
        return $project;
    }

    $project_id = isset($project->ID) ? $project->ID : (isset($project->id) ? $project->id : 0);
    if (!$project_id) {
        return $project;
    }

    $title_en = get_post_meta($project_id, '_projects_title_en', true);
    if (!empty($title_en)) {
        if (isset($project->post_title)) {
            $project->post_title = $title_en;
        }
        $project->title = $title_en;
    }

    $excerpt_en = get_post_meta($project_id, '_projects_excerpt_en', true);
    if (!empty($excerpt_en)) {
        if (isset($project->post_excerpt)) {
            $project->post_excerpt = $excerpt_en;
        }
        $project->excerpt = $excerpt_en;
    }

    if (function_exists('get_english_url')) {
        $project->link = get_english_url($project_id);
    }

    if (function_exists('get_taxonomy_name')) {
        if (!empty($project->project_types)) {
            foreach ($project->project_types as $term) {
                if (isset($term->term_id)) {
                    $term->name = get_taxonomy_name($term->term_id, 'en');
                }
            }
        }

        if (!empty($project->cities)) {
            foreach ($project->cities as $term) {
                if (isset($term->term_id)) {
                    $term->name = get_taxonomy_name($term->term_id, 'en');
                }
            }
        }
    }

    return $project;
};

if ($featured_query->have_posts()) {
    $featured_post = Timber::get_post($featured_query->posts[0]->ID);
    if ($featured_post) {
        $featured_post->project_types = $featured_post->terms('project_type');
        $featured_post->cities = $featured_post->terms('city');
        $featured_post = $localize_project($featured_post);
    }
}

wp_reset_postdata();

// Grid projects (remaining projects within taxonomy)
$grid_query_args = [
    'post_type' => 'projects',
    'posts_per_page' => -1,
    'orderby' => 'rand',
    'post_status' => 'publish',
];

if ($featured_post) {
    $grid_query_args['post__not_in'] = [$featured_post->id];
}

if (!empty($tax_query)) {
    $grid_query_args['tax_query'] = $tax_query;
}

if (!empty($search_query)) {
    $grid_query_args['s'] = $search_query;
    $grid_query_args['orderby'] = 'relevance';
}

$grid_query = new WP_Query($grid_query_args);
$grid_posts = [];

if ($grid_query->have_posts()) {
    foreach ($grid_query->posts as $grid_post) {
        $timber_post = Timber::get_post($grid_post->ID);
        if ($timber_post) {
            $timber_post->project_types = $timber_post->terms('project_type');
            $timber_post->cities = $timber_post->terms('city');
            $grid_posts[] = $localize_project($timber_post);
        }
    }
}

wp_reset_postdata();

// Prepare main posts with pagination using server-side filtering
$paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
if ($paged < 1 && get_query_var('page')) {
    $paged = (int) get_query_var('page');
}
if ($paged < 1) {
    $paged = 1;
}

$main_query_args = [
    'post_type' => 'projects',
    'posts_per_page' => get_option('posts_per_page', 9),
    'paged' => $paged,
    'post_status' => 'publish',
];

if (!empty($tax_query)) {
    $main_query_args['tax_query'] = $tax_query;
}

if (!empty($search_query)) {
    $main_query_args['s'] = $search_query;
}

$main_posts = Timber::get_posts($main_query_args);
foreach ($main_posts as $post) {
    $post->project_types = $post->terms('project_type');
    $post->cities = $post->terms('city');
    $localize_project($post);
}

// Hero content: prioritize term data, fall back to options
if ($current_term instanceof WP_Term) {
    if ($is_english) {
        $hero_title = function_exists('get_taxonomy_name') ? get_taxonomy_name($current_term->term_id, 'en') : '';
        $hero_description = function_exists('get_taxonomy_description') ? get_taxonomy_description($current_term->term_id, 'en') : '';

        $context['projects_hero_title'] = !empty($hero_title) ? $hero_title : get_option('projects_hero_title_en', get_option('projects_hero_title', 'Our Projects'));
        $context['projects_hero_description'] = !empty($hero_description)
            ? $hero_description
            : get_option('projects_hero_description_en', get_option('projects_hero_description', 'Discover premium real estate projects across global markets.'));
    } else {
        $context['projects_hero_title'] = $current_term->name;
        $context['projects_hero_description'] = !empty($current_term->description)
            ? $current_term->description
            : get_option('projects_hero_description', 'مشاريع عقارية متميزة في أسواق عالمية.');
    }
} else {
    if ($is_english) {
        $context['projects_hero_title'] = get_option('projects_hero_title_en', get_option('projects_hero_title', 'Our Projects'));
        $context['projects_hero_description'] = get_option('projects_hero_description_en', get_option('projects_hero_description', 'Discover premium real estate projects across global markets.'));
    } else {
        $context['projects_hero_title'] = get_option('projects_hero_title', 'مشاريعنا');
        $context['projects_hero_description'] = get_option('projects_hero_description', 'مشاريع عقارية متميزة في أسواق عالمية.');
    }
}

$context['projects_background_image'] = get_option('projects_background_image', '');

// Context assignments
$context['featured_post'] = $featured_post;
$context['grid_posts'] = $grid_posts;
$context['posts'] = $main_posts;
$context['project_types'] = $project_types;
$context['cities'] = $cities;
$context['selected_project_type'] = $selected_project_type;
$context['selected_city'] = $selected_city;
$context['search_query'] = $search_query;
$context['archive_url'] = ($current_term instanceof WP_Term)
    ? get_term_link($current_term)
    : get_post_type_archive_link('projects');

if ($is_english && !is_wp_error($context['archive_url'])) {
    $term_link = $context['archive_url'];
    if (strpos($term_link, '/en/') === false) {
        $home_url = home_url('/');
        $path = str_replace($home_url, '', $term_link);
        $context['archive_url'] = $home_url . 'en/' . ltrim($path, '/');
    }
}

// Choose correct Twig template based on language
if (function_exists('get_language_template')) {
    $template = get_language_template('archive-projects.twig');
} else {
    $template = 'archive-projects.twig';
}

Timber::render($template, $context);


