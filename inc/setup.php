<?php


use Timber\Timber;
use Timber\URLHelper;
use Timber\Site;

global $paged;

if (!isset($paged) || !$paged){
    $paged = 1;
}






function setup_theme(){
  register_nav_menu('header_menu_ar','arabic Header Menu');
  register_nav_menu('header_menu_en','english Header Menu');
  register_nav_menu('footer_menu_ar','arabic Footer Menu');
  register_nav_menu('footer_menu_en','english Footer ');

add_theme_support( 'post-thumbnails' );



}

add_action('after_setup_theme','setup_theme');

// Enable debug mode for language routing
if (!defined('DEV_MODE')) {
    define('DEV_MODE', true);
}


add_filter( 'show_admin_bar', '__return_false' );
add_filter( 'rest_allow_anonymous_comments', '__return_true' );
add_filter('xmlrpc_enabled', '__return_false');

function post_remove () { 
    remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_remove');










add_filter( 'timber/context', function( $context ) {

  global $paged , $post;

    // All menus (English and Arabic)
    $context['header_menu_en'] = Timber::get_menu('header_menu_en');
    $context['footer_menu_en'] = Timber::get_menu('footer_menu_en');
    $context['header_menu_ar'] = Timber::get_menu('header_menu_ar');
    $context['footer_menu_ar'] = Timber::get_menu('footer_menu_ar');



    $context['current_url'] = URLHelper::get_current_url();

    // Site title and description variables
    $context['site_title_ar'] = get_option('AlQasrGroup_site_title_ar', '');
    $context['site_title_en'] = get_option('AlQasrGroup_site_title_en', '');
    $context['site_description_ar'] = get_option('AlQasrGroup_site_description_ar', '');
    $context['site_description_en'] = get_option('AlQasrGroup_site_description_en', '');

    $context['site'] = new Site();
    $context['current_language'] = get_current_language();
    $context['is_english'] = is_english_version();






    // Get first 3 project types for header gallery
    $project_types = get_terms(array(
        'taxonomy' => 'project_type',
        'hide_empty' => false,
        'number' => 3,
        'orderby' => 'term_id',
        'order' => 'ASC'
    ));
    
    $context['header_project_types'] = array();
    if (!is_wp_error($project_types) && !empty($project_types)) {
        $current_lang = is_english_version() ? 'en' : 'ar';
        
        foreach ($project_types as $term) {
            $timber_term = Timber::get_term($term->term_id);
            if ($timber_term) {
                // Get taxonomy image
                $image_id = get_term_meta($term->term_id, 'taxonomy_image', true);
                $image_url = '';
                if ($image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, 'full');
                }
                $timber_term->image_url = $image_url;
                $timber_term->image_id = $image_id;
                
                // Get name based on current language
                $timber_term->name = get_taxonomy_name($term->term_id, $current_lang);
                
                // Get description based on current language
                $timber_term->description = get_taxonomy_description($term->term_id, $current_lang);
                
                // Get term link (with language support)
                $term_link = get_term_link($term->term_id);
                if ($current_lang === 'en' && strpos($term_link, '/en/') === false) {
                    $home_url = home_url('/');
                    $path = str_replace($home_url, '', $term_link);
                    $term_link = $home_url . 'en/' . $path;
                } elseif ($current_lang === 'ar' && strpos($term_link, '/en/') !== false) {
                    $home_url = home_url('/');
                    $path = str_replace($home_url, '', $term_link);
                    $path = str_replace('en/', '', $path);
                    $term_link = $home_url . $path;
                }
                $timber_term->link = $term_link;
                
                $context['header_project_types'][] = $timber_term;
            }
        }
    }

    // Get About section settings based on current language
    $current_lang = is_english_version() ? 'en' : 'ar';
    
    $context['about_image'] = get_option('about_image', '');
    
    if ($current_lang === 'en') {
        $context['about_subtitle'] = get_option('about_subtitle_en', '');
        $context['about_title'] = get_option('about_title_en', 'About Us');
        $context['about_description'] = get_option('about_description_en', '');
        $context['about_link_text'] = get_option('about_link_text_en', 'Read More');
        $context['about_link_url'] = get_option('about_link_url_en', '');
    } else {
        $context['about_subtitle'] = get_option('about_subtitle', '');
        $context['about_title'] = get_option('about_title', 'من نحن');
        $context['about_description'] = get_option('about_description', '');
        $context['about_link_text'] = get_option('about_link_text', 'قراءة المزيد');
        $context['about_link_url'] = get_option('about_link_url', '');
    }
    
    // Get Projects section settings based on current language
    if ($current_lang === 'en') {
        $context['projects_subtitle'] = get_option('projects_subtitle_en', '');
        $context['projects_title'] = get_option('projects_title_en', 'Projects');
        $context['projects_description'] = get_option('projects_description_en', '');
        
        // International Projects section
        $context['international_subtitle'] = get_option('international_subtitle_en', '');
        $context['international_title'] = get_option('international_title_en', 'Our International Projects');
        $context['international_description'] = get_option('international_description_en', '');
    } else {
        $context['projects_subtitle'] = get_option('projects_subtitle', '');
        $context['projects_title'] = get_option('projects_title', 'المشاريع');
        $context['projects_description'] = get_option('projects_description', '');
        
        // International Projects section
        $context['international_subtitle'] = get_option('international_subtitle', '');
        $context['international_title'] = get_option('international_title', 'مشاريعنا الدولية');
        $context['international_description'] = get_option('international_description', '');
    }
    
    // Get Why Choose Us section settings based on current language
    $context['why_choose_large_image'] = get_option('why_choose_large_image', '');
    $context['why_choose_small_image_1'] = get_option('why_choose_small_image_1', '');
    $context['why_choose_small_image_2'] = get_option('why_choose_small_image_2', '');
    
    if ($current_lang === 'en') {
        $context['why_choose_subtitle'] = get_option('why_choose_subtitle_en', '');
        $context['why_choose_title'] = get_option('why_choose_title_en', 'Why Choose Us');
        $context['why_choose_description'] = get_option('why_choose_description_en', '');
    } else {
        $context['why_choose_subtitle'] = get_option('why_choose_subtitle', '');
        $context['why_choose_title'] = get_option('why_choose_title', 'لماذا نحن');
        $context['why_choose_description'] = get_option('why_choose_description', '');
    }
    
    // Get News section settings based on current language
    if ($current_lang === 'en') {
        $context['news_subtitle'] = get_option('news_subtitle_en', '');
        $context['news_title'] = get_option('news_title_en', '');
    } else {
        $context['news_subtitle'] = get_option('news_subtitle', '');
        $context['news_title'] = get_option('news_title', '');
    }
    
    // Get Contact information for footer
    if ($current_lang === 'en') {
        $context['contact_company_address'] = get_option('contact_company_address_en', '');
        $context['contact_methods'] = get_option('contact_methods_en', array());
    } else {
        $context['contact_company_address'] = get_option('contact_company_address_ar', '');
        $context['contact_methods'] = get_option('contact_methods_ar', array());
    }

    // Get Blog settings based on current language
    if (is_english_version()) {
        $context['blog_hero_title'] = get_option('blog_hero_title_en', get_option('blog_hero_title', ''));
        $context['blog_hero_description'] = get_option('blog_hero_description_en', get_option('blog_hero_description', ''));
    } else {
        $context['blog_hero_title'] = get_option('blog_hero_title', '');
        $context['blog_hero_description'] = get_option('blog_hero_description', '');
    }

    return $context;

} );

