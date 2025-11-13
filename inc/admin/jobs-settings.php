<?php

// Register Jobs settings
function AlQasrGroup_register_jobs_settings() {
    register_setting('AlQasrGroup_settings', 'jobs_main_title');
    register_setting('AlQasrGroup_settings', 'jobs_main_title_en');
    register_setting('AlQasrGroup_settings', 'jobs_main_description');
    register_setting('AlQasrGroup_settings', 'jobs_main_description_en');

    register_setting('AlQasrGroup_settings', 'jobs_highlight_subtitle');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_subtitle_en');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_title');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_title_en');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_description');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_description_en');
    register_setting('AlQasrGroup_settings', 'jobs_highlight_image');

    register_setting('AlQasrGroup_settings', 'jobs_cta_small_title');
    register_setting('AlQasrGroup_settings', 'jobs_cta_small_title_en');
    register_setting('AlQasrGroup_settings', 'jobs_cta_main_title');
    register_setting('AlQasrGroup_settings', 'jobs_cta_main_title_en');
}
add_action('admin_init', 'AlQasrGroup_register_jobs_settings');

// Save Jobs settings
function AlQasrGroup_save_jobs_settings() {
    if (isset($_POST['jobs_main_title'])) {
        update_option('jobs_main_title', sanitize_text_field($_POST['jobs_main_title']));
    }

    if (isset($_POST['jobs_main_title_en'])) {
        update_option('jobs_main_title_en', sanitize_text_field($_POST['jobs_main_title_en']));
    }

    if (isset($_POST['jobs_main_description'])) {
        update_option('jobs_main_description', sanitize_textarea_field($_POST['jobs_main_description']));
    }

    if (isset($_POST['jobs_main_description_en'])) {
        update_option('jobs_main_description_en', sanitize_textarea_field($_POST['jobs_main_description_en']));
    }

    if (isset($_POST['jobs_highlight_subtitle'])) {
        update_option('jobs_highlight_subtitle', sanitize_text_field($_POST['jobs_highlight_subtitle']));
    }

    if (isset($_POST['jobs_highlight_subtitle_en'])) {
        update_option('jobs_highlight_subtitle_en', sanitize_text_field($_POST['jobs_highlight_subtitle_en']));
    }

    if (isset($_POST['jobs_highlight_title'])) {
        update_option('jobs_highlight_title', sanitize_text_field($_POST['jobs_highlight_title']));
    }

    if (isset($_POST['jobs_highlight_title_en'])) {
        update_option('jobs_highlight_title_en', sanitize_text_field($_POST['jobs_highlight_title_en']));
    }

    if (isset($_POST['jobs_highlight_description'])) {
        update_option('jobs_highlight_description', sanitize_textarea_field($_POST['jobs_highlight_description']));
    }

    if (isset($_POST['jobs_highlight_description_en'])) {
        update_option('jobs_highlight_description_en', sanitize_textarea_field($_POST['jobs_highlight_description_en']));
    }

    if (isset($_POST['jobs_highlight_image'])) {
        update_option('jobs_highlight_image', esc_url_raw($_POST['jobs_highlight_image']));
    }

    if (isset($_POST['jobs_cta_small_title'])) {
        update_option('jobs_cta_small_title', sanitize_text_field($_POST['jobs_cta_small_title']));
    }

    if (isset($_POST['jobs_cta_small_title_en'])) {
        update_option('jobs_cta_small_title_en', sanitize_text_field($_POST['jobs_cta_small_title_en']));
    }

    if (isset($_POST['jobs_cta_main_title'])) {
        update_option('jobs_cta_main_title', sanitize_text_field($_POST['jobs_cta_main_title']));
    }

    if (isset($_POST['jobs_cta_main_title_en'])) {
        update_option('jobs_cta_main_title_en', sanitize_text_field($_POST['jobs_cta_main_title_en']));
    }
}

