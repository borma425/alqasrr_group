<?php

if (!defined('ABSPATH')) exit;

/**
 * HTML for Colors settings tab.
 */
function AlQasrGroup_colors_settings_html() {
	// Default values read from base.css if possible
	$defaults = AlQasrGroup_colors_read_from_css();
	$primary   = isset($defaults['--primary']) ? $defaults['--primary'] : '#282828';
	$secondary = isset($defaults['--secondary']) ? $defaults['--secondary'] : '#FF5A00';
	$textGray  = isset($defaults['--text-gray']) ? $defaults['--text-gray'] : '#616161';
	$white     = isset($defaults['--white']) ? $defaults['--white'] : '#FBFBFB';
	$black     = isset($defaults['--black']) ? $defaults['--black'] : '#141415';
	?>
	<?php
		// Arabic version (assets/css/main/base.css) FIRST
		$defaults_ar = AlQasrGroup_colors_read_from_css_ar();
		$primary_ar   = isset($defaults_ar['--primary']) ? $defaults_ar['--primary'] : '#282828';
		$secondary_ar = isset($defaults_ar['--secondary']) ? $defaults_ar['--secondary'] : '#FF5A00';
		$textGray_ar  = isset($defaults_ar['--text-gray']) ? $defaults_ar['--text-gray'] : '#616161';
		$white_ar     = isset($defaults_ar['--white']) ? $defaults_ar['--white'] : '#FBFBFB';
		$black_ar     = isset($defaults_ar['--black']) ? $defaults_ar['--black'] : '#141415';
	?>
	<h2>الألوان - النسخة العربية</h2>
	<p>تحديث ألوان النسخة العربية من الملف: <code>assets/css/main/base.css</code>. يتم عرض معاينة مباشرة وتحديث حقل HEX تلقائياً.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="alq_color_primary_ar">اللون الأساسي (primary)</label></th>
			<td>
				<input type="color" id="alq_color_primary_ar" name="alq_color_primary_ar" value="<?php echo esc_attr($primary_ar); ?>" data-var="--primary" class="alq-color-input" />
				<input type="text" name="alq_color_primary_text_ar" value="<?php echo esc_attr($primary_ar); ?>" data-var="--primary" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_secondary_ar">اللون الثانوي (secondary)</label></th>
			<td>
				<input type="color" id="alq_color_secondary_ar" name="alq_color_secondary_ar" value="<?php echo esc_attr($secondary_ar); ?>" data-var="--secondary" class="alq-color-input" />
				<input type="text" name="alq_color_secondary_text_ar" value="<?php echo esc_attr($secondary_ar); ?>" data-var="--secondary" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_text_ar">لون النص (text-gray)</label></th>
			<td>
				<input type="color" id="alq_color_text_ar" name="alq_color_text_ar" value="<?php echo esc_attr($textGray_ar); ?>" data-var="--text-gray" class="alq-color-input" />
				<input type="text" name="alq_color_text_text_ar" value="<?php echo esc_attr($textGray_ar); ?>" data-var="--text-gray" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_white_ar">الأبيض (white)</label></th>
			<td>
				<input type="color" id="alq_color_white_ar" name="alq_color_white_ar" value="<?php echo esc_attr($white_ar); ?>" data-var="--white" class="alq-color-input" />
				<input type="text" name="alq_color_white_text_ar" value="<?php echo esc_attr($white_ar); ?>" data-var="--white" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_black_ar">الأسود (black)</label></th>
			<td>
				<input type="color" id="alq_color_black_ar" name="alq_color_black_ar" value="<?php echo esc_attr($black_ar); ?>" data-var="--black" class="alq-color-input" />
				<input type="text" name="alq_color_black_text_ar" value="<?php echo esc_attr($black_ar); ?>" data-var="--black" class="regular-text alq-hex-input" />
			</td>
		</tr>
	</table>
	<?php
		// Extras for Arabic root variables
		$rendered = ['--primary', '--secondary', '--text-gray', '--white', '--black'];
		$extras_ar = [];
		foreach ($defaults_ar as $varName => $varValue) {
			if (in_array($varName, $rendered, true)) continue;
			if (preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', trim($varValue))) {
				$extras_ar[$varName] = strtoupper($varValue);
			}
		}
		if (!empty($extras_ar)) {
			echo '<h3 style="margin-top:20px;">متغيرات ألوان إضافية من :root (النسخة العربية)</h3>';
			echo '<table class="form-table">';
			foreach ($extras_ar as $name => $value) {
				$field_id = 'alq_extra_ar_' . preg_replace('/[^a-z0-9\-]+/i', '_', $name);
				?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($name); ?></label></th>
					<td>
						<input type="color" id="<?php echo esc_attr($field_id); ?>" name="alq_extra_color_ar[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" data-var="<?php echo esc_attr($name); ?>" class="alq-color-input" />
						<input type="text" name="alq_extra_hex_ar[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" data-var="<?php echo esc_attr($name); ?>" class="regular-text alq-hex-input" />
					</td>
				</tr>
				<?php
			}
			echo '</table>';
		}
	?>
	<h2>الألوان - النسخة الإنجليزية</h2>
	<p>تحكم في ألوان النسخة الإنجليزية. عند الحفظ يتم تحديث الملف: <code>assets/en/css/main/base.css</code>. يتم عرض معاينة مباشرة وتحديث حقل HEX تلقائياً.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="alq_color_primary">اللون الأساسي (primary)</label></th>
			<td>
				<input type="color" id="alq_color_primary" name="alq_color_primary" value="<?php echo esc_attr($primary); ?>" data-var="--primary" class="alq-color-input" />
				<input type="text" name="alq_color_primary_text" value="<?php echo esc_attr($primary); ?>" data-var="--primary" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_secondary">اللون الثانوي (secondary)</label></th>
			<td>
				<input type="color" id="alq_color_secondary" name="alq_color_secondary" value="<?php echo esc_attr($secondary); ?>" data-var="--secondary" class="alq-color-input" />
				<input type="text" name="alq_color_secondary_text" value="<?php echo esc_attr($secondary); ?>" data-var="--secondary" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_text">لون النص (text-gray)</label></th>
			<td>
				<input type="color" id="alq_color_text" name="alq_color_text" value="<?php echo esc_attr($textGray); ?>" data-var="--text-gray" class="alq-color-input" />
				<input type="text" name="alq_color_text_text" value="<?php echo esc_attr($textGray); ?>" data-var="--text-gray" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_white">الأبيض (white)</label></th>
			<td>
				<input type="color" id="alq_color_white" name="alq_color_white" value="<?php echo esc_attr($white); ?>" data-var="--white" class="alq-color-input" />
				<input type="text" name="alq_color_white_text" value="<?php echo esc_attr($white); ?>" data-var="--white" class="regular-text alq-hex-input" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="alq_color_black">الأسود (black)</label></th>
			<td>
				<input type="color" id="alq_color_black" name="alq_color_black" value="<?php echo esc_attr($black); ?>" data-var="--black" class="alq-color-input" />
				<input type="text" name="alq_color_black_text" value="<?php echo esc_attr($black); ?>" data-var="--black" class="regular-text alq-hex-input" />
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" class="button button-secondary" name="reset_colors" value="إرجاع الألوان الأساسية (كل النسخ)" />
	</p>
	<?php
		// Render any additional :root variables that look like hex colors
		$rendered = ['--primary', '--secondary', '--text-gray', '--white', '--black'];
		$extras = [];
		foreach ($defaults as $varName => $varValue) {
			if (in_array($varName, $rendered, true)) continue;
			if (preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', trim($varValue))) {
				$extras[$varName] = strtoupper($varValue);
			}
		}
		if (!empty($extras)) {
			echo '<h3 style="margin-top:20px;">متغيرات ألوان إضافية من :root</h3>';
			echo '<table class="form-table">';
			foreach ($extras as $name => $value) {
				$field_id = 'alq_extra_' . preg_replace('/[^a-z0-9\-]+/i', '_', $name);
				?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($name); ?></label></th>
					<td>
						<input type="color" id="<?php echo esc_attr($field_id); ?>" name="alq_extra_color[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" data-var="<?php echo esc_attr($name); ?>" class="alq-color-input" />
						<input type="text" name="alq_extra_hex[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" data-var="<?php echo esc_attr($name); ?>" class="regular-text alq-hex-input" />
					</td>
				</tr>
				<?php
			}
			echo '</table>';
		}
	?>
	<?php
}

