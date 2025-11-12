<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the project details meta box.
 */
function projects_details_callback($post) {
    wp_nonce_field('projects_description_nonce', 'projects_description_nonce_field');
    
    $details_title = get_post_meta($post->ID, '_projects_details_title', true);
    $details_title_en = get_post_meta($post->ID, '_projects_details_title_en', true);
    $details_text = get_post_meta($post->ID, '_projects_details_text', true);
    $details_text_en = get_post_meta($post->ID, '_projects_details_text_en', true);
    $project_price = get_post_meta($post->ID, '_projects_price', true);
    $project_price_en = get_post_meta($post->ID, '_projects_price_en', true);
    $project_city = get_post_meta($post->ID, '_projects_city', true);
    $project_city_en = get_post_meta($post->ID, '_projects_city_en', true);
    $project_status = get_post_meta($post->ID, '_projects_status', true);
    $project_status_en = get_post_meta($post->ID, '_projects_status_en', true);
    ?>
    
    <div class="projects-meta-container project-details-modern">
        <div class="meta-section project-details-card">
            <div class="details-header">
                <label for="projects_details_title">
                    <strong><?php _e('العنوان الرئيسي', 'borma'); ?></strong>
                </label>
                <input type="text" id="projects_details_title" name="projects_details_title" 
                       value="<?php echo esc_attr($details_title); ?>" 
                       class="widefat details-title-input" 
                       placeholder="<?php _e('أدخل العنوان الرئيسي الذي يظهر في القسم...', 'borma'); ?>" />

                <label for="projects_details_title_en" class="mt-compact label-with-en">
                    <strong><?php _e('Main Title (English)', 'borma'); ?></strong>
                    <span class="badge-en">EN</span>
                </label>
                <input type="text" id="projects_details_title_en" name="projects_details_title_en" 
                       value="<?php echo esc_attr($details_title_en); ?>" 
                       class="widefat details-title-input" 
                       placeholder="<?php _e('Enter the main section title in English...', 'borma'); ?>" />
                <hr>
            </div>

            <div class="details-body">
                <div class="details-description">
                    <label for="projects_details_text">
                        <strong><?php _e('النص التمهيدي', 'borma'); ?></strong>
                    </label>
                    <textarea id="projects_details_text" name="projects_details_text" 
                              class="widefat" 
                              rows="4" 
                              placeholder="<?php _e('أدخل وصفًا موجزًا عن المشروع...', 'borma'); ?>"><?php echo esc_textarea($details_text); ?></textarea>

                    <label for="projects_details_text_en" class="mt-compact label-with-en">
                        <strong><?php _e('Intro Text (English)', 'borma'); ?></strong>
                        <span class="badge-en">EN</span>
                    </label>
                    <textarea id="projects_details_text_en" name="projects_details_text_en" 
                              class="widefat" 
                              rows="4" 
                              placeholder="<?php _e('Enter a brief description in English...', 'borma'); ?>"><?php echo esc_textarea($details_text_en); ?></textarea>
                </div>

                <div class="project-details-table">
                    <div class="details-row">
                        <div class="details-label">
                            <span><?php _e('تبدأ من', 'borma'); ?></span>
                        </div>
                        <div class="details-field">
                            <input type="text" id="projects_price" name="projects_price" 
                                   value="<?php echo esc_attr($project_price); ?>" 
                                   class="widefat" 
                                   placeholder="<?php _e('مثال: 320,000 €', 'borma'); ?>" />
                            <label for="projects_price_en" class="mt-compact label-with-en">
                                <span><?php _e('Starts From (English)', 'borma'); ?></span>
                                <span class="badge-en">EN</span>
                            </label>
                            <input type="text" id="projects_price_en" name="projects_price_en" 
                                   value="<?php echo esc_attr($project_price_en); ?>" 
                                   class="widefat mt-input-compact" 
                                   placeholder="<?php _e('e.g. 320,000 €', 'borma'); ?>" />
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="details-label">
                            <span><?php _e('المدينة', 'borma'); ?></span>
                        </div>
                        <div class="details-field">
                            <input type="text" id="projects_city" name="projects_city" 
                                   value="<?php echo esc_attr($project_city); ?>" 
                                   class="widefat" 
                                   placeholder="<?php _e('مثال: صقلية، إيطاليا', 'borma'); ?>" />
                            <label for="projects_city_en" class="mt-compact label-with-en">
                                <span><?php _e('City (English)', 'borma'); ?></span>
                                <span class="badge-en">EN</span>
                            </label>
                            <input type="text" id="projects_city_en" name="projects_city_en" 
                                   value="<?php echo esc_attr($project_city_en); ?>" 
                                   class="widefat mt-input-compact" 
                                   placeholder="<?php _e('e.g. Sicily, Italy', 'borma'); ?>" />
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="details-label">
                            <span><?php _e('الحالة', 'borma'); ?></span>
                        </div>
                        <div class="details-field">
                            <select id="projects_status" name="projects_status" class="widefat">
                                <option value=""><?php _e('اختر حالة المشروع', 'borma'); ?></option>
                                <option value="ready_to_handover" <?php selected($project_status, 'ready_to_handover'); ?>><?php _e('جاهز للتسليم', 'borma'); ?></option>
                                <option value="under_construction" <?php selected($project_status, 'under_construction'); ?>><?php _e('قيد الإنشاء', 'borma'); ?></option>
                                <option value="off_plan" <?php selected($project_status, 'off_plan'); ?>><?php _e('على المخطط', 'borma'); ?></option>
                                <option value="coming_soon" <?php selected($project_status, 'coming_soon'); ?>><?php _e('قريبًا', 'borma'); ?></option>
                                <option value="sold_out" <?php selected($project_status, 'sold_out'); ?>><?php _e('مباع بالكامل', 'borma'); ?></option>
                            </select>
                            <label for="projects_status_en" class="mt-compact label-with-en">
                                <span><?php _e('Project Status (English)', 'borma'); ?></span>
                                <span class="badge-en">EN</span>
                            </label>
                            <select id="projects_status_en" name="projects_status_en" class="widefat mt-input-compact">
                                <option value=""><?php _e('Select project status (English)', 'borma'); ?></option>
                                <option value="Ready to handover" <?php selected($project_status_en, 'Ready to handover'); ?>><?php _e('Ready to handover', 'borma'); ?></option>
                                <option value="Under construction" <?php selected($project_status_en, 'Under construction'); ?>><?php _e('Under construction', 'borma'); ?></option>
                                <option value="Off-plan" <?php selected($project_status_en, 'Off-plan'); ?>><?php _e('Off-plan', 'borma'); ?></option>
                                <option value="Coming soon" <?php selected($project_status_en, 'Coming soon'); ?>><?php _e('Coming soon', 'borma'); ?></option>
                                <option value="Sold out" <?php selected($project_status_en, 'Sold out'); ?>><?php _e('Sold out', 'borma'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

