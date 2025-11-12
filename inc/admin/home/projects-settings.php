<?php
/**
 * Projects Settings
 * إعدادات قسم المشاريع
 */

if (!defined('ABSPATH')) exit;

// Register Projects settings
function AlQasrGroup_register_projects_settings() {
    // First section
    register_setting('AlQasrGroup_home_settings', 'projects_subtitle');
    register_setting('AlQasrGroup_home_settings', 'projects_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'projects_title');
    register_setting('AlQasrGroup_home_settings', 'projects_title_en');
    register_setting('AlQasrGroup_home_settings', 'projects_description');
    register_setting('AlQasrGroup_home_settings', 'projects_description_en');
    
    // International Projects section
    register_setting('AlQasrGroup_home_settings', 'international_subtitle');
    register_setting('AlQasrGroup_home_settings', 'international_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'international_title');
    register_setting('AlQasrGroup_home_settings', 'international_title_en');
    register_setting('AlQasrGroup_home_settings', 'international_description');
    register_setting('AlQasrGroup_home_settings', 'international_description_en');
}
add_action('admin_init', 'AlQasrGroup_register_projects_settings');

// Save Projects settings
function AlQasrGroup_save_projects_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['projects_subtitle'])) {
        update_option('projects_subtitle', sanitize_text_field($_POST['projects_subtitle']));
    }
    if (isset($_POST['projects_subtitle_en'])) {
        update_option('projects_subtitle_en', sanitize_text_field($_POST['projects_subtitle_en']));
    }
    if (isset($_POST['projects_title'])) {
        update_option('projects_title', sanitize_text_field($_POST['projects_title']));
    }
    if (isset($_POST['projects_title_en'])) {
        update_option('projects_title_en', sanitize_text_field($_POST['projects_title_en']));
    }
    if (isset($_POST['projects_description'])) {
        update_option('projects_description', sanitize_textarea_field($_POST['projects_description']));
    }
    if (isset($_POST['projects_description_en'])) {
        update_option('projects_description_en', sanitize_textarea_field($_POST['projects_description_en']));
    }
    
    // International Projects section save
    if (isset($_POST['international_subtitle'])) {
        update_option('international_subtitle', sanitize_text_field($_POST['international_subtitle']));
    }
    if (isset($_POST['international_subtitle_en'])) {
        update_option('international_subtitle_en', sanitize_text_field($_POST['international_subtitle_en']));
    }
    if (isset($_POST['international_title'])) {
        update_option('international_title', sanitize_text_field($_POST['international_title']));
    }
    if (isset($_POST['international_title_en'])) {
        update_option('international_title_en', sanitize_text_field($_POST['international_title_en']));
    }
    if (isset($_POST['international_description'])) {
        update_option('international_description', sanitize_textarea_field($_POST['international_description']));
    }
    if (isset($_POST['international_description_en'])) {
        update_option('international_description_en', sanitize_textarea_field($_POST['international_description_en']));
    }
}

// Projects settings HTML
function AlQasrGroup_projects_settings_html() {
    $projects_subtitle = get_option('projects_subtitle', '');
    $projects_subtitle_en = get_option('projects_subtitle_en', '');
    $projects_title = get_option('projects_title', 'المشاريع');
    $projects_title_en = get_option('projects_title_en', 'Projects');
    $projects_description = get_option('projects_description', '');
    $projects_description_en = get_option('projects_description_en', '');
    ?>
    <h2>المشاريع</h2>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الصغير (عربي)</th>
            <td><input type="text" name="projects_subtitle" value="<?php echo esc_attr($projects_subtitle); ?>" class="regular-text" placeholder="عنوان صغير يظهر قبل العنوان الرئيسي" /></td>
        </tr>
        <tr>
            <th scope="row">Projects Subtitle (English)</th>
            <td><input type="text" name="projects_subtitle_en" value="<?php echo esc_attr($projects_subtitle_en); ?>" class="regular-text" placeholder="Small title that appears before the main title" /></td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td><input type="text" name="projects_title" value="<?php echo esc_attr($projects_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">Projects Title (English)</th>
            <td><input type="text" name="projects_title_en" value="<?php echo esc_attr($projects_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">الوصف (عربي)</th>
            <td><textarea name="projects_description" rows="4" class="large-text"><?php echo esc_textarea($projects_description); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">Projects Description (English)</th>
            <td><textarea name="projects_description_en" rows="4" class="large-text"><?php echo esc_textarea($projects_description_en); ?></textarea></td>
        </tr>
    </table>
    
    <hr style="margin: 30px 0; border: none; border-top: 2px solid #ddd;">
    
    <h2>المشاريع الدولية</h2>
    <?php
    $international_subtitle = get_option('international_subtitle', '');
    $international_subtitle_en = get_option('international_subtitle_en', '');
    $international_title = get_option('international_title', 'مشاريعنا الدولية');
    $international_title_en = get_option('international_title_en', 'Our International Projects');
    $international_description = get_option('international_description', '');
    $international_description_en = get_option('international_description_en', '');
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الصغير (عربي)</th>
            <td><input type="text" name="international_subtitle" value="<?php echo esc_attr($international_subtitle); ?>" class="regular-text" placeholder="عنوان صغير يظهر قبل العنوان الرئيسي" /></td>
        </tr>
        <tr>
            <th scope="row">International Subtitle (English)</th>
            <td><input type="text" name="international_subtitle_en" value="<?php echo esc_attr($international_subtitle_en); ?>" class="regular-text" placeholder="Small title that appears before the main title" /></td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td><input type="text" name="international_title" value="<?php echo esc_attr($international_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">International Title (English)</th>
            <td><input type="text" name="international_title_en" value="<?php echo esc_attr($international_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">الوصف (عربي)</th>
            <td><textarea name="international_description" rows="4" class="large-text"><?php echo esc_textarea($international_description); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">International Description (English)</th>
            <td><textarea name="international_description_en" rows="4" class="large-text"><?php echo esc_textarea($international_description_en); ?></textarea></td>
        </tr>
    </table>
    <?php
}

