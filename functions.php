<?php

// Development mode
define('DEV_MODE', true);


// Include Composer autoloader
require_once get_template_directory() . '/vendor/autoload.php';


// Include theme files
require(get_theme_file_path('inc/setup.php'));
require(get_theme_file_path('inc/enqueues.php'));
require(get_theme_file_path('inc/custom_post_type.php'));
require(get_theme_file_path('inc/archive-pagination.php'));
require(get_theme_file_path('inc/language-routing.php'));
require(get_theme_file_path('inc/taxonomy-images.php'));
require(get_theme_file_path('inc/export-functions.php'));
require(get_theme_file_path('inc/admin/export-handlers.php'));
require(get_theme_file_path('inc/admin/settings-page.php'));
require(get_theme_file_path('inc/admin/about-settings.php'));
require(get_theme_file_path('inc/admin/contact-submissions-admin.php'));
require(get_theme_file_path('inc/admin/dashboard-audio.php'));
require(get_theme_file_path('inc/view-page-source.php'));
require(get_theme_file_path('inc/contact-form-handler.php'));
require(get_theme_file_path('inc/job-application-handler.php'));

require(get_theme_file_path('inc/meta_boxes/main.php'));
require(get_theme_file_path('inc/admin/job-applications-admin.php'));




