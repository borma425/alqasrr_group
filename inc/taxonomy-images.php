<?php
/**
 * Taxonomy Image Upload Functionality
 * Allows uploading thumbnail images for project_type and city taxonomies
 */

if (!defined('ABSPATH')) exit;

// Add image and language fields to Add New Taxonomy form
function AlQasrGroup_add_taxonomy_image_field($taxonomy) {
    $allowed_taxonomies = array('project_type', 'city');
    
    if (!in_array($taxonomy, $allowed_taxonomies)) {
        return;
    }
    ?>
    <div class="form-field term-name-en-wrap">
        <label for="term_name_en"><?php _e('الاسم بالإنجليزية', 'borma'); ?></label>
        <input type="text" id="term_name_en" name="term_name_en" value="" class="regular-text" />
        <p class="description"><?php _e('أدخل اسم التصنيف باللغة الإنجليزية', 'borma'); ?></p>
    </div>
    
    <div class="form-field term-description-en-wrap">
        <label for="term_description_en"><?php _e('الوصف بالإنجليزية', 'borma'); ?></label>
        <textarea id="term_description_en" name="term_description_en" rows="3" class="large-text"></textarea>
        <p class="description"><?php _e('أدخل وصف التصنيف باللغة الإنجليزية', 'borma'); ?></p>
    </div>
    
    <div class="form-field term-image-wrap">
        <label for="taxonomy_image"><?php _e('الصورة المصغرة', 'borma'); ?></label>
        <div class="taxonomy-image-upload">
            <input type="hidden" id="taxonomy_image" name="taxonomy_image" value="" />
            <div class="taxonomy-image-preview" style="margin: 10px 0;">
                <img src="" style="max-width: 150px; height: auto; display: none;" />
            </div>
            <button type="button" class="button upload-taxonomy-image-btn">
                <?php _e('اختر صورة', 'borma'); ?>
            </button>
            <button type="button" class="button button-secondary remove-taxonomy-image-btn" style="display: none;">
                <?php _e('إزالة الصورة', 'borma'); ?>
            </button>
        </div>
        <p class="description"><?php _e('اختر صورة مصغرة لهذا التصنيف', 'borma'); ?></p>
    </div>
    <?php
}
add_action('project_type_add_form_fields', 'AlQasrGroup_add_taxonomy_image_field');
add_action('city_add_form_fields', 'AlQasrGroup_add_taxonomy_image_field');

// Add image and language fields to Edit Taxonomy form
function AlQasrGroup_edit_taxonomy_image_field($term, $taxonomy) {
    $allowed_taxonomies = array('project_type', 'city');
    
    if (!in_array($taxonomy, $allowed_taxonomies)) {
        return;
    }
    
    $name_en = get_term_meta($term->term_id, 'term_name_en', true);
    $description_en = get_term_meta($term->term_id, 'term_description_en', true);
    $image_id = get_term_meta($term->term_id, 'taxonomy_image', true);
    $image_url = '';
    
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
    }
    ?>
    <tr class="form-field term-name-en-wrap">
        <th scope="row">
            <label for="term_name_en"><?php _e('الاسم بالإنجليزية', 'borma'); ?></label>
        </th>
        <td>
            <input type="text" id="term_name_en" name="term_name_en" value="<?php echo esc_attr($name_en); ?>" class="regular-text" />
            <p class="description"><?php _e('أدخل اسم التصنيف باللغة الإنجليزية', 'borma'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field term-description-en-wrap">
        <th scope="row">
            <label for="term_description_en"><?php _e('الوصف بالإنجليزية', 'borma'); ?></label>
        </th>
        <td>
            <textarea id="term_description_en" name="term_description_en" rows="3" class="large-text"><?php echo esc_textarea($description_en); ?></textarea>
            <p class="description"><?php _e('أدخل وصف التصنيف باللغة الإنجليزية', 'borma'); ?></p>
        </td>
    </tr>
    
    <tr class="form-field term-image-wrap">
        <th scope="row">
            <label for="taxonomy_image"><?php _e('الصورة المصغرة', 'borma'); ?></label>
        </th>
        <td>
            <div class="taxonomy-image-upload">
                <input type="hidden" id="taxonomy_image" name="taxonomy_image" value="<?php echo esc_attr($image_id); ?>" />
                <div class="taxonomy-image-preview" style="margin: 10px 0;">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 150px; height: auto;" />
                    <?php else: ?>
                        <img src="" style="max-width: 150px; height: auto; display: none;" />
                    <?php endif; ?>
                </div>
                <button type="button" class="button upload-taxonomy-image-btn">
                    <?php _e('اختر صورة', 'borma'); ?>
                </button>
                <button type="button" class="button button-secondary remove-taxonomy-image-btn" <?php echo $image_url ? '' : 'style="display: none;"'; ?>>
                    <?php _e('إزالة الصورة', 'borma'); ?>
                </button>
                <p class="description"><?php _e('اختر صورة مصغرة لهذا التصنيف', 'borma'); ?></p>
            </div>
        </td>
    </tr>
    <?php
}
add_action('project_type_edit_form_fields', 'AlQasrGroup_edit_taxonomy_image_field', 10, 2);
add_action('city_edit_form_fields', 'AlQasrGroup_edit_taxonomy_image_field', 10, 2);

