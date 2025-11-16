<?php
if (!defined('ABSPATH')) exit;

/**
 * English fields (Title + Content) for built-in Page post type
 * Arabic uses the default Title/Editor fields.
 */

add_action('add_meta_boxes', 'alqasrgroup_add_page_english_meta_box');

function alqasrgroup_add_page_english_meta_box() {
    add_meta_box(
        'page_english_content',
        __('English Content', 'AlQasrGroup'),
        'alqasrgroup_render_page_english_meta_box',
        'page',
        'normal',
        'default'
    );
}

function alqasrgroup_render_page_english_meta_box($post) {
    wp_nonce_field('alqasrgroup_page_english_meta_box_nonce', 'alqasrgroup_page_english_meta_box_nonce_field');

    $page_title_en   = get_post_meta($post->ID, 'page_title_en', true);
    $page_content_en = get_post_meta($post->ID, 'page_content_en', true);
    ?>
    <div class="page-english-meta" style="padding: 15px;">
        <div class="meta-field-group" style="margin-bottom: 20px;">
            <label for="page_title_en" style="display: block; margin-bottom: 5px;">
                <strong><?php esc_html_e('English Title', 'AlQasrGroup'); ?></strong>
            </label>
            <input
                type="text"
                id="page_title_en"
                name="page_title_en"
                value="<?php echo esc_attr($page_title_en); ?>"
                style="width: 100%; padding: 8px; font-size: 14px;"
                placeholder="<?php esc_attr_e('Enter the English title for the page', 'AlQasrGroup'); ?>"
            />
            <p class="description" style="margin-top: 5px; color: #666;">
                <?php esc_html_e('If left empty, the default page title will be used.', 'AlQasrGroup'); ?>
            </p>
        </div>

        <div class="meta-field-group" style="margin-bottom: 20px;">
            <label for="page_content_en" style="display: block; margin-bottom: 5px;">
                <strong><?php esc_html_e('English Content', 'AlQasrGroup'); ?></strong>
            </label>
            <?php
            $editor_settings = array(
                'textarea_name'    => 'page_content_en',
                'textarea_rows'    => 20,
                'media_buttons'    => true,
                'teeny'            => false,
                'tinymce'          => true,
                'quicktags'        => true,
                'drag_drop_upload' => true,
            );
            wp_editor($page_content_en, 'page_content_en', $editor_settings);
            ?>
            <p class="description" style="margin-top: 5px; color: #666;">
                <?php esc_html_e('Full English content of the page.', 'AlQasrGroup'); ?>
            </p>
        </div>
    </div>
    <?php
}

add_action('save_post_page', 'alqasrgroup_save_page_english_meta_box');

function alqasrgroup_save_page_english_meta_box($post_id) {
    if (!isset($_POST['alqasrgroup_page_english_meta_box_nonce_field']) ||
        !wp_verify_nonce($_POST['alqasrgroup_page_english_meta_box_nonce_field'], 'alqasrgroup_page_english_meta_box_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'page') {
        return;
    }

    if (isset($_POST['page_title_en'])) {
        update_post_meta($post_id, 'page_title_en', sanitize_text_field($_POST['page_title_en']));
    }

    if (isset($_POST['page_content_en'])) {
        update_post_meta($post_id, 'page_content_en', wp_kses_post($_POST['page_content_en']));
    }
}


