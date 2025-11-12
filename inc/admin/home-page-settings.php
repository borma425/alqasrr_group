<?php
/**
 * Home Page Settings
 * إعدادات الصفحة الرئيسية
 * 
 * This file includes all home page settings tabs
 */

if (!defined('ABSPATH')) exit;

// Include all home settings files
$home_settings_files = [
    'hero-settings.php',
    'about-settings.php',
    'projects-settings.php',
    'why-choose-settings.php',
    'news-settings.php'
];

foreach ($home_settings_files as $file) {
    $file_path = get_template_directory() . '/inc/admin/home/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

// Add admin menu for home page settings
function AlQasrGroup_add_home_page_menu() {
    add_menu_page(
        'إعدادات الصفحة الرئيسية', // Page title
        'إعدادات الصفحة الرئيسية', // Menu title
        'manage_options', // Capability
        'AlQasrGroup-home-settings', // Menu slug
        'AlQasrGroup_home_settings_page', // Function to output the page
        'dashicons-admin-home', // Icon
        31 // Position (right after AlQasrGroup-settings)
    );
}
add_action('admin_menu', 'AlQasrGroup_add_home_page_menu');

// Enqueue admin scripts for home page settings
function AlQasrGroup_home_admin_scripts($hook) {
    if ($hook != 'toplevel_page_AlQasrGroup-home-settings') {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_script('AlQasrGroup-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery', 'media-upload'), '1.0', true);
    wp_localize_script('AlQasrGroup-admin', 'AlQasrGroup_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('AlQasrGroup_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'AlQasrGroup_home_admin_scripts');

// Home page settings page HTML
function AlQasrGroup_home_settings_page() {
    // Handle form submission
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['AlQasrGroup_home_nonce'], 'AlQasrGroup_home_settings_nonce')) {
        // Save all settings from included files
        if (function_exists('AlQasrGroup_save_hero_settings')) {
            AlQasrGroup_save_hero_settings();
        }
        if (function_exists('AlQasrGroup_save_about_settings')) {
            AlQasrGroup_save_about_settings();
        }
        if (function_exists('AlQasrGroup_save_projects_settings')) {
            AlQasrGroup_save_projects_settings();
        }
        if (function_exists('AlQasrGroup_save_why_choose_settings')) {
            AlQasrGroup_save_why_choose_settings();
        }
        if (function_exists('AlQasrGroup_save_news_settings')) {
            AlQasrGroup_save_news_settings();
        }
        
        echo '<div class="notice notice-success"><p>تم حفظ الإعدادات بنجاح!</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>إعدادات الصفحة الرئيسية</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('AlQasrGroup_home_settings_nonce', 'AlQasrGroup_home_nonce'); ?>
            <div class="nav-tab-wrapper">
                <a href="#hero" class="nav-tab nav-tab-active">قسم Hero</a>
                <a href="#about" class="nav-tab">من نحن</a>
                <a href="#projects" class="nav-tab">المشاريع</a>
                <a href="#why-choose" class="nav-tab">لماذا نحن</a>
                <a href="#news" class="nav-tab">المقالات</a>
            </div>
            
            <!-- Hero Section -->
            <div id="hero" class="tab-content">
                <?php 
                if (function_exists('AlQasrGroup_hero_settings_html')) {
                    AlQasrGroup_hero_settings_html(); 
                }
                ?>
            </div>
            
            <!-- About Section -->
            <div id="about" class="tab-content" style="display:none;">
                <?php 
                if (function_exists('AlQasrGroup_about_settings_html')) {
                    AlQasrGroup_about_settings_html(); 
                }
                ?>
            </div>
            
            <!-- Projects Section -->
            <div id="projects" class="tab-content" style="display:none;">
                <?php 
                if (function_exists('AlQasrGroup_projects_settings_html')) {
                    AlQasrGroup_projects_settings_html(); 
                }
                ?>
            </div>
            
            <!-- Why Choose Section -->
            <div id="why-choose" class="tab-content" style="display:none;">
                <?php 
                if (function_exists('AlQasrGroup_why_choose_settings_html')) {
                    AlQasrGroup_why_choose_settings_html(); 
                }
                ?>
            </div>
            
            <!-- News Section -->
            <div id="news" class="tab-content" style="display:none;">
                <?php 
                if (function_exists('AlQasrGroup_news_settings_html')) {
                    AlQasrGroup_news_settings_html(); 
                }
                ?>
            </div>
            
            <?php submit_button('حفظ الإعدادات', 'primary', 'submit', false); ?>
        </form>
    </div>
    
    <style>
    .nav-tab-wrapper {
        margin-bottom: 20px;
    }
    .tab-content {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccd0d4;
        border-top: none;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            $('.nav-tab').removeClass('nav-tab-active');
            
            // Add active class to clicked tab
            $(this).addClass('nav-tab-active');
            
            // Hide all tab contents
            $('.tab-content').hide();
            
            // Show selected tab content
            var target = $(this).attr('href');
            $(target).show();
        });
    });
    </script>
    <?php
}
