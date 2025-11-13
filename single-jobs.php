<?php

use Timber\Timber;

$context = Timber::context();

$post = Timber::get_post();
$context['post'] = $post;

$job_id = $post ? $post->ID : 0;

$job_type = $job_id ? get_post_meta($job_id, 'job_type', true) : '';
if (empty($job_type) && $job_id) {
    $job_type = get_post_meta($job_id, '_job_type', true);
}
$job_type_en = $job_id ? get_post_meta($job_id, 'job_type_en', true) : '';
$job_location = $job_id ? get_post_meta($job_id, 'job_location', true) : '';
if (empty($job_location) && $job_id) {
    $job_location = get_post_meta($job_id, '_job_location', true);
}
$job_location_en = $job_id ? get_post_meta($job_id, 'job_location_en', true) : '';
$job_apply_link = $job_id ? get_post_meta($job_id, 'job_apply_link', true) : '';
$job_apply_label = $job_id ? get_post_meta($job_id, 'job_apply_label', true) : '';

$context['job_type'] = $job_type;
$context['job_location'] = $job_location;
$context['job_apply_link'] = $job_apply_link ?: get_permalink($job_id);
$context['job_apply_label'] = $job_apply_label;

$context['job_featured_image'] = null;

if ($post && has_post_thumbnail($job_id)) {
    $context['job_featured_image'] = get_the_post_thumbnail_url($job_id, 'large');
}

if (!$context['job_featured_image']) {
    if (function_exists('asset_url')) {
        $context['job_featured_image'] = asset_url('rectangle-4.png', '/img/main/');
    } else {
        $context['job_featured_image'] = get_template_directory_uri() . '/assets/img/main/rectangle-4.png';
    }
}

$is_english = function_exists('is_english_version') && is_english_version();

$job_title = $post ? (method_exists($post, 'title') ? $post->title() : ($post->title ?? '')) : '';
$job_excerpt = $post ? (method_exists($post, 'excerpt') ? $post->excerpt() : ($post->excerpt ?? '')) : '';
$job_content = $post ? (method_exists($post, 'content') ? $post->content() : ($post->content ?? '')) : '';

if ($is_english) {
    $section_label = get_option('jobs_cta_small_title_en', get_option('jobs_cta_small_title', 'Join Us'));
    $section_title = get_option('jobs_cta_main_title_en', get_option('jobs_cta_main_title', 'Apply Now'));

    $job_type_display = !empty($job_type_en) ? $job_type_en : $job_type;
    $job_location_display = !empty($job_location_en) ? $job_location_en : $job_location;

    $job_title_en = get_post_meta($job_id, 'job_title_en', true);
    $job_excerpt_en = get_post_meta($job_id, 'job_excerpt_en', true);
    $job_content_en = get_post_meta($job_id, 'job_content_en', true);

    if (!empty($job_title_en)) {
        $job_title = $job_title_en;
    }

    if (!empty($job_excerpt_en)) {
        $job_excerpt = $job_excerpt_en;
    }

    if (!empty($job_content_en)) {
        $job_content = $job_content_en;
    }
} else {
    $section_label = get_option('jobs_cta_small_title', 'انضم إلينا');
    $section_title = get_option('jobs_cta_main_title', 'قدّم الآن');

    $job_type_display = $job_type;
    $job_location_display = $job_location;
}

$context['job_type_display'] = $job_type_display;
$context['job_location_display'] = $job_location_display;
$context['job_title'] = $job_title;
$context['job_excerpt'] = $job_excerpt;
$context['job_content'] = $job_content;

if ($is_english) {
    $job_form_texts = array(
        'section_label'            => $section_label ?: 'Join Us',
        'section_title'            => $section_title ?: 'Apply Now',
        'first_name_label'         => 'First Name',
        'first_name_placeholder'   => 'e.g. John',
        'last_name_label'          => 'Last Name',
        'last_name_placeholder'    => 'e.g. Smith',
        'phone_label'              => 'Phone Number',
        'phone_placeholder'        => 'e.g. +971 50 0000 000',
        'email_label'              => 'Email',
        'email_placeholder'        => 'username@example.com',
        'upload_label'             => 'Upload Resume',
        'upload_placeholder'       => 'Click to upload your resume',
        'submit_label'             => 'Submit',
        'required_indicator'       => '*',
    );
} else {
    $job_form_texts = array(
        'section_label'            => $section_label ?: 'انضم إلينا',
        'section_title'            => $section_title ?: 'قدّم الآن على هذه الوظيفة',
        'first_name_label'         => 'الاسم الأول',
        'first_name_placeholder'   => 'مثال : محمد',
        'last_name_label'          => 'الاسم الأخير',
        'last_name_placeholder'    => 'مثال : أحمد',
        'phone_label'              => 'رقم الجوال',
        'phone_placeholder'        => 'مثال : +971 50 0000 000',
        'email_label'              => 'بريدك الإلكتروني',
        'email_placeholder'        => 'username@example.com',
        'upload_label'             => 'حمّل السيرة الذاتية',
        'upload_placeholder'       => 'اضغط لرفع السيرة الذاتية',
        'submit_label'             => 'إرسال',
        'required_indicator'       => '*',
    );
}

$context['job_form_texts'] = $job_form_texts;

if (function_exists('get_language_template')) {
    $template = get_language_template('single-job.twig');
} else {
    $template = 'single-job.twig';
}

Timber::render($template, $context);