/**
 * Save handler for Colors tab.
 * Updates CSS variables in assets/en/css/main/base.css
 */
function AlQasrGroup_save_colors_settings() {
	$map = [
		'--primary'    => AlQasrGroup_pick_color('alq_color_primary', 'alq_color_primary_text'),
		'--secondary'  => AlQasrGroup_pick_color('alq_color_secondary', 'alq_color_secondary_text'),
		'--text-gray'  => AlQasrGroup_pick_color('alq_color_text', 'alq_color_text_text'),
		'--white'      => AlQasrGroup_pick_color('alq_color_white', 'alq_color_white_text'),
		'--black'      => AlQasrGroup_pick_color('alq_color_black', 'alq_color_black_text'),
	];
	$map = array_filter($map); // remove nulls
	if (empty($map)) {
		return;
	}
	AlQasrGroup_colors_write_to_css($map);

	// Handle extra variables submitted dynamically
	$extra_colors = isset($_POST['alq_extra_color']) && is_array($_POST['alq_extra_color']) ? $_POST['alq_extra_color'] : [];
	$extra_hex    = isset($_POST['alq_extra_hex']) && is_array($_POST['alq_extra_hex']) ? $_POST['alq_extra_hex'] : [];
	$extra_map = [];
	foreach ($extra_colors as $varName => $colorVal) {
		$hexVal = isset($extra_hex[$varName]) ? trim((string) $extra_hex[$varName]) : '';
		$pick = $hexVal !== '' ? $hexVal : $colorVal;
		$pick = preg_replace('/[^#a-fA-F0-9]/', '', (string) $pick);
		if ($pick !== '' && $pick[0] !== '#') $pick = '#' . $pick;
		if (preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $pick)) {
			$extra_map[$varName] = strtoupper($pick);
		}
	}
	if (!empty($extra_map)) {
		AlQasrGroup_colors_write_to_css($extra_map);
	}

	// Arabic base.css (assets/css/main/base.css)
	$map_ar = [
		'--primary'    => AlQasrGroup_pick_color('alq_color_primary_ar', 'alq_color_primary_text_ar'),
		'--secondary'  => AlQasrGroup_pick_color('alq_color_secondary_ar', 'alq_color_secondary_text_ar'),
		'--text-gray'  => AlQasrGroup_pick_color('alq_color_text_ar', 'alq_color_text_text_ar'),
		'--white'      => AlQasrGroup_pick_color('alq_color_white_ar', 'alq_color_white_text_ar'),
		'--black'      => AlQasrGroup_pick_color('alq_color_black_ar', 'alq_color_black_text_ar'),
	];
	$map_ar = array_filter($map_ar);
	if (!empty($map_ar)) {
		AlQasrGroup_colors_write_to_css_ar($map_ar);
	}
	$extra_colors_ar = isset($_POST['alq_extra_color_ar']) && is_array($_POST['alq_extra_color_ar']) ? $_POST['alq_extra_color_ar'] : [];
	$extra_hex_ar    = isset($_POST['alq_extra_hex_ar']) && is_array($_POST['alq_extra_hex_ar']) ? $_POST['alq_extra_hex_ar'] : [];
	$extra_map_ar = [];
	foreach ($extra_colors_ar as $varName => $colorVal) {
		$hexVal = isset($extra_hex_ar[$varName]) ? trim((string) $extra_hex_ar[$varName]) : '';
		$pick = $hexVal !== '' ? $hexVal : $colorVal;
		$pick = preg_replace('/[^#a-fA-F0-9]/', '', (string) $pick);
		if ($pick !== '' && $pick[0] !== '#') $pick = '#' . $pick;
		if (preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $pick)) {
			$extra_map_ar[$varName] = strtoupper($pick);
		}
	}
	if (!empty($extra_map_ar)) {
		AlQasrGroup_colors_write_to_css_ar($extra_map_ar);
	}
}

