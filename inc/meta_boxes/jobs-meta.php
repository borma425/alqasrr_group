<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', 'AlQasrGroup_add_jobs_meta_boxes');

function AlQasrGroup_add_jobs_meta_boxes() {
    add_meta_box(
        'AlQasrGroup_jobs_details_meta_box',
        __('تفاصيل الوظيفة', 'AlQasrGroup'),
        'AlQasrGroup_render_jobs_meta_box',
        'jobs',
        'side',
        'default'
    );

    add_meta_box(
        'AlQasrGroup_jobs_english_content',
        __('English Content', 'AlQasrGroup'),
        'AlQasrGroup_render_jobs_english_meta_box',
        'jobs',
        'normal',
        'default'
    );
}

function AlQasrGroup_render_jobs_meta_box($post) {
    wp_nonce_field('AlQasrGroup_jobs_meta_box_nonce', 'AlQasrGroup_jobs_meta_box_nonce');

    $job_type        = get_post_meta($post->ID, 'job_type', true);
    $job_type_en     = get_post_meta($post->ID, 'job_type_en', true);
    $job_location    = get_post_meta($post->ID, 'job_location', true);
    $job_location_en = get_post_meta($post->ID, 'job_location_en', true);
    ?>
    <div class="jobs-meta-box">
        <p>
            <label for="AlQasrGroup_job_type"><?php esc_html_e('نوع الوظيفة', 'AlQasrGroup'); ?></label>
            <input type="text" id="AlQasrGroup_job_type" name="AlQasrGroup_job_type" class="widefat" value="<?php echo esc_attr($job_type); ?>" placeholder="<?php esc_attr_e('مثال: دوام كامل', 'AlQasrGroup'); ?>" />
        </p>
        <p>
            <label for="AlQasrGroup_job_type_en"><?php esc_html_e('Job Type (English)', 'AlQasrGroup'); ?></label>
            <input type="text" id="AlQasrGroup_job_type_en" name="AlQasrGroup_job_type_en" class="widefat" value="<?php echo esc_attr($job_type_en); ?>" placeholder="<?php esc_attr_e('e.g. Full-time', 'AlQasrGroup'); ?>" />
        </p>
        <hr />
        <p>
            <label for="AlQasrGroup_job_location"><?php esc_html_e('موقع الوظيفة', 'AlQasrGroup'); ?></label>
            <input type="text" id="AlQasrGroup_job_location" name="AlQasrGroup_job_location" class="widefat" value="<?php echo esc_attr($job_location); ?>" placeholder="<?php esc_attr_e('مثال: دبي، الإمارات', 'AlQasrGroup'); ?>" />
        </p>
        <p>
            <label for="AlQasrGroup_job_location_en"><?php esc_html_e('Job Location (English)', 'AlQasrGroup'); ?></label>
            <input type="text" id="AlQasrGroup_job_location_en" name="AlQasrGroup_job_location_en" class="widefat" value="<?php echo esc_attr($job_location_en); ?>" placeholder="<?php esc_attr_e('e.g. Dubai, UAE', 'AlQasrGroup'); ?>" />
        </p>
    </div>
    <?php
}

