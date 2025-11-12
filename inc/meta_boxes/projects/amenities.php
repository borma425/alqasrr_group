<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the amenities meta box.
 */
function projects_amenities_callback($post) {
    wp_nonce_field('projects_amenities_nonce', 'projects_amenities_nonce_field');

    $amenities_subtitle = get_post_meta($post->ID, '_projects_amenities_subtitle', true);
    $amenities_subtitle_en = get_post_meta($post->ID, '_projects_amenities_subtitle_en', true);
    $amenities_items    = get_post_meta($post->ID, '_projects_amenities_items', true);
    $amenities_items    = $amenities_items ? json_decode($amenities_items, true) : array();
    ?>
    <div class="projects-meta-container project-details-modern amenities-meta">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <label for="projects_amenities_subtitle">
                    <strong><?php _e('العنوان الفرعي', 'borma'); ?></strong>
                </label>
                <input type="text" id="projects_amenities_subtitle" name="projects_amenities_subtitle"
                       value="<?php echo esc_attr($amenities_subtitle); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('مثال: استمتع بخدمات فاخرة', 'borma'); ?>" />

                <label for="projects_amenities_subtitle_en" class="mt-compact label-with-en">
                    <strong><?php _e('Subtitle (English)', 'borma'); ?></strong>
                    <span class="badge-en">EN</span>
                </label>
                <input type="text" id="projects_amenities_subtitle_en" name="projects_amenities_subtitle_en"
                       value="<?php echo esc_attr($amenities_subtitle_en); ?>"
                       class="widefat details-title-input"
                       placeholder="<?php _e('e.g. Enjoy premium services', 'borma'); ?>" />

                <hr>
            </div>

            <div class="details-body amenities-body">
                <div class="amenities-wrapper">
                    <div class="amenities-header">
                        <strong><?php _e('عناصر المرافق', 'borma'); ?></strong>
                        <p class="description"><?php _e('أضف المرافق والخدمات المتاحة في المشروع.', 'borma'); ?></p>
                    </div>

                    <div class="amenities-preview" id="amenities-preview">
                        <?php if (!empty($amenities_items)): ?>
                            <?php foreach ($amenities_items as $index => $item): ?>
                                <div class="amenity-item" data-index="<?php echo $index; ?>">
                                    <div class="amenity-content">
                                        <div class="amenity-media">
                                            <label><?php _e('الصورة المصغرة', 'borma'); ?></label>
                                            <div class="amenity-upload-field">
                                                <input type="hidden"
                                                       name="amenities_items[<?php echo $index; ?>][image]"
                                                       value="<?php echo esc_attr($item['image']); ?>"
                                                       class="amenity-image-url" />
                                                <div class="amenity-image-preview">
                                                    <?php if (!empty($item['image'])): ?>
                                                        <img src="<?php echo esc_url($item['image']); ?>" alt="">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="amenity-buttons">
                                                    <button type="button" class="button button-small upload-amenity-image-btn">
                                                        <?php _e('اختر الصورة', 'borma'); ?>
                                                    </button>
                                                    <button type="button" class="button button-small remove-amenity-image-btn">
                                                        <?php _e('إزالة', 'borma'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="amenity-text">
                                            <label><?php _e('العنوان الرئيسي', 'borma'); ?></label>
                                            <input type="text"
                                                   name="amenities_items[<?php echo $index; ?>][title]"
                                                   value="<?php echo esc_attr($item['title']); ?>"
                                                   class="widefat amenity-title-input"
                                                   placeholder="<?php _e('اكتب عنوان المرفق...', 'borma'); ?>" />

                                            <label class="mt-compact label-with-en"><?php _e('Main Title (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                            <input type="text"
                                                   name="amenities_items[<?php echo $index; ?>][title_en]"
                                                   value="<?php echo esc_attr($item['title_en'] ?? ''); ?>"
                                                   class="widefat amenity-title-input-en"
                                                   placeholder="<?php _e('Enter amenity title in English...', 'borma'); ?>" />

                                            <label class="mt-compact"><?php _e('الوصف', 'borma'); ?></label>
                                            <textarea name="amenities_items[<?php echo $index; ?>][description]"
                                                      class="widefat"
                                                      rows="3"
                                                      placeholder="<?php _e('اشرح المرفق بإيجاز...', 'borma'); ?>"><?php echo esc_textarea($item['description']); ?></textarea>

                                            <label class="mt-compact label-with-en"><?php _e('Description (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                            <textarea name="amenities_items[<?php echo $index; ?>][description_en]"
                                                      class="widefat"
                                                      rows="3"
                                                      placeholder="<?php _e('Describe the amenity briefly in English...', 'borma'); ?>"><?php echo esc_textarea($item['description_en'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="amenity-actions">
                                        <button type="button" class="button button-small remove-amenity-btn">×</button>
                                    </div>
                                    <hr class="feature-divider global-divider">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="amenities-actions">
                        <button type="button" class="button button-primary add-amenity-btn">
                            <?php _e('إضافة مرفق جديد', 'borma'); ?>
                        </button>
                        <button type="button" class="button button-secondary clear-amenities-btn">
                            <?php _e('مسح جميع المرافق', 'borma'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/template" id="amenity-template">
        <div class="amenity-item" data-index="{index}">
            <div class="amenity-content">
                <div class="amenity-media">
                    <label><?php _e('الصورة المصغرة', 'borma'); ?></label>
                    <div class="amenity-upload-field">
                        <input type="hidden"
                               name="amenities_items[{index}][image]"
                               value=""
                               class="amenity-image-url" />
                        <div class="amenity-image-preview"></div>
                        <div class="amenity-buttons">
                            <button type="button" class="button button-small upload-amenity-image-btn">
                                <?php _e('اختر الصورة', 'borma'); ?>
                            </button>
                            <button type="button" class="button button-small remove-amenity-image-btn">
                                <?php _e('إزالة', 'borma'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="amenity-text">
                    <label><?php _e('العنوان الرئيسي', 'borma'); ?></label>
                    <input type="text"
                           name="amenities_items[{index}][title]"
                           value=""
                           class="widefat amenity-title-input"
                           placeholder="<?php _e('اكتب عنوان المرفق...', 'borma'); ?>" />

                    <label class="mt-compact label-with-en"><?php _e('Main Title (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                    <input type="text"
                           name="amenities_items[{index}][title_en]"
                           value=""
                           class="widefat amenity-title-input-en"
                           placeholder="<?php _e('Enter amenity title in English...', 'borma'); ?>" />
                    <label class="mt-compact"><?php _e('الوصف', 'borma'); ?></label>
                    <textarea name="amenities_items[{index}][description]"
                              class="widefat"
                              rows="3"
                              placeholder="<?php _e('اشرح المرفق بإيجاز...', 'borma'); ?>"></textarea>

                    <label class="mt-compact label-with-en"><?php _e('Description (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                    <textarea name="amenities_items[{index}][description_en]"
                              class="widefat"
                              rows="3"
                              placeholder="<?php _e('Describe the amenity briefly in English...', 'borma'); ?>"></textarea>
                </div>
            </div>
            <div class="amenity-actions">
                <button type="button" class="button button-small remove-amenity-btn">×</button>
            </div>
            <hr class="feature-divider global-divider">
        </div>
    </script>
    <?php
}

