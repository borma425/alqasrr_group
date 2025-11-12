<?php
/**
 * Hero Settings
 * إعدادات قسم Hero
 */

if (!defined('ABSPATH')) exit;

// Register Hero settings
function AlQasrGroup_register_hero_settings() {
    register_setting('AlQasrGroup_home_settings', 'hero_title_ar');
    register_setting('AlQasrGroup_home_settings', 'hero_title_en');
    register_setting('AlQasrGroup_home_settings', 'hero_subtitle_ar');
    register_setting('AlQasrGroup_home_settings', 'hero_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'hero_bg_image');
}
add_action('admin_init', 'AlQasrGroup_register_hero_settings');

// Save Hero settings
function AlQasrGroup_save_hero_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['hero_title_ar'])) {
        update_option('hero_title_ar', sanitize_text_field($_POST['hero_title_ar']));
    }
    if (isset($_POST['hero_title_en'])) {
        update_option('hero_title_en', sanitize_text_field($_POST['hero_title_en']));
    }
    if (isset($_POST['hero_subtitle_ar'])) {
        update_option('hero_subtitle_ar', sanitize_textarea_field($_POST['hero_subtitle_ar']));
    }
    if (isset($_POST['hero_subtitle_en'])) {
        update_option('hero_subtitle_en', sanitize_textarea_field($_POST['hero_subtitle_en']));
    }
    if (isset($_POST['hero_bg_image'])) {
        update_option('hero_bg_image', esc_url_raw($_POST['hero_bg_image']));
    }
}

// Hero settings HTML
function AlQasrGroup_hero_settings_html() {
    // Get current values
    $hero_title_ar = get_option('hero_title_ar', 'إنشاء تواصل حقيقي');
    $hero_title_en = get_option('hero_title_en', 'Creating Real Connection');
    $hero_subtitle_ar = get_option('hero_subtitle_ar', 'تقنيات متطورة للتواصل البديل');
    $hero_subtitle_en = get_option('hero_subtitle_en', 'Advanced technologies for alternative communication');
    $hero_bg_image = get_option('hero_bg_image', '');
    
    ?>
    <h2>قسم Hero</h2>
    <table class="form-table">
        <tr>
            <th scope="row">عنوان Hero (عربي)</th>
            <td><input type="text" name="hero_title_ar" value="<?php echo esc_attr($hero_title_ar); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">Hero Title (English)</th>
            <td><input type="text" name="hero_title_en" value="<?php echo esc_attr($hero_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">نص فرعي Hero (عربي)</th>
            <td><textarea name="hero_subtitle_ar" rows="3" class="large-text"><?php echo esc_textarea($hero_subtitle_ar); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">Hero Subtitle (English)</th>
            <td><textarea name="hero_subtitle_en" rows="3" class="large-text"><?php echo esc_textarea($hero_subtitle_en); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">صورة الخلفية</th>
            <td>
                <input type="hidden" name="hero_bg_image" value="<?php echo esc_attr($hero_bg_image); ?>" class="image-field" />
                <button type="button" class="button upload-image-btn" data-target="hero_bg_image">اختر صورة</button>
                <button type="button" class="button remove-image-btn" data-target="hero_bg_image" style="<?php echo $hero_bg_image ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                <div id="hero_bg_image_preview" class="image-preview">
                    <?php if ($hero_bg_image): ?>
                        <img src="<?php echo esc_url($hero_bg_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                    <?php endif; ?>
                </div>
                <p class="description">اختر صورة خلفية للقسم Hero</p>
            </td>
        </tr>
    </table>
    <?php
}

