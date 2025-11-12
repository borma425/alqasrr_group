<?php
/**
 * News Settings
 * إعدادات قسم المقالات
 */

if (!defined('ABSPATH')) exit;

// Register News settings
function AlQasrGroup_register_news_settings() {
    register_setting('AlQasrGroup_home_settings', 'news_subtitle');
    register_setting('AlQasrGroup_home_settings', 'news_subtitle_en');
    register_setting('AlQasrGroup_home_settings', 'news_title');
    register_setting('AlQasrGroup_home_settings', 'news_title_en');
}
add_action('admin_init', 'AlQasrGroup_register_news_settings');

// Save News settings
function AlQasrGroup_save_news_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['news_subtitle'])) {
        update_option('news_subtitle', sanitize_text_field($_POST['news_subtitle']));
    }
    if (isset($_POST['news_subtitle_en'])) {
        update_option('news_subtitle_en', sanitize_text_field($_POST['news_subtitle_en']));
    }
    if (isset($_POST['news_title'])) {
        update_option('news_title', sanitize_text_field($_POST['news_title']));
    }
    if (isset($_POST['news_title_en'])) {
        update_option('news_title_en', sanitize_text_field($_POST['news_title_en']));
    }
}

// News settings HTML
function AlQasrGroup_news_settings_html() {
    $news_subtitle = get_option('news_subtitle', '');
    $news_subtitle_en = get_option('news_subtitle_en', '');
    $news_title = get_option('news_title', '');
    $news_title_en = get_option('news_title_en', '');
    ?>
    <h2>المقالات</h2>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الصغير (عربي)</th>
            <td><input type="text" name="news_subtitle" value="<?php echo esc_attr($news_subtitle); ?>" class="regular-text" placeholder="عنوان صغير يظهر قبل العنوان الرئيسي" /></td>
        </tr>
        <tr>
            <th scope="row">News Subtitle (English)</th>
            <td><input type="text" name="news_subtitle_en" value="<?php echo esc_attr($news_subtitle_en); ?>" class="regular-text" placeholder="Small title that appears before the main title" /></td>
        </tr>
        <tr>
            <th scope="row">العنوان الرئيسي (عربي)</th>
            <td><input type="text" name="news_title" value="<?php echo esc_attr($news_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th scope="row">News Title (English)</th>
            <td><input type="text" name="news_title_en" value="<?php echo esc_attr($news_title_en); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

