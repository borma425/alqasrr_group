<?php
/**
 * Template for displaying single contact submissions
 * قالب لعرض رسائل الاتصال الفردية
 */

use Timber\Timber;

// Check permissions - only admins can view
if (!current_user_can('administrator')) {
    wp_die('ليس لديك صلاحية', 'غير مصرح', array('response' => 403));
}

$context = Timber::context();
$context['post'] = Timber::get_post();

// Get meta data
$context['contact_first_name'] = get_post_meta($context['post']->ID, 'contact_first_name', true);
$context['contact_second_name'] = get_post_meta($context['post']->ID, 'contact_second_name', true);
$context['contact_email'] = get_post_meta($context['post']->ID, 'contact_email', true);
$context['contact_phone'] = get_post_meta($context['post']->ID, 'contact_phone', true);
$context['contact_message'] = get_post_meta($context['post']->ID, 'contact_message', true);

// Get appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('single-contact_submissions.twig');
} else {
    // Fallback to Arabic template
    $template = 'ar/single-contact_submissions.twig';
}

Timber::render($template, $context);

