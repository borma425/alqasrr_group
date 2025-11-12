<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the mobility speed meta box.
 */
function projects_speed_travel_callback($post) {
    wp_nonce_field('projects_speed_travel_nonce', 'projects_speed_travel_nonce_field');

    $speed_sections      = get_post_meta($post->ID, '_projects_speed_sections', true);
    $speed_sections      = $speed_sections ? json_decode($speed_sections, true) : array();
    ?>
    <div class="projects-meta-container project-details-modern speed-travel-meta">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <strong><?php _e('سرعة التنقل', 'borma'); ?></strong>
                <p class="description">
                    <?php _e('أضف المحطات الزمنية لإظهار الوقت والمسافة إلى المواقع المهمة، مثل المثال الموضح في التصميم.', 'borma'); ?>
                </p>
                <hr>
            </div>

            <div class="details-body speed-body">
                <div class="speed-wrapper">
                    <div class="speed-sections" id="speed-sections">
                        <?php if (!empty($speed_sections)): ?>
                            <?php foreach ($speed_sections as $section_index => $section): ?>
                                <div class="speed-section" data-section="<?php echo $section_index; ?>">
                                    <div class="speed-section-header">
                                        <div class="speed-section-name">
                                            <label><?php _e('اسم القسم', 'borma'); ?></label>
                                            <input type="text"
                                                   name="speed_sections[<?php echo $section_index; ?>][name]"
                                                   value="<?php echo esc_attr($section['name'] ?? ''); ?>"
                                                   class="widefat"
                                                   placeholder="<?php _e('مثال: من موقع المشروع', 'borma'); ?>" />
                                            <label class="mt-compact label-with-en"><?php _e('Section Name (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                            <input type="text"
                                                   name="speed_sections[<?php echo $section_index; ?>][name_en]"
                                                   value="<?php echo esc_attr($section['name_en'] ?? ''); ?>"
                                                   class="widefat"
                                                   placeholder="<?php _e('e.g. From project site', 'borma'); ?>" />
                                        </div>
                                        <button type="button" class="button button-small remove-speed-section-btn">×</button>
                                    </div>

                                    <div class="speed-preview" data-items>
                                        <?php if (!empty($section['items'])): ?>
                                            <?php foreach ($section['items'] as $item_index => $item): ?>
                                                <div class="speed-item" data-index="<?php echo $item_index; ?>">
                                                    <div class="speed-item-content">
                                                        <div class="speed-number">
                                                            <label><?php _e('المدة (رقم)', 'borma'); ?></label>
                                                            <input type="text"
                                                                   name="speed_sections[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][time]"
                                                                   value="<?php echo esc_attr($item['time'] ?? ''); ?>"
                                                                   class="widefat speed-time-input"
                                                                   placeholder="<?php _e('مثال: 12', 'borma'); ?>" />
                                                        </div>
                                                        <div class="speed-unit">
                                                            <label><?php _e('الوحدة (نص صغير فوق الرقم)', 'borma'); ?></label>
                                                            <input type="text"
                                                                   name="speed_sections[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][unit]"
                                                                   value="<?php echo esc_attr($item['unit'] ?? ''); ?>"
                                                                   class="widefat"
                                                                   placeholder="<?php _e('مثال: دقيقة', 'borma'); ?>" />
                                                            <label class="mt-compact label-with-en"><?php _e('Unit (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                            <input type="text"
                                                                   name="speed_sections[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][unit_en]"
                                                                   value="<?php echo esc_attr($item['unit_en'] ?? ''); ?>"
                                                                   class="widefat"
                                                                   placeholder="<?php _e('e.g. minutes', 'borma'); ?>" />
                                                        </div>
                                                        <div class="speed-location">
                                                            <label><?php _e('الوجهة / الوصف', 'borma'); ?></label>
                                                            <input type="text"
                                                                   name="speed_sections[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][label]"
                                                                   value="<?php echo esc_attr($item['label'] ?? ''); ?>"
                                                                   class="widefat"
                                                                   placeholder="<?php _e('مثال: مطار كاتانيا', 'borma'); ?>" />
                                                            <label class="mt-compact label-with-en"><?php _e('Destination (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                                                            <input type="text"
                                                                   name="speed_sections[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][label_en]"
                                                                   value="<?php echo esc_attr($item['label_en'] ?? ''); ?>"
                                                                   class="widefat"
                                                                   placeholder="<?php _e('e.g. Catania Airport', 'borma'); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="speed-item-actions">
                                                        <button type="button" class="button button-small remove-speed-item-btn">×</button>
                                                    </div>
                                                    <hr class="feature-divider global-divider">
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="speed-actions">
                                        <button type="button" class="button button-primary add-speed-item-btn">
                                            <?php _e('إضافة محطة جديدة', 'borma'); ?>
                                        </button>
                                        <button type="button" class="button button-secondary clear-speed-items-btn">
                                            <?php _e('مسح محطات هذا القسم', 'borma'); ?>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="speed-sections-actions">
                        <button type="button" class="button button-primary add-speed-section-btn">
                            <?php _e('إضافة قسم فرعي جديد', 'borma'); ?>
                        </button>
                        <button type="button" class="button button-secondary clear-speed-sections-btn">
                            <?php _e('مسح جميع الأقسام', 'borma'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/template" id="speed-section-template">
        <div class="speed-section" data-section="{section}">
            <div class="speed-section-header">
                <div class="speed-section-name">
                    <label><?php _e('اسم القسم', 'borma'); ?></label>
                    <input type="text"
                           name="speed_sections[{section}][name]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('مثال: من موقع المشروع', 'borma'); ?>" />
                    <label class="mt-compact label-with-en"><?php _e('Section Name (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                    <input type="text"
                           name="speed_sections[{section}][name_en]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('e.g. From project site', 'borma'); ?>" />
                </div>
                <button type="button" class="button button-small remove-speed-section-btn">×</button>
            </div>

            <div class="speed-preview" data-items></div>

            <div class="speed-actions">
                <button type="button" class="button button-primary add-speed-item-btn">
                    <?php _e('إضافة محطة جديدة', 'borma'); ?>
                </button>
                <button type="button" class="button button-secondary clear-speed-items-btn">
                    <?php _e('مسح محطات هذا القسم', 'borma'); ?>
                </button>
            </div>
        </div>
    </script>

    <script type="text/template" id="speed-item-template">
        <div class="speed-item" data-index="{index}">
            <div class="speed-item-content">
                <div class="speed-number">
                    <label><?php _e('المدة (رقم)', 'borma'); ?></label>
                    <input type="text"
                           name="speed_sections[{section}][items][{index}][time]"
                           value=""
                           class="widefat speed-time-input"
                           placeholder="<?php _e('مثال: 12', 'borma'); ?>" />
                </div>
                <div class="speed-unit">
                    <label><?php _e('الوحدة (نص صغير فوق الرقم)', 'borma'); ?></label>
                    <input type="text"
                           name="speed_sections[{section}][items][{index}][unit]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('مثال: دقيقة', 'borma'); ?>" />
                    <label class="mt-compact label-with-en"><?php _e('Unit (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                    <input type="text"
                           name="speed_sections[{section}][items][{index}][unit_en]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('e.g. minutes', 'borma'); ?>" />
                </div>
                <div class="speed-location">
                    <label><?php _e('الوجهة / الوصف', 'borma'); ?></label>
                    <input type="text"
                           name="speed_sections[{section}][items][{index}][label]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('مثال: مطار كاتانيا', 'borma'); ?>" />
                    <label class="mt-compact label-with-en"><?php _e('Destination (English)', 'borma'); ?><span class="badge-en">EN</span></label>
                    <input type="text"
                           name="speed_sections[{section}][items][{index}][label_en]"
                           value=""
                           class="widefat"
                           placeholder="<?php _e('e.g. Catania Airport', 'borma'); ?>" />
                </div>
            </div>
            <div class="speed-item-actions">
                <button type="button" class="button button-small remove-speed-item-btn">×</button>
            </div>
            <hr class="feature-divider global-divider">
        </div>
    </script>
    <?php
}

