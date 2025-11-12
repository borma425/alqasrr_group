<?php

/**
 * Logo and Site Description Settings
 * إعدادات اللوجو ووصف الموقع
 */

// Save logo settings
function AlQasrGroup_save_logo_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Logo settings
    if (isset($_POST['site_logo'])) {
        update_option('AlQasrGroup_site_logo', sanitize_text_field($_POST['site_logo']));
    }
    
    // Small logo settings
    if (isset($_POST['site_logo_small'])) {
        update_option('AlQasrGroup_site_logo_small', sanitize_text_field($_POST['site_logo_small']));
    }
    
    // Medium logo settings
    if (isset($_POST['site_logo_medium'])) {
        update_option('AlQasrGroup_site_logo_medium', sanitize_text_field($_POST['site_logo_medium']));
    }
    
    // Small dark logo settings
    if (isset($_POST['site_logo_small_dark'])) {
        update_option('AlQasrGroup_site_logo_small_dark', sanitize_text_field($_POST['site_logo_small_dark']));
    }
    
    // Medium dark logo settings
    if (isset($_POST['site_logo_medium_dark'])) {
        update_option('AlQasrGroup_site_logo_medium_dark', sanitize_text_field($_POST['site_logo_medium_dark']));
    }
    
    // Site description settings
    if (isset($_POST['site_description_ar'])) {
        update_option('AlQasrGroup_site_description_ar', sanitize_textarea_field($_POST['site_description_ar']));
    }
    
    if (isset($_POST['site_description_en'])) {
        update_option('AlQasrGroup_site_description_en', sanitize_textarea_field($_POST['site_description_en']));
    }
    
    // Site title settings
    if (isset($_POST['site_title_ar'])) {
        update_option('AlQasrGroup_site_title_ar', sanitize_text_field($_POST['site_title_ar']));
    }
    
    if (isset($_POST['site_title_en'])) {
        update_option('AlQasrGroup_site_title_en', sanitize_text_field($_POST['site_title_en']));
    }
    

}

