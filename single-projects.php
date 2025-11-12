<?php

use Timber\Timber;

$context = Timber::context();

// Get the current post
$post = Timber::get_post();
$context['post'] = $post;

// Prepare project-specific media assets for Twig templates
$project_id = $post ? $post->ID : 0;
$project_title_en = $project_id ? get_post_meta($project_id, '_projects_title_en', true) : '';
if ($post) {
    $post->title_en = $project_title_en;
}
$context['post_title_en'] = $project_title_en;
$small_top_image = $project_id ? get_post_meta($project_id, '_projects_small_top_image', true) : '';
$separator_image = $project_id ? get_post_meta($project_id, '_projects_separator_image', true) : '';

$status_labels = array(
    'ready_to_handover'   => __('جاهز للتسليم', 'borma'),
    'under_construction'  => __('قيد الإنشاء', 'borma'),
    'off_plan'            => __('على المخطط', 'borma'),
    'coming_soon'         => __('قريبًا', 'borma'),
    'sold_out'            => __('مباع بالكامل', 'borma'),
);

$project_details = array(
    'title'        => $project_id ? get_post_meta($project_id, '_projects_details_title', true) : '',
    'description'  => $project_id ? get_post_meta($project_id, '_projects_details_text', true) : '',
    'excerpt'      => $project_id ? get_post_meta($project_id, '_projects_excerpt_ar', true) : '',
    'price'        => $project_id ? get_post_meta($project_id, '_projects_price', true) : '',
    'city'         => $project_id ? get_post_meta($project_id, '_projects_city', true) : '',
    'status'       => $project_id ? get_post_meta($project_id, '_projects_status', true) : '',
);

$status_key = $project_details['status'] ?? '';
$project_details['status_label'] = $status_key && isset($status_labels[$status_key])
    ? $status_labels[$status_key]
    : $status_key;

$gallery_meta = $project_id ? get_post_meta($project_id, '_projects_side_gallery_images', true) : '';
$gallery_images = array();
$project_gallery_images = array();

if (!empty($gallery_meta)) {
    $ids = array_filter(array_map('intval', explode(',', $gallery_meta)));

    foreach ($ids as $attachment_id) {
        $image_url = wp_get_attachment_image_url($attachment_id, 'full');

        if (!$image_url) {
            continue;
        }

        $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        if (empty($image_alt)) {
            $attachment_post = get_post($attachment_id);
            $image_alt = $attachment_post ? $attachment_post->post_title : '';
        }

        $project_gallery_images[] = array(
            'id'  => $attachment_id,
            'url' => esc_url_raw($image_url),
            'alt' => $image_alt,
        );
    }

    if (!empty($project_gallery_images)) {
        $gallery_images = array_slice($project_gallery_images, 0, 2);
    }
}

$features = array(
    'section_title' => $project_id ? get_post_meta($project_id, '_projects_features_title', true) : '',
    'main_title'    => $project_id ? get_post_meta($project_id, '_projects_features_main_title', true) : '',
    'description'   => $project_id ? get_post_meta($project_id, '_projects_features_description', true) : '',
    'items'         => array(),
);

$features_items_meta = $project_id ? get_post_meta($project_id, '_projects_features_icons', true) : '';

if (!empty($features_items_meta)) {
    if (is_string($features_items_meta)) {
        $decoded_items = json_decode($features_items_meta, true);
    } else {
        $decoded_items = $features_items_meta;
    }

    if (is_array($decoded_items)) {
        foreach ($decoded_items as $feature_item) {
            if (empty($feature_item) || (!is_array($feature_item))) {
                continue;
            }

            $icon_url = isset($feature_item['icon']) ? esc_url_raw($feature_item['icon']) : '';
            $title = isset($feature_item['title']) ? trim(wp_kses_post($feature_item['title'])) : '';
            $description = isset($feature_item['description']) ? trim(wp_kses_post($feature_item['description'])) : '';

            if (empty($title) && empty($description)) {
                continue;
            }

            $features['items'][] = array(
                'icon'        => $icon_url,
                'title'       => $title,
                'description' => $description,
            );
        }
    }
}

