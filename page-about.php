<?php
use Timber\Timber;

$context = Timber::context();
$context['post'] = Timber::get_post();

// Get current language
$current_lang = is_english_version() ? 'en' : 'ar';

// Hero Section
$context['about_hero_title'] = $current_lang === 'en' ? get_option('about_hero_title_en', '') : get_option('about_hero_title_ar', '');
$context['about_hero_description'] = $current_lang === 'en' ? get_option('about_hero_description_en', '') : get_option('about_hero_description_ar', '');
$context['about_hero_background'] = get_option('about_hero_background', '');

// About Us Section
$context['about_us_subtitle'] = $current_lang === 'en' ? get_option('about_us_subtitle_en', '') : get_option('about_us_subtitle_ar', '');
$context['about_us_title'] = $current_lang === 'en' ? get_option('about_us_title_en', '') : get_option('about_us_title_ar', '');
$context['about_us_description'] = $current_lang === 'en' ? get_option('about_us_description_en', '') : get_option('about_us_description_ar', '');
$context['about_us_image_1'] = get_option('about_us_image_1', '');
$context['about_us_image_2'] = get_option('about_us_image_2', '');

// Why Choose Us - Identity Section
$context['why_choose_identity_items'] = get_option('why_choose_identity_items', array());

// Why Choose Us - What Makes Us Different Section
$context['why_choose_subtitle'] = $current_lang === 'en' ? get_option('why_choose_subtitle_en', '') : get_option('why_choose_subtitle_ar', '');
$context['why_choose_title'] = $current_lang === 'en' ? get_option('why_choose_title_en', '') : get_option('why_choose_title_ar', '');
$context['why_choose_different_items'] = get_option('why_choose_different_items', array());

// Our Values Section
$context['our_values_subtitle'] = $current_lang === 'en' ? get_option('our_values_subtitle_en', '') : get_option('our_values_subtitle_ar', '');
$context['our_values_title'] = $current_lang === 'en' ? get_option('our_values_title_en', '') : get_option('our_values_title_ar', '');
$context['our_values_background'] = get_option('our_values_background', '');
$context['our_values_items'] = get_option('our_values_items', array());

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('page-about.twig');
} else {
    // Fallback to default template
    $template = 'page-about.twig';
}

Timber::render($template, $context);
