<?php

use Timber\Timber;

/**
 * Archive: Jobs
 * أرشيف: الوظائف
 *
 * Loads Jobs archive data and passes theme options to Twig templates.
 */

$context = Timber::context();

$is_english = function_exists('is_english_version') && is_english_version();

// Collect jobs from the main query (supports pagination if enabled)
$jobs_posts = Timber::get_posts();

if (!empty($jobs_posts)) {
    foreach ($jobs_posts as $job_post) {
        $job_post->job_type = get_post_meta($job_post->ID, 'job_type', true);
        if (empty($job_post->job_type)) {
            $job_post->job_type = get_post_meta($job_post->ID, '_job_type', true);
        }
        $job_post->job_location = get_post_meta($job_post->ID, 'job_location', true);
        if (empty($job_post->job_location)) {
            $job_post->job_location = get_post_meta($job_post->ID, '_job_location', true);
        }
        $job_post->job_type_en = get_post_meta($job_post->ID, 'job_type_en', true);
        $job_post->job_location_en = get_post_meta($job_post->ID, 'job_location_en', true);

        $job_post->job_apply_link = get_post_meta($job_post->ID, 'job_apply_link', true);
        if (empty($job_post->job_apply_link)) {
            $job_post->job_apply_link = get_permalink($job_post->ID);
        }

        $job_post->job_apply_label = get_post_meta($job_post->ID, 'job_apply_label', true);

        $job_title_en = get_post_meta($job_post->ID, 'job_title_en', true);
        $job_excerpt_en = get_post_meta($job_post->ID, 'job_excerpt_en', true);

        $job_post->job_title_display = $is_english && !empty($job_title_en) ? $job_title_en : $job_post->title;
        $job_post->job_excerpt_display = $is_english && !empty($job_excerpt_en) ? $job_excerpt_en : $job_post->excerpt;
        $job_post->job_type_display = $is_english && !empty($job_post->job_type_en) ? $job_post->job_type_en : $job_post->job_type;
        $job_post->job_location_display = $is_english && !empty($job_post->job_location_en) ? $job_post->job_location_en : $job_post->job_location;

        if ($is_english) {
            if (!empty($job_title_en)) {
                if (isset($job_post->post_title)) {
                    $job_post->post_title = $job_title_en;
                }
                $job_post->title = $job_title_en;
            }

            if (!empty($job_excerpt_en)) {
                if (isset($job_post->post_excerpt)) {
                    $job_post->post_excerpt = $job_excerpt_en;
                }
                $job_post->excerpt = $job_excerpt_en;
            }

            $job_content_en = get_post_meta($job_post->ID, 'job_content_en', true);
            if (!empty($job_content_en)) {
                if (isset($job_post->post_content)) {
                    $job_post->post_content = $job_content_en;
                }
                $job_post->content = $job_content_en;
            }

            if (function_exists('get_english_url')) {
                $job_post->link = get_english_url($job_post->ID);
            }

            if (empty($job_post->job_apply_link) && function_exists('get_english_url')) {
                $job_post->job_apply_link = get_english_url($job_post->ID);
            }
        }
    }
}

$context['jobs'] = $jobs_posts;
$context['posts'] = $jobs_posts;

if ($is_english) {
    $context['jobs_main_title'] = get_option('jobs_main_title_en', get_option('jobs_main_title', 'Join Our Team'));
    $context['jobs_main_description'] = get_option('jobs_main_description_en', get_option('jobs_main_description', 'Discover career opportunities with our exceptional team.'));

    $context['jobs_highlight_subtitle'] = get_option('jobs_highlight_subtitle_en', get_option('jobs_highlight_subtitle', 'Working at AlQasr Real Estate'));
    $context['jobs_highlight_title'] = get_option('jobs_highlight_title_en', get_option('jobs_highlight_title', 'We Are Proud of Our Inspiring Work Environment'));
    $context['jobs_highlight_description'] = get_option('jobs_highlight_description_en', get_option('jobs_highlight_description', 'Our unique culture empowers every team member to reach their full potential.'));

    $context['jobs_cta_small_title'] = get_option('jobs_cta_small_title_en', get_option('jobs_cta_small_title', 'Join Us'));
    $context['jobs_cta_main_title'] = get_option('jobs_cta_main_title_en', get_option('jobs_cta_main_title', 'Apply Now'));
} else {
    $context['jobs_main_title'] = get_option('jobs_main_title', 'انضم إلينا');
    $context['jobs_main_description'] = get_option('jobs_main_description', 'اكتشف مستقبلك مع فريقنا المتميز وفرص التطور المهني.');

    $context['jobs_highlight_subtitle'] = get_option('jobs_highlight_subtitle', 'العمل في القصر العقارية');
    $context['jobs_highlight_title'] = get_option('jobs_highlight_title', 'نفتخر بامتلاكنا بيئة عمل ملهمة وناجحة');
    $context['jobs_highlight_description'] = get_option('jobs_highlight_description', 'نعمل على بناء منظومة متكاملة تدعم الإبداع والتميز لتحقيق الأهداف الاستراتيجية للشركة والموظفين.');

    $context['jobs_cta_small_title'] = get_option('jobs_cta_small_title', 'انضم إلينا');
    $context['jobs_cta_main_title'] = get_option('jobs_cta_main_title', 'قدّم الآن');
}

$context['jobs_highlight_image'] = get_option('jobs_highlight_image', '');

// Choose template based on language folder availability
if (function_exists('get_language_template')) {
    $template = get_language_template('archive-jobs.twig');
} else {
    $template = 'archive-jobs.twig';
}

Timber::render($template, $context);