// Register Twig functions
add_filter( 'timber/twig', function( $twig ) {
    $twig->addFunction( new Twig\TwigFunction( 'asset_url', 'asset_url' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_option', 'get_AlQasrGroup_option' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_image', 'get_AlQasrGroup_image' ) );
    $twig->addFunction( new Twig\TwigFunction( 'format_date_english', 'format_date_english' ) );
    $twig->addFunction( new Twig\TwigFunction( 'format_date_arabic', 'format_date_arabic' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_english_url', 'get_english_url' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_arabic_url', 'get_arabic_url' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_site_logo', 'get_AlQasrGroup_site_logo' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_site_title', 'get_AlQasrGroup_site_title' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_site_description', 'get_AlQasrGroup_site_description' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_hero_background', 'get_AlQasrGroup_contact_hero_background' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_archive_blogs_hero_background', 'get_AlQasrGroup_archive_blogs_hero_background' ) );
    
    // Contact functions
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_title', 'get_AlQasrGroup_contact_title' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_subtitle', 'get_AlQasrGroup_contact_subtitle' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_description', 'get_AlQasrGroup_contact_description' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_address', 'get_AlQasrGroup_contact_address' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_address_details', 'get_AlQasrGroup_contact_address_details' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_email', 'get_AlQasrGroup_contact_email' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_phone', 'get_AlQasrGroup_contact_phone' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_form_title', 'get_AlQasrGroup_contact_form_title' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_contact_form_description', 'get_AlQasrGroup_contact_form_description' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_AlQasrGroup_social_icons', 'get_AlQasrGroup_social_icons' ) );
    
    // Taxonomy image functions
    $twig->addFunction( new Twig\TwigFunction( 'get_taxonomy_image', 'get_taxonomy_image' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_taxonomy_image_id', 'get_taxonomy_image_id' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_taxonomy_name', 'get_taxonomy_name' ) );
    $twig->addFunction( new Twig\TwigFunction( 'get_taxonomy_description', 'get_taxonomy_description' ) );
    
    return $twig;
} );





