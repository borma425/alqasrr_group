<?php
if (!defined('ABSPATH')) exit;

/**
 * إضافة meta box للمدونة (Blog)
 */
add_action('add_meta_boxes', 'blog_meta_boxes');

function blog_meta_boxes() {
    // فقط إضافة meta box للمحتوى الإنجليزي
    // الحقول الافتراضية (العنوان والمحتوى والوصف) تستخدم للغة العربية
    // نستخدم priority 'default' لتظهر بعد حقل Excerpt
    add_meta_box(
        'blog_english_content',
        __('English Content', 'AlQasrGroup'),
        'blog_english_content_callback',
        'blog',
        'normal',
        'default'
    );
}

/**
 * قسم المحتوى الإنجليزي
 */
function blog_english_content_callback($post) {
    wp_nonce_field('blog_english_content_nonce', 'blog_english_content_nonce_field');
    
    $blog_title_en = get_post_meta($post->ID, 'blog_title_en', true);
    $blog_content_en = get_post_meta($post->ID, 'blog_content_en', true);
    $blog_excerpt_en = get_post_meta($post->ID, 'blog_excerpt_en', true);
    ?>
    
    <div class="blog-meta-container" style="padding: 15px;">
        <div class="meta-field-group" style="margin-bottom: 20px;">
            <label for="blog_title_en" style="display: block; margin-bottom: 5px;">
                <strong><?php _e('English Title', 'AlQasrGroup'); ?></strong>
            </label>
            <input 
                type="text" 
                id="blog_title_en" 
                name="blog_title_en" 
                value="<?php echo esc_attr($blog_title_en); ?>" 
                style="width: 100%; padding: 8px; font-size: 14px;"
                placeholder="<?php _e('Enter the English title for the blog post', 'AlQasrGroup'); ?>"
            />
            <p class="description" style="margin-top: 5px; color: #666;">
                <?php _e('If left empty, the default post title will be used', 'AlQasrGroup'); ?>
            </p>
        </div>

        <div class="meta-field-group" style="margin-bottom: 20px;">
            <label for="blog_excerpt_en" style="display: block; margin-bottom: 5px;">
                <strong><?php _e('English Excerpt', 'AlQasrGroup'); ?></strong>
            </label>
            <textarea 
                id="blog_excerpt_en" 
                name="blog_excerpt_en" 
                rows="4" 
                style="width: 100%; padding: 8px; font-size: 14px;"
                placeholder="<?php _e('Enter the English excerpt for the blog post', 'AlQasrGroup'); ?>"
            ><?php echo esc_textarea($blog_excerpt_en); ?></textarea>
            <p class="description" style="margin-top: 5px; color: #666;">
                <?php _e('Short summary of the blog post in English', 'AlQasrGroup'); ?>
            </p>
        </div>

        <div class="meta-field-group" style="margin-bottom: 20px;">
            <label for="blog_content_en" style="display: block; margin-bottom: 5px;">
                <strong><?php _e('English Content', 'AlQasrGroup'); ?></strong>
            </label>
            <?php
            // محرر TinyMCE كامل الميزات مثل المحرر الافتراضي في WordPress (Classic Editor)
            // استخدام الإعدادات الافتراضية الكاملة - نفس إعدادات محرر المحتوى الرئيسي
            $editor_settings = array(
                'textarea_name' => 'blog_content_en',
                'textarea_rows' => 20,
                'media_buttons' => true, // زر إضافة الوسائط
                'teeny' => false, // محرر كامل وليس مبسط
                // عدم تمرير إعدادات tinymce مخصصة للسماح بالإعدادات الافتراضية الكاملة من WordPress
                'tinymce' => true, // استخدام الإعدادات الافتراضية الكاملة
                'quicktags' => true, // تفعيل Quicktags
                'drag_drop_upload' => true, // تفعيل سحب وإفلات الملفات
            );
            
            wp_editor($blog_content_en, 'blog_content_en', $editor_settings);
            ?>
            <p class="description" style="margin-top: 5px; color: #666;">
                <?php _e('Full content of the blog post in English', 'AlQasrGroup'); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * حفظ بيانات meta box
 */
add_action('save_post', 'blog_meta_boxes_save');

function blog_meta_boxes_save($post_id) {
    // التحقق من nonce للحقول الإنجليزية فقط
    if (!isset($_POST['blog_english_content_nonce_field']) || 
        !wp_verify_nonce($_POST['blog_english_content_nonce_field'], 'blog_english_content_nonce')) {
        return;
    }

    // التحقق من autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // التحقق من الصلاحيات
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // التحقق من نوع المقال
    if (get_post_type($post_id) !== 'blog') {
        return;
    }

    // حفظ الحقول الإنجليزية فقط
    // الحقول العربية تستخدم الحقول الافتراضية (title, content, excerpt)
    if (isset($_POST['blog_title_en'])) {
        update_post_meta($post_id, 'blog_title_en', sanitize_text_field($_POST['blog_title_en']));
    }

    if (isset($_POST['blog_excerpt_en'])) {
        update_post_meta($post_id, 'blog_excerpt_en', sanitize_textarea_field($_POST['blog_excerpt_en']));
    }

    if (isset($_POST['blog_content_en'])) {
        // wp_kses_post for rich text editor content
        update_post_meta($post_id, 'blog_content_en', wp_kses_post($_POST['blog_content_en']));
    }
}

/**
 * إضافة CSS و JavaScript إذا لزم الأمر
 */
add_action('admin_footer', 'blog_meta_boxes_reorder_script');

function blog_meta_boxes_reorder_script() {
    global $post_type, $pagenow;
    
    // تحميل فقط في صفحات تحرير المقالات من نوع blog
    if (($pagenow !== 'post.php' && $pagenow !== 'post-new.php') || $post_type !== 'blog') {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // نقل English Content meta box بعد Excerpt
        function moveEnglishBox() {
            var englishBox = $('#blog_english_content');
            var excerptBox = $('#postexcerpt');
            
            if (englishBox.length && excerptBox.length) {
                // إزالة من موقعه الحالي
                englishBox.detach();
                // إضافته بعد Excerpt
                excerptBox.after(englishBox);
            }
        }
        
        // محاولة فورية
        moveEnglishBox();
        
        // محاولة بعد تحميل الصفحة بالكامل (في حالة التأخير)
        setTimeout(moveEnglishBox, 100);
    });
    </script>
    <?php
}

