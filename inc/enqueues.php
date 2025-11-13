<?php

// Helper function to check if current page matches any given page/post type
function is_current_page($page_slugs = array()) {
    // Convert single string to array
    if (!is_array($page_slugs)) {
        $page_slugs = array($page_slugs);
    }
    
    // Check regular WordPress pages
    foreach ($page_slugs as $slug) {
        if (is_page($slug) || is_page_template("page-{$slug}.php")) {
            return true;
        }
    }
    
    // Check Custom Post Types
    foreach ($page_slugs as $slug) {
        if (is_singular($slug) || is_post_type_archive($slug)) {
            return true;
        }
    }
    
    // Check for language-specific pages
    if (function_exists('get_current_language') && get_current_language() === 'en') {
        // Check query parameters
        if (isset($_GET['name']) && in_array($_GET['name'], $page_slugs)) {
            return true;
        }
        if (isset($_GET['pagename']) && in_array($_GET['pagename'], $page_slugs)) {
            return true;
        }
        
        // Check current URL path
        $current_url = $_SERVER['REQUEST_URI'];
        foreach ($page_slugs as $slug) {
            if (strpos($current_url, "/en/{$slug}") !== false) {
                return true;
            }
        }
        
        // Check WordPress query
        global $wp_query;
        if (isset($wp_query->query_vars['name']) && in_array($wp_query->query_vars['name'], $page_slugs)) {
            return true;
        }
        if (isset($wp_query->query_vars['pagename']) && in_array($wp_query->query_vars['pagename'], $page_slugs)) {
            return true;
        }
    }
    
    return false;
}




// Helper function to add new page assets
function add_page_assets($page_slug, $css_files = array(), $js_files = array()) {
    global $page_assets;
    
    if (!isset($page_assets)) {
        $page_assets = array();
    }
    
    $page_assets[$page_slug] = array(
        'css' => $css_files,
        'js' => $js_files
    );
    

}

// Example usage:
// add_page_assets('services', array('services.css'), array('services.js'));
// add_page_assets('portfolio', array('portfolio.css'), array('portfolio.js'));

