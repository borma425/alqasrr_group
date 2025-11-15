<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/details.php';
require_once __DIR__ . '/separator.php';
require_once __DIR__ . '/features.php';
require_once __DIR__ . '/gallery.php';
require_once __DIR__ . '/amenities.php';
require_once __DIR__ . '/speed-travel.php';
require_once __DIR__ . '/small-top-image.php';

/**
 * Register all project meta boxes.
 */
function projects_meta_boxes() {
    add_meta_box(
        'projects_description_section',
        __('تفاصيل المشروع', 'borma'),
        'projects_details_callback',
        'projects',
        'normal',
        'high'
    );

    add_meta_box(
        'projects_separator_image_box',
        __('الصورة الفاصلة', 'borma'),
        'projects_separator_image_callback',
        'projects',
        'normal',
        'default'
    );

    add_meta_box(
        'projects_features_section',
        __('ما الذي يميزنا', 'borma'),
        'projects_features_callback',
        'projects',
        'normal',
        'default'
    );

    add_meta_box(
        'projects_speed_travel_box',
        __('سرعة التنقل', 'borma'),
        'projects_speed_travel_callback',
        'projects',
        'normal',
        'default'
    );

    add_meta_box(
        'projects_gallery_box',
        __('جاليري معرض الصور', 'borma'),
        'projects_gallery_callback',
        'projects',
        'normal',
        'default'
    );

    add_meta_box(
        'projects_amenities_section',
        __('المرافق والخدمات', 'borma'),
        'projects_amenities_callback',
        'projects',
        'normal',
        'default'
    );

    add_meta_box(
        'projects_small_top_image_box',
        __('الصورة العلوية الصغيرة', 'borma'),
        'projects_small_top_image_callback',
        'projects',
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'projects_meta_boxes');

/**
 * Save meta box data.
 */
function save_projects_meta_boxes($post_id) {
    $nonces = array(
        'description'      => 'projects_description_nonce_field',
        'small_top_image'  => 'projects_small_top_image_nonce_field',
        'gallery'          => 'projects_gallery_nonce_field',
        'separator'        => 'projects_separator_image_nonce_field',
        'features'         => 'projects_features_nonce_field',
        'amenities'        => 'projects_amenities_nonce_field',
        'speed_travel'     => 'projects_speed_travel_nonce_field',
    );

    foreach ($nonces as $key => $nonce) {
        if (!isset($_POST[$nonce]) || !wp_verify_nonce($_POST[$nonce], 'projects_' . $key . '_nonce')) {
            continue;
        }
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['projects_small_top_image_nonce_field']) && wp_verify_nonce($_POST['projects_small_top_image_nonce_field'], 'projects_small_top_image_nonce')) {
        $small_top_image = isset($_POST['projects_small_top_image']) ? sanitize_text_field($_POST['projects_small_top_image']) : '';
        if (!empty($small_top_image)) {
            update_post_meta($post_id, '_projects_small_top_image', $small_top_image);
        } else {
            delete_post_meta($post_id, '_projects_small_top_image');
        }

        $pdf_fields = array(
            'projects_pdf_ar' => '_projects_pdf_ar',
            'projects_pdf_en' => '_projects_pdf_en',
        );

        foreach ($pdf_fields as $field => $meta_key) {
            if (isset($_POST[$field]) && !empty($_POST[$field])) {
                update_post_meta($post_id, $meta_key, esc_url_raw($_POST[$field]));
            } else {
                delete_post_meta($post_id, $meta_key);
            }
        }
    }

    if (isset($_POST['projects_excerpt_nonce_field']) && wp_verify_nonce($_POST['projects_excerpt_nonce_field'], 'projects_excerpt_nonce')) {
        $excerpt_ar = isset($_POST['projects_excerpt_ar']) ? sanitize_textarea_field($_POST['projects_excerpt_ar']) : '';
        $excerpt_en = isset($_POST['projects_excerpt_en']) ? sanitize_textarea_field($_POST['projects_excerpt_en']) : '';

        if (!empty($excerpt_ar)) {
            update_post_meta($post_id, '_projects_excerpt_ar', $excerpt_ar);
        } else {
            delete_post_meta($post_id, '_projects_excerpt_ar');
        }

        if (!empty($excerpt_en)) {
            update_post_meta($post_id, '_projects_excerpt_en', $excerpt_en);
        } else {
            delete_post_meta($post_id, '_projects_excerpt_en');
        }

    }

    if (isset($_POST['projects_title_en_nonce_field']) && wp_verify_nonce($_POST['projects_title_en_nonce_field'], 'projects_title_en_nonce')) {
        $title_en = isset($_POST['projects_title_en']) ? sanitize_text_field($_POST['projects_title_en']) : '';

        if (!empty($title_en)) {
            update_post_meta($post_id, '_projects_title_en', $title_en);
        } else {
            delete_post_meta($post_id, '_projects_title_en');
        }
    }

    if (isset($_POST['projects_side_gallery_images']) && isset($_POST['projects_gallery_nonce_field']) && wp_verify_nonce($_POST['projects_gallery_nonce_field'], 'projects_gallery_nonce')) {
        $gallery_images = array_map('intval', (array) $_POST['projects_side_gallery_images']);
        $gallery_images = array_filter($gallery_images);

        if (!empty($gallery_images)) {
            update_post_meta($post_id, '_projects_side_gallery_images', implode(',', $gallery_images));
        } else {
            delete_post_meta($post_id, '_projects_side_gallery_images');
        }
    } elseif (isset($_POST['projects_gallery_nonce_field']) && wp_verify_nonce($_POST['projects_gallery_nonce_field'], 'projects_gallery_nonce')) {
        delete_post_meta($post_id, '_projects_side_gallery_images');
    }

    if (isset($_POST['projects_separator_image']) && isset($_POST['projects_separator_image_nonce_field']) && wp_verify_nonce($_POST['projects_separator_image_nonce_field'], 'projects_separator_image_nonce')) {
        $separator_image = sanitize_text_field($_POST['projects_separator_image']);
        if (!empty($separator_image)) {
            update_post_meta($post_id, '_projects_separator_image', $separator_image);
        } else {
            delete_post_meta($post_id, '_projects_separator_image');
        }
    }

    if (isset($_POST['projects_features_nonce_field']) && wp_verify_nonce($_POST['projects_features_nonce_field'], 'projects_features_nonce')) {
        $features_fields = array(
            'projects_features_title'         => '_projects_features_title',
            'projects_features_title_en'      => '_projects_features_title_en',
            'projects_features_main_title'    => '_projects_features_main_title',
            'projects_features_main_title_en' => '_projects_features_main_title_en',
            'projects_features_description'   => '_projects_features_description',
            'projects_features_description_en'=> '_projects_features_description_en',
        );

        foreach ($features_fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $is_textarea = in_array($field, array('projects_features_description', 'projects_features_description_en'), true);
                $value = $is_textarea
                    ? sanitize_textarea_field($_POST[$field])
                    : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }

        if (isset($_POST['features_icons'])) {
            $icons_data = array();
            foreach ($_POST['features_icons'] as $icon) {
                if (!empty($icon['title']) || !empty($icon['title_en']) || !empty($icon['description']) || !empty($icon['description_en']) || !empty($icon['icon'])) {
                    $icons_data[] = array(
                        'icon'           => sanitize_text_field($icon['icon']),
                        'title'          => sanitize_text_field($icon['title']),
                        'title_en'       => sanitize_text_field($icon['title_en'] ?? ''),
                        'description'    => sanitize_textarea_field($icon['description']),
                        'description_en' => sanitize_textarea_field($icon['description_en'] ?? ''),
                    );
                }
            }
            update_post_meta($post_id, '_projects_features_icons', json_encode($icons_data, JSON_UNESCAPED_UNICODE));
        } else {
            delete_post_meta($post_id, '_projects_features_icons');
        }
    }

    if (isset($_POST['projects_amenities_nonce_field']) && wp_verify_nonce($_POST['projects_amenities_nonce_field'], 'projects_amenities_nonce')) {
        $amenities_fields = array(
            'projects_amenities_subtitle'     => '_projects_amenities_subtitle',
            'projects_amenities_subtitle_en'  => '_projects_amenities_subtitle_en',
        );

        foreach ($amenities_fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }

        if (isset($_POST['amenities_items'])) {
            $amenities_data = array();
            foreach ($_POST['amenities_items'] as $amenity) {
                if (!empty($amenity['title']) || !empty($amenity['title_en']) || !empty($amenity['description']) || !empty($amenity['description_en']) || !empty($amenity['image'])) {
                    $amenities_data[] = array(
                        'image'           => sanitize_text_field($amenity['image']),
                        'title'           => sanitize_text_field($amenity['title']),
                        'title_en'        => sanitize_text_field($amenity['title_en'] ?? ''),
                        'description'     => sanitize_textarea_field($amenity['description']),
                        'description_en'  => sanitize_textarea_field($amenity['description_en'] ?? ''),
                    );
                }
            }
            update_post_meta($post_id, '_projects_amenities_items', json_encode($amenities_data, JSON_UNESCAPED_UNICODE));
        } else {
            delete_post_meta($post_id, '_projects_amenities_items');
        }
    }

    if (isset($_POST['projects_speed_travel_nonce_field']) && wp_verify_nonce($_POST['projects_speed_travel_nonce_field'], 'projects_speed_travel_nonce')) {
        if (isset($_POST['speed_sections'])) {
            $sections_data = array();

            foreach ($_POST['speed_sections'] as $section) {
                $section_name = isset($section['name']) ? sanitize_text_field($section['name']) : '';
                $section_name_en = isset($section['name_en']) ? sanitize_text_field($section['name_en']) : '';
                $items = array();

                if (!empty($section['items'])) {
                    foreach ($section['items'] as $item) {
                        if (!empty($item['time']) || !empty($item['unit']) || !empty($item['unit_en']) || !empty($item['label']) || !empty($item['label_en'])) {
                            $items[] = array(
                                'time'    => sanitize_text_field($item['time']),
                                'unit'    => sanitize_text_field($item['unit']),
                                'unit_en' => sanitize_text_field($item['unit_en'] ?? ''),
                                'label'   => sanitize_text_field($item['label']),
                                'label_en'=> sanitize_text_field($item['label_en'] ?? ''),
                            );
                        }
                    }
                }

                if (!empty($section_name) || !empty($section_name_en) || !empty($items)) {
                    $sections_data[] = array(
                        'name'    => $section_name,
                        'name_en' => $section_name_en,
                        'items'   => $items,
                    );
                }
            }

            if (!empty($sections_data)) {
                update_post_meta($post_id, '_projects_speed_sections', json_encode($sections_data, JSON_UNESCAPED_UNICODE));
            } else {
                delete_post_meta($post_id, '_projects_speed_sections');
            }
            delete_post_meta($post_id, '_projects_speed_items'); // Legacy cleanup
        } else {
            delete_post_meta($post_id, '_projects_speed_sections');
        }
    }

    if (isset($_POST['projects_gallery_nonce_field']) && wp_verify_nonce($_POST['projects_gallery_nonce_field'], 'projects_gallery_nonce')) {
        $gallery_fields = array(
            'projects_gallery_subtitle'    => '_projects_gallery_subtitle',
            'projects_gallery_subtitle_en' => '_projects_gallery_subtitle_en',
            'projects_gallery_title'       => '_projects_gallery_title',
            'projects_gallery_title_en'    => '_projects_gallery_title_en',
        );

        foreach ($gallery_fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }

    $details_fields = array(
        'projects_details_title'    => '_projects_details_title',
        'projects_details_title_en' => '_projects_details_title_en',
        'projects_details_text'     => '_projects_details_text',
        'projects_details_text_en'  => '_projects_details_text_en',
        'projects_price'            => '_projects_price',
        'projects_price_en'         => '_projects_price_en',
        'projects_city'             => '_projects_city',
        'projects_city_en'          => '_projects_city_en',
        'projects_status'           => '_projects_status',
        'projects_status_en'        => '_projects_status_en',
    );

    foreach ($details_fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            $is_textarea = in_array($field, array('projects_details_text', 'projects_details_text_en'), true);
            if ($is_textarea) {
                update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$field]));
            } else {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post', 'save_projects_meta_boxes');

/**
 * Enqueue admin assets for the meta boxes.
 */
function projects_meta_boxes_scripts($hook) {
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    global $post_type;
    if ('projects' !== $post_type) {
        return;
    }

    wp_enqueue_style('projects-meta-boxes', asset_url('projects-meta-boxes.css', '/css/admin/'));

    wp_enqueue_media();
    wp_enqueue_script('projects-meta-boxes', asset_url('projects-meta-boxes.js', '/js/admin/'), array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'projects_meta_boxes_scripts');

