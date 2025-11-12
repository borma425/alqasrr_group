<?php

if (!defined('ABSPATH')) exit;

// Register Archive Projects settings
function AlQasrGroup_register_archive_projects_settings() {
    // Hero title
    register_setting('AlQasrGroup_settings', 'projects_hero_title');
    register_setting('AlQasrGroup_settings', 'projects_hero_title_en');
    
    // Hero description
    register_setting('AlQasrGroup_settings', 'projects_hero_description');
    register_setting('AlQasrGroup_settings', 'projects_hero_description_en');
    
    // Background image
    register_setting('AlQasrGroup_settings', 'projects_background_image');
}
add_action('admin_init', 'AlQasrGroup_register_archive_projects_settings');

// Save Archive Projects settings
function AlQasrGroup_save_archive_projects_settings() {
    // Hero title
    if (isset($_POST['projects_hero_title'])) {
        update_option('projects_hero_title', sanitize_text_field($_POST['projects_hero_title']));
    }

    if (isset($_POST['projects_hero_title_en'])) {
        update_option('projects_hero_title_en', sanitize_text_field($_POST['projects_hero_title_en']));
    }
    
    // Hero description
    if (isset($_POST['projects_hero_description'])) {
        update_option('projects_hero_description', sanitize_textarea_field($_POST['projects_hero_description']));
    }

    if (isset($_POST['projects_hero_description_en'])) {
        update_option('projects_hero_description_en', sanitize_textarea_field($_POST['projects_hero_description_en']));
    }
    
    // Background image
    if (isset($_POST['projects_background_image'])) {
        update_option('projects_background_image', esc_url_raw($_POST['projects_background_image']));
    }
}

// Archive Projects settings HTML
function AlQasrGroup_archive_projects_settings_html() {
    // Get current values
    $projects_hero_title = get_option('projects_hero_title', '');
    $projects_hero_title_en = get_option('projects_hero_title_en', '');
    $projects_hero_description = get_option('projects_hero_description', '');
    $projects_hero_description_en = get_option('projects_hero_description_en', '');
    $projects_background_image = get_option('projects_background_image', '');
    
    ?>
    <div class="projects-settings">
        <h2>إعدادات المشاريع</h2>
        
        <!-- Hero Section Settings -->
        <div class="form-section">
            <h3>إعدادات قسم Hero</h3>
            
            <div class="form-field">
                <label for="projects_hero_title">العنوان الرئيسي</label>
                <input type="text" id="projects_hero_title" name="projects_hero_title" value="<?php echo esc_attr($projects_hero_title); ?>" class="regular-text" placeholder="مثال: مشاريعنا" />
                <p class="description">العنوان الرئيسي الذي يظهر في قسم Hero في صفحة المشاريع</p>
            </div>

            <div class="form-field">
                <label for="projects_hero_title_en">Main Title (English)</label>
                <input type="text" id="projects_hero_title_en" name="projects_hero_title_en" value="<?php echo esc_attr($projects_hero_title_en); ?>" class="regular-text" placeholder="e.g. Our Projects" />
                <p class="description">English hero title displayed on the English projects archive</p>
            </div>
            
            <div class="form-field">
                <label for="projects_hero_description">الوصف</label>
                <textarea id="projects_hero_description" name="projects_hero_description" rows="4" class="large-text" placeholder="مثال: مشاريع عقارية متميزة في أسواق عالمية."><?php echo esc_textarea($projects_hero_description); ?></textarea>
                <p class="description">الوصف الذي يظهر تحت العنوان الرئيسي في قسم Hero</p>
            </div>

            <div class="form-field">
                <label for="projects_hero_description_en">Description (English)</label>
                <textarea id="projects_hero_description_en" name="projects_hero_description_en" rows="4" class="large-text" placeholder="e.g. Exceptional real estate investments across global markets."><?php echo esc_textarea($projects_hero_description_en); ?></textarea>
                <p class="description">English description displayed below the hero title</p>
            </div>
            
            <div class="form-field">
                <label for="projects_background_image">صورة الخلفية</label>
                <input type="text" id="projects_background_image" name="projects_background_image" value="<?php echo esc_url($projects_background_image); ?>" class="regular-text" placeholder="URL الصورة" />
                <button type="button" class="button button-secondary" onclick="openProjectsMediaUploader('projects_background_image')">اختر صورة</button>
                <p class="description">صورة الخلفية لقسم Hero في صفحة المشاريع</p>
                <?php if (!empty($projects_background_image)): ?>
                    <div class="image-preview" style="margin-top: 10px;">
                        <img src="<?php echo esc_url($projects_background_image); ?>" alt="صورة الخلفية" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />
                    </div>
                    <button type="button" class="button button-secondary remove-image-btn" onclick="removeProjectsImage('projects_background_image')" style="margin-top: 10px;">حذف الصورة</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <style>
    .projects-settings .form-section {
        margin-bottom: 30px;
    }
    .projects-settings .form-section h3 {
        margin-bottom: 20px;
        color: #23282d;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    .projects-settings .form-field {
        margin-bottom: 20px;
    }
    .projects-settings label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .projects-settings input[type="text"],
    .projects-settings textarea {
        width: 100%;
        max-width: 600px;
    }
    .projects-settings .description {
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
    </style>
    
    <script>
    function openProjectsMediaUploader(fieldId) {
        if (typeof wp === 'undefined' || !wp.media) {
            alert('WordPress Media Library غير متاح');
            return;
        }
        
        var mediaUploader = wp.media({
            title: 'اختر صورة الخلفية',
            button: {
                text: 'استخدم هذه الصورة'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById(fieldId).value = attachment.url;
            
            // Update preview immediately
            updateProjectsImagePreview(fieldId, attachment.url);
        });
        
        mediaUploader.open();
    }
    
    function updateProjectsImagePreview(fieldId, imageUrl) {
        var fieldContainer = document.getElementById(fieldId).parentNode;
        var existingPreview = fieldContainer.querySelector('.image-preview');
        
        if (existingPreview) {
            // Update existing preview
            existingPreview.innerHTML = '<img src="' + imageUrl + '" alt="صورة الخلفية" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />';
        } else {
            // Create new preview
            var newPreview = document.createElement('div');
            newPreview.className = 'image-preview';
            newPreview.style.marginTop = '10px';
            newPreview.innerHTML = '<img src="' + imageUrl + '" alt="صورة الخلفية" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />';
            fieldContainer.appendChild(newPreview);
        }
        
        // Add remove button if it doesn't exist
        var existingRemoveBtn = fieldContainer.querySelector('.remove-image-btn');
        if (!existingRemoveBtn) {
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'button button-secondary remove-image-btn';
            removeBtn.style.marginTop = '10px';
            removeBtn.textContent = 'حذف الصورة';
            removeBtn.onclick = function() {
                removeProjectsImage(fieldId);
            };
            fieldContainer.appendChild(removeBtn);
        }
    }
    
    function removeProjectsImage(fieldId) {
        document.getElementById(fieldId).value = '';
        var fieldContainer = document.getElementById(fieldId).parentNode;
        var preview = fieldContainer.querySelector('.image-preview');
        var removeBtn = fieldContainer.querySelector('.remove-image-btn');
        if (preview) {
            preview.remove();
        }
        if (removeBtn) {
            removeBtn.remove();
        }
    }
    </script>
    
    <?php
}

