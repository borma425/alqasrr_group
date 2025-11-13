<?php
add_action('add_meta_boxes', 'AlQasrGroup_add_jobs_meta_box');

function AlQasrGroup_add_jobs_meta_box() {
    add_meta_box(
        'AlQasrGroup_jobs_details_meta_box',
        'تفاصيل الوظيفة',
        'AlQasrGroup_render_jobs_meta_box',
        'jobs',
        'side',
        'default'
    );
}

function AlQasrGroup_render_jobs_meta_box($post) {
    wp_nonce_field('AlQasrGroup_jobs_meta_box_nonce', 'AlQasrGroup_jobs_meta_box_nonce');

    $job_type    = get_post_meta($post->ID, 'job_type', true);
    $job_location = get_post_meta($post->ID, 'job_location', true);

    ?>
    <div class="jobs-meta-box">
        <p>
            <label for="AlQasrGroup_job_type">نوع الوظيفة</label>
            <input type="text" id="AlQasrGroup_job_type" name="AlQasrGroup_job_type" class="widefat" value="<?php echo esc_attr($job_type); ?>" placeholder="مثال: دوام كامل" />
        </p>
        <p>
            <label for="AlQasrGroup_job_location">موقع الوظيفة</label>
            <input type="text" id="AlQasrGroup_job_location" name="AlQasrGroup_job_location" class="widefat" value="<?php echo esc_attr($job_location); ?>" placeholder="مثال: دبي، الإمارات" />
        </p>
    </div>
    <?php
}

add_action('save_post_jobs', 'AlQasrGroup_save_jobs_meta_box');

function AlQasrGroup_save_jobs_meta_box($post_id) {
    if (!isset($_POST['AlQasrGroup_jobs_meta_box_nonce']) || !wp_verify_nonce($_POST['AlQasrGroup_jobs_meta_box_nonce'], 'AlQasrGroup_jobs_meta_box_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['AlQasrGroup_job_type'])) {
        update_post_meta($post_id, 'job_type', sanitize_text_field($_POST['AlQasrGroup_job_type']));
    } else {
        delete_post_meta($post_id, 'job_type');
    }

    if (isset($_POST['AlQasrGroup_job_location'])) {
        update_post_meta($post_id, 'job_location', sanitize_text_field($_POST['AlQasrGroup_job_location']));
    } else {
        delete_post_meta($post_id, 'job_location');
    }
}
