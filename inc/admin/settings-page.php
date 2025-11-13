<?php

// Include admin settings files
$files_to_include = [
    'logo-settings.php',
    'contact-settings.php',
    'social-settings.php',
    'partners-settings.php',
    'footer-settings.php',
    'home-page-settings.php',
    'blog-settings.php',
    'projects-settings.php',
    'jobs-settings.php'
];

foreach ($files_to_include as $file) {
    $file_path = get_template_directory() . '/inc/admin/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

// Add admin menu
function AlQasrGroup_add_admin_menu() {
    add_menu_page(
        'إعدادات هامة', // Page title
        'إعدادات هامة', // Menu title
        'manage_options', // Capability
        'AlQasrGroup-settings', // Menu slug
        'AlQasrGroup_settings_page', // Function to output the page
        'dashicons-admin-generic', // Icon
        30 // Position
    );
}
add_action('admin_menu', 'AlQasrGroup_add_admin_menu');

// Enqueue admin scripts
function AlQasrGroup_admin_scripts($hook) {
    if ($hook != 'toplevel_page_AlQasrGroup-settings') {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_script('AlQasrGroup-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery', 'media-upload'), '1.0', true);
    wp_localize_script('AlQasrGroup-admin', 'AlQasrGroup_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('AlQasrGroup_nonce')
    ));
    
    // Add performance optimizations for admin
    wp_add_inline_script('AlQasrGroup-admin', '
        // Disable autosave for better performance
        if (typeof wp !== "undefined" && wp.autosave) {
            wp.autosave.server.suspend();
        }
    ');
}
add_action('admin_enqueue_scripts', 'AlQasrGroup_admin_scripts');

// Settings page HTML
function AlQasrGroup_settings_page() {
    // Handle form submission
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['AlQasrGroup_nonce'], 'AlQasrGroup_settings_nonce')) {
        // Check if functions exist before calling them
        if (function_exists('AlQasrGroup_save_logo_settings')) {
            AlQasrGroup_save_logo_settings();
        }
        
        if (function_exists('AlQasrGroup_save_contact_settings')) {
            AlQasrGroup_save_contact_settings();
        }
        
        if (function_exists('AlQasrGroup_save_social_settings')) {
            AlQasrGroup_save_social_settings();
        }
        
        if (function_exists('AlQasrGroup_save_partners_settings')) {
            AlQasrGroup_save_partners_settings();
        }
        
        if (function_exists('AlQasrGroup_save_footer_settings')) {
            AlQasrGroup_save_footer_settings();
        }
        
        if (function_exists('AlQasrGroup_save_blog_settings')) {
            AlQasrGroup_save_blog_settings();
        }
        
        if (function_exists('AlQasrGroup_save_archive_projects_settings')) {
            AlQasrGroup_save_archive_projects_settings();
        }

        if (function_exists('AlQasrGroup_save_jobs_settings')) {
            AlQasrGroup_save_jobs_settings();
        }
        
        echo '<div class="notice notice-success"><p>تم حفظ الإعدادات بنجاح!</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>إعدادات هامة</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('AlQasrGroup_settings_nonce', 'AlQasrGroup_nonce'); ?>
            <div class="nav-tab-wrapper">
                <a href="#logo" class="nav-tab nav-tab-active">اللوجو والوصف</a>
                <a href="#contact" class="nav-tab">معلومات الاتصال</a>
                <a href="#social" class="nav-tab">وسائل التواصل الاجتماعي</a>
                <a href="#partners" class="nav-tab">شركاؤنا</a>
                <a href="#footer" class="nav-tab">الفوتر CTA</a>
                <a href="#blog" class="nav-tab">المدونة</a>
                <a href="#projects" class="nav-tab">المشاريع</a>
                <a href="#jobs" class="nav-tab">الوظائف</a>
            </div>
            
            <!-- Logo Section -->
            <div id="logo" class="tab-content">
                <?php AlQasrGroup_logo_settings_html(); ?>
            </div>
            
            <!-- Contact Section -->
            <div id="contact" class="tab-content" style="display:none;">
                <?php AlQasrGroup_contact_settings_html(); ?>
            </div>
            
            <!-- Social Media -->
            <div id="social" class="tab-content" style="display:none;">
                <?php AlQasrGroup_social_settings_html(); ?>
            </div>
            
            <!-- Partners Section -->
            <div id="partners" class="tab-content" style="display:none;">
                <?php AlQasrGroup_partners_settings_html(); ?>
            </div>
            
            <!-- Footer Section -->
            <div id="footer" class="tab-content" style="display:none;">
                <?php AlQasrGroup_footer_settings_html(); ?>
            </div>
            
            <!-- Blog Section -->
            <div id="blog" class="tab-content" style="display:none;">
                <?php AlQasrGroup_blog_settings_html(); ?>
            </div>
            
            <!-- Projects Section -->
            <div id="projects" class="tab-content" style="display:none;">
                <?php AlQasrGroup_archive_projects_settings_html(); ?>
            </div>

            <!-- Jobs Section -->
            <div id="jobs" class="tab-content" style="display:none;">
                <?php AlQasrGroup_jobs_settings_html(); ?>
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
    <?php
}
