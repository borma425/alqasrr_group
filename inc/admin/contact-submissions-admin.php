<?php
/**
 * Contact Submissions Admin
 * إدارة رسائل الاتصال في لوحة التحكم
 * 
 * Handles admin panel customizations for contact_submissions post type
 * يتعامل مع تخصيصات لوحة التحكم لنوع المنشور contact_submissions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add custom columns to Contact Submissions admin list
 * إضافة أعمدة مخصصة لقائمة رسائل الاتصال في لوحة التحكم
 * 
 * @param array $columns Default columns
 * @return array Modified columns
 */
function AlQasrGroup_contact_submissions_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'الاسم الكامل';
    $new_columns['first_name'] = 'الأسم الأول';
    $new_columns['second_name'] = 'الأسم الأخير';
    $new_columns['email'] = 'البريد الإلكتروني';
    $new_columns['phone'] = 'رقم الجوال';
    $new_columns['message'] = 'الرسالة';
    $new_columns['date'] = 'التاريخ';
    return $new_columns;
}
add_filter('manage_contact_submissions_posts_columns', 'AlQasrGroup_contact_submissions_columns');

/**
 * Fill custom columns with data
 * ملء الأعمدة المخصصة بالبيانات
 * 
 * @param string $column Column name
 * @param int $post_id Post ID
 */
function AlQasrGroup_contact_submissions_custom_column($column, $post_id) {
    switch ($column) {
        case 'first_name':
            $first_name = get_post_meta($post_id, 'contact_first_name', true);
            if (!empty($first_name)) {
                echo esc_html($first_name);
            } else {
                // Fallback: try to extract from post title
                $title = get_the_title($post_id);
                $name_parts = explode(' ', $title, 2);
                echo esc_html(isset($name_parts[0]) ? $name_parts[0] : '');
            }
            break;
            
        case 'second_name':
            $second_name = get_post_meta($post_id, 'contact_second_name', true);
            if (!empty($second_name)) {
                echo esc_html($second_name);
            } else {
                // Fallback: try to extract from post title
                $title = get_the_title($post_id);
                $name_parts = explode(' ', $title, 2);
                echo esc_html(isset($name_parts[1]) ? $name_parts[1] : '');
            }
            break;
            
        case 'email':
            $email = get_post_meta($post_id, 'contact_email', true);
            if (!empty($email)) {
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
            
        case 'phone':
            $phone = get_post_meta($post_id, 'contact_phone', true);
            if (!empty($phone)) {
                echo '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
            
        case 'message':
            $message = get_post_meta($post_id, 'contact_message', true);
            if (!empty($message)) {
                $trimmed_message = wp_trim_words($message, 15, '...');
                echo '<div title="' . esc_attr($message) . '">' . esc_html($trimmed_message) . '</div>';
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
    }
}
add_action('manage_contact_submissions_posts_custom_column', 'AlQasrGroup_contact_submissions_custom_column', 10, 2);

/**
 * Make columns sortable
 * جعل الأعمدة قابلة للترتيب
 * 
 * @param array $columns Sortable columns
 * @return array Modified sortable columns
 */
function AlQasrGroup_contact_submissions_sortable_columns($columns) {
    $columns['first_name'] = 'first_name';
    $columns['second_name'] = 'second_name';
    $columns['email'] = 'email';
    $columns['phone'] = 'phone';
    $columns['date'] = 'date';
    return $columns;
}
add_filter('manage_edit-contact_submissions_sortable_columns', 'AlQasrGroup_contact_submissions_sortable_columns');

/**
 * Handle sorting for custom columns
 * التعامل مع الترتيب للأعمدة المخصصة
 * 
 * @param WP_Query $query
 */
function AlQasrGroup_contact_submissions_sort_column($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    // Only apply to contact_submissions post type
    if ($query->get('post_type') !== 'contact_submissions') {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'first_name':
            $query->set('meta_key', 'contact_first_name');
            $query->set('orderby', 'meta_value');
            break;
            
        case 'second_name':
            $query->set('meta_key', 'contact_second_name');
            $query->set('orderby', 'meta_value');
            break;
            
        case 'email':
            $query->set('meta_key', 'contact_email');
            $query->set('orderby', 'meta_value');
            break;
            
        case 'phone':
            $query->set('meta_key', 'contact_phone');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'AlQasrGroup_contact_submissions_sort_column');

/**
 * Remove row actions (Edit, Quick Edit) but keep Delete, Trash and View
 * إزالة إجراءات الصف (تحرير، تحرير سريع) ولكن الاحتفاظ بالحذف وسلة المهملات والعرض
 */
function AlQasrGroup_remove_contact_submissions_row_actions($actions, $post) {
    if ($post->post_type === 'contact_submissions') {
        // Remove edit and quick edit only
        unset($actions['edit']);
        unset($actions['inline hide-if-no-js']); // Quick edit
        
        // Keep all delete/trash actions (WordPress handles them automatically)
        // Keep restore if post is in trash
        
        // Add view link for published/private posts
        if ($post->post_status !== 'trash' && !isset($actions['view'])) {
            $view_link = get_permalink($post->ID);
            if ($view_link) {
                $actions['view'] = '<a href="' . esc_url($view_link) . '" target="_blank">عرض</a>';
            }
        }
    }
    return $actions;
}
add_filter('post_row_actions', 'AlQasrGroup_remove_contact_submissions_row_actions', 10, 2);

/**
 * Remove title link and make it non-clickable
 * إزالة رابط العنوان وجعله غير قابل للنقر
 * 
 * @param string $title Post title
 * @param int $post_id Post ID
 * @return string Modified title
 */
function AlQasrGroup_remove_contact_submissions_title_link($title, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'contact_submissions') {
        // Return title without link
        return esc_html($title);
    }
    return $title;
}
add_filter('the_title', 'AlQasrGroup_remove_contact_submissions_title_link', 10, 2);

/**
 * Make title column non-clickable in admin list
 * جعل عمود العنوان غير قابل للنقر في قائمة الإدارة
 */
function AlQasrGroup_contact_submissions_title_column($column_name, $post_id) {
    if ($column_name === 'title') {
        $post = get_post($post_id);
        if ($post && $post->post_type === 'contact_submissions') {
            $title = get_the_title($post_id);
            echo '<strong>' . esc_html($title) . '</strong>';
            return;
        }
    }
}
// We'll handle this differently - remove the default title column link via CSS/JS or column override

/**
 * Redirect edit page to view page
 * إعادة توجيه صفحة التحرير إلى صفحة العرض
 */
function AlQasrGroup_redirect_contact_submissions_edit_page() {
    global $pagenow, $post_type;
    
    if ($pagenow === 'post.php' && isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
        $post_id = intval($_GET['post']);
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'contact_submissions') {
            // Redirect to view page (single post)
            $view_url = get_permalink($post_id);
            if ($view_url) {
                wp_safe_redirect($view_url);
                exit;
            } else {
                // Fallback: redirect to list
                wp_safe_redirect(admin_url('edit.php?post_type=contact_submissions'));
                exit;
            }
        }
    }
    
    // Redirect new post page
    if ($pagenow === 'post-new.php' && $post_type === 'contact_submissions') {
        wp_safe_redirect(admin_url('edit.php?post_type=contact_submissions'));
        exit;
    }
}
add_action('admin_init', 'AlQasrGroup_redirect_contact_submissions_edit_page');

/**
 * Remove edit from bulk actions but keep delete/trash
 * إزالة التحرير من الإجراءات الجماعية ولكن الاحتفاظ بالحذف
 */
function AlQasrGroup_remove_contact_submissions_bulk_actions($actions) {
    global $post_type;
    
    if ($post_type === 'contact_submissions') {
        // Remove edit action only
        unset($actions['edit']);
        // Keep trash and delete actions (WordPress will show them)
    }
    
    return $actions;
}
add_filter('bulk_actions-edit-contact_submissions', 'AlQasrGroup_remove_contact_submissions_bulk_actions');

/**
 * Remove edit links from title column in admin list
 * إزالة روابط التحرير من عمود العنوان في قائمة الإدارة
 */
function AlQasrGroup_remove_contact_submissions_title_links() {
    global $post_type;
    
    if ($post_type === 'contact_submissions') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Remove links from title column
            $('.wp-list-table .row-title').each(function() {
                var title = $(this).text();
                $(this).replaceWith('<strong>' + title + '</strong>');
            });
            
            // Remove checkbox column if you want (optional)
            // $('.wp-list-table .check-column').remove();
        });
        </script>
        <style>
        .wp-list-table .row-title,
        .wp-list-table .row-title:hover {
            cursor: default;
            color: #23282d;
            text-decoration: none;
        }
        </style>
        <?php
    }
}
add_action('admin_footer', 'AlQasrGroup_remove_contact_submissions_title_links');


