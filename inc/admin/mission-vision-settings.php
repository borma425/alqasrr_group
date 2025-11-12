<?php
// Mission, Vision, Value Settings (Bilingual)

if (!defined('ABSPATH')) {
    exit;
}

// Register settings
function AlQasrGroup_register_mission_vision_settings() {
    // Mission Settings
    register_setting('AlQasrGroup_settings', 'mission_title_ar');
    register_setting('AlQasrGroup_settings', 'mission_title_en');
    register_setting('AlQasrGroup_settings', 'mission_content_ar');
    register_setting('AlQasrGroup_settings', 'mission_content_en');
    register_setting('AlQasrGroup_settings', 'mission_hero_image');
    register_setting('AlQasrGroup_settings', 'mission_hex_image');
    register_setting('AlQasrGroup_settings', 'mission_icon_image');
    
    // Vision Settings
    register_setting('AlQasrGroup_settings', 'vision_title_ar');
    register_setting('AlQasrGroup_settings', 'vision_title_en');
    register_setting('AlQasrGroup_settings', 'vision_content_ar');
    register_setting('AlQasrGroup_settings', 'vision_content_en');
    register_setting('AlQasrGroup_settings', 'vision_hero_image');
    register_setting('AlQasrGroup_settings', 'vision_hex_image');
    register_setting('AlQasrGroup_settings', 'vision_icon_image');
    
    // Value Settings
    register_setting('AlQasrGroup_settings', 'value_title_ar');
    register_setting('AlQasrGroup_settings', 'value_title_en');
    register_setting('AlQasrGroup_settings', 'value_content_ar');
    register_setting('AlQasrGroup_settings', 'value_content_en');
    register_setting('AlQasrGroup_settings', 'value_hero_image');
    register_setting('AlQasrGroup_settings', 'value_hex_image');
    register_setting('AlQasrGroup_settings', 'value_icon_image');
    
    // Section Title
    register_setting('AlQasrGroup_settings', 'mission_section_title_ar');
    register_setting('AlQasrGroup_settings', 'mission_section_title_en');
}
add_action('admin_init', 'AlQasrGroup_register_mission_vision_settings');

// Save settings
function AlQasrGroup_save_mission_vision_settings() {
    // Mission
    if (isset($_POST['mission_title_ar'])) {
        update_option('mission_title_ar', sanitize_text_field($_POST['mission_title_ar']));
    }
    if (isset($_POST['mission_title_en'])) {
        update_option('mission_title_en', sanitize_text_field($_POST['mission_title_en']));
    }
    if (isset($_POST['mission_content_ar'])) {
        update_option('mission_content_ar', sanitize_textarea_field($_POST['mission_content_ar']));
    }
    if (isset($_POST['mission_content_en'])) {
        update_option('mission_content_en', sanitize_textarea_field($_POST['mission_content_en']));
    }
    if (isset($_POST['mission_hero_image'])) {
        update_option('mission_hero_image', sanitize_text_field($_POST['mission_hero_image']));
    }
    if (isset($_POST['mission_hex_image'])) {
        update_option('mission_hex_image', sanitize_text_field($_POST['mission_hex_image']));
    }
    if (isset($_POST['mission_icon_image'])) {
        update_option('mission_icon_image', sanitize_text_field($_POST['mission_icon_image']));
    }
    
    // Vision
    if (isset($_POST['vision_title_ar'])) {
        update_option('vision_title_ar', sanitize_text_field($_POST['vision_title_ar']));
    }
    if (isset($_POST['vision_title_en'])) {
        update_option('vision_title_en', sanitize_text_field($_POST['vision_title_en']));
    }
    if (isset($_POST['vision_content_ar'])) {
        update_option('vision_content_ar', sanitize_textarea_field($_POST['vision_content_ar']));
    }
    if (isset($_POST['vision_content_en'])) {
        update_option('vision_content_en', sanitize_textarea_field($_POST['vision_content_en']));
    }
    if (isset($_POST['vision_hero_image'])) {
        update_option('vision_hero_image', sanitize_text_field($_POST['vision_hero_image']));
    }
    if (isset($_POST['vision_hex_image'])) {
        update_option('vision_hex_image', sanitize_text_field($_POST['vision_hex_image']));
    }
    if (isset($_POST['vision_icon_image'])) {
        update_option('vision_icon_image', sanitize_text_field($_POST['vision_icon_image']));
    }
    
    // Value
    if (isset($_POST['value_title_ar'])) {
        update_option('value_title_ar', sanitize_text_field($_POST['value_title_ar']));
    }
    if (isset($_POST['value_title_en'])) {
        update_option('value_title_en', sanitize_text_field($_POST['value_title_en']));
    }
    if (isset($_POST['value_content_ar'])) {
        update_option('value_content_ar', sanitize_textarea_field($_POST['value_content_ar']));
    }
    if (isset($_POST['value_content_en'])) {
        update_option('value_content_en', sanitize_textarea_field($_POST['value_content_en']));
    }
    if (isset($_POST['value_hero_image'])) {
        update_option('value_hero_image', sanitize_text_field($_POST['value_hero_image']));
    }
    if (isset($_POST['value_hex_image'])) {
        update_option('value_hex_image', sanitize_text_field($_POST['value_hex_image']));
    }
    if (isset($_POST['value_icon_image'])) {
        update_option('value_icon_image', sanitize_text_field($_POST['value_icon_image']));
    }
    
    // Section Title
    if (isset($_POST['mission_section_title_ar'])) {
        update_option('mission_section_title_ar', sanitize_text_field($_POST['mission_section_title_ar']));
    }
    if (isset($_POST['mission_section_title_en'])) {
        update_option('mission_section_title_en', sanitize_text_field($_POST['mission_section_title_en']));
    }
}