/**
 * Prefer color input; fallback to text. Return sanitized hex or null.
 */
function AlQasrGroup_pick_color($color_field, $text_field) {
	$val = isset($_POST[$color_field]) ? trim((string) $_POST[$color_field]) : '';
	if ($val === '' && isset($_POST[$text_field])) {
		$val = trim((string) $_POST[$text_field]);
	}
	if ($val === '') return null;
	$val = preg_replace('/[^#a-fA-F0-9]/', '', $val);
	if ($val && $val[0] !== '#') {
		$val = '#' . $val;
	}
	// validate length
	if (!preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $val)) {
		return null;
	}
	return $val;
}

/**
 * Read current values from base.css :root block.
 */
function AlQasrGroup_colors_read_from_css() {
	$path = get_template_directory() . '/assets/en/css/main/base.css';
	if (!file_exists($path)) return [];
	$css = file_get_contents($path);
	if ($css === false) return [];
	$vars = [];
	if (preg_match('/:root\s*\{([\s\S]*?)\}/', $css, $m)) {
		$root = $m[1];
		if (preg_match_all('/(--[a-z0-9\-]+)\s*:\s*([^;]+);/i', $root, $mm, PREG_SET_ORDER)) {
			foreach ($mm as $row) {
				$name = trim($row[1]);
				$value = trim($row[2]);
				$vars[$name] = $value;
			}
		}
	}
	return $vars;
}

/**
 * Write variables into base.css by replacing existing variable values.
 */
