<?php
/**
 * About Settings
 * إعدادات قسم من نحن
 */

if (!defined('ABSPATH')) exit;

// Register About settings
function AlQasrGroup_register_about_settings() {
    register_setting('AlQasrGroup_home_settings', 'about_image');
    register_setting('AlQasrGroup_home_settings', 'about_subtitle');
    register_setting('AlQasrGroup_home_settings', 'about_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'about_title');
    register_setting('AlQasrGroup_home_settings', 'about_title_en');
    register_setting('AlQasrGroup_home_settings', 'about_description');
    register_setting('AlQasrGroup_home_settings', 'about_description_en');
    register_setting('AlQasrGroup_home_settings', 'about_link_text');
    register_setting('AlQasrGroup_home_settings', 'about_link_text_en');
    register_setting('AlQasrGroup_home_settings', 'about_link_url');
    register_setting('AlQasrGroup_home_settings', 'about_link_url_en');
}
add_action('admin_init', 'AlQasrGroup_register_about_settings');

// Save About settings
function AlQasrGroup_save_about_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['about_image'])) {
        update_option('about_image', esc_url_raw($_POST['about_image']));
    }
    if (isset($_POST['about_subtitle'])) {
        update_option('about_subtitle', sanitize_text_field($_POST['about_subtitle']));
    }
    if (isset($_POST['about_subtitle_en'])) {
        update_option('about_subtitle_en', sanitize_text_field($_POST['about_subtitle_en']));
    }
    if (isset($_POST['about_title'])) {
        update_option('about_title', sanitize_text_field($_POST['about_title']));
    }
    if (isset($_POST['about_title_en'])) {
        update_option('about_title_en', sanitize_text_field($_POST['about_title_en']));
    }
    if (isset($_POST['about_description'])) {
        update_option('about_description', sanitize_textarea_field($_POST['about_description']));
    }
    if (isset($_POST['about_description_en'])) {
        update_option('about_description_en', sanitize_textarea_field($_POST['about_description_en']));
    }
    if (isset($_POST['about_link_text'])) {
        update_option('about_link_text', sanitize_text_field($_POST['about_link_text']));
    }
    if (isset($_POST['about_link_text_en'])) {
        update_option('about_link_text_en', sanitize_text_field($_POST['about_link_text_en']));
    }
    if (isset($_POST['about_link_url'])) {
        $url = trim($_POST['about_link_url']);
        update_option('about_link_url', $url ? esc_url_raw($url) : '');
    }
    if (isset($_POST['about_link_url_en'])) {
        $url = trim($_POST['about_link_url_en']);
        update_option('about_link_url_en', $url ? esc_url_raw($url) : '');
    }
}

// About settings HTML
function AlQasrGroup_about_settings_html() {
    $about_image = get_option('about_image', '');
    $about_subtitle = get_option('about_subtitle', '');
    $about_subtitle_en = get_option('about_subtitle_en', '');
    $about_title = get_option('about_title', 'من نحن');
    $about_title_en = get_option('about_title_en', 'About Us');
    $about_description = get_option('about_description', 'وصف مختصر عن الشركة.');
    $about_description_en = get_option('about_description_en', 'Brief description about the company.');
    $about_link_text = get_option('about_link_text', 'قراءة المزيد');
    $about_link_text_en = get_option('about_link_text_en', 'Read More');
    $about_link_url = get_option('about_link_url', '');
    $about_link_url_en = get_option('about_link_url_en', '');
    ?>
    <h2>من نحن</h2>
    <table class="form-table">
        <tr>
            <th scope="row">الصورة</th>
            <td>
                <input type="hidden" name="about_image" value="<?php echo esc_attr($about_image); ?>" class="image-field" />
                <button type="button" class="button upload-image-btn" data-target="about_image">اختر صورة</button>
                <button type="button" class="button remove-image-btn" data-target="about_image" style="<?php echo $about_image ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                <div id="about_image_preview" class="image-preview">
                    <?php if ($about_image): ?>
                        <img src="<?php echo esc_url($about_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">العنوان الصغير (عربي)</th>
            <td><input type="text" name="about_subtitle" value="<?php echo esc_attr($about_subtitle); ?>" class="regular-text" placeholder="عنوان صغير يظهر قبل العنوان الرئيسي" /></td>
        </tr>
        <tr>
            <th scope="row">About Subtitle (English)</th>
            <td><input type="text" name="about_subtitle_en" value="<?php echo esc_attr($about_subtitle_en); ?>" class="regular-text" placeholder="Small title that appears before the main title" /></td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td><input type="text" name="about_title" value="<?php echo esc_attr($about_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">About Title (English)</th>
            <td><input type="text" name="about_title_en" value="<?php echo esc_attr($about_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">الوصف (عربي)</th>
            <td><textarea name="about_description" rows="4" class="large-text"><?php echo esc_textarea($about_description); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">About Description (English)</th>
            <td><textarea name="about_description_en" rows="4" class="large-text"><?php echo esc_textarea($about_description_en); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">نص الزر (عربي)</th>
            <td><input type="text" name="about_link_text" value="<?php echo esc_attr($about_link_text); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">Button Text (English)</th>
            <td><input type="text" name="about_link_text_en" value="<?php echo esc_attr($about_link_text_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">رابط الزر (عربي)</th>
            <td><input type="url" name="about_link_url" value="<?php echo esc_attr($about_link_url); ?>" class="regular-text" placeholder="https://example.com" /></td>
        </tr>
        <tr>
            <th scope="row">Button Link (English)</th>
            <td><input type="url" name="about_link_url_en" value="<?php echo esc_attr($about_link_url_en); ?>" class="regular-text" placeholder="https://example.com" /></td>
        </tr>
    </table>
    <?php
}

