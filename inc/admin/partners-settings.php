<?php

/**
 * Partners Settings
 * إعدادات شركاؤنا
 */

// Register Partners settings
function AlQasrGroup_register_partners_settings() {
    register_setting('AlQasrGroup_settings', 'partners_section_title');
    register_setting('AlQasrGroup_settings', 'partners_section_title_en');
    register_setting('AlQasrGroup_settings', 'partners_section_description');
    register_setting('AlQasrGroup_settings', 'partners_section_description_en');
    register_setting('AlQasrGroup_settings', 'partners_list');
}
add_action('admin_init', 'AlQasrGroup_register_partners_settings');

// Save Partners settings
function AlQasrGroup_save_partners_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Save section title and description
    if (isset($_POST['partners_section_title'])) {
        update_option('partners_section_title', sanitize_text_field($_POST['partners_section_title']));
    }

    if (isset($_POST['partners_section_title_en'])) {
        update_option('partners_section_title_en', sanitize_text_field($_POST['partners_section_title_en']));
    }
    
    if (isset($_POST['partners_section_description'])) {
        update_option('partners_section_description', sanitize_textarea_field($_POST['partners_section_description']));
    }

    if (isset($_POST['partners_section_description_en'])) {
        update_option('partners_section_description_en', sanitize_textarea_field($_POST['partners_section_description_en']));
    }
    
    // Save partners list
    if (isset($_POST['partners_list']) && is_array($_POST['partners_list'])) {
        $partners = array();
        foreach ($_POST['partners_list'] as $partner) {
            $name = isset($partner['name']) ? sanitize_text_field($partner['name']) : '';
            $image = isset($partner['image']) ? esc_url_raw($partner['image']) : '';

            if (!empty($name) || !empty($image)) {
                $partners[] = array(
                    'name'  => $name,
                    'image' => $image
                );
            }
        }
        update_option('partners_list', $partners);
    } else {
        update_option('partners_list', array());
    }
}

// Partners settings HTML
function AlQasrGroup_partners_settings_html() {
    $partners_section_title = get_option('partners_section_title', 'شركاؤنا');
    $partners_section_title_en = get_option('partners_section_title_en', '');
    $partners_section_description = get_option('partners_section_description', '');
    $partners_section_description_en = get_option('partners_section_description_en', '');
    $partners_list = get_option('partners_list', array());
    
    if (empty($partners_list)) {
        $partners_list = array(array('name' => '', 'image' => ''));
    }
    
    ?>
    <h2>شركاؤنا</h2>
    <table class="form-table">
        <tr>
            <th scope="row">العنوان الرئيسي</th>
            <td>
                <input type="text" name="partners_section_title" value="<?php echo esc_attr($partners_section_title); ?>" class="regular-text" />
                <p class="description">العنوان الرئيسي لقسم الشركاء</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Main Title (English)</th>
            <td>
                <input type="text" name="partners_section_title_en" value="<?php echo esc_attr($partners_section_title_en); ?>" class="regular-text" />
                <p class="description">English title displayed in the partners section</p>
            </td>
        </tr>
        <tr>
            <th scope="row">الوصف</th>
            <td>
                <textarea name="partners_section_description" rows="3" class="large-text"><?php echo esc_textarea($partners_section_description); ?></textarea>
                <p class="description">وصف مختصر لقسم الشركاء</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Description (English)</th>
            <td>
                <textarea name="partners_section_description_en" rows="3" class="large-text"><?php echo esc_textarea($partners_section_description_en); ?></textarea>
                <p class="description">English description for the partners section</p>
            </td>
        </tr>
    </table>
    
    <h3>قائمة الشركاء</h3>
    <div id="partners-list">
        <?php foreach ($partners_list as $index => $partner): ?>
        <div class="partner-item" data-index="<?php echo $index; ?>" style="background: #f9f9f9; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
            <table class="form-table">
                <tr>
                    <th scope="row">اسم الشريك</th>
                    <td>
                        <input type="text" name="partners_list[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name']); ?>" class="regular-text" placeholder="اسم الشريك" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">صورة الشريك</th>
                    <td>
                        <input type="hidden" name="partners_list[<?php echo $index; ?>][image]" value="<?php echo esc_attr($partner['image']); ?>" class="image-field partner-image" />
                        <button type="button" class="button upload-image-btn" data-target="partners_list[<?php echo $index; ?>][image]">اختر صورة</button>
                        <button type="button" class="button remove-image-btn" data-target="partners_list[<?php echo $index; ?>][image]" style="<?php echo $partner['image'] ? 'display: inline-block;' : 'display: none;'; ?>">حذف الصورة</button>
                        <button type="button" class="button button-link-delete remove-partner-btn" style="color: #b32d2e; margin-left: 10px;">حذف الشريك</button>
                        <div class="image-preview partner-image-preview" id="partners_list[<?php echo $index; ?>][image]_preview">
                            <?php if ($partner['image']): ?>
                                <img src="<?php echo esc_url($partner['image']); ?>" style="max-width: 150px; height: auto; margin-top: 10px;" />
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
    
    <p>
        <button type="button" class="button" id="add-partner-btn">+ إضافة شريك جديد</button>
    </p>
    
    <script>
    jQuery(document).ready(function($) {
        var partnerIndex = <?php echo count($partners_list); ?>;
        
        // Add new partner
        $('#add-partner-btn').on('click', function() {
            var partnerHtml = '<div class="partner-item" data-index="' + partnerIndex + '" style="background: #f9f9f9; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">' +
                '<table class="form-table">' +
                '<tr><th scope="row">اسم الشريك</th>' +
                '<td><input type="text" name="partners_list[' + partnerIndex + '][name]" value="" class="regular-text" placeholder="اسم الشريك" /></td></tr>' +
                '<tr><th scope="row">صورة الشريك</th>' +
                '<td>' +
                '<input type="hidden" name="partners_list[' + partnerIndex + '][image]" value="" class="image-field partner-image" />' +
                '<button type="button" class="button upload-image-btn" data-target="partners_list[' + partnerIndex + '][image]">اختر صورة</button> ' +
                '<button type="button" class="button remove-image-btn" data-target="partners_list[' + partnerIndex + '][image]" style="display: none;">حذف الصورة</button> ' +
                '<button type="button" class="button button-link-delete remove-partner-btn" style="color: #b32d2e; margin-left: 10px;">حذف الشريك</button>' +
                '<div class="image-preview partner-image-preview" id="partners_list[' + partnerIndex + '][image]_preview"></div>' +
                '</td></tr>' +
                '</table>' +
                '</div>';
            
            $('#partners-list').append(partnerHtml);
            partnerIndex++;
        });
        
        // Remove partner
        $(document).on('click', '.remove-partner-btn', function() {
            $(this).closest('.partner-item').remove();
        });
    });
    </script>
    
    <style>
    .partner-item {
        position: relative;
    }
    .partner-item .form-table th {
        width: 150px;
    }
    </style>
    <?php
}