function header_gallery_cards(){


    
}








# extract full path of IMAGE dir
function asset_url( $filename ,  $path="/img/" ){

  $Path_url =  get_template_directory_uri() . '/assets/' . $path ;

  if(is_array($filename) && count($filename) > 1){

  $IMGarray = [];

  foreach( $filename as $item){
  array_push( $IMGarray, esc_url( $Path_url . $item ) );
  }

  $result   = $IMGarray;

  } else{

  $result   = esc_url( $Path_url . $filename ) ;

  }

  return   $result;

};

// Helper function to get option value with fallback
function get_AlQasrGroup_option($option_name, $default = '') {
    $value = get_option($option_name, $default);
    return $value ? $value : $default;
}

// Helper function to get image URL from WordPress Media Library
function get_AlQasrGroup_image($option_name, $default_image = '') {
    $image_id = get_option($option_name, '');
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        return $image_url ? $image_url : $default_image;
    }
    return $default_image;
}

// Helper function to format date in English regardless of site language
function format_date_english($date_string, $format = 'M j, Y') {
    // Save current locale
    $current_locale = get_locale();
    
    // Temporarily set locale to English
    setlocale(LC_TIME, 'en_US.UTF-8', 'en_US', 'en');
    
    // Format the date
    $formatted_date = date($format, strtotime($date_string));
    
    // Restore original locale
    setlocale(LC_TIME, $current_locale);
    
    return $formatted_date;
}

// Helper function to get English URL for posts
function get_english_url($post_id) {
    $permalink = get_permalink($post_id);
    // Add /en/ to the URL if it doesn't already exist
    if (strpos($permalink, '/en/') === false) {
        $home_url = home_url('/');
        $path = str_replace($home_url, '', $permalink);
        return $home_url . 'en/' . $path;
    }
    return $permalink;
}

// Helper function to get Arabic URL for posts
function get_arabic_url($post_id) {
    $permalink = get_permalink($post_id);
    // Remove /en/ from the URL if it exists
    if (strpos($permalink, '/en/') !== false) {
        $home_url = home_url('/');
        $path = str_replace($home_url, '', $permalink);
        $path = str_replace('en/', '', $path);
        return $home_url . $path;
    }
    return $permalink;
}

