<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the separator image meta box.
 */
function projects_separator_image_callback($post) {
    wp_nonce_field('projects_separator_image_nonce', 'projects_separator_image_nonce_field');

    $separator_image = get_post_meta($post->ID, '_projects_separator_image', true);
    ?>
    <div class="projects-meta-container project-details-modern project-separator-image">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <label for="projects_separator_image_field">
                    <strong><?php _e('الصورة الفاصلة', 'borma'); ?></strong>
                </label>
                <p class="description"><?php _e('هذه الصورة تظهر كفاصل بصري في منتصف صفحة المشروع.', 'borma'); ?></p>
                <hr>
            </div>

            <div class="details-body">
                <div class="image-upload-field">
                    <input type="hidden" id="projects_separator_image_field" name="projects_separator_image" value="<?php echo esc_attr($separator_image); ?>" />
                    <div class="image-preview separator-preview">
                        <?php if ($separator_image): ?>
                            <div class="image-preview-container">
                                <img src="<?php echo esc_url($separator_image); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" />
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="separator-actions">
                        <button type="button" class="button upload-image-btn" data-target="projects_separator_image_field">
                            <?php _e('اختر الصورة', 'borma'); ?>
                        </button>
                        <button type="button" class="button button-secondary remove-image-btn" data-target="projects_separator_image_field">
                            <?php _e('إزالة الصورة', 'borma'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

