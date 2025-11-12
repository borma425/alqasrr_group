<?php

// Register Contact settings
function AlQasrGroup_register_contact_settings() {
    // Company address
    register_setting('AlQasrGroup_settings', 'contact_company_address_ar');
    register_setting('AlQasrGroup_settings', 'contact_company_address_en');
    
    // Contact methods (dynamic key-value pairs)
    register_setting('AlQasrGroup_settings', 'contact_methods_ar');
    register_setting('AlQasrGroup_settings', 'contact_methods_en');
    
    // Google Map link
    register_setting('AlQasrGroup_settings', 'contact_map_link');
    
    // Background image
    register_setting('AlQasrGroup_settings', 'contact_background_image');
}
add_action('admin_init', 'AlQasrGroup_register_contact_settings');

// Save Contact settings
function AlQasrGroup_save_contact_settings() {
    // Company address
    if (isset($_POST['contact_company_address_ar'])) {
        update_option('contact_company_address_ar', sanitize_text_field($_POST['contact_company_address_ar']));
    }
    if (isset($_POST['contact_company_address_en'])) {
        update_option('contact_company_address_en', sanitize_text_field($_POST['contact_company_address_en']));
    }
    
    // Contact methods (Arabic)
    if (isset($_POST['contact_methods_ar'])) {
        $methods_ar = array();
        if (is_array($_POST['contact_methods_ar'])) {
            foreach ($_POST['contact_methods_ar'] as $method) {
                if (!empty($method['key']) && !empty($method['value'])) {
                    $methods_ar[sanitize_text_field($method['key'])] = sanitize_text_field($method['value']);
                }
            }
        }
        update_option('contact_methods_ar', $methods_ar);
    }
    
    // Contact methods (English)
    if (isset($_POST['contact_methods_en'])) {
        $methods_en = array();
        if (is_array($_POST['contact_methods_en'])) {
            foreach ($_POST['contact_methods_en'] as $method) {
                if (!empty($method['key']) && !empty($method['value'])) {
                    $methods_en[sanitize_text_field($method['key'])] = sanitize_text_field($method['value']);
                }
            }
        }
        update_option('contact_methods_en', $methods_en);
    }
    
    // Google Map link
    if (isset($_POST['contact_map_link'])) {
        update_option('contact_map_link', esc_url_raw($_POST['contact_map_link']));
    }
    
    // Background image
    if (isset($_POST['contact_background_image'])) {
        update_option('contact_background_image', esc_url_raw($_POST['contact_background_image']));
    }
}