$speed_sections = array();
$speed_sections_meta = $project_id ? get_post_meta($project_id, '_projects_speed_sections', true) : '';

if (!empty($speed_sections_meta)) {
    if (is_string($speed_sections_meta)) {
        $decoded_sections = json_decode($speed_sections_meta, true);
    } else {
        $decoded_sections = $speed_sections_meta;
    }

    if (is_array($decoded_sections)) {
        foreach ($decoded_sections as $section) {
            if (empty($section) || !is_array($section)) {
                continue;
            }

            $section_name = isset($section['name']) ? trim(wp_kses_post($section['name'])) : '';
            $items = array();

            if (!empty($section['items']) && is_array($section['items'])) {
                $section_items = array_reverse($section['items']);

                foreach ($section_items as $item) {
                    $time = isset($item['time']) ? trim(wp_kses_post($item['time'])) : '';
                    $unit = isset($item['unit']) ? trim(wp_kses_post($item['unit'])) : '';
                    $label = isset($item['label']) ? trim(wp_kses_post($item['label'])) : '';

                    if ($time === '' && $unit === '' && $label === '') {
                        continue;
                    }

                    $items[] = array(
                        'time'  => $time,
                        'unit'  => $unit,
                        'label' => $label,
                    );
                }
            }

            if ($section_name === '' && empty($items)) {
                continue;
            }

            $speed_sections[] = array(
                'name'  => $section_name,
                'items' => $items,
            );
        }
    }
}

$amenities = array(
    'subtitle' => '',
    'items'    => array(),
);

if ($project_id) {
    $amenities_subtitle = get_post_meta($project_id, '_projects_amenities_subtitle', true);
    if (!empty($amenities_subtitle)) {
        $amenities['subtitle'] = trim(wp_kses_post($amenities_subtitle));
    }
}

$amenities_items_meta = $project_id ? get_post_meta($project_id, '_projects_amenities_items', true) : '';

if (!empty($amenities_items_meta)) {
    if (is_string($amenities_items_meta)) {
        $decoded_amenities = json_decode($amenities_items_meta, true);
    } else {
        $decoded_amenities = $amenities_items_meta;
    }

    if (is_array($decoded_amenities)) {
        foreach ($decoded_amenities as $amenity_item) {
            if (empty($amenity_item) || !is_array($amenity_item)) {
                continue;
            }

            $image = isset($amenity_item['image']) ? esc_url_raw($amenity_item['image']) : '';
            $title = isset($amenity_item['title']) ? trim(wp_kses_post($amenity_item['title'])) : '';
            $description = isset($amenity_item['description']) ? trim(wp_kses_post($amenity_item['description'])) : '';

            if (empty($title) && empty($description) && empty($image)) {
                continue;
            }

            $amenities['items'][] = array(
                'image'       => $image,
                'title'       => $title,
                'description' => $description,
            );
        }
    }
}

$context['project_media'] = array(
    'small_top_image' => $small_top_image ? esc_url_raw($small_top_image) : null,
    'separator_image' => $separator_image ? esc_url_raw($separator_image) : null,
    'gallery_images'  => $gallery_images,
);

$context['project_details'] = $project_details;
$context['project_features'] = $features;
$context['project_speed_sections'] = $speed_sections;
$context['project_amenities'] = $amenities;
$gallery_subtitle = $project_id ? get_post_meta($project_id, '_projects_gallery_subtitle', true) : '';
$gallery_title = $project_id ? get_post_meta($project_id, '_projects_gallery_title', true) : '';
$context['project_gallery'] = array(
    'subtitle' => $gallery_subtitle ? trim(wp_kses_post($gallery_subtitle)) : '',
    'title'    => $gallery_title ? trim(wp_kses_post($gallery_title)) : '',
    'images'   => $project_gallery_images,
);

// Get the appropriate template based on current language
if (function_exists('get_language_template')) {
    $template = get_language_template('single-project.twig');
} else {
    // Fallback to default template
    $template = 'single-project.twig';
}

Timber::render($template, $context);

