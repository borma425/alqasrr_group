<?php

/**
 * Footer Settings
 * إعدادات الفوتر
 */

// Register Footer settings
function AlQasrGroup_register_footer_settings() {
    register_setting('AlQasrGroup_settings', 'footer_title');
    register_setting('AlQasrGroup_settings', 'footer_description');
    register_setting('AlQasrGroup_settings', 'footer_title_en');
    register_setting('AlQasrGroup_settings', 'footer_description_en');
}
add_action('admin_init', 'AlQasrGroup_register_footer_settings');

// Save Footer settings
function AlQasrGroup_save_footer_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['footer_title'])) {
        update_option('footer_title', sanitize_text_field($_POST['footer_title']));
    }
    
    if (isset($_POST['footer_description'])) {
        update_option('footer_description', sanitize_textarea_field($_POST['footer_description']));
    }
    
    if (isset($_POST['footer_title_en'])) {
        update_option('footer_title_en', sanitize_text_field($_POST['footer_title_en']));
    }
    
    if (isset($_POST['footer_description_en'])) {
        update_option('footer_description_en', sanitize_textarea_field($_POST['footer_description_en']));
    }
}

// Footer settings HTML
function AlQasrGroup_footer_settings_html() {
    $footer_title = get_option('footer_title', '');
    $footer_description = get_option('footer_description', '');
    $footer_title_en = get_option('footer_title_en', '');
    $footer_description_en = get_option('footer_description_en', '');
    
    ?>
    <h2>الفوتر CTA</h2>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td>
                <input type="text" name="footer_title" value="<?php echo esc_attr($footer_title); ?>" class="regular-text" />
                <p class="description">العنوان الرئيسي للفوتر باللغة العربية</p>
            </td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (إنجليزي)</th>
            <td>
                <input type="text" name="footer_title_en" value="<?php echo esc_attr($footer_title_en); ?>" class="regular-text" />
                <p class="description">العنوان الرئيسي للفوتر باللغة الإنجليزية</p>
            </td>
        </tr>
        <tr>
            <th scope="row">الوصف (عربي)</th>
            <td>
                <textarea name="footer_description" rows="4" class="large-text"><?php echo esc_textarea($footer_description); ?></textarea>
                <p class="description">وصف الفوتر باللغة العربية</p>
            </td>
        </tr>
        <tr>
            <th scope="row">الوصف (إنجليزي)</th>
            <td>
                <textarea name="footer_description_en" rows="4" class="large-text"><?php echo esc_textarea($footer_description_en); ?></textarea>
                <p class="description">وصف الفوتر باللغة الإنجليزية</p>
            </td>
        </tr>
    </table>
    <?php
}