function AlQasrGroup_colors_write_to_css(array $variables) {
	$path = get_template_directory() . '/assets/en/css/main/base.css';
	if (!file_exists($path) || !is_writable($path)) {
		add_action('admin_notices', function () use ($path) {
			echo '<div class="notice notice-error"><p>تعذر الكتابة إلى الملف: ' . esc_html($path) . ' تأكد من صلاحيات الكتابة.</p></div>';
		});
		return;
	}
	$css = file_get_contents($path);
	if ($css === false) return;

	// Replace each var with new value, preserving spacing
	foreach ($variables as $name => $value) {
		$pattern = '/(' . preg_quote($name, '/') . '\s*:\s*)([^;]+)(;)/';
		$replacement = '$1' . $value . '$3';
		$css = preg_replace($pattern, $replacement, $css, 1);
	}

	// Write back atomically
	$tmp = $path . '.tmp';
	if (file_put_contents($tmp, $css) !== false) {
		@rename($tmp, $path);
	} else {
		@unlink($tmp);
		add_action('admin_notices', function () {
			echo '<div class="notice notice-error"><p>فشل حفظ التغييرات في ملف الأنماط.</p></div>';
		});
		return;
	}

	// Bust caches by appending filemtime via inline admin notice preview link if needed
	add_action('admin_notices', function () {
		echo '<div class="notice notice-success is-dismissible"><p>تم تحديث ألوان الموقع في ملف CSS بنجاح.</p></div>';
	});
}

/**
 * Arabic helpers targeting assets/css/main/base.css
 */
function AlQasrGroup_colors_read_from_css_ar() {
	$path = get_template_directory() . '/assets/css/main/base.css';
	if (!file_exists($path)) return [];
	$css = file_get_contents($path);
	if ($css === false) return [];
	$vars = [];
	if (preg_match('/:root\s*\{([\s\S]*?)\}/', $css, $m)) {
		$root = $m[1];
		if (preg_match_all('/(--[a-z0-9\-]+)\s*:\s*([^;]+);/i', $root, $mm, PREG_SET_ORDER)) {
			foreach ($mm as $row) {
				$name = trim($row[1]);
				$value = trim($row[2]);
				$vars[$name] = $value;
			}
		}
	}
	return $vars;
}

function AlQasrGroup_colors_write_to_css_ar(array $variables) {
	$path = get_template_directory() . '/assets/css/main/base.css';
	if (!file_exists($path) || !is_writable($path)) {
		add_action('admin_notices', function () use ($path) {
			echo '<div class="notice notice-error"><p>تعذر الكتابة إلى الملف: ' . esc_html($path) . ' تأكد من صلاحيات الكتابة.</p></div>';
		});
		return;
	}
	$css = file_get_contents($path);
	if ($css === false) return;
	foreach ($variables as $name => $value) {
		$pattern = '/(' . preg_quote($name, '/') . '\s*:\s*)([^;]+)(;)/';
		$replacement = '$1' . $value . '$3';
		$css = preg_replace($pattern, $replacement, $css, 1);
	}
	$tmp = $path . '.tmp';
	if (file_put_contents($tmp, $css) !== false) {
		@rename($tmp, $path);
	} else {
		@unlink($tmp);
		add_action('admin_notices', function () {
			echo '<div class="notice notice-error"><p>فشل حفظ التغييرات في ملف الأنماط (العربي).</p></div>';
		});
		return;
	}
	add_action('admin_notices', function () {
		echo '<div class="notice notice-success is-dismissible"><p>تم تحديث ألوان النسخة العربية في ملف CSS بنجاح.</p></div>';
	});
}

/**
 * Reset defaults for both EN and AR base.css files
 */
function AlQasrGroup_colors_reset_defaults_all() {
	$defaults = [
		'--primary' => '#282828',
		'--secondary' => '#FF5A00',
		'--secondary-orange' => '#FF5A00',
		'--text-gray' => '#616161',
		'--paragraph-grey' => '#4A4949',
		'--gray-light' => '#BDBDBD',
		'--gray-dark' => '#828282',
		'--white' => '#FBFBFB',
		'--black' => '#141415',
		'--secandary-color' => 'rgba(237, 134, 59, 1)',
		'--gray-400' => 'rgba(152, 162, 179, 1)',
		'--gray-500' => 'rgba(102, 112, 133, 1)',
		'--gray-600' => 'rgba(71, 84, 103, 1)',
		'--gray-300' => 'rgba(208, 213, 221, 1)',
		'--gray-4' => 'rgba(189, 189, 189, 1)',
		'--gray-3' => 'rgba(130, 130, 130, 1)',
		'--gray-5' => 'rgba(224, 224, 224, 1)',
		'--white-200' => 'rgba(255, 255, 255, 0.08)',
	];
	AlQasrGroup_colors_write_to_css($defaults);
	AlQasrGroup_colors_write_to_css_ar($defaults);
}