// Logo settings HTML
function AlQasrGroup_logo_settings_html() {
    $site_logo = get_option('AlQasrGroup_site_logo', '');
    $site_logo_small = get_option('AlQasrGroup_site_logo_small', '');
    $site_logo_medium = get_option('AlQasrGroup_site_logo_medium', '');
    $site_logo_small_dark = get_option('AlQasrGroup_site_logo_small_dark', '');
    $site_logo_medium_dark = get_option('AlQasrGroup_site_logo_medium_dark', '');
    $site_description_ar = get_option('AlQasrGroup_site_description_ar', '');
    $site_description_en = get_option('AlQasrGroup_site_description_en', '');
    $site_title_ar = get_option('AlQasrGroup_site_title_ar', '');
    $site_title_en = get_option('AlQasrGroup_site_title_en', '');
    ?>
    
    <div class="logo-settings">
        <h2>إعدادات اللوجو ووصف الموقع</h2>
        
        <!-- Site Logo (for backward compatibility) -->
        <div class="form-field">
            <label for="site_logo">لوجو الموقع (الافتراضي)</label>
            <input type="text" id="site_logo" name="site_logo" value="<?php echo esc_attr($site_logo); ?>" class="regular-text" />
            <button type="button" class="button button-secondary" id="upload_logo_button">اختر صورة</button>
            <p class="description">اختر لوجو الموقع الافتراضي (يفضل أن يكون بصيغة PNG مع خلفية شفافة)</p>
            <?php if ($site_logo): ?>
                <div class="logo-preview" id="site_logo_preview">
                    <img src="<?php echo esc_url($site_logo); ?>" alt="لوجو الموقع" style="max-width: 200px; margin-top: 10px;" />
                </div>
            <?php endif; ?>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Small Logo -->
        <div class="form-field">
            <label for="site_logo_small">لوجو صغير</label>
            <input type="text" id="site_logo_small" name="site_logo_small" value="<?php echo esc_attr($site_logo_small); ?>" class="regular-text" />
            <button type="button" class="button button-secondary" id="upload_logo_small_button">اختر صورة</button>
            <p class="description">اختر لوجو صغير (يفضل أن يكون بصيغة PNG مع خلفية شفافة)</p>
            <?php if ($site_logo_small): ?>
                <div class="logo-preview" id="site_logo_small_preview">
                    <img src="<?php echo esc_url($site_logo_small); ?>" alt="لوجو صغير" style="max-width: 150px; margin-top: 10px;" />
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Medium Logo -->
        <div class="form-field">
            <label for="site_logo_medium">لوجو متوسط</label>
            <input type="text" id="site_logo_medium" name="site_logo_medium" value="<?php echo esc_attr($site_logo_medium); ?>" class="regular-text" />
            <button type="button" class="button button-secondary" id="upload_logo_medium_button">اختر صورة</button>
            <p class="description">اختر لوجو متوسط (يفضل أن يكون بصيغة PNG مع خلفية شفافة)</p>
            <?php if ($site_logo_medium): ?>
                <div class="logo-preview" id="site_logo_medium_preview">
                    <img src="<?php echo esc_url($site_logo_medium); ?>" alt="لوجو متوسط" style="max-width: 200px; margin-top: 10px;" />
                </div>
            <?php endif; ?>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Small Dark Logo -->
        <div class="form-field">
            <label for="site_logo_small_dark">لوجو صغير (داكن)</label>
            <input type="text" id="site_logo_small_dark" name="site_logo_small_dark" value="<?php echo esc_attr($site_logo_small_dark); ?>" class="regular-text" />
            <button type="button" class="button button-secondary" id="upload_logo_small_dark_button">اختر صورة</button>
            <p class="description">اختر لوجو صغير للوضع الداكن (يفضل أن يكون بصيغة PNG مع خلفية شفافة)</p>
            <?php if ($site_logo_small_dark): ?>
                <div class="logo-preview" id="site_logo_small_dark_preview">
                    <img src="<?php echo esc_url($site_logo_small_dark); ?>" alt="لوجو صغير داكن" style="max-width: 150px; margin-top: 10px;" />
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Medium Dark Logo -->
        <div class="form-field">
            <label for="site_logo_medium_dark">لوجو متوسط (داكن)</label>
            <input type="text" id="site_logo_medium_dark" name="site_logo_medium_dark" value="<?php echo esc_attr($site_logo_medium_dark); ?>" class="regular-text" />
            <button type="button" class="button button-secondary" id="upload_logo_medium_dark_button">اختر صورة</button>
            <p class="description">اختر لوجو متوسط للوضع الداكن (يفضل أن يكون بصيغة PNG مع خلفية شفافة)</p>
            <?php if ($site_logo_medium_dark): ?>
                <div class="logo-preview" id="site_logo_medium_dark_preview">
                    <img src="<?php echo esc_url($site_logo_medium_dark); ?>" alt="لوجو متوسط داكن" style="max-width: 200px; margin-top: 10px;" />
                </div>
            <?php endif; ?>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Site Title -->
        <div class="form-field">
            <h3>عنوان الموقع</h3>
            
            <div class="form-field">
                <label for="site_title_ar">العنوان (عربي)</label>
                <input type="text" id="site_title_ar" name="site_title_ar" value="<?php echo esc_attr($site_title_ar); ?>" class="regular-text" />
                <p class="description">عنوان الموقع باللغة العربية</p>
            </div>
            
            <div class="form-field">
                <label for="site_title_en">العنوان (إنجليزي)</label>
                <input type="text" id="site_title_en" name="site_title_en" value="<?php echo esc_attr($site_title_en); ?>" class="regular-text" />
                <p class="description">عنوان الموقع باللغة الإنجليزية</p>
            </div>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Site Description -->
        <div class="form-field">
            <h3>وصف الموقع</h3>
            
            <div class="form-field">
                <label for="site_description_ar">الوصف (عربي)</label>
                <textarea id="site_description_ar" name="site_description_ar" rows="4" class="large-text"><?php echo esc_textarea($site_description_ar); ?></textarea>
                <p class="description">وصف مختصر للموقع باللغة العربية (يظهر في محركات البحث)</p>
            </div>
            
            <div class="form-field">
                <label for="site_description_en">الوصف (إنجليزي)</label>
                <textarea id="site_description_en" name="site_description_en" rows="4" class="large-text"><?php echo esc_textarea($site_description_en); ?></textarea>
                <p class="description">وصف مختصر للموقع باللغة الإنجليزية (يظهر في محركات البحث)</p>
            </div>
        </div>
    </div>
    
    <style>
    .logo-settings .form-field {
        margin-bottom: 20px;
    }
    .logo-settings label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .logo-settings input[type="text"],
    .logo-settings textarea {
        width: 100%;
        max-width: 500px;
    }
    .logo-settings .description {
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
    .logo-preview {
        margin-top: 10px;
        padding: 10px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Default Logo upload functionality
        $('#upload_logo_button').click(function(e) {
            e.preventDefault();
            
            var image = wp.media({
                title: 'اختر لوجو الموقع',
                multiple: false
            }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#site_logo').val(image_url);
                
                // Update preview
                var preview = $('#site_logo_preview');
                if (preview.length) {
                    preview.find('img').attr('src', image_url);
                } else {
                    $('#site_logo').after('<div class="logo-preview" id="site_logo_preview"><img src="' + image_url + '" alt="لوجو الموقع" style="max-width: 200px; margin-top: 10px;" /></div>');
                }
            });
        });
        
        // Small Logo upload functionality
        $('#upload_logo_small_button').click(function(e) {
            e.preventDefault();
            
            var image = wp.media({
                title: 'اختر لوجو صغير',
                multiple: false
            }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#site_logo_small').val(image_url);
                
                // Update preview
                var preview = $('#site_logo_small_preview');
                if (preview.length) {
                    preview.find('img').attr('src', image_url);
                } else {
                    $('#site_logo_small').after('<div class="logo-preview" id="site_logo_small_preview"><img src="' + image_url + '" alt="لوجو صغير" style="max-width: 150px; margin-top: 10px;" /></div>');
                }
            });
        });
        
        // Medium Logo upload functionality
        $('#upload_logo_medium_button').click(function(e) {
            e.preventDefault();
            
            var image = wp.media({
                title: 'اختر لوجو متوسط',
                multiple: false
            }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#site_logo_medium').val(image_url);
                
                // Update preview
                var preview = $('#site_logo_medium_preview');
                if (preview.length) {
                    preview.find('img').attr('src', image_url);
                } else {
                    $('#site_logo_medium').after('<div class="logo-preview" id="site_logo_medium_preview"><img src="' + image_url + '" alt="لوجو متوسط" style="max-width: 200px; margin-top: 10px;" /></div>');
                }
            });
        });
        
        // Small Dark Logo upload functionality
        $('#upload_logo_small_dark_button').click(function(e) {
            e.preventDefault();
            
            var image = wp.media({
                title: 'اختر لوجو صغير داكن',
                multiple: false
            }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#site_logo_small_dark').val(image_url);
                
                // Update preview
                var preview = $('#site_logo_small_dark_preview');
                if (preview.length) {
                    preview.find('img').attr('src', image_url);
                } else {
                    $('#site_logo_small_dark').after('<div class="logo-preview" id="site_logo_small_dark_preview"><img src="' + image_url + '" alt="لوجو صغير داكن" style="max-width: 150px; margin-top: 10px;" /></div>');
                }
            });
        });
        
        // Medium Dark Logo upload functionality
        $('#upload_logo_medium_dark_button').click(function(e) {
            e.preventDefault();
            
            var image = wp.media({
                title: 'اختر لوجو متوسط داكن',
                multiple: false
            }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#site_logo_medium_dark').val(image_url);
                
                // Update preview
                var preview = $('#site_logo_medium_dark_preview');
                if (preview.length) {
                    preview.find('img').attr('src', image_url);
                } else {
                    $('#site_logo_medium_dark').after('<div class="logo-preview" id="site_logo_medium_dark_preview"><img src="' + image_url + '" alt="لوجو متوسط داكن" style="max-width: 200px; margin-top: 10px;" /></div>');
                }
            });
        });
    });
    </script>
    
    <?php
}
