<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the investment features meta box.
 */
function projects_features_callback($post) {
    wp_nonce_field('projects_features_nonce', 'projects_features_nonce_field');
    
    $features_title = get_post_meta($post->ID, '_projects_features_title', true);
    $features_title_en = get_post_meta($post->ID, '_projects_features_title_en', true);
    $features_main_title = get_post_meta($post->ID, '_projects_features_main_title', true);
    $features_main_title_en = get_post_meta($post->ID, '_projects_features_main_title_en', true);
    $features_description = get_post_meta($post->ID, '_projects_features_description', true);
    $features_description_en = get_post_meta($post->ID, '_projects_features_description_en', true);
    
    $features_icons = get_post_meta($post->ID, '_projects_features_icons', true);
    $features_icons = $features_icons ? json_decode($features_icons, true) : array();
    ?>
    
    <div class="projects-meta-container project-details-modern features-meta">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <label for="projects_features_title">
                    <strong><?php _e('عنوان القسم', 'borma'); ?></strong>
                </label>
                <input type="text" id="projects_features_title" name="projects_features_title" 
                       value="<?php echo esc_attr($features_title); ?>" 
                       class="widefat details-title-input" 
                       placeholder="<?php _e('أدخل عنوان القسم...', 'borma'); ?>" />

                <label for="projects_features_title_en" class="mt-compact label-with-en">
                    <strong><?php _e('Section Title (English)', 'borma'); ?></strong>
                    <span class="badge-en">EN</span>
                </label>
                <input type="text" id="projects_features_title_en" name="projects_features_title_en" 
                       value="<?php echo esc_attr($features_title_en); ?>" 
                       class="widefat details-title-input" 
                       placeholder="<?php _e('Enter section title in English...', 'borma'); ?>" />
                <hr>
            </div>

            <div class="details-body features-body">
                <div class="details-description">
                    <label for="projects_features_main_title">
                        <strong><?php _e('العنوان الرئيسي', 'borma'); ?></strong>
                    </label>
                    <input type="text" id="projects_features_main_title" name="projects_features_main_title" 
                           value="<?php echo esc_attr($features_main_title); ?>" 
                           class="widefat details-title-input" 
                           placeholder="<?php _e('أدخل العنوان الرئيسي...', 'borma'); ?>" />

                    <label for="projects_features_main_title_en" class="mt-compact label-with-en">
                        <strong><?php _e('Main Heading (English)', 'borma'); ?></strong>
                        <span class="badge-en">EN</span>
                    </label>
                    <input type="text" id="projects_features_main_title_en" name="projects_features_main_title_en" 
                           value="<?php echo esc_attr($features_main_title_en); ?>" 
                           class="widefat details-title-input" 
                           placeholder="<?php _e('Enter main heading in English...', 'borma'); ?>" />

                    <label for="projects_features_description" class="mt-compact">
                        <strong><?php _e('الوصف', 'borma'); ?></strong>
                    </label>
                    <textarea id="projects_features_description" name="projects_features_description" 
                              class="widefat features-description-textarea" 
                              rows="5" 
                              placeholder="<?php _e('أدخل وصفًا موجزًا يبرز قيمة المشروع...', 'borma'); ?>"><?php echo esc_textarea($features_description); ?></textarea>

                    <label for="projects_features_description_en" class="mt-compact label-with-en">
                        <strong><?php _e('Description (English)', 'borma'); ?></strong>
                        <span class="badge-en">EN</span>
                    </label>
                    <textarea id="projects_features_description_en" name="projects_features_description_en" 
                              class="widefat features-description-textarea" 
                              rows="5" 
                              placeholder="<?php _e('Enter a short English description...', 'borma'); ?>"><?php echo esc_textarea($features_description_en); ?></textarea>
                </div>

                <div class="features-icons-wrapper">
                    <div class="features-icons-header">
                        <strong><?php _e('مزايا الاستثمار', 'borma'); ?></strong>
                        <p class="description"><?php _e('أضف عناصر تبرز نقاط القوة في المشروع.', 'borma'); ?></p>
                    </div>
                
                    <div class="icons-container">
                        <div class="icons-preview" id="features-icons-preview">
                            <?php if (!empty($features_icons)): ?>
                                <?php foreach ($features_icons as $index => $icon): ?>
                                    <div class="icon-item" data-index="<?php echo $index; ?>">
                                        <div class="icon-header">
                                            <span class="icon-title"><?php echo esc_html($icon['title']); ?></span>
                                            <button type="button" class="button button-small remove-icon-btn">×</button>
                                        </div>
                                        <div class="icon-content investment-feature">
                                            <div class="icon-media">
                                                <label><?php _e('الأيقونة', 'borma'); ?></label>
                                                <div class="icon-upload-field">
                                                    <input type="hidden" 
                                                           name="features_icons[<?php echo $index; ?>][icon]" 
                                                           value="<?php echo esc_attr($icon['icon']); ?>" 
                                                           class="icon-image-url" />
                                                    <div class="icon-image-preview">
                                                        <?php if (!empty($icon['icon'])): ?>
                                                            <img src="<?php echo esc_url($icon['icon']); ?>" alt="">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="icon-buttons">
                                                        <button type="button" class="button button-small upload-icon-btn">
                                                            <?php _e('اختر الأيقونة', 'borma'); ?>
                                                        </button>
                                                        <button type="button" class="button button-small remove-icon-image-btn">
                                                            <?php _e('إزالة', 'borma'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="icon-text">
                                                <label><?php _e('العنوان الرئيسي', 'borma'); ?></label>
                                                <input type="text" 
                                                       name="features_icons[<?php echo $index; ?>][title]" 
                                                       value="<?php echo esc_attr($icon['title']); ?>" 
                                                       class="widefat" 
                                                       placeholder="<?php _e('اكتب عنوان الميزة...', 'borma'); ?>" />
                                                <label class="mt-compact label-with-en"><?php _e('Main Title (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                <input type="text" 
                                                       name="features_icons[<?php echo $index; ?>][title_en]" 
                                                       value="<?php echo esc_attr($icon['title_en'] ?? ''); ?>" 
                                                       class="widefat" 
                                                       placeholder="<?php _e('Enter feature title in English...', 'borma'); ?>" />
                                                <label class="mt-compact"><?php _e('الوصف', 'borma'); ?></label>
                                                <textarea name="features_icons[<?php echo $index; ?>][description]" 
                                                          class="widefat" 
                                                          rows="3" 
                                                          placeholder="<?php _e('اشرح الميزة بإيجاز...', 'borma'); ?>"><?php echo esc_textarea($icon['description']); ?></textarea>
                                                <label class="mt-compact label-with-en"><?php _e('Description (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                <textarea name="features_icons[<?php echo $index; ?>][description_en]" 
                                                          class="widefat" 
                                                          rows="3" 
                                                          placeholder="<?php _e('Explain the feature briefly in English...', 'borma'); ?>"><?php echo esc_textarea($icon['description_en'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        <hr class="feature-divider global-divider">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="icons-actions">
                            <button type="button" class="button button-primary add-icon-btn">
                                <?php _e('إضافة ميزة جديدة', 'borma'); ?>
                            </button>
                            <button type="button" class="button button-secondary clear-icons-btn">
                                <?php _e('مسح جميع المزايا', 'borma'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/template" id="icon-template">
        <div class="icon-item" data-index="{index}">
            <div class="icon-header">
                <span class="icon-title"><?php _e('ميزة جديدة', 'borma'); ?></span>
                <button type="button" class="button button-small remove-icon-btn">×</button>
            </div>
            <div class="icon-content investment-feature">
                <div class="icon-media">
                    <label><?php _e('الأيقونة', 'borma'); ?></label>
                    <div class="icon-upload-field">
                        <input type="hidden" 
                               name="features_icons[{index}][icon]" 
                               value="" 
                               class="icon-image-url" />
                        <div class="icon-image-preview"></div>
                        <div class="icon-buttons">
                            <button type="button" class="button button-small upload-icon-btn">
                                <?php _e('اختر الأيقونة', 'borma'); ?>
                            </button>
                            <button type="button" class="button button-small remove-icon-image-btn">
                                <?php _e('إزالة', 'borma'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="icon-text">
                                                <label><?php _e('العنوان الرئيسي', 'borma'); ?></label>
                    <input type="text" 
                           name="features_icons[{index}][title]" 
                           value="" 
                           class="widefat" 
                           placeholder="<?php _e('اكتب عنوان الميزة...', 'borma'); ?>" />
                                                <label class="mt-compact label-with-en"><?php _e('Main Title (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                <input type="text" 
                                                       name="features_icons[{index}][title_en]" 
                                                       value="" 
                                                       class="widefat" 
                                                       placeholder="<?php _e('Enter feature title in English...', 'borma'); ?>" />
                    <label class="mt-compact"><?php _e('الوصف', 'borma'); ?></label>
                    <textarea name="features_icons[{index}][description]" 
                              class="widefat" 
                              rows="3" 
                              placeholder="<?php _e('اشرح الميزة بإيجاز...', 'borma'); ?>"></textarea>
                                                <label class="mt-compact label-with-en"><?php _e('Description (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                <textarea name="features_icons[{index}][description_en]" 
                                                          class="widefat" 
                                                          rows="3" 
                                                          placeholder="<?php _e('Explain the feature briefly in English...', 'borma'); ?>"></textarea>
                </div>
            </div>
            <hr class="feature-divider global-divider">
        </div>
    </script>
    <?php
}

