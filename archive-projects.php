<?php

use Timber\Timber;

/**
 * Archive: Projects
 * أرشيف: المشاريع
 * 
 * Posts per page is set in inc/archive-pagination.php
 * عدد المشاريع لكل صفحة يتم تعيينه في inc/archive-pagination.php
 * 
 * To change the number of posts per page, edit:
 * لتغيير عدد المشاريع لكل صفحة، عدّل:
 * inc/archive-pagination.php → $archive_posts_per_page['projects'] = 9;
 */

// Helper function to decode URL parameters
// دالة مساعدة لفك ترميز معاملات URL
function decode_url_param($param) {
    if (empty($param)) {
        return '';
    }
    $decoded = urldecode($param);
    // Handle double encoding
    // معالجة الترميز المضاعف
    if (strpos($decoded, '%') !== false) {
        $decoded = urldecode($decoded);
    }
    return trim(sanitize_text_field($decoded));
}

// Modify main query to apply taxonomy filters
// تعديل الاستعلام الرئيسي لتطبيق فلترات taxonomy
function apply_projects_archive_filters($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if (!is_post_type_archive('projects')) {
        return;
    }
    
    // Get filter parameters from query string
    // الحصول على معاملات الفلترة من query string
    $selected_project_type = isset($_GET['project_type']) ? decode_url_param($_GET['project_type']) : '';
    $selected_city = isset($_GET['city']) ? decode_url_param($_GET['city']) : '';
    $search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    
    // Apply search query if set (works independently)
    // تطبيق استعلام البحث إذا كان محدداً (يعمل بشكل مستقل)
    if (!empty($search_query)) {
        $query->set('s', $search_query);
    }
    
    // Build taxonomy query
    // بناء استعلام taxonomy
    $tax_query = array();
    
    if (!empty($selected_project_type)) {
        // Verify that the term exists
        // التحقق من وجود term
        $term = get_term_by('slug', $selected_project_type, 'project_type');
        if ($term && !is_wp_error($term)) {
        $tax_query[] = array(
            'taxonomy' => 'project_type',
            'field' => 'slug',
            'terms' => $selected_project_type
        );
        }
    }
    
    if (!empty($selected_city)) {
        // Verify that the term exists
        // التحقق من وجود term
        $term = get_term_by('slug', $selected_city, 'city');
        if ($term && !is_wp_error($term)) {
        $tax_query[] = array(
            'taxonomy' => 'city',
            'field' => 'slug',
            'terms' => $selected_city
        );
        }
    }
    
    // Only add tax_query if we have filters
    // إضافة tax_query فقط إذا كانت هناك فلترات
    if (!empty($tax_query)) {
        // Add relation only if we have multiple filters
        // إضافة relation فقط إذا كان لدينا أكثر من filter
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $query->set('tax_query', $tax_query);
    }
}
add_action('pre_get_posts', 'apply_projects_archive_filters', 20);

$context = Timber::context();

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

// Get filter parameters from query string
// الحصول على معاملات الفلترة من query string
$selected_project_type = isset($_GET['project_type']) ? decode_url_param($_GET['project_type']) : '';
$selected_city = isset($_GET['city']) ? decode_url_param($_GET['city']) : '';

$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