// Save taxonomy image and language fields when term is created
function AlQasrGroup_save_taxonomy_image($term_id, $tt_id) {
    // Save English name
    if (isset($_POST['term_name_en'])) {
        $name_en = sanitize_text_field($_POST['term_name_en']);
        if ($name_en) {
            update_term_meta($term_id, 'term_name_en', $name_en);
        } else {
            delete_term_meta($term_id, 'term_name_en');
        }
    }
    
    // Save English description
    if (isset($_POST['term_description_en'])) {
        $description_en = sanitize_textarea_field($_POST['term_description_en']);
        if ($description_en) {
            update_term_meta($term_id, 'term_description_en', $description_en);
        } else {
            delete_term_meta($term_id, 'term_description_en');
        }
    }
    
    // Save taxonomy image
    if (isset($_POST['taxonomy_image'])) {
        $image_id = sanitize_text_field($_POST['taxonomy_image']);
        if ($image_id) {
            update_term_meta($term_id, 'taxonomy_image', $image_id);
        } else {
            delete_term_meta($term_id, 'taxonomy_image');
        }
    }
}
add_action('created_project_type', 'AlQasrGroup_save_taxonomy_image', 10, 2);
add_action('created_city', 'AlQasrGroup_save_taxonomy_image', 10, 2);

// Save taxonomy image and language fields when term is updated
function AlQasrGroup_update_taxonomy_image($term_id, $tt_id) {
    // Save English name
    if (isset($_POST['term_name_en'])) {
        $name_en = sanitize_text_field($_POST['term_name_en']);
        if ($name_en) {
            update_term_meta($term_id, 'term_name_en', $name_en);
        } else {
            delete_term_meta($term_id, 'term_name_en');
        }
    }
    
    // Save English description
    if (isset($_POST['term_description_en'])) {
        $description_en = sanitize_textarea_field($_POST['term_description_en']);
        if ($description_en) {
            update_term_meta($term_id, 'term_description_en', $description_en);
        } else {
            delete_term_meta($term_id, 'term_description_en');
        }
    }
    
    // Save taxonomy image
    if (isset($_POST['taxonomy_image'])) {
        $image_id = sanitize_text_field($_POST['taxonomy_image']);
        if ($image_id) {
            update_term_meta($term_id, 'taxonomy_image', $image_id);
        } else {
            delete_term_meta($term_id, 'taxonomy_image');
        }
    }
}
add_action('edited_project_type', 'AlQasrGroup_update_taxonomy_image', 10, 2);
add_action('edited_city', 'AlQasrGroup_update_taxonomy_image', 10, 2);

