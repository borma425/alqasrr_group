<?php
use Timber\Timber;

$context = Timber::context();
$context['post'] = Timber::get_post();

// Get current language
$current_lang = is_english_version() ? 'en' : 'ar';

$translate_option = function ($base_key, $default = '') use ($current_lang) {
    $value = '';

    if ($current_lang === 'en') {
        $value = get_option("{$base_key}_en", '');
        if (trim((string) $value) === '') {
            $value = get_option("{$base_key}_ar", '');
        }
    } else {
        $value = get_option("{$base_key}_ar", '');
    }

    $value = trim((string) $value);

    return $value !== '' ? $value : $default;
};

// Hero Section
$context['about_hero_title'] = $translate_option('about_hero_title');
$context['about_hero_description'] = $translate_option('about_hero_description');
$context['about_hero_background'] = get_option('about_hero_background', '');

// About Us Section
$context['about_us_subtitle'] = $translate_option('about_us_subtitle');
$context['about_us_title'] = $translate_option('about_us_title');
$context['about_us_description'] = $translate_option('about_us_description');
$context['about_us_image_1'] = get_option('about_us_image_1', '');
$context['about_us_image_2'] = get_option('about_us_image_2', '');

// Why Choose Us - Identity Section
$context['why_choose_identity_items'] = get_option('why_choose_identity_items', array());

// Why Choose Us - What Makes Us Different Section
$context['why_choose_subtitle'] = $translate_option('why_choose_subtitle');
$context['why_choose_title'] = $translate_option('why_choose_title');
$context['why_choose_different_items'] = get_option('why_choose_different_items', array());

// Our Values Section
$context['our_values_subtitle'] = $translate_option('our_values_subtitle');
$context['our_values_title'] = $translate_option('our_values_title');
$context['our_values_background'] = get_option('our_values_background', '');
$context['our_values_items'] = get_option('our_values_items', array());

// Build template fallback chain to avoid missing-language issues
$templates = array('page-about.twig');

if ($current_lang === 'en') {
    array_unshift($templates, 'en/page-about.twig');
    $templates[] = 'ar/page-about.twig';
} else {
    array_unshift($templates, 'ar/page-about.twig');
    $templates[] = 'en/page-about.twig';
}

Timber::render($templates, $context);