function AlQasrGroup_render_jobs_english_meta_box($post) {
    wp_nonce_field('AlQasrGroup_jobs_english_meta_box_nonce', 'AlQasrGroup_jobs_english_meta_box_nonce');

    $job_title_en   = get_post_meta($post->ID, 'job_title_en', true);
    $job_excerpt_en = get_post_meta($post->ID, 'job_excerpt_en', true);
    $job_content_en = get_post_meta($post->ID, 'job_content_en', true);
    ?>
    <div class="jobs-english-meta" style="padding:15px 5px;">
        <div class="meta-field-group" style="margin-bottom:20px;">
            <label for="job_title_en" style="display:block; margin-bottom:5px;">
                <strong><?php esc_html_e('English Title', 'AlQasrGroup'); ?></strong>
            </label>
            <input
                type="text"
                id="job_title_en"
                name="job_title_en"
                value="<?php echo esc_attr($job_title_en); ?>"
                style="width:100%; padding:8px;"
                placeholder="<?php esc_attr_e('Enter the English title for the job', 'AlQasrGroup'); ?>"
            />
            <p class="description" style="margin-top:5px; color:#666;">
                <?php esc_html_e('Leave blank to reuse the main (Arabic) title.', 'AlQasrGroup'); ?>
            </p>
        </div>

        <div class="meta-field-group" style="margin-bottom:20px;">
            <label for="job_excerpt_en" style="display:block; margin-bottom:5px;">
                <strong><?php esc_html_e('English Excerpt', 'AlQasrGroup'); ?></strong>
            </label>
            <textarea
                id="job_excerpt_en"
                name="job_excerpt_en"
                rows="4"
                style="width:100%; padding:8px;"
                placeholder="<?php esc_attr_e('Enter a short English summary for the job posting', 'AlQasrGroup'); ?>"
            ><?php echo esc_textarea($job_excerpt_en); ?></textarea>
        </div>

        <div class="meta-field-group">
            <label for="job_content_en" style="display:block; margin-bottom:5px;">
                <strong><?php esc_html_e('English Content', 'AlQasrGroup'); ?></strong>
            </label>
            <?php
            $editor_settings = array(
                'textarea_name'    => 'job_content_en',
                'textarea_rows'    => 16,
                'media_buttons'    => true,
                'teeny'            => false,
                'tinymce'          => true,
                'quicktags'        => true,
                'drag_drop_upload' => true,
            );

            wp_editor($job_content_en, 'job_content_en', $editor_settings);
            ?>
            <p class="description" style="margin-top:5px; color:#666;">
                <?php esc_html_e('Provide the full English job description. Leave blank to reuse the Arabic content.', 'AlQasrGroup'); ?>
            </p>
        </div>
    </div>
    <?php
}

add_action('save_post_jobs', 'AlQasrGroup_save_jobs_meta_box');

function AlQasrGroup_save_jobs_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['AlQasrGroup_jobs_meta_box_nonce']) || !wp_verify_nonce($_POST['AlQasrGroup_jobs_meta_box_nonce'], 'AlQasrGroup_jobs_meta_box_nonce')) {
        return;
    }

    $fields_to_update = array(
        'job_type'        => array('sanitize_callback' => 'sanitize_text_field', 'field' => 'AlQasrGroup_job_type'),
        'job_type_en'     => array('sanitize_callback' => 'sanitize_text_field', 'field' => 'AlQasrGroup_job_type_en'),
        'job_location'    => array('sanitize_callback' => 'sanitize_text_field', 'field' => 'AlQasrGroup_job_location'),
        'job_location_en' => array('sanitize_callback' => 'sanitize_text_field', 'field' => 'AlQasrGroup_job_location_en'),
    );

    foreach ($fields_to_update as $meta_key => $config) {
        if (isset($_POST[$config['field']])) {
            update_post_meta($post_id, $meta_key, call_user_func($config['sanitize_callback'], wp_unslash($_POST[$config['field']])));
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }

    if (isset($_POST['AlQasrGroup_jobs_english_meta_box_nonce']) && wp_verify_nonce($_POST['AlQasrGroup_jobs_english_meta_box_nonce'], 'AlQasrGroup_jobs_english_meta_box_nonce')) {
        if (isset($_POST['job_title_en'])) {
            update_post_meta($post_id, 'job_title_en', sanitize_text_field(wp_unslash($_POST['job_title_en'])));
        }

        if (isset($_POST['job_excerpt_en'])) {
            update_post_meta($post_id, 'job_excerpt_en', sanitize_textarea_field(wp_unslash($_POST['job_excerpt_en'])));
        }

        if (isset($_POST['job_content_en'])) {
            update_post_meta($post_id, 'job_content_en', wp_kses_post($_POST['job_content_en']));
        }
    }
}