// Enqueue scripts and styles for taxonomy image uploader
function AlQasrGroup_taxonomy_image_scripts($hook) {
    // Check if we're on the right taxonomy pages
    $screen = get_current_screen();
    
    // Only load on taxonomy edit/add pages for our specific taxonomies
    if (!$screen || !in_array($screen->taxonomy, array('project_type', 'city'))) {
        return;
    }
    
    // Enqueue media uploader
    wp_enqueue_media();
    
    // Register and enqueue a custom script for taxonomy image uploader
    wp_register_script(
        'alqasrgroup-taxonomy-image',
        '', // Empty src since we'll add inline script
        array('jquery', 'media-upload', 'media-views'),
        '1.0.0',
        true
    );
    
    // Add inline JavaScript for media uploader
    $js = "jQuery(document).ready(function($) {
        var mediaUploader;
        
        // Upload button click
        $(document).on('click', '.upload-taxonomy-image-btn', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var container = button.closest('.taxonomy-image-upload');
            var imageInput = container.find('input[name=\"taxonomy_image\"]');
            var imagePreview = container.find('.taxonomy-image-preview img');
            var removeButton = container.find('.remove-taxonomy-image-btn');
            
            // If the uploader object has already been created, reopen it
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'اختر صورة',
                button: {
                    text: 'استخدم هذه الصورة'
                },
                multiple: false
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Set the image ID
                imageInput.val(attachment.id);
                
                // Set the image preview
                var previewUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                imagePreview.attr('src', previewUrl).show();
                
                // Show remove button
                removeButton.show();
            });
            
            // Open the uploader
            mediaUploader.open();
        });
        
        // Remove button click
        $(document).on('click', '.remove-taxonomy-image-btn', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var container = button.closest('.taxonomy-image-upload');
            var imageInput = container.find('input[name=\"taxonomy_image\"]');
            var imagePreview = container.find('.taxonomy-image-preview img');
            
            // Clear the image
            imageInput.val('');
            imagePreview.attr('src', '').hide();
            button.hide();
        });
    });";
    
    wp_add_inline_script('alqasrgroup-taxonomy-image', $js);
    wp_enqueue_script('alqasrgroup-taxonomy-image');
    
    // Add inline CSS
    $css = ".taxonomy-image-upload {
        margin: 10px 0;
    }
    
    .taxonomy-image-preview {
        margin: 10px 0;
    }
    
    .taxonomy-image-preview img {
        border: 1px solid #ddd;
        padding: 5px;
        background: #fff;
        border-radius: 3px;
        display: block;
    }
    
    .upload-taxonomy-image-btn,
    .remove-taxonomy-image-btn {
        margin-right: 5px;
        margin-top: 5px;
    }
    
    .term-image-wrap {
        background: #fff;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }";
    
    wp_add_inline_style('wp-admin', $css);
}
add_action('admin_enqueue_scripts', 'AlQasrGroup_taxonomy_image_scripts');

// Helper function to get taxonomy image
function get_taxonomy_image($term_id, $size = 'thumbnail') {
    $image_id = get_term_meta($term_id, 'taxonomy_image', true);
    
    if ($image_id) {
        return wp_get_attachment_image_url($image_id, $size);
    }
    
    return false;
}

// Helper function to get taxonomy image ID
function get_taxonomy_image_id($term_id) {
    return get_term_meta($term_id, 'taxonomy_image', true);
}

// Helper function to get taxonomy name based on current language
function get_taxonomy_name($term_id, $language = null) {
    if ($language === null) {
        $language = is_english_version() ? 'en' : 'ar';
    }
    
    if ($language === 'en') {
        $name_en = get_term_meta($term_id, 'term_name_en', true);
        if (!empty($name_en)) {
            return $name_en;
        }
    }
    
    // Fallback to default name (Arabic)
    $term = get_term($term_id);
    return $term ? $term->name : '';
}

// Helper function to get taxonomy description based on current language
function get_taxonomy_description($term_id, $language = null) {
    if ($language === null) {
        $language = is_english_version() ? 'en' : 'ar';
    }
    
    if ($language === 'en') {
        $description_en = get_term_meta($term_id, 'term_description_en', true);
        if (!empty($description_en)) {
            return $description_en;
        }
    }
    
    // Fallback to default description (Arabic)
    $term = get_term($term_id);
    return $term ? $term->description : '';
}

// Add image column to taxonomy list table
function AlQasrGroup_add_taxonomy_image_column($columns) {
    $columns['taxonomy_image'] = __('الصورة', 'borma');
    return $columns;
}
add_filter('manage_edit-project_type_columns', 'AlQasrGroup_add_taxonomy_image_column');
add_filter('manage_edit-city_columns', 'AlQasrGroup_add_taxonomy_image_column');

// Display image in taxonomy list table
function AlQasrGroup_taxonomy_image_column_content($content, $column_name, $term_id) {
    if ($column_name === 'taxonomy_image') {
        $image_id = get_term_meta($term_id, 'taxonomy_image', true);
        
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            if ($image_url) {
                $content = '<img src="' . esc_url($image_url) . '" style="max-width: 50px; height: auto;" />';
            }
        } else {
            $content = '—';
        }
    }
    
    return $content;
}
add_filter('manage_project_type_custom_column', 'AlQasrGroup_taxonomy_image_column_content', 10, 3);
add_filter('manage_city_custom_column', 'AlQasrGroup_taxonomy_image_column_content', 10, 3);

