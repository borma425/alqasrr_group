<?php

// Register Custom Post Types
function AlQasrGroup_register_post_types() {

    // Contact Form Submissions CPT
    register_post_type('contact_submissions', array(
        'labels' => array(
            'name' => 'رسائل الاتصال',
            'singular_name' => 'رسالة اتصال',
            'add_new' => 'إضافة رسالة جديدة',
            'add_new_item' => 'إضافة رسالة جديدة',
            'edit_item' => 'تعديل الرسالة',
            'new_item' => 'رسالة جديدة',
            'view_item' => 'عرض الرسالة',
            'search_items' => 'البحث في الرسائل',
            'not_found' => 'لم يتم العثور على رسائل',
            'not_found_in_trash' => 'لم يتم العثور على رسائل في سلة المحذوفات'
        ),
        'public' => true, // Enable for clean URLs
        'publicly_queryable' => true, // Allow viewing single posts
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false, // Disable creating manually
        ),
        'map_meta_cap' => true,
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-email-alt',
        'menu_position' => 25,
        'rewrite' => array('slug' => 'contact_submissions', 'with_front' => false),
        'has_archive' => false,
        'show_in_rest' => false
    ));

    // Job Applications CPT
    register_post_type('job_applications', array(
        'labels' => array(
            'name' => 'طلبات الوظائف',
            'singular_name' => 'طلب وظيفة',
            'add_new' => 'إضافة طلب جديد',
            'add_new_item' => 'إضافة طلب وظيفة جديد',
            'edit_item' => 'تعديل طلب الوظيفة',
            'new_item' => 'طلب وظيفة جديد',
            'view_item' => 'عرض طلب الوظيفة',
            'search_items' => 'البحث في طلبات الوظائف',
            'not_found' => 'لم يتم العثور على طلبات',
            'not_found_in_trash' => 'لا توجد طلبات في سلة المحذوفات',
            'all_items' => 'جميع طلبات الوظائف'
        ),
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_rest' => false,
        'supports' => array('title'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => false,
        'has_archive' => false,
        'query_var' => false
    ));

    // Projects CPT
    register_post_type('projects', array(
        'labels' => array(
            'name' => 'المشاريع',
            'singular_name' => 'مشروع',
            'add_new' => 'إضافة مشروع جديد',
            'add_new_item' => 'إضافة مشروع جديد',
            'edit_item' => 'تعديل المشروع',
            'new_item' => 'مشروع جديد',
            'view_item' => 'عرض المشروع',
            'search_items' => 'البحث في المشاريع',
            'not_found' => 'لم يتم العثور على مشاريع',
            'not_found_in_trash' => 'لم يتم العثور على مشاريع في سلة المحذوفات',
            'all_items' => 'جميع المشاريع'
        ),
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-portfolio',
        'menu_position' => 26,
        'rewrite' => array('slug' => 'projects'),
        'query_var' => true
    ));

    // Blog CPT
    register_post_type('blog', array(
        'labels' => array(
            'name' => 'المدونة',
            'singular_name' => 'مقال',
            'add_new' => 'إضافة مقال جديد',
            'add_new_item' => 'إضافة مقال جديد',
            'edit_item' => 'تعديل المقال',
            'new_item' => 'مقال جديد',
            'view_item' => 'عرض المقال',
            'search_items' => 'البحث في المقالات',
            'not_found' => 'لم يتم العثور على مقالات',
            'not_found_in_trash' => 'لم يتم العثور على مقالات في سلة المحذوفات',
            'all_items' => 'جميع المقالات'
        ),
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'menu_icon' => 'dashicons-edit',
        'menu_position' => 28,
        'rewrite' => array('slug' => 'blog'),
        'query_var' => true
    ));

    // Jobs CPT
    register_post_type('jobs', array(
        'labels' => array(
            'name' => 'الوظائف',
            'singular_name' => 'وظيفة',
            'add_new' => 'إضافة وظيفة جديدة',
            'add_new_item' => 'إضافة وظيفة جديدة',
            'edit_item' => 'تعديل الوظيفة',
            'new_item' => 'وظيفة جديدة',
            'view_item' => 'عرض الوظيفة',
            'search_items' => 'البحث في الوظائف',
            'not_found' => 'لم يتم العثور على وظائف',
            'not_found_in_trash' => 'لم يتم العثور على وظائف في سلة المحذوفات',
            'all_items' => 'جميع الوظائف'
        ),
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-businessman',
        'menu_position' => 29,
        'rewrite' => array('slug' => 'jobs'),
        'query_var' => true
    ));
}
add_action('init', 'AlQasrGroup_register_post_types');

// Register Custom Taxonomies for Projects
function AlQasrGroup_register_project_taxonomies() {
    
    // City Taxonomy
    register_taxonomy('city', array('projects'), array(
        'labels' => array(
            'name' => 'المدن',
            'singular_name' => 'مدينة',
            'search_items' => 'البحث في المدن',
            'all_items' => 'جميع المدن',
            'edit_item' => 'تعديل المدينة',
            'update_item' => 'تحديث المدينة',
            'add_new_item' => 'إضافة مدينة جديدة',
            'new_item_name' => 'اسم المدينة الجديدة',
            'menu_name' => 'المدن',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'city'),
    ));

    // Project Type Taxonomy
    register_taxonomy('project_type', array('projects'), array(
        'labels' => array(
            'name' => 'أنواع المشاريع',
            'singular_name' => 'نوع المشروع',
            'search_items' => 'البحث في أنواع المشاريع',
            'all_items' => 'جميع أنواع المشاريع',
            'edit_item' => 'تعديل نوع المشروع',
            'update_item' => 'تحديث نوع المشروع',
            'add_new_item' => 'إضافة نوع مشروع جديد',
            'new_item_name' => 'اسم نوع المشروع الجديد',
            'menu_name' => 'أنواع المشاريع',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'project-type'),
    ));
}
add_action('init', 'AlQasrGroup_register_project_taxonomies', 0);

// Register Project Type and Tags for Blog CPT
// تسجيل project_type والعلامات للمدونة
function AlQasrGroup_register_blog_taxonomies() {
    // Register project_type taxonomy for blog post type
    register_taxonomy_for_object_type('project_type', 'blog');
    
    // Register post_tag taxonomy for blog post type
    register_taxonomy_for_object_type('post_tag', 'blog');
}
// Use priority 11 to ensure it runs after post types are registered (default is 10)
// استخدام أولوية 11 للتأكد من تنفيذها بعد تسجيل post types (الافتراضي هو 10)
add_action('init', 'AlQasrGroup_register_blog_taxonomies', 11);