// Contact settings HTML
function AlQasrGroup_contact_settings_html() {
    // Get current values
    $contact_company_address_ar = get_option('contact_company_address_ar', '');
    $contact_company_address_en = get_option('contact_company_address_en', '');
    $contact_methods_ar = get_option('contact_methods_ar', array());
    $contact_methods_en = get_option('contact_methods_en', array());
    $contact_map_link = get_option('contact_map_link', '');
    $contact_background_image = get_option('contact_background_image', '');
    
    // Convert associative array to indexed array for easier handling
    $methods_ar_list = array();
    foreach ($contact_methods_ar as $key => $value) {
        $methods_ar_list[] = array('key' => $key, 'value' => $value);
    }
    if (empty($methods_ar_list)) {
        $methods_ar_list[] = array('key' => '', 'value' => '');
    }
    
    $methods_en_list = array();
    foreach ($contact_methods_en as $key => $value) {
        $methods_en_list[] = array('key' => $key, 'value' => $value);
    }
    if (empty($methods_en_list)) {
        $methods_en_list[] = array('key' => '', 'value' => '');
    }
    
    ?>
    <div class="contact-settings">
        <h2>معلومات الاتصال</h2>
        
        <!-- Company Address -->
        <div class="form-section">
            <h3>عنوان الشركة</h3>
            
            <div class="form-field">
                <label for="contact_company_address_ar">عنوان الشركة (عربي)</label>
                <input type="text" id="contact_company_address_ar" name="contact_company_address_ar" value="<?php echo esc_attr($contact_company_address_ar); ?>" class="regular-text" placeholder="مثال: جدة، المملكة العربية السعودية" />
                <p class="description">عنوان الشركة باللغة العربية</p>
            </div>
            
            <div class="form-field">
                <label for="contact_company_address_en">عنوان الشركة (إنجليزي)</label>
                <input type="text" id="contact_company_address_en" name="contact_company_address_en" value="<?php echo esc_attr($contact_company_address_en); ?>" class="regular-text" placeholder="Example: Jeddah, Saudi Arabia" />
                <p class="description">عنوان الشركة باللغة الإنجليزية</p>
            </div>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Contact Methods (Arabic) -->
        <div class="form-section">
            <h3>وسائل التواصل (عربي)</h3>
            <p class="description">يمكنك إضافة وسائل تواصل متعددة. مثال: "البريد" : "text@gmail.com"</p>
            
            <div id="contact-methods-ar-container">
                <?php foreach ($methods_ar_list as $index => $method): ?>
                <div class="contact-method-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;">
                    <div style="flex: 1;">
                        <label>المفتاح (Key)</label>
                        <input type="text" name="contact_methods_ar[<?php echo $index; ?>][key]" value="<?php echo esc_attr($method['key']); ?>" class="regular-text" placeholder="مثال: البريد" />
                    </div>
                    <div style="flex: 2;">
                        <label>القيمة (Value)</label>
                        <input type="text" name="contact_methods_ar[<?php echo $index; ?>][value]" value="<?php echo esc_attr($method['value']); ?>" class="regular-text" placeholder="مثال: text@gmail.com" />
                    </div>
                    <div style="padding-top: 25px;">
                        <button type="button" class="button button-secondary remove-method-btn" data-lang="ar">حذف</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button button-secondary" id="add-method-ar">+ إضافة وسيلة تواصل</button>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Contact Methods (English) -->
        <div class="form-section">
            <h3>وسائل التواصل (إنجليزي)</h3>
            <p class="description">يمكنك إضافة وسائل تواصل متعددة. مثال: "Email" : "text@gmail.com"</p>
            
            <div id="contact-methods-en-container">
                <?php foreach ($methods_en_list as $index => $method): ?>
                <div class="contact-method-row" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;">
                    <div style="flex: 1;">
                        <label>المفتاح (Key)</label>
                        <input type="text" name="contact_methods_en[<?php echo $index; ?>][key]" value="<?php echo esc_attr($method['key']); ?>" class="regular-text" placeholder="مثال: Email" />
                    </div>
                    <div style="flex: 2;">
                        <label>القيمة (Value)</label>
                        <input type="text" name="contact_methods_en[<?php echo $index; ?>][value]" value="<?php echo esc_attr($method['value']); ?>" class="regular-text" placeholder="مثال: text@gmail.com" />
                    </div>
                    <div style="padding-top: 25px;">
                        <button type="button" class="button button-secondary remove-method-btn" data-lang="en">حذف</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button button-secondary" id="add-method-en">+ إضافة وسيلة تواصل</button>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Google Map Link -->
        <div class="form-section">
            <h3>رابط الخريطة</h3>
            
            <div class="form-field">
                <label for="contact_map_link">رابط Google Map</label>
                <input type="url" id="contact_map_link" name="contact_map_link" value="<?php echo esc_url($contact_map_link); ?>" class="regular-text" placeholder="https://www.google.com/maps/..." />
                <p class="description">أدخل رابط الخريطة من Google Maps</p>
            </div>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <!-- Background Image -->
        <div class="form-section">
            <h3>صورة الخلفية</h3>
            
            <div class="form-field">
                <label for="contact_background_image">صورة الخلفية</label>
                <input type="text" id="contact_background_image" name="contact_background_image" value="<?php echo esc_url($contact_background_image); ?>" class="regular-text" />
                <button type="button" class="button button-secondary" onclick="openMediaUploader('contact_background_image')">اختر صورة</button>
                <p class="description">صورة الخلفية لمعلومات الاتصال</p>
                
                <?php if (!empty($contact_background_image)): ?>
                <div class="image-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($contact_background_image); ?>" alt="صورة الخلفية" style="max-width: 200px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />
                </div>
                <button type="button" class="button button-link-delete remove-image-btn" style="margin-top: 5px;" onclick="removeImage('contact_background_image')">إزالة الصورة</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    // Add new contact method (Arabic)
    document.getElementById('add-method-ar').addEventListener('click', function() {
        var container = document.getElementById('contact-methods-ar-container');
        var index = container.children.length;
        var newRow = document.createElement('div');
        newRow.className = 'contact-method-row';
        newRow.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;';
        newRow.innerHTML = `
            <div style="flex: 1;">
                <label>المفتاح (Key)</label>
                <input type="text" name="contact_methods_ar[${index}][key]" value="" class="regular-text" placeholder="مثال: البريد" />
            </div>
            <div style="flex: 2;">
                <label>القيمة (Value)</label>
                <input type="text" name="contact_methods_ar[${index}][value]" value="" class="regular-text" placeholder="مثال: text@gmail.com" />
            </div>
            <div style="padding-top: 25px;">
                <button type="button" class="button button-secondary remove-method-btn" data-lang="ar">حذف</button>
            </div>
        `;
        container.appendChild(newRow);
        attachRemoveHandler(newRow.querySelector('.remove-method-btn'));
    });
    
    // Add new contact method (English)
    document.getElementById('add-method-en').addEventListener('click', function() {
        var container = document.getElementById('contact-methods-en-container');
        var index = container.children.length;
        var newRow = document.createElement('div');
        newRow.className = 'contact-method-row';
        newRow.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start;';
        newRow.innerHTML = `
            <div style="flex: 1;">
                <label>المفتاح (Key)</label>
                <input type="text" name="contact_methods_en[${index}][key]" value="" class="regular-text" placeholder="مثال: Email" />
            </div>
            <div style="flex: 2;">
                <label>القيمة (Value)</label>
                <input type="text" name="contact_methods_en[${index}][value]" value="" class="regular-text" placeholder="مثال: text@gmail.com" />
            </div>
            <div style="padding-top: 25px;">
                <button type="button" class="button button-secondary remove-method-btn" data-lang="en">حذف</button>
            </div>
        `;
        container.appendChild(newRow);
        attachRemoveHandler(newRow.querySelector('.remove-method-btn'));
    });
    
    // Attach remove handler to button
    function attachRemoveHandler(btn) {
        btn.addEventListener('click', function() {
            var row = this.closest('.contact-method-row');
            if (row) {
                row.remove();
            }
        });
    }
    
    // Attach remove handlers to existing buttons
    document.querySelectorAll('.remove-method-btn').forEach(function(btn) {
        attachRemoveHandler(btn);
    });
    
    // Media uploader
    function openMediaUploader(fieldId) {
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
            updateImagePreview(fieldId, attachment.url);
        });
        
        mediaUploader.open();
    }
    
    function updateImagePreview(fieldId, imageUrl) {
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
        
        // Add remove button
        addRemoveButton(fieldId, fieldContainer);
    }
    
    function addRemoveButton(fieldId, container) {
        var existingRemoveBtn = container.querySelector('.remove-image-btn');
        if (!existingRemoveBtn) {
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'button button-link-delete remove-image-btn';
            removeBtn.style.marginTop = '5px';
            removeBtn.innerHTML = 'إزالة الصورة';
            removeBtn.onclick = function() {
                document.getElementById(fieldId).value = '';
                var preview = container.querySelector('.image-preview');
                if (preview) {
                    preview.remove();
                }
                removeBtn.remove();
            };
            container.appendChild(removeBtn);
        }
    }
    
    function removeImage(fieldId) {
        document.getElementById(fieldId).value = '';
        var container = document.getElementById(fieldId).parentNode;
        var preview = container.querySelector('.image-preview');
        var removeBtn = container.querySelector('.remove-image-btn');
        
        if (preview) {
            preview.remove();
        }
        if (removeBtn) {
            removeBtn.remove();
        }
    }
    
    // Initialize remove buttons for existing images
    document.addEventListener('DOMContentLoaded', function() {
        var imageFields = document.querySelectorAll('input[id*="background_image"]');
        imageFields.forEach(function(field) {
            if (field.value) {
                addRemoveButton(field.id, field.parentNode);
            }
        });
    });
    </script>
    
    <style>
    .contact-settings .form-section {
        margin-bottom: 30px;
    }
    .contact-settings .form-section h3 {
        margin-bottom: 20px;
        color: #23282d;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    .contact-settings .form-field {
        margin-bottom: 20px;
    }
    .contact-settings label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .contact-settings input[type="text"],
    .contact-settings input[type="url"],
    .contact-settings textarea {
        width: 100%;
        max-width: 500px;
    }
    .contact-settings .description {
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
    .contact-method-row label {
        font-weight: normal;
        font-size: 13px;
    }
    </style>
    
    <?php
}
