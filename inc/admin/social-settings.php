<?php

if (!defined('ABSPATH')) {
    exit;
}

// Register Social settings
function AlQasrGroup_register_social_settings() {
    register_setting('AlQasrGroup_settings', 'social_media_icons', array(
        'type' => 'string',
        'sanitize_callback' => 'AlQasrGroup_sanitize_social_icons',
        'default' => ''
    ));
}
add_action('admin_init', 'AlQasrGroup_register_social_settings');

// Sanitize social icons data for security
function AlQasrGroup_sanitize_social_icons($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $sanitized = array();
    foreach ($input as $key => $icon) {
        $sanitized[$key] = array(
            'title_ar' => sanitize_text_field(isset($icon['title_ar']) ? $icon['title_ar'] : ''),
            'title_en' => sanitize_text_field(isset($icon['title_en']) ? $icon['title_en'] : ''),
            'url' => esc_url_raw(isset($icon['url']) ? $icon['url'] : ''),
            'icon' => esc_url_raw(isset($icon['icon']) ? $icon['icon'] : '')
        );
    }
    
    return $sanitized;
}

// Save Social settings
function AlQasrGroup_save_social_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['social_media_icons']) && is_array($_POST['social_media_icons'])) {
        $icons = array();
        
        foreach ($_POST['social_media_icons'] as $index => $icon) {
            // Check if at least one field is filled
            if (!empty($icon['title_ar']) || !empty($icon['title_en']) || !empty($icon['url']) || !empty($icon['icon'])) {
                $icons[] = array(
                    'title_ar' => sanitize_text_field(isset($icon['title_ar']) ? $icon['title_ar'] : ''),
                    'title_en' => sanitize_text_field(isset($icon['title_en']) ? $icon['title_en'] : ''),
                    'url' => esc_url_raw(isset($icon['url']) ? $icon['url'] : ''),
                    'icon' => esc_url_raw(isset($icon['icon']) ? $icon['icon'] : '')
                );
            }
        }
        
        update_option('social_media_icons', $icons);
    } else {
        // If no icons submitted, clear the option
        update_option('social_media_icons', array());
    }
}

