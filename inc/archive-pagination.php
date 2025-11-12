<?php
/**
 * Archive Pagination Handler
 * معالج Pagination للأرشيفات
 * 
 * This file provides a clean and organized way to set posts per page for CPT archives
 * هذا الملف يوفر طريقة نظيفة ومنظمة لتعيين عدد المقالات لكل صفحة لأرشيفات CPT
 */

/**
 * Archive posts per page settings
 * إعدادات عدد المقالات لكل صفحة للأرشيفات
 * 
 * To set posts per page for an archive, add your settings here:
 * لتعيين عدد المقالات لكل صفحة لأرشيف، أضف إعداداتك هنا:
 * 
 * Example:
 * $archive_posts_per_page['blog'] = 2;
 * $archive_posts_per_page['projects'] = 12;
 * $archive_posts_per_page['jobs'] = 9;
 */
$archive_posts_per_page = array(
    'blog' => 9,        // Blog archive: 2 posts per page
    'projects' => 5,    // Projects archive: 9 posts per page (default)
    'jobs' => 9,        // Jobs archive: 9 posts per page (default)
);

/**
 * Set posts per page for a specific archive
 * تعيين عدد المقالات لكل صفحة لأرشيف محدد
 * 
 * This function can be called from archive files to set posts per page
 * يمكن استدعاء هذه الدالة من ملفات archive لتعيين عدد المقالات لكل صفحة
 * 
 * @param string $post_type Post type name (e.g., 'blog', 'projects')
 * @param int $posts_per_page Number of posts per page
 * 
 * @example
 * // In archive-blog.php (at the top of the file)
 * set_archive_posts_per_page('blog', 9);
 */
function set_archive_posts_per_page($post_type, $posts_per_page) {
    global $archive_posts_per_page;
    $archive_posts_per_page[$post_type] = (int) $posts_per_page;
}

/**
 * Get posts per page for a specific archive
 * الحصول على عدد المقالات لكل صفحة لأرشيف محدد
 * 
 * @param string $post_type Post type name
 * @return int Posts per page (default: 9)
 */
function get_archive_posts_per_page($post_type) {
    global $archive_posts_per_page;
    return isset($archive_posts_per_page[$post_type]) 
        ? $archive_posts_per_page[$post_type] 
        : 9; // Default value
}

/**
 * Modify main query to set posts per page for archives
 * تعديل الاستعلام الرئيسي لتعيين عدد المقالات لكل صفحة للأرشيفات
 */
function apply_archive_posts_per_page($query) {
    // Only modify main query on frontend
    // تعديل الاستعلام الرئيسي فقط في الواجهة الأمامية
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Only modify post type archives
    // تعديل أرشيفات أنواع المقالات فقط
    if (!is_post_type_archive()) {
        return;
    }
    
    // Get the post type
    // الحصول على نوع المقالات
    $post_type = $query->get('post_type');
    if (is_array($post_type)) {
        $post_type = $post_type[0];
    }
    
    // Get posts per page for this archive
    // الحصول على عدد المقالات لكل صفحة لهذا الأرشيف
    $posts_per_page = get_archive_posts_per_page($post_type);
    
    // Set posts per page
    // تعيين عدد المقالات لكل صفحة
    $query->set('posts_per_page', $posts_per_page);
}
add_action('pre_get_posts', 'apply_archive_posts_per_page', 10);

