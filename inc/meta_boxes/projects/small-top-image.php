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
    $project_pdf_ar  = get_post_meta($post->ID, '_projects_pdf_ar', true);
    $project_pdf_en  = get_post_meta($post->ID, '_projects_pdf_en', true);

    $pdf_ar_name = '';
    if (!empty($project_pdf_ar)) {
        $pdf_ar_path = wp_parse_url($project_pdf_ar, PHP_URL_PATH);
        $pdf_ar_name = wp_basename($pdf_ar_path ? $pdf_ar_path : $project_pdf_ar);
    }

    $pdf_en_name = '';
    if (!empty($project_pdf_en)) {
        $pdf_en_path = wp_parse_url($project_pdf_en, PHP_URL_PATH);
        $pdf_en_name = wp_basename($pdf_en_path ? $pdf_en_path : $project_pdf_en);
    }
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
                <div class="field-row projects-file-row">
                    <div class="field-column half-width">
                        <label for="projects_pdf_ar_field">
                            <strong><?php _e('ملف المشروع (PDF) - عربي', 'borma'); ?></strong>
                        </label>
                        <div class="file-upload-field">
                            <input type="hidden" id="projects_pdf_ar_field" name="projects_pdf_ar" value="<?php echo esc_attr($project_pdf_ar); ?>" />
                            <div class="file-info">
                                <span class="file-name" data-placeholder="<?php esc_attr_e('لا يوجد ملف محدد', 'borma'); ?>">
                                    <?php
                                    if ($pdf_ar_name) {
                                        echo esc_html($pdf_ar_name);
                                    } else {
                                        esc_html_e('لا يوجد ملف محدد', 'borma');
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="file-actions">
                                <button type="button" class="button upload-file-btn" data-target="projects_pdf_ar_field" data-frame-title="<?php esc_attr_e('اختر ملف PDF (عربي)', 'borma'); ?>" data-file-type="application/pdf">
                                    <?php _e('رفع ملف PDF', 'borma'); ?>
                                </button>
                                <button type="button" class="button button-secondary remove-file-btn" data-target="projects_pdf_ar_field">
                                    <?php _e('إزالة الملف', 'borma'); ?>
                                </button>
                            </div>
                            <p class="description"><?php _e('ارفع ملف البروشور أو التفاصيل باللغة العربية ليظهر للزوار العرب.', 'borma'); ?></p>
                        </div>
                    </div>
                    <div class="field-column half-width">
                        <label for="projects_pdf_en_field" class="label-with-en">
                            <strong><?php _e('Project PDF (English)', 'borma'); ?></strong>
                            <span class="badge-en">EN</span>
                        </label>
                        <div class="file-upload-field">
                            <input type="hidden" id="projects_pdf_en_field" name="projects_pdf_en" value="<?php echo esc_attr($project_pdf_en); ?>" />
                            <div class="file-info">
                                <span class="file-name" data-placeholder="<?php esc_attr_e('No file selected', 'borma'); ?>">
                                    <?php
                                    if ($pdf_en_name) {
                                        echo esc_html($pdf_en_name);
                                    } else {
                                        esc_html_e('No file selected', 'borma');
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="file-actions">
                                <button type="button" class="button upload-file-btn" data-target="projects_pdf_en_field" data-frame-title="<?php esc_attr_e('Select PDF (English)', 'borma'); ?>" data-file-type="application/pdf">
                                    <?php _e('Upload PDF', 'borma'); ?>
                                </button>
                                <button type="button" class="button button-secondary remove-file-btn" data-target="projects_pdf_en_field">
                                    <?php _e('Remove File', 'borma'); ?>
                                </button>
                            </div>
                            <p class="description"><?php _e('Upload the brochure or project sheet in English for EN visitors.', 'borma'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

