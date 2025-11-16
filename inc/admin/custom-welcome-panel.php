<?php

if (!defined('ABSPATH')) exit;

/**
 * Enqueue custom styles and scripts on the Dashboard home only.
 */
function alqasrgroup_dashboard_welcome_assets($hook) {
	if ($hook !== 'index.php') {
		return;
	}

	wp_enqueue_style(
		'alqasrgroup-dashboard-welcome',
		get_template_directory_uri() . '/assets/css/admin/dashboard-welcome.css',
		array(),
		'1.0'
	);

	wp_enqueue_script(
		'alqasrgroup-dashboard-welcome',
		get_template_directory_uri() . '/assets/js/admin/dashboard-welcome.js',
		array(),
		'1.0',
		true
	);
}
add_action('admin_enqueue_scripts', 'alqasrgroup_dashboard_welcome_assets');

/**
 * Shared renderer for the custom welcome content.
 */
function alqasrgroup_render_welcome_content() {
	$site_name       = get_bloginfo('name');
	$site_tagline    = get_bloginfo('description');
	$site_url        = home_url('/');
	$custom_logo_id  = get_theme_mod('custom_logo');
	$logo_src        = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
	?>
	<div class="alqasr-welcome">
		<div class="alqasr-welcome__bg"></div>

		<div class="alqasr-welcome__header">
			<?php if ($logo_src): ?>
				<img class="alqasr-welcome__logo" src="<?php echo esc_url($logo_src); ?>" alt="<?php echo esc_attr($site_name); ?>">
			<?php endif; ?>
			<div class="alqasr-welcome__title-wrap">
				<h1 class="alqasr-welcome__title">مرحباً بك في <?php echo esc_html($site_name); ?></h1>
				<?php if ($site_tagline): ?>
					<p class="alqasr-welcome__subtitle"><?php echo esc_html($site_tagline); ?></p>
				<?php endif; ?>
			</div>

			<button type="button" class="alqasr-welcome__mode-toggle" aria-label="تبديل الوضع">
				<span class="alqasr-welcome__mode-icon" aria-hidden="true">🌓</span>
			</button>
		</div>

		<div class="alqasr-welcome__actions">
			<a class="alqasr-btn" href="<?php echo esc_url(admin_url('admin.php?page=AlQasrGroup-settings#colors')); ?>">تعديل الألوان</a>
			<a class="alqasr-btn" href="<?php echo esc_url($site_url); ?>" target="_blank" rel="noopener">عرض الموقع</a>
			<a class="alqasr-btn" href="<?php echo esc_url(admin_url('post-new.php?post_type=projects')); ?>">إضافة مشروع</a>
			<a class="alqasr-btn" href="<?php echo esc_url(admin_url('post-new.php?post_type=blog')); ?>">كتابة مقال</a>
			<a class="alqasr-btn" href="<?php echo esc_url(admin_url('post-new.php?post_type=jobs')); ?>">إضافة وظيفة</a>
			<a class="alqasr-btn" href="<?php echo esc_url(admin_url('themes.php?page=AlQasrGroup-settings')); ?>">إعدادات الموقع</a>
		</div>

		<div class="alqasr-welcome__kit">
			<div class="alqasr-card">
				<div class="alqasr-card__icon">🏢</div>
				<div class="alqasr-card__content">
					<h3 class="alqasr-card__title">المشاريع العقارية</h3>
					<p class="alqasr-card__desc">إدارة وعرض أحدث مشاريعك بسهولة.</p>
				</div>
				<a class="alqasr-card__link" href="<?php echo esc_url(admin_url('edit.php?post_type=projects')); ?>">إدارة</a>
			</div>
			<div class="alqasr-card">
				<div class="alqasr-card__icon">📰</div>
				<div class="alqasr-card__content">
					<h3 class="alqasr-card__title">الأخبار والمقالات</h3>
					<p class="alqasr-card__desc">شارك آخر الأخبار والرؤى في السوق.</p>
				</div>
				<a class="alqasr-card__link" href="<?php echo esc_url(admin_url('edit.php?post_type=blog')); ?>">إدارة</a>
			</div>
			<div class="alqasr-card">
				<div class="alqasr-card__icon">💼</div>
				<div class="alqasr-card__content">
					<h3 class="alqasr-card__title">الوظائف</h3>
					<p class="alqasr-card__desc">استقطب أفضل المواهب لفريقك.</p>
				</div>
				<a class="alqasr-card__link" href="<?php echo esc_url(admin_url('edit.php?post_type=jobs')); ?>">إدارة</a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Keep compatibility: also render inside the native Welcome panel if it is visible.
 * This shows our content alongside the default panel when not dismissed.
 */
function alqasrgroup_custom_welcome_panel() {
	alqasrgroup_render_welcome_content();
}
add_action('welcome_panel', 'alqasrgroup_custom_welcome_panel');

/**
 * Add a persistent dashboard widget so our panel still appears even if the
 * native WordPress welcome panel is dismissed.
 */
function alqasrgroup_register_dashboard_widget() {
	wp_add_dashboard_widget(
		'alqasrgroup_welcome_widget',
		'ترحيب - ' . get_bloginfo('name'),
		function () {
			alqasrgroup_render_welcome_content();
		}
	);
}
add_action('wp_dashboard_setup', 'alqasrgroup_register_dashboard_widget');

/**
 * Show a full-width top notice on Dashboard when the native welcome panel is dismissed,
 * so our panel remains prominent and first.
 */
function alqasrgroup_dashboard_top_notice() {
	if (!function_exists('get_current_screen')) {
		return;
	}
	$screen = get_current_screen();
	if (!$screen || $screen->id !== 'dashboard') {
		return;
	}
	$user_id = get_current_user_id();
	$show_native_welcome = get_user_meta($user_id, 'show_welcome_panel', true);
	// If user has dismissed native welcome (value '0'), render our full-width notice.
	if ($show_native_welcome === '0' || $show_native_welcome === 0) {
		alqasrgroup_render_welcome_content();
	}
}
add_action('admin_notices', 'alqasrgroup_dashboard_top_notice');

/**
 * If native welcome is dismissed, remove our dashboard widget to avoid duplication
 * since we already show the full-width notice at the top.
 */
function alqasrgroup_maybe_remove_welcome_widget() {
	if (!function_exists('get_current_screen')) {
		return;
	}
	$screen = get_current_screen();
	if (!$screen || $screen->id !== 'dashboard') {
		return;
	}
	$user_id = get_current_user_id();
	$show_native_welcome = get_user_meta($user_id, 'show_welcome_panel', true);
	if ($show_native_welcome === '0' || $show_native_welcome === 0) {
		global $wp_meta_boxes;
		$widget_id = 'alqasrgroup_welcome_widget';
		foreach (array('normal', 'side') as $context) {
			foreach (array('high', 'core', 'default', 'low') as $priority) {
				if (isset($wp_meta_boxes['dashboard'][$context][$priority][$widget_id])) {
					unset($wp_meta_boxes['dashboard'][$context][$priority][$widget_id]);
				}
			}
		}
	}
}
add_action('wp_dashboard_setup', 'alqasrgroup_maybe_remove_welcome_widget', 100);


