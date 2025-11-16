<?php

use Timber\Timber;

/**
 * Archive: Jobs
 * أرشيف: الوظائف
 *
 * Loads Jobs archive data and passes theme options to Twig templates.
 */

$context = Timber::context();

$is_english = false;
if (function_exists('is_english_version') && is_english_version()) {
    $is_english = true;
} elseif (function_exists('get_current_language') && get_current_language() === 'en') {
    $is_english = true;
} elseif (!empty($_SERVER['REQUEST_URI']) && preg_match('#/en(/|$)#', $_SERVER['REQUEST_URI'])) {
    // Fallback: detect English from the current request URI
    $is_english = true;
}

// Helper function to localize job posts (similar to archive-projects.php)
$localize_job = function ($job_post) use ($is_english) {
    if (!$is_english || !is_object($job_post)) {
        return $job_post;
    }

    $job_id = isset($job_post->ID) ? $job_post->ID : (isset($job_post->id) ? $job_post->id : 0);
    if (!$job_id) {
        return $job_post;
    }

    $job_title_en = get_post_meta($job_id, 'job_title_en', true);
    if (!empty($job_title_en)) {
        if (isset($job_post->post_title)) {
            $job_post->post_title = $job_title_en;
        }
        $job_post->title = $job_title_en;
    }

    // Build English URL using the post slug to avoid losing the path
    $slug = get_post_field('post_name', $job_id);
    if (!empty($slug)) {
        $base = rtrim(home_url('/'), '/');
        $english_link = $base . '/en/jobs/' . $slug . '/';
        $job_post->link = $english_link;
        $job_post->job_apply_link = $english_link;
    } else {
        // Fallbacks
        if (function_exists('get_english_url')) {
            $english_link = get_english_url($job_id);
            if (!empty($english_link)) {
                $job_post->link = $english_link;
                $job_post->job_apply_link = $english_link;
            }
        } else {
            $permalink = get_permalink($job_id);
            $home_url = home_url('/');
            if (!empty($permalink) && strpos($permalink, '/en/') === false) {
                $path = str_replace(array($home_url, rtrim($home_url, '/') . '/'), '', $permalink);
                $path = ltrim($path, '/');
                if (!empty($path)) {
                    $english_link = rtrim($home_url, '/') . '/en/' . $path;
                    $job_post->link = $english_link;
                    $job_post->job_apply_link = $english_link;
                }
            } else if (!empty($permalink)) {
                $job_post->link = $permalink;
                $job_post->job_apply_link = $permalink;
            }
        }
    }

    return $job_post;
};

// Collect jobs from the main query (supports pagination if enabled)
$jobs_posts = Timber::get_posts();

$jobs_for_view = [];

if (!empty($jobs_posts)) {
	foreach ($jobs_posts as $job_post) {
		// Try multiple meta key variations to ensure we get the data
		$job_type = get_post_meta($job_post->ID, 'job_type', true);
		if (empty($job_type)) {
			$job_type = get_post_meta($job_post->ID, '_job_type', true);
		}
		if (empty($job_type)) {
			$job_type = get_post_meta($job_post->ID, 'AlQasrGroup_job_type', true);
		}
		
		$job_location = get_post_meta($job_post->ID, 'job_location', true);
		if (empty($job_location)) {
			$job_location = get_post_meta($job_post->ID, '_job_location', true);
		}
		if (empty($job_location)) {
			$job_location = get_post_meta($job_post->ID, 'AlQasrGroup_job_location', true);
		}
		
		$job_type_en = get_post_meta($job_post->ID, 'job_type_en', true);
		if (empty($job_type_en)) {
			$job_type_en = get_post_meta($job_post->ID, 'AlQasrGroup_job_type_en', true);
		}
		
		$job_location_en = get_post_meta($job_post->ID, 'job_location_en', true);
		if (empty($job_location_en)) {
			$job_location_en = get_post_meta($job_post->ID, 'AlQasrGroup_job_location_en', true);
		}

		$job_title_en = get_post_meta($job_post->ID, 'job_title_en', true);

		$title_display = $is_english ? (!empty($job_title_en) ? $job_title_en : $job_post->title) : $job_post->title;
		$type_display = $is_english ? (!empty($job_type_en) ? $job_type_en : $job_type) : $job_type;
		$location_display = $is_english ? (!empty($job_location_en) ? $job_location_en : $job_location) : $job_location;

		// Build link/apply link
		$link = get_permalink($job_post->ID);
		$apply_link = $link;

		$current_is_english = (!empty($_SERVER['REQUEST_URI']) && preg_match('#/en(/|$)#', $_SERVER['REQUEST_URI'])) || $is_english;
		if ($current_is_english) {
			$slug = get_post_field('post_name', $job_post->ID);
			if (!empty($slug)) {
				$base = rtrim(home_url('/'), '/');
				$english_link = $base . '/en/jobs/' . $slug . '/';
				$link = $english_link;
				$apply_link = $english_link;
			} elseif (function_exists('get_english_url')) {
				$link = get_english_url($job_post->ID);
				$apply_link = $link;
			}
		}

		$jobs_for_view[] = [
			'ID' => $job_post->ID,
			'title' => $job_post->title,
			'job_title_display' => $title_display,
			'job_type_display' => $type_display,
			'job_location_display' => $location_display,
			'job_apply_link' => $apply_link,
			'link' => $link,
		];
	}
}