// HTML Form
function AlQasrGroup_mission_vision_settings_html() {
    // Get existing values
    $mission_title_ar = get_option('mission_title_ar', 'المهمة');
    $mission_title_en = get_option('mission_title_en', 'Mission');
    $mission_content_ar = get_option('mission_content_ar', 'في تكنولوجيز التواصل، مهمتنا هي تمكين كل صوت - منطوق أو غير منطوق. من خلال إنشاء تقنيات متعاطفة وعميقة الجذور ثقافياً، نضمن عدم إسكات أي فرد بسبب الإعاقة...');
    $mission_content_en = get_option('mission_content_en', 'At Tawasol Technologies, our mission is to empower every voice—spoken or unspoken. By creating deeply empathetic and culturally-grounded technologies, we ensure no individual is silenced by disability…');
    $mission_hero_image = get_option('mission_hero_image', '');
    $mission_hex_image = get_option('mission_hex_image', '');
    $mission_icon_image = get_option('mission_icon_image', '');
    
    $vision_title_ar = get_option('vision_title_ar', 'الرؤية');
    $vision_title_en = get_option('vision_title_en', 'Vision');
    $vision_content_ar = get_option('vision_content_ar', 'نتصور عالماً حيث يكون التواصل متاحاً عالمياً، حيث لا يُترك أي فرد بلا صوت بسبب الإعاقة أو الحواجز اللغوية أو الثقافية. نبدأ بالمجتمعات الناطقة بالعربية...');
    $vision_content_en = get_option('vision_content_en', 'We envision a world where communication is universally accessible, where no individual is left voiceless due to disability, language, or cultural barriers. Starting with Arabic-speaking communities…');
    $vision_hero_image = get_option('vision_hero_image', '');
    $vision_hex_image = get_option('vision_hex_image', '');
    $vision_icon_image = get_option('vision_icon_image', '');
    
    $value_title_ar = get_option('value_title_ar', 'القيم');
    $value_title_en = get_option('value_title_en', 'Value');
    $value_content_ar = get_option('value_content_ar', 'نعتقد أن لكل شخص الحق في التعبير عن نفسه - وليس فقط أولئك الذين لديهم إمكانية الوصول أو الامتياز أو القدرة. نبني أدوات مستوحاة من عمق اللغة العربية والواقع الثقافي المعاش...');
    $value_content_en = get_option('value_content_en', 'We believe everyone has the right to express themselves—not just those with access, privilege, or ability. We build tools inspired by the depth of Arabic language and lived cultural realities…');
    $value_hero_image = get_option('value_hero_image', '');
    $value_hex_image = get_option('value_hex_image', '');
    $value_icon_image = get_option('value_icon_image', '');
    
    $mission_section_title_ar = get_option('mission_section_title_ar', 'المهمة والرؤية والقيم');
    $mission_section_title_en = get_option('mission_section_title_en', 'Mission, vision and value');
    ?>
    
    <div class="AlQasrGroup-settings-section">
        <h3>عنوان القسم الرئيسي</h3>
        <div class="row">
            <div class="col-md-6">
                <label>العنوان (عربي)</label>
                <input type="text" name="mission_section_title_ar" value="<?php echo esc_attr($mission_section_title_ar); ?>" class="regular-text" placeholder="مثال: المهمة والرؤية والقيم">
            </div>
            <div class="col-md-6">
                <label>Title (English)</label>
                <input type="text" name="mission_section_title_en" value="<?php echo esc_attr($mission_section_title_en); ?>" class="regular-text" placeholder="Example: Mission, vision and value">
            </div>
        </div>
        
        <hr>
        
        <!-- Mission -->
        <h3>قسم المهمة / Mission Section</h3>
        <div class="row">
            <div class="col-md-6">
                <h4>المحتوى العربي</h4>
                <label>عنوان المهمة</label>
                <input type="text" name="mission_title_ar" value="<?php echo esc_attr($mission_title_ar); ?>" class="regular-text" placeholder="مثال: المهمة">
                
                <label>نص المهمة</label>
                <textarea name="mission_content_ar" rows="4" class="large-text" placeholder="اكتب هنا وصف المهمة باللغة العربية..."><?php echo esc_textarea($mission_content_ar); ?></textarea>
            </div>
            <div class="col-md-6">
                <h4>English Content</h4>
                <label>Mission Title</label>
                <input type="text" name="mission_title_en" value="<?php echo esc_attr($mission_title_en); ?>" class="regular-text" placeholder="Example: Mission">
                
                <label>Mission Text</label>
                <textarea name="mission_content_en" rows="4" class="large-text" placeholder="Write the mission description in English here..."><?php echo esc_textarea($mission_content_en); ?></textarea>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4">
                <label>صورة خلفية العنوان <span style="color: red;">*</span></label>
                <input type="hidden" name="mission_hero_image" value="<?php echo esc_attr($mission_hero_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="mission_hero_image">رفع صورة خلفية العنوان</button>
                <div class="image-preview" id="mission_hero_image_preview">
                    <?php if ($mission_hero_image): ?>
                        <img src="<?php echo esc_url($mission_hero_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="mission_hero_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الشكل السداسي <span style="color: red;">*</span></label>
                <input type="hidden" name="mission_hex_image" value="<?php echo esc_attr($mission_hex_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="mission_hex_image">رفع صورة الشكل السداسي</button>
                <div class="image-preview" id="mission_hex_image_preview">
                    <?php if ($mission_hex_image): ?>
                        <img src="<?php echo esc_url($mission_hex_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="mission_hex_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الأيقونة الصغيرة <span style="color: red;">*</span></label>
                <input type="hidden" name="mission_icon_image" value="<?php echo esc_attr($mission_icon_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="mission_icon_image">رفع صورة الأيقونة</button>
                <div class="image-preview" id="mission_icon_image_preview">
                    <?php if ($mission_icon_image): ?>
                        <img src="<?php echo esc_url($mission_icon_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="mission_icon_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <hr>
        
        <!-- Vision -->
        <h3>قسم الرؤية / Vision Section</h3>
        <div class="row">
            <div class="col-md-6">
                <h4>المحتوى العربي</h4>
                <label>عنوان الرؤية</label>
                <input type="text" name="vision_title_ar" value="<?php echo esc_attr($vision_title_ar); ?>" class="regular-text" placeholder="مثال: الرؤية">
                
                <label>نص الرؤية</label>
                <textarea name="vision_content_ar" rows="4" class="large-text" placeholder="اكتب هنا وصف الرؤية باللغة العربية..."><?php echo esc_textarea($vision_content_ar); ?></textarea>
            </div>
            <div class="col-md-6">
                <h4>English Content</h4>
                <label>Vision Title</label>
                <input type="text" name="vision_title_en" value="<?php echo esc_attr($vision_title_en); ?>" class="regular-text" placeholder="Example: Vision">
                
                <label>Vision Text</label>
                <textarea name="vision_content_en" rows="4" class="large-text" placeholder="Write the vision description in English here..."><?php echo esc_textarea($vision_content_en); ?></textarea>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4">
                <label>صورة خلفية العنوان <span style="color: red;">*</span></label>
                <input type="hidden" name="vision_hero_image" value="<?php echo esc_attr($vision_hero_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="vision_hero_image">رفع صورة خلفية العنوان</button>
                <div class="image-preview" id="vision_hero_image_preview">
                    <?php if ($vision_hero_image): ?>
                        <img src="<?php echo esc_url($vision_hero_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="vision_hero_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الشكل السداسي <span style="color: red;">*</span></label>
                <input type="hidden" name="vision_hex_image" value="<?php echo esc_attr($vision_hex_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="vision_hex_image">رفع صورة الشكل السداسي</button>
                <div class="image-preview" id="vision_hex_image_preview">
                    <?php if ($vision_hex_image): ?>
                        <img src="<?php echo esc_url($vision_hex_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="vision_hex_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الأيقونة الصغيرة <span style="color: red;">*</span></label>
                <input type="hidden" name="vision_icon_image" value="<?php echo esc_attr($vision_icon_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="vision_icon_image">رفع صورة الأيقونة</button>
                <div class="image-preview" id="vision_icon_image_preview">
                    <?php if ($vision_icon_image): ?>
                        <img src="<?php echo esc_url($vision_icon_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="vision_icon_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <hr>
        
        <!-- Value -->
        <h3>قسم القيم / Value Section</h3>
        <div class="row">
            <div class="col-md-6">
                <h4>المحتوى العربي</h4>
                <label>عنوان القيم</label>
                <input type="text" name="value_title_ar" value="<?php echo esc_attr($value_title_ar); ?>" class="regular-text" placeholder="مثال: القيم">
                
                <label>نص القيم</label>
                <textarea name="value_content_ar" rows="4" class="large-text" placeholder="اكتب هنا وصف القيم باللغة العربية..."><?php echo esc_textarea($value_content_ar); ?></textarea>
            </div>
            <div class="col-md-6">
                <h4>English Content</h4>
                <label>Value Title</label>
                <input type="text" name="value_title_en" value="<?php echo esc_attr($value_title_en); ?>" class="regular-text" placeholder="Example: Value">
                
                <label>Value Text</label>
                <textarea name="value_content_en" rows="4" class="large-text" placeholder="Write the value description in English here..."><?php echo esc_textarea($value_content_en); ?></textarea>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-4">
                <label>صورة خلفية العنوان <span style="color: red;">*</span></label>
                <input type="hidden" name="value_hero_image" value="<?php echo esc_attr($value_hero_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="value_hero_image">رفع صورة خلفية العنوان</button>
                <div class="image-preview" id="value_hero_image_preview">
                    <?php if ($value_hero_image): ?>
                        <img src="<?php echo esc_url($value_hero_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="value_hero_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الشكل السداسي <span style="color: red;">*</span></label>
                <input type="hidden" name="value_hex_image" value="<?php echo esc_attr($value_hex_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="value_hex_image">رفع صورة الشكل السداسي</button>
                <div class="image-preview" id="value_hex_image_preview">
                    <?php if ($value_hex_image): ?>
                        <img src="<?php echo esc_url($value_hex_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="value_hex_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label>صورة الأيقونة الصغيرة <span style="color: red;">*</span></label>
                <input type="hidden" name="value_icon_image" value="<?php echo esc_attr($value_icon_image); ?>" class="image-input">
                <button type="button" class="button button-primary upload-image-btn" data-target="value_icon_image">رفع صورة الأيقونة</button>
                <div class="image-preview" id="value_icon_image_preview">
                    <?php if ($value_icon_image): ?>
                        <img src="<?php echo esc_url($value_icon_image); ?>" style="max-width: 150px; height: auto; margin-top: 10px;">
                        <br><button type="button" class="button button-small remove-image-btn" data-target="value_icon_image">حذف الصورة</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .AlQasrGroup-settings-section { margin: 20px 0; }
    .AlQasrGroup-settings-section h3 { border-bottom: 2px solid #0073aa; padding-bottom: 10px; margin-bottom: 20px; }
    .AlQasrGroup-settings-section h4 { color: #0073aa; margin-top: 15px; }
    .AlQasrGroup-settings-section label { display: block; margin: 10px 0 5px; font-weight: 600; }
    .AlQasrGroup-settings-section input, .AlQasrGroup-settings-section textarea { margin-bottom: 15px; }
    .image-preview img { border: 1px solid #ddd; border-radius: 4px; }
    .AlQasrGroup-settings-section .row { margin-bottom: 20px; }
    .AlQasrGroup-settings-section hr { margin: 30px 0; border-color: #ddd; }
    .upload-image-btn { margin-bottom: 10px; }
    .remove-image-btn { margin-top: 5px; }
    </style>
    <?php
}
