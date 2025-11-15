<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/projects/register.php';

/**
 * Move the excerpt meta box below the title for projects.
 */
add_action('admin_menu', 'projects_move_excerpt_metabox', 20);
function projects_move_excerpt_metabox() {
    remove_meta_box('postexcerpt', 'projects', 'normal');
}

/**
 * Render the excerpt field directly after the title.
 */
add_action('edit_form_after_title', 'projects_render_excerpt_after_title');
function projects_render_excerpt_after_title($post) {
    if ($post->post_type !== 'projects') {
        return;
    }

    $title_en = get_post_meta($post->ID, '_projects_title_en', true);
    $excerpt_ar = get_post_meta($post->ID, '_projects_excerpt_ar', true);
    $excerpt_en = get_post_meta($post->ID, '_projects_excerpt_en', true);

    echo '<div id="projects-excerpt-wrapper" class="postbox">';
    echo '<div class="postbox-header"><h2 class="hndle">' . esc_html__('مقتطف المشروع', 'borma') . '</h2></div>';
    echo '<div class="inside projects-excerpt-container project-details-modern">';
    wp_nonce_field('projects_title_en_nonce', 'projects_title_en_nonce_field');
    wp_nonce_field('projects_excerpt_nonce', 'projects_excerpt_nonce_field');
    echo '<input type="hidden" name="excerpt" value="">';
    echo '<div class="projects-title-en">';
    echo '    <label for="projects_title_en" class="mt-compact label-with-en">';
    echo '        <strong>' . esc_html__('Project Title (English)', 'borma') . '</strong>';
    echo '        <span class="badge-en">EN</span>';
    echo '    </label>';
    echo '    <input type="text" id="projects_title_en" name="projects_title_en" class="widefat details-title-input" value="' . esc_attr($title_en) . '" placeholder="' . esc_attr__('Enter the English title that appears on EN pages...', 'borma') . '">';
    echo '</div>';
    echo '<div class="projects-excerpt-box">';
    echo '    <div class="excerpt-field">';
    echo '        <label for="projects_excerpt_ar">';
    echo '            <strong>' . esc_html__('مقتطف (عربي)', 'borma') . '</strong>';
    echo '        </label>';
    echo '        <textarea id="projects_excerpt_ar" name="projects_excerpt_ar" class="widefat excerpt-textarea excerpt-textarea--ar" rows="5" placeholder="' . esc_attr__('أدخل مقتطفًا مختصرًا يظهر في القوائم والمعاينات...', 'borma') . '">' . esc_textarea($excerpt_ar) . '</textarea>';
    echo '    </div>';
    echo '    <div class="excerpt-field">';
    echo '        <label for="projects_excerpt_en" class="label-with-en">';
    echo '            <strong>' . esc_html__('Excerpt (English)', 'borma') . '</strong>';
    echo '            <span class="badge-en">EN</span>';
    echo '        </label>';
    echo '        <textarea id="projects_excerpt_en" name="projects_excerpt_en" class="widefat excerpt-textarea excerpt-textarea--en" rows="5" placeholder="' . esc_attr__('Write a concise English excerpt for listings...', 'borma') . '">' . esc_textarea($excerpt_en) . '</textarea>';
    echo '    </div>';
    echo '    <p class="description excerpt-hint">' . esc_html__('يتم استخدام هذه المقتطفات في البطاقات والمعاينات حسب لغة الواجهة.', 'borma') . '</p>';
    echo '</div>';
    echo '</div></div>';
}

