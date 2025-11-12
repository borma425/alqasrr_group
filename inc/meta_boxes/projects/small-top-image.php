<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the small top image meta box.
 */
function projects_small_top_image_callback($post) {
    wp_nonce_field('projects_small_top_image_nonce', 'projects_small_top_image_nonce_field');

    $small_top_image = get_post_meta($post->ID, '_projects_small_top_image', true);
    ?>
    <div class="projects-meta-container projects-small-top-image">
        <div class="meta-section">
            <div class="meta-field-group">
                <div class="field-row">
                    <div class="field-column full-width">
                        <div class="image-upload-field">
                            <input type="hidden" id="projects_small_top_image_field" name="projects_small_top_image" value="<?php echo esc_attr($small_top_image); ?>" />
                            <div class="image-preview">
                                <?php if ($small_top_image): ?>
                                    <div class="image-preview-container">
                                        <img src="<?php echo esc_url($small_top_image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" />
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="button upload-image-btn" data-target="projects_small_top_image_field">
                                <?php _e('اختر الصورة', 'borma'); ?>
                            </button>
                            <button type="button" class="button button-secondary remove-image-btn" data-target="projects_small_top_image_field">
                                <?php _e('إزالة الصورة', 'borma'); ?>
                            </button>
                        </div>
                        <p class="description"><?php _e('ارفع صورة صغيرة لتظهر أعلى المحتوى.', 'borma'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