// Helper function to format date in Arabic
function format_date_arabic($date_string, $format = 'j M Y') {
    // Arabic month names
    $arabic_months = array(
        'Jan' => 'يناير',
        'Feb' => 'فبراير',
        'Mar' => 'مارس',
        'Apr' => 'أبريل',
        'May' => 'مايو',
        'Jun' => 'يونيو',
        'Jul' => 'يوليو',
        'Aug' => 'أغسطس',
        'Sep' => 'سبتمبر',
        'Oct' => 'أكتوبر',
        'Nov' => 'نوفمبر',
        'Dec' => 'ديسمبر'
    );
    
    // Format the date in English first
    $english_date = date($format, strtotime($date_string));
    
    // Replace English month names with Arabic
    foreach ($arabic_months as $english => $arabic) {
        $english_date = str_replace($english, $arabic, $english_date);
    }
    
    return $english_date;
}






// Logo and Site Description Helper Functions
// دوال مساعدة للوجو ووصف الموقع

function get_AlQasrGroup_site_logo() {
    return get_option('AlQasrGroup_site_logo', '');
}

function get_AlQasrGroup_site_title($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('AlQasrGroup_site_title_en', get_bloginfo('name'));
    }
    return get_option('AlQasrGroup_site_title_ar', get_bloginfo('name'));
}

function get_AlQasrGroup_site_description($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('AlQasrGroup_site_description_en', get_bloginfo('description'));
    }
    return get_option('AlQasrGroup_site_description_ar', get_bloginfo('description'));
}

// Helper function to get contact hero background image
function get_AlQasrGroup_contact_hero_background() {
    $background_image = get_option('contact_hero_background_image', '');
    if (!empty($background_image)) {
        return $background_image;
    }
    // Fallback to default image
    return get_template_directory_uri() . '/assets/img/about-hero.png';
}

// Helper function to get archive blogs hero background image
function get_AlQasrGroup_archive_blogs_hero_background() {
    $background_image = get_option('archive_blogs_hero_background_image', '');
    if (!empty($background_image)) {
        return $background_image;
    }
    // Fallback to default image
    return get_template_directory_uri() . '/assets/img/about-hero.png';
}

// Contact Information Helper Functions
// دوال مساعدة لمعلومات الاتصال

function get_AlQasrGroup_contact_title($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_title_en', 'Contact Us');
    }
    return get_option('contact_title_ar', 'اتصل بنا');
}

function get_AlQasrGroup_contact_subtitle($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_subtitle_en', 'We\'d love to hear from you — your voice matters');
    }
    return get_option('contact_subtitle_ar', 'نود أن نسمع منك — صوتك مهم');
}

function get_AlQasrGroup_contact_description($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_description_en', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna');
    }
    return get_option('contact_description_ar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna');
}

function get_AlQasrGroup_contact_address($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_address_en', 'Jakarta');
    }
    return get_option('contact_address_ar', 'جاكرتا');
}

function get_AlQasrGroup_contact_address_details($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_address_details_en', 'Jln Cempaka Wangi No 22, Jakarta - Indonesia');
    }
    return get_option('contact_address_details_ar', 'Jln Cempaka Wangi No 22, Jakarta - Indonesia');
}

function get_AlQasrGroup_contact_email() {
    return get_option('contact_email', 'support@tawasol.com');
}

function get_AlQasrGroup_contact_phone() {
    return get_option('contact_phone', '+6221.2002.2012');
}

function get_AlQasrGroup_contact_form_title($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_form_title_en', 'Send Us A Message');
    }
    return get_option('contact_form_title_ar', 'أرسل لنا رسالة');
}

function get_AlQasrGroup_contact_form_description($language = null) {
    if ($language === 'en' || is_english_version()) {
        return get_option('contact_form_description_en', 'Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit. Ut Elit Tellus, Luctus Nec Ullamcorper Mattis, Pulvinar Dapibus Leo.');
    }
    return get_option('contact_form_description_ar', 'Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit. Ut Elit Tellus, Luctus Nec Ullamcorper Mattis, Pulvinar Dapibus Leo.');
}

