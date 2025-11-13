<?php

use Timber\Timber;

$context = Timber::context();

$post = Timber::get_post();
$context['post'] = $post;

$job_id = $post ? $post->ID : 0;

$job_type = $job_id ? get_post_meta($job_id, 'job_type', true) : '';
$job_location = $job_id ? get_post_meta($job_id, 'job_location', true) : '';
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

if ($is_english) {
    $section_label = get_option('jobs_cta_small_title_en', get_option('jobs_cta_small_title', 'Join Us'));
    $section_title = get_option('jobs_cta_main_title_en', get_option('jobs_cta_main_title', 'Apply Now'));

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
    $section_label = get_option('jobs_cta_small_title', 'انضم إلينا');
    $section_title = get_option('jobs_cta_main_title', 'قدّم الآن');

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


