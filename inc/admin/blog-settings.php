<?php

// Register Blog settings
function AlQasrGroup_register_blog_settings() {
    // Hero title
    register_setting('AlQasrGroup_settings', 'blog_hero_title');
    register_setting('AlQasrGroup_settings', 'blog_hero_title_en');
    
    // Hero description
    register_setting('AlQasrGroup_settings', 'blog_hero_description');
    register_setting('AlQasrGroup_settings', 'blog_hero_description_en');
}
add_action('admin_init', 'AlQasrGroup_register_blog_settings');

// Save Blog settings
function AlQasrGroup_save_blog_settings() {
    // Hero title
    if (isset($_POST['blog_hero_title'])) {
        update_option('blog_hero_title', sanitize_text_field($_POST['blog_hero_title']));
    }

    if (isset($_POST['blog_hero_title_en'])) {
        update_option('blog_hero_title_en', sanitize_text_field($_POST['blog_hero_title_en']));
    }
    
    // Hero description
    if (isset($_POST['blog_hero_description'])) {
        update_option('blog_hero_description', sanitize_textarea_field($_POST['blog_hero_description']));
    }

    if (isset($_POST['blog_hero_description_en'])) {
        update_option('blog_hero_description_en', sanitize_textarea_field($_POST['blog_hero_description_en']));
    }
}

// Blog settings HTML
function AlQasrGroup_blog_settings_html() {
    // Get current values
    $blog_hero_title = get_option('blog_hero_title', '');
    $blog_hero_title_en = get_option('blog_hero_title_en', '');
    $blog_hero_description = get_option('blog_hero_description', '');
    $blog_hero_description_en = get_option('blog_hero_description_en', '');
    
    ?>
    <div class="blog-settings">
        <h2>إعدادات المدونة</h2>
        
        <!-- Hero Section Settings -->
        <div class="form-section">
            <h3>إعدادات قسم  (Hero Section)</h3>
            
            <div class="form-field">
                <label for="blog_hero_title">العنوان الرئيسي</label>
                <input type="text" id="blog_hero_title" name="blog_hero_title" value="<?php echo esc_attr($blog_hero_title); ?>" class="regular-text" placeholder="مثال: المركز الإعلامي" />
                <p class="description">العنوان الرئيسي الذي يظهر في قسم  في صفحة المدونة</p>
            </div>

            <div class="form-field">
                <label for="blog_hero_title_en">Main Title (English)</label>
                <input type="text" id="blog_hero_title_en" name="blog_hero_title_en" value="<?php echo esc_attr($blog_hero_title_en); ?>" class="regular-text" placeholder="e.g. Media Center" />
                <p class="description">English title displayed in the blog hero section</p>
            </div>
            
            <div class="form-field">
                <label for="blog_hero_description">الوصف</label>
                <textarea id="blog_hero_description" name="blog_hero_description" rows="4" class="large-text" placeholder="مثال: اكتشف مستقبل سكنك بتجربة فريدة من نوعها عبر شاشتنا الإعلامية المتميزة."><?php echo esc_textarea($blog_hero_description); ?></textarea>
                <p class="description">الوصف الذي يظهر تحت العنوان الرئيسي في قسم </p>
            </div>

            <div class="form-field">
                <label for="blog_hero_description_en">Description (English)</label>
                <textarea id="blog_hero_description_en" name="blog_hero_description_en" rows="4" class="large-text" placeholder="e.g. Discover a new perspective on luxury living through our media center."><?php echo esc_textarea($blog_hero_description_en); ?></textarea>
                <p class="description">English description that appears below the hero title</p>
            </div>
        </div>
    </div>
    
    <style>
    .blog-settings .form-section {
        margin-bottom: 30px;
    }
    .blog-settings .form-section h3 {
        margin-bottom: 20px;
        color: #23282d;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    .blog-settings .form-field {
        margin-bottom: 20px;
    }
    .blog-settings label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .blog-settings input[type="text"],
    .blog-settings textarea {
        width: 100%;
        max-width: 600px;
    }
    .blog-settings .description {
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
    </style>
    
    <?php
}

