<?php
/**
 * About Page Settings
 * إعدادات صفحة من نحن
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu for About settings
function AlQasrGroup_add_page_about_settings_menu() {
    add_menu_page(
        'إعدادات من نحن',
        'إعدادات من نحن',
        'manage_options',
        'AlQasrGroup-page-about-settings',
        'AlQasrGroup_page_about_settings_page',
        'dashicons-groups',
        31
    );
}
add_action('admin_menu', 'AlQasrGroup_add_page_about_settings_menu', 11);

// Enqueue admin scripts for About settings
function AlQasrGroup_page_about_admin_scripts($hook) {
    if ($hook != 'toplevel_page_AlQasrGroup-page-about-settings') {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('AlQasrGroup-about-admin', get_template_directory_uri() . '/assets/js/admin-about.js', array('jquery', 'media-upload', 'jquery-ui-sortable'), '1.0', true);
    wp_localize_script('AlQasrGroup-about-admin', 'AlQasrGroup_about_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('AlQasrGroup_page_about_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'AlQasrGroup_page_about_admin_scripts');

// Save About page settings
function AlQasrGroup_save_page_about_settings() {
    if (!current_user_can('manage_options') || !isset($_POST['AlQasrGroup_page_about_nonce']) || !wp_verify_nonce($_POST['AlQasrGroup_page_about_nonce'], 'AlQasrGroup_page_about_settings_nonce')) {
        return;
    }
    
    // Hero Section
    if (isset($_POST['about_hero_title_ar'])) {
        update_option('about_hero_title_ar', sanitize_text_field($_POST['about_hero_title_ar']));
    }
    if (isset($_POST['about_hero_title_en'])) {
        update_option('about_hero_title_en', sanitize_text_field($_POST['about_hero_title_en']));
    }
    if (isset($_POST['about_hero_description_ar'])) {
        update_option('about_hero_description_ar', sanitize_textarea_field($_POST['about_hero_description_ar']));
    }
    if (isset($_POST['about_hero_description_en'])) {
        update_option('about_hero_description_en', sanitize_textarea_field($_POST['about_hero_description_en']));
    }
    if (isset($_POST['about_hero_background'])) {
        update_option('about_hero_background', esc_url_raw($_POST['about_hero_background']));
    }
    
    // About Us Section
    if (isset($_POST['about_us_subtitle_ar'])) {
        update_option('about_us_subtitle_ar', sanitize_text_field($_POST['about_us_subtitle_ar']));
    }
    if (isset($_POST['about_us_subtitle_en'])) {
        update_option('about_us_subtitle_en', sanitize_text_field($_POST['about_us_subtitle_en']));
    }
    if (isset($_POST['about_us_title_ar'])) {
        update_option('about_us_title_ar', sanitize_text_field($_POST['about_us_title_ar']));
    }
    if (isset($_POST['about_us_title_en'])) {
        update_option('about_us_title_en', sanitize_text_field($_POST['about_us_title_en']));
    }
    if (isset($_POST['about_us_description_ar'])) {
        update_option('about_us_description_ar', sanitize_textarea_field($_POST['about_us_description_ar']));
    }
    if (isset($_POST['about_us_description_en'])) {
        update_option('about_us_description_en', sanitize_textarea_field($_POST['about_us_description_en']));
    }
    if (isset($_POST['about_us_image_1'])) {
        update_option('about_us_image_1', esc_url_raw($_POST['about_us_image_1']));
    }
    if (isset($_POST['about_us_image_2'])) {
        update_option('about_us_image_2', esc_url_raw($_POST['about_us_image_2']));
    }
    
    // Why Choose Us - Identity Section
    if (isset($_POST['why_choose_identity_items'])) {
        $identity_items = array();
        if (is_array($_POST['why_choose_identity_items'])) {
            foreach ($_POST['why_choose_identity_items'] as $item) {
                if (!empty($item['icon']) || !empty($item['title_ar']) || !empty($item['title_en'])) {
                    $identity_items[] = array(
                        'icon' => esc_url_raw($item['icon']),
                        'title_ar' => sanitize_text_field($item['title_ar']),
                        'title_en' => sanitize_text_field($item['title_en']),
                        'description_ar' => sanitize_textarea_field($item['description_ar']),
                        'description_en' => sanitize_textarea_field($item['description_en'])
                    );
                }
            }
        }
        update_option('why_choose_identity_items', $identity_items);
    }
    
    // Why Choose Us - What Makes Us Different Section
    if (isset($_POST['why_choose_subtitle_ar'])) {
        update_option('why_choose_subtitle_ar', sanitize_text_field($_POST['why_choose_subtitle_ar']));
    }
    if (isset($_POST['why_choose_subtitle_en'])) {
        update_option('why_choose_subtitle_en', sanitize_text_field($_POST['why_choose_subtitle_en']));
    }
    if (isset($_POST['why_choose_title_ar'])) {
        update_option('why_choose_title_ar', sanitize_text_field($_POST['why_choose_title_ar']));
    }
    if (isset($_POST['why_choose_title_en'])) {
        update_option('why_choose_title_en', sanitize_text_field($_POST['why_choose_title_en']));
    }
    
    if (isset($_POST['why_choose_different_items'])) {
        $different_items = array();
        if (is_array($_POST['why_choose_different_items'])) {
            foreach ($_POST['why_choose_different_items'] as $item) {
                if (!empty($item['image']) || !empty($item['title_ar']) || !empty($item['title_en'])) {
                    $different_items[] = array(
                        'image' => esc_url_raw($item['image']),
                        'title_ar' => sanitize_text_field($item['title_ar']),
                        'title_en' => sanitize_text_field($item['title_en']),
                        'description_ar' => sanitize_textarea_field($item['description_ar']),
                        'description_en' => sanitize_textarea_field($item['description_en'])
                    );
                }
            }
        }
        update_option('why_choose_different_items', $different_items);
    }
    
    // Our Values Section
    if (isset($_POST['our_values_subtitle_ar'])) {
        update_option('our_values_subtitle_ar', sanitize_text_field($_POST['our_values_subtitle_ar']));
    }
    if (isset($_POST['our_values_subtitle_en'])) {
        update_option('our_values_subtitle_en', sanitize_text_field($_POST['our_values_subtitle_en']));
    }
    if (isset($_POST['our_values_title_ar'])) {
        update_option('our_values_title_ar', sanitize_text_field($_POST['our_values_title_ar']));
    }
    if (isset($_POST['our_values_title_en'])) {
        update_option('our_values_title_en', sanitize_text_field($_POST['our_values_title_en']));
    }
    if (isset($_POST['our_values_background'])) {
        update_option('our_values_background', esc_url_raw($_POST['our_values_background']));
    }
    
    if (isset($_POST['our_values_items'])) {
        $values_items = array();
        if (is_array($_POST['our_values_items'])) {
            foreach ($_POST['our_values_items'] as $item) {
                if (!empty($item['title_ar']) || !empty($item['title_en'])) {
                    $values_items[] = array(
                        'title_ar' => sanitize_text_field($item['title_ar']),
                        'title_en' => sanitize_text_field($item['title_en']),
                        'description_ar' => sanitize_textarea_field($item['description_ar']),
                        'description_en' => sanitize_textarea_field($item['description_en'])
                    );
                }
            }
        }
        update_option('our_values_items', $values_items);
    }
    
    echo '<div class="notice notice-success"><p>تم حفظ الإعدادات بنجاح!</p></div>';
}

// About Settings Page HTML
function AlQasrGroup_page_about_settings_page() {
    // Handle form submission
    if (isset($_POST['submit']) && isset($_POST['AlQasrGroup_page_about_nonce'])) {
        AlQasrGroup_save_page_about_settings();
    }
    
    // Get existing values
    $hero_title_ar = get_option('about_hero_title_ar', '');
    $hero_title_en = get_option('about_hero_title_en', '');
    $hero_description_ar = get_option('about_hero_description_ar', '');
    $hero_description_en = get_option('about_hero_description_en', '');
    $hero_background = get_option('about_hero_background', '');
    
    $about_us_subtitle_ar = get_option('about_us_subtitle_ar', '');
    $about_us_subtitle_en = get_option('about_us_subtitle_en', '');
    $about_us_title_ar = get_option('about_us_title_ar', '');
    $about_us_title_en = get_option('about_us_title_en', '');
    $about_us_description_ar = get_option('about_us_description_ar', '');
    $about_us_description_en = get_option('about_us_description_en', '');
    $about_us_image_1 = get_option('about_us_image_1', '');
    $about_us_image_2 = get_option('about_us_image_2', '');
    
    $why_choose_identity_items = get_option('why_choose_identity_items', array());
    $why_choose_subtitle_ar = get_option('why_choose_subtitle_ar', '');
    $why_choose_subtitle_en = get_option('why_choose_subtitle_en', '');
    $why_choose_title_ar = get_option('why_choose_title_ar', '');
    $why_choose_title_en = get_option('why_choose_title_en', '');
    $why_choose_different_items = get_option('why_choose_different_items', array());
    
    $our_values_subtitle_ar = get_option('our_values_subtitle_ar', '');
    $our_values_subtitle_en = get_option('our_values_subtitle_en', '');
    $our_values_title_ar = get_option('our_values_title_ar', '');
    $our_values_title_en = get_option('our_values_title_en', '');
    $our_values_background = get_option('our_values_background', '');
    $our_values_items = get_option('our_values_items', array());
    
    ?>
    <div class="wrap about-settings-wrap">
        <h1 class="wp-heading-inline">إعدادات صفحة من نحن</h1>
        
        <form method="post" action="" id="about-settings-form">
            <?php wp_nonce_field('AlQasrGroup_page_about_settings_nonce', 'AlQasrGroup_page_about_nonce'); ?>
            
            <div class="nav-tab-wrapper about-nav-tabs">
                <a href="#hero" class="nav-tab nav-tab-active">Hero</a>
                <a href="#about-us" class="nav-tab">من نحن</a>
                <a href="#why-choose" class="nav-tab">لماذا نحن</a>
                <a href="#our-values" class="nav-tab">قيمنا</a>
            </div>
            
            <!-- Hero Tab -->
            <div id="hero" class="tab-content about-tab-content active">
                <div class="settings-section">
                    <h2 class="section-title">قسم Hero</h2>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الرئيسي (عربي) <span class="required">*</span></label>
                            <input type="text" name="about_hero_title_ar" value="<?php echo esc_attr($hero_title_ar); ?>" class="regular-text" placeholder="أدخل العنوان الرئيسي">
                        </div>
                        <div class="settings-col">
                            <label>Main Title (English)</label>
                            <input type="text" name="about_hero_title_en" value="<?php echo esc_attr($hero_title_en); ?>" class="regular-text" placeholder="Enter main title">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>الوصف (عربي)</label>
                            <textarea name="about_hero_description_ar" rows="4" class="large-text" placeholder="أدخل الوصف"><?php echo esc_textarea($hero_description_ar); ?></textarea>
                        </div>
                        <div class="settings-col">
                            <label>Description (English)</label>
                            <textarea name="about_hero_description_en" rows="4" class="large-text" placeholder="Enter description"><?php echo esc_textarea($hero_description_en); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col-full">
                            <label>صورة الخلفية <span class="required">*</span></label>
                            <input type="hidden" name="about_hero_background" value="<?php echo esc_url($hero_background); ?>" class="image-input" id="about_hero_background">
                            <button type="button" class="button button-primary upload-image-btn" data-target="about_hero_background">رفع صورة الخلفية</button>
                            <div class="image-preview" id="about_hero_background_preview">
                                <?php if ($hero_background): ?>
                                    <img src="<?php echo esc_url($hero_background); ?>" style="max-width: 300px; height: auto; margin-top: 10px; border-radius: 8px;">
                                    <br><button type="button" class="button button-small remove-image-btn" data-target="about_hero_background">حذف الصورة</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- About Us Tab -->
            <div id="about-us" class="tab-content about-tab-content" style="display:none;">
                <div class="settings-section">
                    <h2 class="section-title">قسم من نحن</h2>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الصغير (عربي)</label>
                            <input type="text" name="about_us_subtitle_ar" value="<?php echo esc_attr($about_us_subtitle_ar); ?>" class="regular-text" placeholder="أدخل العنوان الصغير">
                        </div>
                        <div class="settings-col">
                            <label>Subtitle (English)</label>
                            <input type="text" name="about_us_subtitle_en" value="<?php echo esc_attr($about_us_subtitle_en); ?>" class="regular-text" placeholder="Enter subtitle">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الرئيسي (عربي) <span class="required">*</span></label>
                            <input type="text" name="about_us_title_ar" value="<?php echo esc_attr($about_us_title_ar); ?>" class="regular-text" placeholder="أدخل العنوان الرئيسي">
                        </div>
                        <div class="settings-col">
                            <label>Main Title (English)</label>
                            <input type="text" name="about_us_title_en" value="<?php echo esc_attr($about_us_title_en); ?>" class="regular-text" placeholder="Enter main title">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>الوصف (عربي)</label>
                            <textarea name="about_us_description_ar" rows="5" class="large-text" placeholder="أدخل الوصف"><?php echo esc_textarea($about_us_description_ar); ?></textarea>
                        </div>
                        <div class="settings-col">
                            <label>Description (English)</label>
                            <textarea name="about_us_description_en" rows="5" class="large-text" placeholder="Enter description"><?php echo esc_textarea($about_us_description_en); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>الصورة الأولى <span class="required">*</span></label>
                            <input type="hidden" name="about_us_image_1" value="<?php echo esc_url($about_us_image_1); ?>" class="image-input" id="about_us_image_1">
                            <button type="button" class="button button-primary upload-image-btn" data-target="about_us_image_1">رفع الصورة الأولى</button>
                            <div class="image-preview" id="about_us_image_1_preview">
                                <?php if ($about_us_image_1): ?>
                                    <img src="<?php echo esc_url($about_us_image_1); ?>" style="max-width: 200px; height: auto; margin-top: 10px; border-radius: 8px;">
                                    <br><button type="button" class="button button-small remove-image-btn" data-target="about_us_image_1">حذف الصورة</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="settings-col">
                            <label>الصورة الثانية <span class="required">*</span></label>
                            <input type="hidden" name="about_us_image_2" value="<?php echo esc_url($about_us_image_2); ?>" class="image-input" id="about_us_image_2">
                            <button type="button" class="button button-primary upload-image-btn" data-target="about_us_image_2">رفع الصورة الثانية</button>
                            <div class="image-preview" id="about_us_image_2_preview">
                                <?php if ($about_us_image_2): ?>
                                    <img src="<?php echo esc_url($about_us_image_2); ?>" style="max-width: 200px; height: auto; margin-top: 10px; border-radius: 8px;">
                                    <br><button type="button" class="button button-small remove-image-btn" data-target="about_us_image_2">حذف الصورة</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Why Choose Us Tab -->
            <div id="why-choose" class="tab-content about-tab-content" style="display:none;">
                <!-- Identity Section -->
                <div class="settings-section">
                    <h2 class="section-title">هويتنا</h2>
                    
                    <div id="identity-items-container" class="dynamic-items-container">
                        <?php if (!empty($why_choose_identity_items)): ?>
                            <?php foreach ($why_choose_identity_items as $index => $item): ?>
                                <div class="dynamic-item" data-index="<?php echo $index; ?>">
                                    <div class="item-header">
                                        <span class="item-number"><?php echo $index + 1; ?></span>
                                        <button type="button" class="button button-small remove-item-btn">حذف</button>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>الأيقونة</label>
                                            <input type="hidden" name="why_choose_identity_items[<?php echo $index; ?>][icon]" value="<?php echo esc_url($item['icon'] ?? ''); ?>" class="image-input">
                                            <button type="button" class="button button-secondary upload-image-btn" data-target="why_choose_identity_items[<?php echo $index; ?>][icon]">رفع الأيقونة</button>
                                            <div class="image-preview">
                                                <?php if (!empty($item['icon'])): ?>
                                                    <img src="<?php echo esc_url($item['icon']); ?>" style="max-width: 80px; height: auto; margin-top: 10px; border-radius: 8px;">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>العنوان الرئيسي (عربي)</label>
                                            <input type="text" name="why_choose_identity_items[<?php echo $index; ?>][title_ar]" value="<?php echo esc_attr($item['title_ar'] ?? ''); ?>" class="regular-text">
                                        </div>
                                        <div class="settings-col">
                                            <label>Main Title (English)</label>
                                            <input type="text" name="why_choose_identity_items[<?php echo $index; ?>][title_en]" value="<?php echo esc_attr($item['title_en'] ?? ''); ?>" class="regular-text">
                                        </div>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>الوصف (عربي)</label>
                                            <textarea name="why_choose_identity_items[<?php echo $index; ?>][description_ar]" rows="3" class="large-text"><?php echo esc_textarea($item['description_ar'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="settings-col">
                                            <label>Description (English)</label>
                                            <textarea name="why_choose_identity_items[<?php echo $index; ?>][description_en]" rows="3" class="large-text"><?php echo esc_textarea($item['description_en'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-primary add-item-btn" data-container="identity-items-container" data-template="identity-item-template">+ إضافة عنصر جديد</button>
                </div>
                
                <hr class="section-divider">
                
                <!-- What Makes Us Different Section -->
                <div class="settings-section">
                    <h2 class="section-title">الذي يميزنا</h2>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الصغير (عربي)</label>
                            <input type="text" name="why_choose_subtitle_ar" value="<?php echo esc_attr($why_choose_subtitle_ar); ?>" class="regular-text" placeholder="أدخل العنوان الصغير">
                        </div>
                        <div class="settings-col">
                            <label>Subtitle (English)</label>
                            <input type="text" name="why_choose_subtitle_en" value="<?php echo esc_attr($why_choose_subtitle_en); ?>" class="regular-text" placeholder="Enter subtitle">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الرئيسي (عربي) <span class="required">*</span></label>
                            <input type="text" name="why_choose_title_ar" value="<?php echo esc_attr($why_choose_title_ar); ?>" class="regular-text" placeholder="أدخل العنوان الرئيسي">
                        </div>
                        <div class="settings-col">
                            <label>Main Title (English)</label>
                            <input type="text" name="why_choose_title_en" value="<?php echo esc_attr($why_choose_title_en); ?>" class="regular-text" placeholder="Enter main title">
                        </div>
                    </div>
                    
                    <div id="different-items-container" class="dynamic-items-container">
                        <?php if (!empty($why_choose_different_items)): ?>
                            <?php foreach ($why_choose_different_items as $index => $item): ?>
                                <div class="dynamic-item" data-index="<?php echo $index; ?>">
                                    <div class="item-header">
                                        <span class="item-number"><?php echo $index + 1; ?></span>
                                        <button type="button" class="button button-small remove-item-btn">حذف</button>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col-full">
                                            <label>الصورة</label>
                                            <input type="hidden" name="why_choose_different_items[<?php echo $index; ?>][image]" value="<?php echo esc_url($item['image'] ?? ''); ?>" class="image-input">
                                            <button type="button" class="button button-secondary upload-image-btn" data-target="why_choose_different_items[<?php echo $index; ?>][image]">رفع الصورة</button>
                                            <div class="image-preview">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?php echo esc_url($item['image']); ?>" style="max-width: 200px; height: auto; margin-top: 10px; border-radius: 8px;">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>العنوان الرئيسي (عربي)</label>
                                            <input type="text" name="why_choose_different_items[<?php echo $index; ?>][title_ar]" value="<?php echo esc_attr($item['title_ar'] ?? ''); ?>" class="regular-text">
                                        </div>
                                        <div class="settings-col">
                                            <label>Main Title (English)</label>
                                            <input type="text" name="why_choose_different_items[<?php echo $index; ?>][title_en]" value="<?php echo esc_attr($item['title_en'] ?? ''); ?>" class="regular-text">
                                        </div>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>الوصف (عربي)</label>
                                            <textarea name="why_choose_different_items[<?php echo $index; ?>][description_ar]" rows="3" class="large-text"><?php echo esc_textarea($item['description_ar'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="settings-col">
                                            <label>Description (English)</label>
                                            <textarea name="why_choose_different_items[<?php echo $index; ?>][description_en]" rows="3" class="large-text"><?php echo esc_textarea($item['description_en'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-primary add-item-btn" data-container="different-items-container" data-template="different-item-template">+ إضافة عنصر جديد</button>
                </div>
            </div>
            
            <!-- Our Values Tab -->
            <div id="our-values" class="tab-content about-tab-content" style="display:none;">
                <div class="settings-section">
                    <h2 class="section-title">قسم قيمنا</h2>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الصغير (عربي)</label>
                            <input type="text" name="our_values_subtitle_ar" value="<?php echo esc_attr($our_values_subtitle_ar); ?>" class="regular-text" placeholder="أدخل العنوان الصغير">
                        </div>
                        <div class="settings-col">
                            <label>Subtitle (English)</label>
                            <input type="text" name="our_values_subtitle_en" value="<?php echo esc_attr($our_values_subtitle_en); ?>" class="regular-text" placeholder="Enter subtitle">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col">
                            <label>العنوان الرئيسي (عربي) <span class="required">*</span></label>
                            <input type="text" name="our_values_title_ar" value="<?php echo esc_attr($our_values_title_ar); ?>" class="regular-text" placeholder="أدخل العنوان الرئيسي">
                        </div>
                        <div class="settings-col">
                            <label>Main Title (English)</label>
                            <input type="text" name="our_values_title_en" value="<?php echo esc_attr($our_values_title_en); ?>" class="regular-text" placeholder="Enter main title">
                        </div>
                    </div>
                    
                    <div class="settings-row">
                        <div class="settings-col-full">
                            <label>صورة الخلفية <span class="required">*</span></label>
                            <input type="hidden" name="our_values_background" value="<?php echo esc_url($our_values_background); ?>" class="image-input" id="our_values_background">
                            <button type="button" class="button button-primary upload-image-btn" data-target="our_values_background">رفع صورة الخلفية</button>
                            <div class="image-preview" id="our_values_background_preview">
                                <?php if ($our_values_background): ?>
                                    <img src="<?php echo esc_url($our_values_background); ?>" style="max-width: 300px; height: auto; margin-top: 10px; border-radius: 8px;">
                                    <br><button type="button" class="button button-small remove-image-btn" data-target="our_values_background">حذف الصورة</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="section-divider">
                    
                    <h3 class="subsection-title">عناصر القيم</h3>
                    
                    <div id="values-items-container" class="dynamic-items-container">
                        <?php if (!empty($our_values_items)): ?>
                            <?php foreach ($our_values_items as $index => $item): ?>
                                <div class="dynamic-item" data-index="<?php echo $index; ?>">
                                    <div class="item-header">
                                        <span class="item-number"><?php echo $index + 1; ?></span>
                                        <button type="button" class="button button-small remove-item-btn">حذف</button>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>العنوان الرئيسي (عربي)</label>
                                            <input type="text" name="our_values_items[<?php echo $index; ?>][title_ar]" value="<?php echo esc_attr($item['title_ar'] ?? ''); ?>" class="regular-text">
                                        </div>
                                        <div class="settings-col">
                                            <label>Main Title (English)</label>
                                            <input type="text" name="our_values_items[<?php echo $index; ?>][title_en]" value="<?php echo esc_attr($item['title_en'] ?? ''); ?>" class="regular-text">
                                        </div>
                                    </div>
                                    <div class="settings-row">
                                        <div class="settings-col">
                                            <label>الوصف (عربي)</label>
                                            <textarea name="our_values_items[<?php echo $index; ?>][description_ar]" rows="3" class="large-text"><?php echo esc_textarea($item['description_ar'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="settings-col">
                                            <label>Description (English)</label>
                                            <textarea name="our_values_items[<?php echo $index; ?>][description_en]" rows="3" class="large-text"><?php echo esc_textarea($item['description_en'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="button button-primary add-item-btn" data-container="values-items-container" data-template="values-item-template">+ إضافة عنصر جديد</button>
                </div>
            </div>
            
            <?php submit_button('حفظ الإعدادات', 'primary', 'submit', false); ?>
        </form>
    </div>
    
    <!-- Templates for dynamic items -->
    <script type="text/template" id="identity-item-template">
        <div class="dynamic-item">
            <div class="item-header">
                <span class="item-number"></span>
                <button type="button" class="button button-small remove-item-btn">حذف</button>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>الأيقونة</label>
                    <input type="hidden" name="why_choose_identity_items[INDEX][icon]" value="" class="image-input">
                    <button type="button" class="button button-secondary upload-image-btn" data-target="why_choose_identity_items[INDEX][icon]">رفع الأيقونة</button>
                    <div class="image-preview"></div>
                </div>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>العنوان الرئيسي (عربي)</label>
                    <input type="text" name="why_choose_identity_items[INDEX][title_ar]" value="" class="regular-text">
                </div>
                <div class="settings-col">
                    <label>Main Title (English)</label>
                    <input type="text" name="why_choose_identity_items[INDEX][title_en]" value="" class="regular-text">
                </div>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>الوصف (عربي)</label>
                    <textarea name="why_choose_identity_items[INDEX][description_ar]" rows="3" class="large-text"></textarea>
                </div>
                <div class="settings-col">
                    <label>Description (English)</label>
                    <textarea name="why_choose_identity_items[INDEX][description_en]" rows="3" class="large-text"></textarea>
                </div>
            </div>
        </div>
    </script>
    
    <script type="text/template" id="different-item-template">
        <div class="dynamic-item">
            <div class="item-header">
                <span class="item-number"></span>
                <button type="button" class="button button-small remove-item-btn">حذف</button>
            </div>
            <div class="settings-row">
                <div class="settings-col-full">
                    <label>الصورة</label>
                    <input type="hidden" name="why_choose_different_items[INDEX][image]" value="" class="image-input">
                    <button type="button" class="button button-secondary upload-image-btn" data-target="why_choose_different_items[INDEX][image]">رفع الصورة</button>
                    <div class="image-preview"></div>
                </div>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>العنوان الرئيسي (عربي)</label>
                    <input type="text" name="why_choose_different_items[INDEX][title_ar]" value="" class="regular-text">
                </div>
                <div class="settings-col">
                    <label>Main Title (English)</label>
                    <input type="text" name="why_choose_different_items[INDEX][title_en]" value="" class="regular-text">
                </div>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>الوصف (عربي)</label>
                    <textarea name="why_choose_different_items[INDEX][description_ar]" rows="3" class="large-text"></textarea>
                </div>
                <div class="settings-col">
                    <label>Description (English)</label>
                    <textarea name="why_choose_different_items[INDEX][description_en]" rows="3" class="large-text"></textarea>
                </div>
            </div>
        </div>
    </script>
    
    <script type="text/template" id="values-item-template">
        <div class="dynamic-item">
            <div class="item-header">
                <span class="item-number"></span>
                <button type="button" class="button button-small remove-item-btn">حذف</button>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>العنوان الرئيسي (عربي)</label>
                    <input type="text" name="our_values_items[INDEX][title_ar]" value="" class="regular-text">
                </div>
                <div class="settings-col">
                    <label>Main Title (English)</label>
                    <input type="text" name="our_values_items[INDEX][title_en]" value="" class="regular-text">
                </div>
            </div>
            <div class="settings-row">
                <div class="settings-col">
                    <label>الوصف (عربي)</label>
                    <textarea name="our_values_items[INDEX][description_ar]" rows="3" class="large-text"></textarea>
                </div>
                <div class="settings-col">
                    <label>Description (English)</label>
                    <textarea name="our_values_items[INDEX][description_en]" rows="3" class="large-text"></textarea>
                </div>
            </div>
        </div>
    </script>
    
    <style>
    .about-settings-wrap {
        max-width: 1400px;
    }
    
    .about-nav-tabs {
        margin: 20px 0;
        border-bottom: 2px solid #2271b1;
    }
    
    .about-nav-tabs .nav-tab {
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 5px;
        border-radius: 4px 4px 0 0;
        transition: all 0.3s ease;
    }
    
    .about-nav-tabs .nav-tab:hover {
        background: #f0f0f1;
    }
    
    .about-nav-tabs .nav-tab-active {
        background: #2271b1;
        color: #fff;
        border-color: #2271b1;
    }
    
    .about-tab-content {
        background: #fff;
        padding: 30px;
        border: 1px solid #c3c4c7;
        border-top: none;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    
    .about-tab-content.active {
        display: block;
    }
    
    .settings-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1d2327;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2271b1;
    }
    
    .subsection-title {
        font-size: 18px;
        font-weight: 600;
        color: #1d2327;
        margin: 30px 0 20px;
    }
    
    .settings-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .settings-col {
        flex: 1;
        min-width: 300px;
    }
    
    .settings-col-full {
        flex: 1 1 100%;
    }
    
    .settings-section label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1d2327;
        font-size: 13px;
    }
    
    .settings-section .required {
        color: #d63638;
    }
    
    .settings-section input[type="text"],
    .settings-section textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    
    .settings-section input[type="text"]:focus,
    .settings-section textarea:focus {
        border-color: #2271b1;
        outline: none;
        box-shadow: 0 0 0 1px #2271b1;
    }
    
    .image-preview {
        margin-top: 10px;
    }
    
    .image-preview img {
        border: 2px solid #c3c4c7;
        border-radius: 8px;
        padding: 5px;
        background: #f6f7f7;
    }
    
    .section-divider {
        margin: 40px 0;
        border: none;
        border-top: 2px solid #dcdcde;
    }
    
    .dynamic-items-container {
        margin: 20px 0;
    }
    
    .dynamic-item {
        background: #f6f7f7;
        border: 1px solid #dcdcde;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .dynamic-item:hover {
        border-color: #2271b1;
        box-shadow: 0 2px 8px rgba(34, 113, 177, 0.1);
    }
    
    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dcdcde;
    }
    
    .item-number {
        font-weight: 600;
        color: #2271b1;
        font-size: 16px;
    }
    
    .add-item-btn {
        margin-top: 15px;
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .remove-item-btn {
        background: #d63638;
        color: #fff;
        border-color: #d63638;
    }
    
    .remove-item-btn:hover {
        background: #b32d2e;
        border-color: #b32d2e;
    }
    
    .upload-image-btn {
        margin-bottom: 10px;
    }
    
    @media (max-width: 782px) {
        .settings-col {
            min-width: 100%;
        }
    }
    </style>
    <?php
}