$context['jobs'] = $jobs_for_view;

// Final pass: ensure links are set correctly based on current language
if (!empty($context['jobs'])) {
	$current_is_english = (!empty($_SERVER['REQUEST_URI']) && preg_match('#/en(/|$)#', $_SERVER['REQUEST_URI'])) || $is_english;
	foreach ($context['jobs'] as &$job_item) {
		if ($current_is_english) {
			$slug = get_post_field('post_name', $job_item['ID']);
			if (!empty($slug)) {
				$base = rtrim(home_url('/'), '/');
				$english_link = $base . '/en/jobs/' . $slug . '/';
				$job_item['link'] = $english_link;
				$job_item['job_apply_link'] = $english_link;
			} elseif (function_exists('get_english_url')) {
				$english_link = get_english_url($job_item['ID']);
				$job_item['link'] = $english_link;
				$job_item['job_apply_link'] = $english_link;
			}
		} else {
			if (empty($job_item['job_apply_link'])) {
				$job_item['job_apply_link'] = get_permalink($job_item['ID']);
				$job_item['link'] = $job_item['job_apply_link'];
			}
		}
	}
	unset($job_item);
}

if ($is_english) {
    $context['jobs_main_title'] = get_option('jobs_main_title_en', get_option('jobs_main_title', 'Join Our Team'));
    $context['jobs_main_description'] = get_option('jobs_main_description_en', get_option('jobs_main_description', 'Discover career opportunities with our exceptional team.'));

    $context['jobs_highlight_subtitle'] = get_option('jobs_highlight_subtitle_en', get_option('jobs_highlight_subtitle', 'Working at AlQasr Real Estate'));
    $context['jobs_highlight_title'] = get_option('jobs_highlight_title_en', get_option('jobs_highlight_title', 'We Are Proud of Our Inspiring Work Environment'));
    $context['jobs_highlight_description'] = get_option('jobs_highlight_description_en', get_option('jobs_highlight_description', 'Our unique culture empowers every team member to reach their full potential.'));

    $context['jobs_cta_small_title'] = get_option('jobs_cta_small_title_en', get_option('jobs_cta_small_title', 'Join Us'));
    $context['jobs_cta_main_title'] = get_option('jobs_cta_main_title_en', get_option('jobs_cta_main_title', 'Apply Now'));
    $context['jobs_cta_small_title_en'] = get_option('jobs_cta_small_title_en', 'Join Us');
    $context['jobs_cta_main_title_en'] = get_option('jobs_cta_main_title_en', 'Apply Now');
} else {
    $context['jobs_main_title'] = get_option('jobs_main_title', 'انضم إلينا');
    $context['jobs_main_description'] = get_option('jobs_main_description', 'اكتشف مستقبلك مع فريقنا المتميز وفرص التطور المهني.');

    $context['jobs_highlight_subtitle'] = get_option('jobs_highlight_subtitle', 'العمل في القصر العقارية');
    $context['jobs_highlight_title'] = get_option('jobs_highlight_title', 'نفتخر بامتلاكنا بيئة عمل ملهمة وناجحة');
    $context['jobs_highlight_description'] = get_option('jobs_highlight_description', 'نعمل على بناء منظومة متكاملة تدعم الإبداع والتميز لتحقيق الأهداف الاستراتيجية للشركة والموظفين.');

    $context['jobs_cta_small_title'] = get_option('jobs_cta_small_title', 'انضم إلينا');
    $context['jobs_cta_main_title'] = get_option('jobs_cta_main_title', 'قدّم الآن');
}

$context['jobs_highlight_image'] = get_option('jobs_highlight_image', '');

// Choose template based on language folder availability
if (function_exists('get_language_template')) {
    $template = get_language_template('archive-jobs.twig');
} else {
    $template = 'archive-jobs.twig';
}

Timber::render($template, $context);