function enqueues() {

$ver = defined('DEV_MODE') && DEV_MODE ? time() : false;


if( !is_admin() ) {

    // Bootstrap CSS - RTL for Arabic, LTR for English
    if (is_english_version()) {
    } else {
    }
    

    // Tailwind CSS - يجب أن يكون قبل style.css لتمكين التخصيصات

    
    // Language-specific styles
    if (is_english_version()) {
    } else {
        // Arabic styles (default)
    }
    

    

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', [], $ver, 'all' );
    wp_enqueue_style( 'base-css', asset_url('base.css', '/css/main/'), [], $ver, 'all' );
    wp_enqueue_style( 'components-css', asset_url('components.css', '/css/main/'), [], $ver, 'all' );
    wp_enqueue_style( 'animations-css', asset_url('animations.css', '/css/main/'), [], $ver, 'all' );



    // Theme JS files
    wp_enqueue_script("parent-utils-js", asset_url('utils.js', '/js/main/'), [], $ver, true );
    wp_enqueue_script("carousel-base", asset_url('carousel-base.js', '/js/main/'), [], $ver, true );
    wp_enqueue_script("parent-js", asset_url('main.js', '/js/main/'), [], $ver, true );
   
    wp_enqueue_script("parent-js", asset_url('main.js', '/js/main/'), [], $ver, true );

    wp_enqueue_script("animations-js", asset_url('animations.js', '/js/main/'), [], $ver, true );

    // Load all.css and all.js only on homepage
    if (is_front_page()) {
        wp_enqueue_script("projects-carousel-js", asset_url('projects-carousel.js', '/js/home/'), [], $ver, true );
        wp_enqueue_script("news-carousel-js", asset_url('news-carousel.js', '/js/home/'), [], $ver, true );
        wp_enqueue_style( 'home-css', asset_url('home.css', '/css/home/'), [], $ver, 'all' );
   
    }

    // Load about page assets
    if (is_current_page('about')) {
        wp_enqueue_style( 'about-css', asset_url('about.css', '/css/about/'), [], $ver, 'all' );
        wp_enqueue_script("why-choose-slider-js", asset_url('why-choose-slider.js', '/js/about/'), [], $ver, true );
    }

    // Load contact page assets
    $is_contact_page = is_current_page('contact') || is_current_page('contact_us');

    if ($is_contact_page) {
        wp_enqueue_style( 'contact-css', asset_url('contact.css', '/css/contact/'), [], $ver, 'all' );
        wp_enqueue_script("contact-tabs-js", asset_url('contact-tabs.js', '/js/contact/'), [], $ver, true );
    }

    if ($is_contact_page || is_singular('projects')) {
        wp_enqueue_script("contact-form-js", asset_url('contact-form.js', '/js/contact/'), [], $ver, true );

        // Localize script to pass AJAX URL and nonce
        wp_localize_script('contact-form-js', 'AlQasrGroupContact', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('AlQasrGroup_contact_nonce')
        ));
    }

    // Load blog archive page assets (only on archive, not single posts)
    if (is_post_type_archive('blog')) {
        wp_enqueue_style( 'blog-css', asset_url('blog.css', '/css/blog/'), [], $ver, 'all' );
    }

    // Load projects archive page assets (only on archive, not single posts)
    if (is_post_type_archive('projects') || is_tax('project_type')) {
        wp_enqueue_style( 'projects-css', asset_url('projects.css', '/css/projects/'), [], $ver, 'all' );
        wp_enqueue_script("projects-filter-js", asset_url('projects-filter.js', '/js/projects/'), [], $ver, true );
    }

        // Load jobs archive page assets (only on archive, not single posts)
        if (is_post_type_archive('jobs') ) {
            wp_enqueue_style( 'careers-css', asset_url('careers.css', '/css/careers/'), [], $ver, 'all' );
        }

    // Load single blog post assets
    if (is_singular('blog')) {
        wp_enqueue_style( 'single-blog-css', asset_url('single-blog.css', '/css/blog/'), [], $ver, 'all' );
        wp_enqueue_style( 'single-content-css', asset_url('content.css', '/css/main/'), [], $ver, 'all' );
      
        wp_enqueue_script("related-articles-carousel-js", asset_url('related-articles-carousel.js', '/js/blog/'), [], $ver, true );
    }


   // Load single jobs post assets
   if (is_singular('jobs')) {
    wp_enqueue_style( 'single-careers-css', asset_url('single-careers.css', '/css/careers/'), [], $ver, 'all' );
    wp_enqueue_style( 'single-content-css', asset_url('content.css', '/css/main/'), [], $ver, 'all' );
  
    wp_enqueue_script("related-articles-carousel-js", asset_url('related-articles-carousel.js', '/js/blog/'), [], $ver, true );
    wp_enqueue_script('job-application-js', asset_url('job-application.js', '/js/careers/'), [], $ver, true);

        $current_language = function_exists('get_current_language') ? get_current_language() : 'ar';
        $is_english_page = function_exists('is_english_version') ? is_english_version() : ($current_language === 'en');

        $localized_strings = array(
            'ajax_url'                 => admin_url('admin-ajax.php'),
            'nonce'                    => wp_create_nonce('AlQasrGroup_job_application_nonce'),
            'current_language'         => $current_language,
            'max_file_size'            => apply_filters('AlQasrGroup_job_application_max_size', 5 * 1024 * 1024),
            'allowed_mime_types'       => array(
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ),
            'success_message'          => $is_english_page ? __('Your application has been submitted successfully.', 'AlQasrGroup') : __('تم استلام طلبك بنجاح.', 'AlQasrGroup'),
            'generic_error_message'    => $is_english_page ? __('Something went wrong. Please try again.', 'AlQasrGroup') : __('حدث خطأ ما، يرجى المحاولة مرة أخرى.', 'AlQasrGroup'),
            'file_type_error'          => $is_english_page ? __('Unsupported file format. Allowed formats: PDF, DOC, DOCX.', 'AlQasrGroup') : __('صيغة الملف غير مدعومة. الصيغ المسموح بها: PDF، DOC، DOCX.', 'AlQasrGroup'),
            'file_size_error'          => $is_english_page ? __('File is too large. Maximum size is 5MB.', 'AlQasrGroup') : __('حجم الملف كبير جداً، الحد الأقصى 5 ميجابايت.', 'AlQasrGroup'),
            'file_required_message'    => $is_english_page ? __('Please attach your resume.', 'AlQasrGroup') : __('يرجى إرفاق السيرة الذاتية.', 'AlQasrGroup'),
            'validation_error_message' => $is_english_page ? __('Please double-check the required fields.', 'AlQasrGroup') : __('يرجى التحقق من الحقول المطلوبة.', 'AlQasrGroup'),
            'file_selected_message'    => $is_english_page ? __('Selected file: %s', 'AlQasrGroup') : __('تم اختيار الملف: %s', 'AlQasrGroup'),
        );

        wp_localize_script('job-application-js', 'AlQasrGroupJobApplication', $localized_strings);
}



    if (is_singular('projects')) {
        wp_enqueue_style( 'single-project-css', asset_url('single-project.css', '/css/projects/'), [], $ver, 'all' );
        wp_enqueue_script("tour-carousel-js", asset_url('tour-carousel.js', '/js/projects/'), [], $ver, true );
        wp_enqueue_script("location-timeline-js", asset_url('location-timeline.js', '/js/projects/'), [], $ver, true );

    
    
    }


    wp_enqueue_style( 'tailwind-css', asset_url('tailwind.css', '/css/'), [], $ver, 'all' );




    }

}


add_action('wp_enqueue_scripts', 'enqueues', 999);







