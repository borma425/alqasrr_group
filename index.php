
<?php

use Timber\Timber;

$context = Timber::context();

$context['is_front_page'] = true;

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
}

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
        $project_types = $project->terms('project_type');
        if (!empty($project_types)) {
            foreach ($project_types as $term) {
                if (isset($term->term_id)) {
                    $term->name = get_taxonomy_name($term->term_id, 'en');
                }
            }
        }

        $cities = $project->terms('city');
        if (!empty($cities)) {
            foreach ($cities as $term) {
                if (isset($term->term_id)) {
                    $term->name = get_taxonomy_name($term->term_id, 'en');
                }
            }
        }
    }

    return $project;
};

$localize_blog = function ($post) use ($is_english) {
    if (!$is_english || !is_object($post)) {
        return $post;
    }

    $post_id = isset($post->ID) ? $post->ID : (isset($post->id) ? $post->id : 0);
    if (!$post_id) {
        return $post;
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

    return $post;
};

$projects = Timber::get_posts([
    'post_type'      => 'projects',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$localized_projects = [];
foreach ($projects as $project) {
    $localized_projects[] = $localize_project($project);
}
$context['projects'] = $localized_projects;

$news_posts = Timber::get_posts([
    'post_type'      => 'blog',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$localized_news = [];
foreach ($news_posts as $news_post) {
    $localized_news[] = $localize_blog($news_post);
}
$context['news'] = $localized_news;


// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('index-home.twig');
} else {
    // Fallback to default template
    $template = 'index-home.twig';
}

Timber::render($template, $context);