// Get all project types for filter dropdown
// الحصول على جميع أنواع المشاريع للفلترة
$project_types = get_terms(array(
    'taxonomy' => 'project_type',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Get all cities for filter dropdown
// الحصول على جميع المدن للفلترة
$cities = get_terms(array(
    'taxonomy' => 'city',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

if ($is_english && function_exists('get_taxonomy_name')) {
    foreach ($project_types as $index => $term) {
        if ($term && isset($term->term_id)) {
            $project_types[$index]->name = get_taxonomy_name($term->term_id, 'en');
        }
    }

    foreach ($cities as $index => $term) {
        if ($term && isset($term->term_id)) {
            $cities[$index]->name = get_taxonomy_name($term->term_id, 'en');
        }
    }
}

// Normalize selected values to match term slugs exactly
// تطبيع القيم المحددة لتطابق term slugs بدقة
// This ensures the selected value matches the actual term slug in database
// هذا يضمن أن القيمة المحددة تطابق term slug الفعلي في قاعدة البيانات
if (!empty($selected_project_type)) {
    // Clean the value first
    // تنظيف القيمة أولاً
    $selected_project_type = trim($selected_project_type);
    
    // Try to find the term by slug (case-insensitive)
    // محاولة العثور على term بواسطة slug (غير حساس لحالة الأحرف)
    $term = get_term_by('slug', $selected_project_type, 'project_type');
    if (!$term || is_wp_error($term)) {
        // Try case-insensitive search
        // محاولة البحث غير الحساس لحالة الأحرف
        $all_terms = get_terms(array(
            'taxonomy' => 'project_type',
            'hide_empty' => false
        ));
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

if (!empty($selected_city)) {
    // Clean the value first
    // تنظيف القيمة أولاً
    $selected_city = trim($selected_city);
    
    // Try to find the term by slug (case-insensitive)
    // محاولة العثور على term بواسطة slug (غير حساس لحالة الأحرف)
    $term = get_term_by('slug', $selected_city, 'city');
    if (!$term || is_wp_error($term)) {
        // Try case-insensitive search
        // محاولة البحث غير الحساس لحالة الأحرف
        $all_terms = get_terms(array(
            'taxonomy' => 'city',
            'hide_empty' => false
        ));
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

// Get all posts for featured project (first project, no filters)
// الحصول على جميع المشاريع للمشروع المميز (أول مشروع، بدون فلترة)
$featured_query = new WP_Query(array(
    'post_type' => 'projects',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish'
));

$featured_post = null;
if ($featured_query->have_posts()) {
    $featured_post = Timber::get_post($featured_query->posts[0]->ID);
    if ($featured_post) {
        $featured_post->project_types = $featured_post->terms('project_type');
        $featured_post->cities = $featured_post->terms('city');
        $featured_post->title_en = get_post_meta($featured_post->ID, '_projects_title_en', true);
        $featured_post = $localize_project($featured_post);
    }
}
wp_reset_postdata();

// Get posts for grid (remaining projects with random order, excluding featured)
// الحصول على المشاريع للشبكة (المشاريع المتبقية بترتيب عشوائي، باستثناء المميز)
$grid_query_args = array(
    'post_type' => 'projects',
    'posts_per_page' => -1, // Get all for random order
    'orderby' => 'rand',
    'post_status' => 'publish'
);

// Exclude featured post if exists
// استبعاد المشروع المميز إذا كان موجوداً
if ($featured_post) {
    $grid_query_args['post__not_in'] = array($featured_post->id);
}

// Apply search query if set (works independently, searches in post title and content)
// تطبيق استعلام البحث إذا كان محدداً (يعمل بشكل مستقل، يبحث في العنوان والمحتوى)
if (!empty($search_query)) {
    $grid_query_args['s'] = $search_query;
    // When searching, order by relevance (title matches first) instead of random
    // عند البحث، ترتيب حسب الصلة (مطابقات العنوان أولاً) بدلاً من العشوائي
    $grid_query_args['orderby'] = 'relevance';
    // WordPress search (s parameter) searches in title, content, and excerpt by default
    // البحث في WordPress (معامل s) يبحث تلقائياً في العنوان والمحتوى والملخص
    // Title matches have higher relevance score
    // مطابقات العنوان لديها درجة صلة أعلى
}

// Apply taxonomy filters if set
// تطبيق فلترات taxonomy إذا كانت محددة
$tax_query = array();

if (!empty($selected_project_type)) {
    // Verify that the term exists
    // التحقق من وجود term
    $term = get_term_by('slug', $selected_project_type, 'project_type');
    if ($term && !is_wp_error($term)) {
        $tax_query[] = array(
            'taxonomy' => 'project_type',
            'field' => 'slug',
            'terms' => $selected_project_type
        );
    }
}

if (!empty($selected_city)) {
    // Verify that the term exists
    // التحقق من وجود term
    $term = get_term_by('slug', $selected_city, 'city');
    if ($term && !is_wp_error($term)) {
        $tax_query[] = array(
            'taxonomy' => 'city',
            'field' => 'slug',
            'terms' => $selected_city
        );
    }
}

if (!empty($tax_query)) {
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }
    $grid_query_args['tax_query'] = $tax_query;
}

$grid_query = new WP_Query($grid_query_args);
$grid_posts = array();
if ($grid_query->have_posts()) {
    foreach ($grid_query->posts as $grid_post) {
        $timber_post = Timber::get_post($grid_post->ID);
        if ($timber_post) {
            $timber_post->project_types = $timber_post->terms('project_type');
            $timber_post->cities = $timber_post->terms('city');
            $timber_post->title_en = get_post_meta($timber_post->ID, '_projects_title_en', true);
            $timber_post = $localize_project($timber_post);
            $grid_posts[] = $timber_post;
        }
    }
}
wp_reset_postdata();

// Get main query posts for pagination (if needed)
// الحصول على مشاريع الاستعلام الرئيسي للـ pagination (إذا لزم الأمر)
$main_posts = Timber::get_posts();
foreach ($main_posts as $post) {
    $post->project_types = $post->terms('project_type');
    $post->cities = $post->terms('city');
    $post->title_en = get_post_meta($post->ID, '_projects_title_en', true);
    $localize_project($post);
}

// Add to context
// إضافة إلى context
$context['featured_post'] = $featured_post;
$context['grid_posts'] = $grid_posts;
$context['posts'] = $main_posts; // For pagination
$context['project_types'] = $project_types;
$context['cities'] = $cities;
$context['selected_project_type'] = $selected_project_type;
$context['selected_city'] = $selected_city;
$context['search_query'] = $search_query;

// Get projects hero settings
// الحصول على إعدادات قسم Hero للمشاريع
if (function_exists('is_english_version') && is_english_version()) {
    $context['projects_hero_title'] = get_option('projects_hero_title_en', get_option('projects_hero_title', 'Our Projects'));
    $context['projects_hero_description'] = get_option('projects_hero_description_en', get_option('projects_hero_description', 'مشاريع عقارية متميزة في أسواق عالمية.'));
} else {
    $context['projects_hero_title'] = get_option('projects_hero_title', 'مشاريعنا');
    $context['projects_hero_description'] = get_option('projects_hero_description', 'مشاريع عقارية متميزة في أسواق عالمية.');
}
$context['projects_background_image'] = get_option('projects_background_image', '');

// Get current archive URL for filter form
// الحصول على URL الأرشيف الحالي لنموذج الفلترة
$context['archive_url'] = ($is_english && function_exists('get_language_url'))
    ? get_language_url('/projects/', 'en')
    : get_post_type_archive_link('projects');

// Add filter to preserve query parameters in pagination links
// إضافة filter للحفاظ على query parameters في روابط pagination
add_filter('get_pagenum_link', function($result) {
    if (is_post_type_archive('projects')) {
        // Get filter parameters (same decoding logic as above)
        // الحصول على معاملات الفلترة (نفس منطق فك الترميز أعلاه)
        $project_type = isset($_GET['project_type']) ? decode_url_param($_GET['project_type']) : '';
        $city = isset($_GET['city']) ? decode_url_param($_GET['city']) : '';
        
        $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
        
        $query_params = array();
        if (!empty($project_type)) {
            $query_params['project_type'] = $project_type;
        }
        if (!empty($city)) {
            $query_params['city'] = $city;
        }
        if (!empty($search)) {
            $query_params['search'] = $search;
        }
        
        if (!empty($query_params)) {
            $result = add_query_arg($query_params, $result);
        }
    }
    return $result;
});

// Get the appropriate template based on current language
// الحصول على القالب المناسب بناءً على اللغة الحالية
if (function_exists('get_language_template')) {
    $template = get_language_template('archive-projects.twig');
} else {
    $template = 'archive-projects.twig';
}

Timber::render($template, $context);