// Jobs settings HTML
function AlQasrGroup_jobs_settings_html() {
    $jobs_main_title = get_option('jobs_main_title', '');
    $jobs_main_title_en = get_option('jobs_main_title_en', '');
    $jobs_main_description = get_option('jobs_main_description', '');
    $jobs_main_description_en = get_option('jobs_main_description_en', '');

    $jobs_highlight_subtitle = get_option('jobs_highlight_subtitle', '');
    $jobs_highlight_subtitle_en = get_option('jobs_highlight_subtitle_en', '');
    $jobs_highlight_title = get_option('jobs_highlight_title', '');
    $jobs_highlight_title_en = get_option('jobs_highlight_title_en', '');
    $jobs_highlight_description = get_option('jobs_highlight_description', '');
    $jobs_highlight_description_en = get_option('jobs_highlight_description_en', '');
    $jobs_highlight_image = get_option('jobs_highlight_image', '');

    $jobs_cta_small_title = get_option('jobs_cta_small_title', '');
    $jobs_cta_small_title_en = get_option('jobs_cta_small_title_en', '');
    $jobs_cta_main_title = get_option('jobs_cta_main_title', '');
    $jobs_cta_main_title_en = get_option('jobs_cta_main_title_en', '');
    ?>
    <div class="jobs-settings">
        <h2>إعدادات صفحة الوظائف</h2>

        <div class="jobs-section">
            <h3>العنوان الرئيسي والوصف</h3>
            <div class="form-field">
                <label for="jobs_main_title">العنوان الرئيسي</label>
                <input type="text" id="jobs_main_title" name="jobs_main_title" value="<?php echo esc_attr($jobs_main_title); ?>" class="regular-text" placeholder="مثال: فرص عمل لدينا" />
            </div>
            <div class="form-field">
                <label for="jobs_main_title_en">Main Title (English)</label>
                <input type="text" id="jobs_main_title_en" name="jobs_main_title_en" value="<?php echo esc_attr($jobs_main_title_en); ?>" class="regular-text" placeholder="e.g. Career Opportunities" />
            </div>
            <div class="form-field">
                <label for="jobs_main_description">الوصف</label>
                <textarea id="jobs_main_description" name="jobs_main_description" rows="4" class="large-text" placeholder="أدخل وصفاً مختصراً للقسم."><?php echo esc_textarea($jobs_main_description); ?></textarea>
            </div>
            <div class="form-field">
                <label for="jobs_main_description_en">Description (English)</label>
                <textarea id="jobs_main_description_en" name="jobs_main_description_en" rows="4" class="large-text" placeholder="Enter a short description for this section."><?php echo esc_textarea($jobs_main_description_en); ?></textarea>
            </div>
        </div>

        <hr class="jobs-separator" />

        <div class="jobs-section">
            <h3>قسم تفاصيل الوظائف</h3>
            <div class="jobs-highlight-grid">
                <div class="jobs-highlight-text">
                    <div class="form-field">
                        <label for="jobs_highlight_subtitle">عنوان فرعي</label>
                        <input type="text" id="jobs_highlight_subtitle" name="jobs_highlight_subtitle" value="<?php echo esc_attr($jobs_highlight_subtitle); ?>" class="regular-text" placeholder="مثال: انضم إلى فريقنا" />
                    </div>
                    <div class="form-field">
                        <label for="jobs_highlight_subtitle_en">Subtitle (English)</label>
                        <input type="text" id="jobs_highlight_subtitle_en" name="jobs_highlight_subtitle_en" value="<?php echo esc_attr($jobs_highlight_subtitle_en); ?>" class="regular-text" placeholder="e.g. Join Our Team" />
                    </div>
                    <div class="form-field">
                        <label for="jobs_highlight_title">العنوان الرئيسي</label>
                        <input type="text" id="jobs_highlight_title" name="jobs_highlight_title" value="<?php echo esc_attr($jobs_highlight_title); ?>" class="regular-text" placeholder="مثال: نبحث عن المواهب المبدعة" />
                    </div>
                    <div class="form-field">
                        <label for="jobs_highlight_title_en">Main Title (English)</label>
                        <input type="text" id="jobs_highlight_title_en" name="jobs_highlight_title_en" value="<?php echo esc_attr($jobs_highlight_title_en); ?>" class="regular-text" placeholder="e.g. We’re Looking for Creative Talent" />
                    </div>
                    <div class="form-field">
                        <label for="jobs_highlight_description">الوصف</label>
                        <textarea id="jobs_highlight_description" name="jobs_highlight_description" rows="5" class="large-text" placeholder="أدخل وصفاً مفصلاً لقسم الوظائف."><?php echo esc_textarea($jobs_highlight_description); ?></textarea>
                    </div>
                    <div class="form-field">
                        <label for="jobs_highlight_description_en">Description (English)</label>
                        <textarea id="jobs_highlight_description_en" name="jobs_highlight_description_en" rows="5" class="large-text" placeholder="Enter a detailed description for the jobs section."><?php echo esc_textarea($jobs_highlight_description_en); ?></textarea>
                    </div>
                </div>
                <div class="jobs-highlight-media">
                    <label>الصورة</label>
                    <div class="jobs-image-field">
                        <input type="text" id="jobs_highlight_image" name="jobs_highlight_image" value="<?php echo esc_attr($jobs_highlight_image); ?>" class="regular-text" placeholder="رابط الصورة أو استخدم الزر أدناه" />
                        <button type="button" class="button upload-image-btn" data-target="jobs_highlight_image">رفع / اختيار صورة</button>
                        <div id="jobs_highlight_image_preview" class="jobs-image-preview">
                            <?php if (!empty($jobs_highlight_image)) : ?>
                                <img src="<?php echo esc_url($jobs_highlight_image); ?>" alt="معاينة الصورة" style="max-width: 180px; height: auto; margin-top: 10px;" />
                                <br>
                                <button type="button" class="button button-small remove-image-btn" data-target="jobs_highlight_image">حذف الصورة</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="jobs-separator" />

        <div class="jobs-section">
            <h3>قسم دعوة لاتخاذ إجراء</h3>
            <div class="form-field">
                <label for="jobs_cta_small_title">عنوان صغير</label>
                <input type="text" id="jobs_cta_small_title" name="jobs_cta_small_title" value="<?php echo esc_attr($jobs_cta_small_title); ?>" class="regular-text" placeholder="مثال: جاهز للخطوة التالية؟" />
            </div>
            <div class="form-field">
                <label for="jobs_cta_small_title_en">Small Title (English)</label>
                <input type="text" id="jobs_cta_small_title_en" name="jobs_cta_small_title_en" value="<?php echo esc_attr($jobs_cta_small_title_en); ?>" class="regular-text" placeholder="e.g. Ready for the next step?" />
            </div>
            <div class="form-field">
                <label for="jobs_cta_main_title">العنوان الرئيسي</label>
                <input type="text" id="jobs_cta_main_title" name="jobs_cta_main_title" value="<?php echo esc_attr($jobs_cta_main_title); ?>" class="regular-text" placeholder="مثال: قدّم الآن" />
            </div>
            <div class="form-field">
                <label for="jobs_cta_main_title_en">Main Title (English)</label>
                <input type="text" id="jobs_cta_main_title_en" name="jobs_cta_main_title_en" value="<?php echo esc_attr($jobs_cta_main_title_en); ?>" class="regular-text" placeholder="e.g. Apply Now" />
            </div>
        </div>
    </div>

    <style>
    .jobs-settings .jobs-section {
        margin-bottom: 30px;
        background: #fdfdfd;
        border: 1px solid #e2e4e7;
        border-radius: 6px;
        padding: 20px;
    }
    .jobs-settings .jobs-section h3 {
        margin-top: 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #d3d7db;
        padding-bottom: 10px;
        font-size: 18px;
    }
    .jobs-settings .form-field {
        margin-bottom: 20px;
    }
    .jobs-settings label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .jobs-settings input[type="text"],
    .jobs-settings textarea {
        width: 100%;
        max-width: 100%;
    }
    .jobs-highlight-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-start;
    }
    .jobs-highlight-text {
        flex: 1 1 320px;
        min-width: 280px;
    }
    .jobs-highlight-media {
        flex: 0 0 240px;
    }
    .jobs-image-field .button {
        margin-top: 10px;
    }
    .jobs-image-preview img {
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 4px;
        background: #fff;
    }
    .jobs-separator {
        border: none;
        border-bottom: 1px solid #dcdcde;
        margin: 30px 0;
    }
    @media (max-width: 782px) {
        .jobs-highlight-grid {
            flex-direction: column;
        }
        .jobs-highlight-media {
            width: 100%;
        }
    }
    </style>
    <?php
}


