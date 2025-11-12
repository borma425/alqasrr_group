<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the gallery meta box.
 */
function projects_gallery_callback($post) {
    wp_nonce_field('projects_gallery_nonce', 'projects_gallery_nonce_field');

    $gallery_subtitle = get_post_meta($post->ID, '_projects_gallery_subtitle', true);
    $gallery_subtitle_en = get_post_meta($post->ID, '_projects_gallery_subtitle_en', true);
    $gallery_title    = get_post_meta($post->ID, '_projects_gallery_title', true);
    $gallery_title_en = get_post_meta($post->ID, '_projects_gallery_title_en', true);
    $gallery_images   = get_post_meta($post->ID, '_projects_side_gallery_images', true);
    $gallery_images   = $gallery_images ? explode(',', $gallery_images) : array();
    ?>
    <div class="projects-meta-container project-details-modern projects-gallery-meta">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <label for="projects_gallery_subtitle">
                    <strong><?php _e('العنوان الفرعي', 'borma'); ?></strong>
                </label>
                <input type="text" id="projects_gallery_subtitle" name="projects_gallery_subtitle"
                       value="<?php echo esc_attr($gallery_subtitle); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('مثال: تعرف على صور المشروع', 'borma'); ?>" />

                <label for="projects_gallery_subtitle_en" class="mt-compact label-with-en">
                    <strong><?php _e('Subtitle (English)', 'borma'); ?></strong>
                    <span class="badge-en">EN</span>
                </label>
                <input type="text" id="projects_gallery_subtitle_en" name="projects_gallery_subtitle_en"
                       value="<?php echo esc_attr($gallery_subtitle_en); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('e.g. Discover the project gallery', 'borma'); ?>" />

                <label for="projects_gallery_title" class="mt-compact">
                    <strong><?php _e('العنوان الرئيسي', 'borma'); ?></strong>
                </label>
                <input type="text" id="projects_gallery_title" name="projects_gallery_title"
                       value="<?php echo esc_attr($gallery_title); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('مثال: جاليري المشروع', 'borma'); ?>" />

                <label for="projects_gallery_title_en" class="mt-compact label-with-en">
                    <strong><?php _e('Main Title (English)', 'borma'); ?></strong>
                    <span class="badge-en">EN</span>
                </label>
                <input type="text" id="projects_gallery_title_en" name="projects_gallery_title_en"
                       value="<?php echo esc_attr($gallery_title_en); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('e.g. Project Gallery', 'borma'); ?>" />
                <hr>
            </div>

            <div class="details-body">
                <div class="gallery-container">
                    <div class="gallery-preview gallery-grid">
                        <?php foreach ($gallery_images as $image_id):
                            $image_id = intval($image_id);
                            if (!$image_id) {
                                continue;
                            }

                            $image_url = wp_get_attachment_image_url($image_id, 'medium_large');
                            if (!$image_url) {
                                $image_url = wp_get_attachment_image_url($image_id, 'large');
                            }
                            if (!$image_url) {
                                $image_url = wp_get_attachment_image_url($image_id, 'full');
                            }
                            if (!$image_url) {
                                continue;
                            }
                            ?>
                            <div class="gallery-item">
                                <img src="<?php echo esc_url($image_url); ?>" alt="">
                                <input type="hidden" name="projects_side_gallery_images[]" value="<?php echo esc_attr($image_id); ?>" />
                                <button type="button" class="button button-small remove-gallery-item">×</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="gallery-actions">
                        <button type="button" class="button add-side-gallery-images">
                            <?php _e('إضافة صور للمعرض', 'borma'); ?>
                        </button>
                        <button type="button" class="button button-secondary clear-side-gallery">
                            <?php _e('مسح جميع الصور', 'borma'); ?>
                        </button>
                    </div>
                </div>
                <p class="description"><?php _e('أضف صور عالية الجودة لعرضها في جاليري المشروع.', 'borma'); ?></p>
            </div>
        </div>
    </div>
    <?php
}

