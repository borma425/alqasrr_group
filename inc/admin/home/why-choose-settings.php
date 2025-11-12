<?php
/**
 * Why Choose Us Settings
 * إعدادات قسم لماذا نحن
 */

if (!defined('ABSPATH')) exit;

// Register Why Choose Us settings
function AlQasrGroup_register_why_choose_settings() {
    register_setting('AlQasrGroup_home_settings', 'why_choose_subtitle');
    register_setting('AlQasrGroup_home_settings', 'why_choose_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'why_choose_title');
    register_setting('AlQasrGroup_home_settings', 'why_choose_title_en');
    register_setting('AlQasrGroup_home_settings', 'why_choose_description');
    register_setting('AlQasrGroup_home_settings', 'why_choose_description_en');
    register_setting('AlQasrGroup_home_settings', 'why_choose_large_image');
    register_setting('AlQasrGroup_home_settings', 'why_choose_small_image_1');
    register_setting('AlQasrGroup_home_settings', 'why_choose_small_image_2');
}
add_action('admin_init', 'AlQasrGroup_register_why_choose_settings');

// Save Why Choose Us settings
function AlQasrGroup_save_why_choose_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['why_choose_subtitle'])) {
        update_option('why_choose_subtitle', sanitize_text_field($_POST['why_choose_subtitle']));
    }
    if (isset($_POST['why_choose_subtitle_en'])) {
        update_option('why_choose_subtitle_en', sanitize_text_field($_POST['why_choose_subtitle_en']));
    }
    if (isset($_POST['why_choose_title'])) {
        update_option('why_choose_title', sanitize_text_field($_POST['why_choose_title']));
    }
    if (isset($_POST['why_choose_title_en'])) {
        update_option('why_choose_title_en', sanitize_text_field($_POST['why_choose_title_en']));
    }
    if (isset($_POST['why_choose_description'])) {
        update_option('why_choose_description', sanitize_textarea_field($_POST['why_choose_description']));
    }
    if (isset($_POST['why_choose_description_en'])) {
        update_option('why_choose_description_en', sanitize_textarea_field($_POST['why_choose_description_en']));
    }
    if (isset($_POST['why_choose_large_image'])) {
        update_option('why_choose_large_image', esc_url_raw($_POST['why_choose_large_image']));
    }
    if (isset($_POST['why_choose_small_image_1'])) {
        update_option('why_choose_small_image_1', esc_url_raw($_POST['why_choose_small_image_1']));
    }
    if (isset($_POST['why_choose_small_image_2'])) {
        update_option('why_choose_small_image_2', esc_url_raw($_POST['why_choose_small_image_2']));
    }
}

// Why Choose Us settings HTML
function AlQasrGroup_why_choose_settings_html() {
    $why_choose_subtitle = get_option('why_choose_subtitle', '');
    $why_choose_subtitle_en = get_option('why_choose_subtitle_en', '');
    $why_choose_title = get_option('why_choose_title', 'لماذا نحن');
    $why_choose_title_en = get_option('why_choose_title_en', 'Why Choose Us');
    $why_choose_description = get_option('why_choose_description', '');
    $why_choose_description_en = get_option('why_choose_description_en', '');
    $why_choose_large_image = get_option('why_choose_large_image', '');
    $why_choose_small_image_1 = get_option('why_choose_small_image_1', '');
    $why_choose_small_image_2 = get_option('why_choose_small_image_2', '');
    ?>
    <h2>لماذا نحن</h2>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الصغير (عربي)</th>
            <td><input type="text" name="why_choose_subtitle" value="<?php echo esc_attr($why_choose_subtitle); ?>" class="regular-text" placeholder="عنوان صغير يظهر قبل العنوان الرئيسي" /></td>
        </tr>
        <tr>
            <th scope="row">Why Choose Subtitle (English)</th>
            <td><input type="text" name="why_choose_subtitle_en" value="<?php echo esc_attr($why_choose_subtitle_en); ?>" class="regular-text" placeholder="Small title that appears before the main title" /></td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td><input type="text" name="why_choose_title" value="<?php echo esc_attr($why_choose_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">Why Choose Title (English)</th>
            <td><input type="text" name="why_choose_title_en" value="<?php echo esc_attr($why_choose_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">الوصف (عربي)</th>
            <td><textarea name="why_choose_description" rows="4" class="large-text"><?php echo esc_textarea($why_choose_description); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">Why Choose Description (English)</th>
            <td><textarea name="why_choose_description_en" rows="4" class="large-text"><?php echo esc_textarea($why_choose_description_en); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">الصورة الكبيرة</th>
            <td>
                <input type="hidden" name="why_choose_large_image" value="<?php echo esc_attr($why_choose_large_image); ?>" class="image-field" />
                <button type="button" class="button upload-image-btn" data-target="why_choose_large_image">اختر صورة</button>
                <button type="button" class="button remove-image-btn" data-target="why_choose_large_image" style="<?php echo $why_choose_large_image ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                <div id="why_choose_large_image_preview" class="image-preview">
                    <?php if ($why_choose_large_image): ?>
                        <img src="<?php echo esc_url($why_choose_large_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">الصورة الصغيرة الأولى</th>
            <td>
                <input type="hidden" name="why_choose_small_image_1" value="<?php echo esc_attr($why_choose_small_image_1); ?>" class="image-field" />
                <button type="button" class="button upload-image-btn" data-target="why_choose_small_image_1">اختر صورة</button>
                <button type="button" class="button remove-image-btn" data-target="why_choose_small_image_1" style="<?php echo $why_choose_small_image_1 ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                <div id="why_choose_small_image_1_preview" class="image-preview">
                    <?php if ($why_choose_small_image_1): ?>
                        <img src="<?php echo esc_url($why_choose_small_image_1); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">الصورة الصغيرة الثانية</th>
            <td>
                <input type="hidden" name="why_choose_small_image_2" value="<?php echo esc_attr($why_choose_small_image_2); ?>" class="image-field" />
                <button type="button" class="button upload-image-btn" data-target="why_choose_small_image_2">اختر صورة</button>
                <button type="button" class="button remove-image-btn" data-target="why_choose_small_image_2" style="<?php echo $why_choose_small_image_2 ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                <div id="why_choose_small_image_2_preview" class="image-preview">
                    <?php if ($why_choose_small_image_2): ?>
                        <img src="<?php echo esc_url($why_choose_small_image_2); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
}