// Helper function to get social media icons
// دالة مساعدة للحصول على أيقونات وسائل التواصل الاجتماعي
function get_AlQasrGroup_social_icons($language = null) {
    $icons = get_option('social_media_icons', array());
    
    // Ensure it's an array
    if (!is_array($icons)) {
        return array();
    }
    
    // Determine current language
    if ($language === null) {
        $language = is_english_version() ? 'en' : 'ar';
    }
    
    // Filter out empty icons (icons without title, url, or icon)
    $filtered_icons = array();
    foreach ($icons as $icon) {
        // Get title based on language
        $title = '';
        if ($language === 'en') {
            $title = isset($icon['title_en']) ? $icon['title_en'] : (isset($icon['title_ar']) ? $icon['title_ar'] : '');
        } else {
            $title = isset($icon['title_ar']) ? $icon['title_ar'] : (isset($icon['title_en']) ? $icon['title_en'] : '');
        }
        
        // Check if icon has required fields
        if (!empty($title) && !empty($icon['url']) && !empty($icon['icon'])) {
            $filtered_icons[] = array(
                'title' => esc_attr($title),
                'title_ar' => esc_attr(isset($icon['title_ar']) ? $icon['title_ar'] : ''),
                'title_en' => esc_attr(isset($icon['title_en']) ? $icon['title_en'] : ''),
                'url' => esc_url($icon['url']),
                'icon' => esc_url($icon['icon'])
            );
        }
    }
    
    return $filtered_icons;
}

// Generate random English permalinks for blogs CPT
function uniq_random_permalink($data, $postarr) {
    $allowed_post_types = array('blogs');
    
    // Check if the post type is 'blogs' and if the post is new (not already published)
    if (in_array($data['post_type'], $allowed_post_types) && empty($postarr['ID'])) {
        // Generate the post name (slug) from the post title
        $post_name = sanitize_title($data['post_title']);
        $unique_id = md5(uniqid($post_name, true));
        $data['post_name'] = substr(md5($unique_id), 0, 11);
    }
    
    return $data;
}

add_filter('wp_insert_post_data', 'uniq_random_permalink', 10, 2);

// Allow SVG file uploads with security (only for users with upload capability)
// السماح برفع ملفات SVG مع الحماية الأمنية (فقط للمستخدمين الذين لديهم صلاحية الرفع)
function AlQasrGroup_allow_svg_upload($mimes) {
    if (current_user_can('upload_files')) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'AlQasrGroup_allow_svg_upload');

// Fix SVG display in media library
// إصلاح عرض SVG في مكتبة الوسائط
function AlQasrGroup_fix_svg_thumbnails($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'AlQasrGroup_fix_svg_thumbnails', 10, 3);

// Security check for SVG files - sanitize SVG content
// فحص أمني لملفات SVG - تنظيف محتوى SVG
function AlQasrGroup_sanitize_svg_upload($file) {
    if ($file['type'] === 'image/svg+xml') {
        // Read the file content
        $file_content = file_get_contents($file['tmp_name']);
        
        // Dangerous SVG elements and attributes to check for
        // العناصر والخصائص الخطيرة في SVG للتحقق منها
        $dangerous_patterns = array(
            '/<script/i',
            '/javascript:/i',
            '/onerror=/i',
            '/onload=/i',
            '/onclick=/i',
            '/<iframe/i',
            '/<embed/i',
            '/<object/i',
            '/<link/i',
            '/<meta/i',
            '/<base/i',
            '/<foreignObject/i',
            '/<use/i.*href\s*=\s*["\'].*data:/i',
        );
        
        // Check for dangerous content
        // التحقق من المحتوى الخطير
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $file_content)) {
                $file['error'] = 'ملف SVG غير آمن. يرجى التأكد من عدم احتواء الملف على كود JavaScript أو عناصر خطيرة.';
                return $file;
            }
        }
        
        // Additional security: Ensure valid SVG structure
        // أمان إضافي: التأكد من بنية SVG صحيحة
        if (!preg_match('/<svg/i', $file_content)) {
            $file['error'] = 'ملف SVG غير صالح. يرجى التأكد من أن الملف هو ملف SVG صحيح.';
            return $file;
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'AlQasrGroup_sanitize_svg_upload');