/**
 * Remove edit from admin bar but keep delete/trash
 * إزالة التحرير من شريط الإدارة ولكن الاحتفاظ بالحذف
 */
function AlQasrGroup_remove_contact_submissions_admin_bar_links($wp_admin_bar) {
    global $post;
    
    if (is_admin() && isset($post) && $post->post_type === 'contact_submissions') {
        $wp_admin_bar->remove_node('edit');
        // Keep trash/delete link in admin bar
    }
}
add_action('admin_bar_menu', 'AlQasrGroup_remove_contact_submissions_admin_bar_links', 999);

/**
 * Remove "Add New" button from contact submissions page
 * إزالة زر "إضافة جديد" من صفحة رسائل الاتصال
 * But keep Export button visible
 * ولكن الاحتفاظ بزر التصدير مرئياً
 */
function AlQasrGroup_remove_contact_submissions_add_new_button() {
    global $post_type;
    
    if ($post_type === 'contact_submissions') {
        ?>
        <style>
        /* Hide "Add New" button but keep Export button */
        .wrap .page-title-action:not(#exportExcelBtn),
        .wrap .add-new-h2 {
            display: none !important;
        }
        /* Ensure Export button is visible */
        #exportExcelBtn {
            display: inline-block !important;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'AlQasrGroup_remove_contact_submissions_add_new_button');

/**
 * Hide checkbox column (optional - uncomment if you want to hide it)
 * إخفاء عمود checkbox (اختياري - قم بإلغاء التعليق إذا أردت إخفاءه)
 */
function AlQasrGroup_hide_contact_submissions_checkbox_column() {
    global $post_type;
    
    if ($post_type === 'contact_submissions') {
        ?>
        <style>
        /* Uncomment the following lines to hide checkbox column */
        /*
        .wp-list-table .check-column {
            display: none;
        }
        */
        </style>
        <?php
    }
}
add_action('admin_head', 'AlQasrGroup_hide_contact_submissions_checkbox_column');