// Social settings HTML
function AlQasrGroup_social_settings_html() {
    // Get current values
    $social_icons = get_option('social_media_icons', array());
    
    // Ensure it's an array
    if (!is_array($social_icons)) {
        $social_icons = array();
    }
    
    // If empty, add one empty row
    if (empty($social_icons)) {
        $social_icons = array(array('title_ar' => '', 'title_en' => '', 'url' => '', 'icon' => ''));
    }
    
    // Migrate old data structure (title) to new structure (title_ar, title_en)
    foreach ($social_icons as $index => $icon) {
        if (isset($icon['title']) && !isset($icon['title_ar'])) {
            $social_icons[$index]['title_ar'] = $icon['title'];
            $social_icons[$index]['title_en'] = $icon['title'];
            unset($social_icons[$index]['title']);
        }
        // Ensure both fields exist
        if (!isset($social_icons[$index]['title_ar'])) {
            $social_icons[$index]['title_ar'] = '';
        }
        if (!isset($social_icons[$index]['title_en'])) {
            $social_icons[$index]['title_en'] = '';
        }
    }
    
    ?>
    <div class="AlQasrGroup-settings-section">
    <h2>وسائل التواصل الاجتماعي</h2>
        <p class="description">يمكنك إضافة أيقونات وسائل التواصل الاجتماعي مع عنوان بالعربية والإنجليزية ورابط لكل منها. الأيقونات يمكن أن تكون SVG أو صور عادية.</p>
        
        <div id="social-icons-container">
            <?php foreach ($social_icons as $index => $icon): ?>
                <div class="social-icon-item" data-index="<?php echo esc_attr($index); ?>">
                    <div style="background: #f9f9f9; padding: 20px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3 style="margin: 0;">أيقونة <?php echo esc_html($index + 1); ?></h3>
                            <button type="button" class="button button-secondary remove-social-icon" style="color: #dc3232;">حذف</button>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                                    العنوان (عربي) <span style="color: red;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="social_media_icons[<?php echo esc_attr($index); ?>][title_ar]" 
                                    value="<?php echo esc_attr(isset($icon['title_ar']) ? $icon['title_ar'] : ''); ?>" 
                                    class="regular-text" 
                                    placeholder="مثال: فيسبوك"
                                    required
                                />
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                                    العنوان (English) <span style="color: red;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="social_media_icons[<?php echo esc_attr($index); ?>][title_en]" 
                                    value="<?php echo esc_attr(isset($icon['title_en']) ? $icon['title_en'] : ''); ?>" 
                                    class="regular-text" 
                                    placeholder="Example: Facebook"
                                    required
                                />
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                                الرابط <span style="color: red;">*</span>
                            </label>
                            <input 
                                type="url" 
                                name="social_media_icons[<?php echo esc_attr($index); ?>][url]" 
                                value="<?php echo esc_url($icon['url']); ?>" 
                                class="regular-text" 
                                placeholder="https://example.com"
                                required
                            />
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                                الأيقونة <span style="color: red;">*</span>
                            </label>
                            <input 
                                type="hidden" 
                                name="social_media_icons[<?php echo esc_attr($index); ?>][icon]" 
                                value="<?php echo esc_url($icon['icon']); ?>" 
                                class="social-icon-input"
                            />
                            <button 
                                type="button" 
                                class="button button-primary upload-social-icon-btn" 
                                data-target-index="<?php echo esc_attr($index); ?>"
                            >
                                رفع الأيقونة
                            </button>
                            <div class="social-icon-preview" id="social_icon_preview_<?php echo esc_attr($index); ?>" style="margin-top: 10px;">
                                <?php if (!empty($icon['icon'])): ?>
                                    <?php 
                                    $icon_url = esc_url($icon['icon']);
                                    $icon_ext = strtolower(pathinfo(parse_url($icon_url, PHP_URL_PATH), PATHINFO_EXTENSION));
                                    ?>
                                    <?php if ($icon_ext === 'svg'): ?>
                                        <img src="<?php echo $icon_url; ?>" alt="Icon" style="max-width: 50px; max-height: 50px; display: block; margin-top: 10px;">
                                    <?php else: ?>
                                        <img src="<?php echo $icon_url; ?>" alt="Icon" style="max-width: 50px; max-height: 50px; display: block; margin-top: 10px;">
                                    <?php endif; ?>
                                    <br>
                                    <button type="button" class="button button-small remove-social-icon-image" data-target-index="<?php echo esc_attr($index); ?>" style="margin-top: 5px;">
                                        حذف الأيقونة
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button type="button" id="add-social-icon" class="button button-secondary">
            + إضافة أيقونة جديدة
        </button>
    </div>
    
    <style>
    .social-icon-item {
        position: relative;
    }
    .social-icon-preview img {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        background: #fff;
    }
    #add-social-icon {
        margin-top: 10px;
    }
    </style>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var iconIndex = <?php echo count($social_icons); ?>;
        
        // Add new social icon
        $('#add-social-icon').on('click', function() {
            var newItem = $('<div class="social-icon-item" data-index="' + iconIndex + '">' +
                '<div style="background: #f9f9f9; padding: 20px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px;">' +
                    '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">' +
                        '<h3 style="margin: 0;">أيقونة ' + (iconIndex + 1) + '</h3>' +
                        '<button type="button" class="button button-secondary remove-social-icon" style="color: #dc3232;">حذف</button>' +
                    '</div>' +
                    '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">' +
                        '<div>' +
                            '<label style="display: block; margin-bottom: 5px; font-weight: 600;">العنوان (عربي) <span style="color: red;">*</span></label>' +
                            '<input type="text" name="social_media_icons[' + iconIndex + '][title_ar]" class="regular-text" placeholder="مثال: فيسبوك" required />' +
                        '</div>' +
                        '<div>' +
                            '<label style="display: block; margin-bottom: 5px; font-weight: 600;">العنوان (English) <span style="color: red;">*</span></label>' +
                            '<input type="text" name="social_media_icons[' + iconIndex + '][title_en]" class="regular-text" placeholder="Example: Facebook" required />' +
                        '</div>' +
                    '</div>' +
                    '<div style="margin-bottom: 15px;">' +
                        '<label style="display: block; margin-bottom: 5px; font-weight: 600;">الرابط <span style="color: red;">*</span></label>' +
                        '<input type="url" name="social_media_icons[' + iconIndex + '][url]" class="regular-text" placeholder="https://example.com" required />' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; margin-bottom: 5px; font-weight: 600;">الأيقونة <span style="color: red;">*</span></label>' +
                        '<input type="hidden" name="social_media_icons[' + iconIndex + '][icon]" class="social-icon-input" />' +
                        '<button type="button" class="button button-primary upload-social-icon-btn" data-target-index="' + iconIndex + '">رفع الأيقونة</button>' +
                        '<div class="social-icon-preview" id="social_icon_preview_' + iconIndex + '" style="margin-top: 10px;"></div>' +
                    '</div>' +
                '</div>' +
            '</div>');
            
            $('#social-icons-container').append(newItem);
            iconIndex++;
        });
        
        // Remove social icon item
        $(document).on('click', '.remove-social-icon', function() {
            if (confirm('هل أنت متأكد من حذف هذه الأيقونة؟')) {
                $(this).closest('.social-icon-item').remove();
            }
        });
        
        // Upload social icon
        $(document).on('click', '.upload-social-icon-btn', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetIndex = button.data('target-index');
            var inputSelector = 'input[name="social_media_icons[' + targetIndex + '][icon]"]';
            var previewSelector = '#social_icon_preview_' + targetIndex;
            
            var mediaUploader = wp.media({
                title: 'اختر الأيقونة',
                button: {
                    text: 'استخدام هذه الأيقونة'
                },
                library: {
                    type: ['image', 'image/svg+xml']
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $(inputSelector).val(attachment.url);
                
                var previewHtml = '<img src="' + attachment.url + '" alt="Icon" style="max-width: 50px; max-height: 50px; display: block; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: #fff;">';
                previewHtml += '<br><button type="button" class="button button-small remove-social-icon-image" data-target-index="' + targetIndex + '" style="margin-top: 5px;">حذف الأيقونة</button>';
                
                $(previewSelector).html(previewHtml);
            });
            
            mediaUploader.open();
        });
        
        // Remove social icon image
        $(document).on('click', '.remove-social-icon-image', function(e) {
            e.preventDefault();
            var targetIndex = $(this).data('target-index');
            var inputSelector = 'input[name="social_media_icons[' + targetIndex + '][icon]"]';
            var previewSelector = '#social_icon_preview_' + targetIndex;
            
            $(inputSelector).val('');
            $(previewSelector).html('');
        });
    });
    </script>
    <?php
}
