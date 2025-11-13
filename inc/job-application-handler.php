<?php
/**
 * Job Application Handler
 * معالج طلبات الوظائف
 *
 * Handles secure processing of job application submissions including CV uploads.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle job application AJAX submissions.
 */
function AlQasrGroup_handle_job_application() {
    // Basic capability check - only process on frontend
    if (!defined('DOING_AJAX') || !DOING_AJAX) {
        wp_send_json_error(__('طلب غير صالح.', 'AlQasrGroup'));
    }

    // Rate limiting by IP
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $rate_limit_key = 'job_application_' . md5($ip_address);
    $last_attempt = get_transient($rate_limit_key);
    if ($last_attempt !== false) {
        wp_send_json_error(__('يرجى الانتظار قبل إعادة المحاولة.', 'AlQasrGroup'));
    }
    set_transient($rate_limit_key, time(), 90); // 90 seconds lock

    // Verify nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'AlQasrGroup_job_application_nonce')) {
        wp_send_json_error(__('فشل التحقق الأمني. يرجى تحديث الصفحة والمحاولة مجدداً.', 'AlQasrGroup'));
    }

    // Determine language
    $current_language = isset($_POST['current_language']) ? sanitize_text_field(wp_unslash($_POST['current_language'])) : 'ar';
    if (!in_array($current_language, array('ar', 'en'), true)) {
        $current_language = 'ar';
    }
    $is_english = ($current_language === 'en');

    // Sanitize fields
    $job_id       = isset($_POST['job_id']) ? absint($_POST['job_id']) : 0;
    $first_name   = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name    = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $phone        = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $email        = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

    // Validate job
    if (!$job_id || get_post_type($job_id) !== 'jobs') {
        wp_send_json_error($is_english ? __('Invalid job reference.', 'AlQasrGroup') : __('طلب وظيفة غير صالح.', 'AlQasrGroup'));
    }

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($phone)) {
        wp_send_json_error($is_english ? __('Please fill out all required fields.', 'AlQasrGroup') : __('يرجى ملء جميع الحقول المطلوبة.', 'AlQasrGroup'));
    }

    // Length constraints
    $field_limits = array(
        'first_name' => 100,
        'last_name'  => 100,
        'phone'      => 50,
        'email'      => 150,
    );

    foreach ($field_limits as $field_key => $limit) {
        $value = $$field_key;
        if (!empty($value) && mb_strlen($value) > $limit) {
            wp_send_json_error($is_english ? __('Provided data is too long.', 'AlQasrGroup') : __('تم تجاوز الحد المسموح للحروف.', 'AlQasrGroup'));
        }
    }

    if (!empty($email) && !is_email($email)) {
        wp_send_json_error($is_english ? __('Please provide a valid email address.', 'AlQasrGroup') : __('يرجى إدخال بريد إلكتروني صالح.', 'AlQasrGroup'));
    }

    // Validate CV file
    if (!isset($_FILES['cv_file'])) {
        wp_send_json_error($is_english ? __('Please attach your resume.', 'AlQasrGroup') : __('يرجى إرفاق السيرة الذاتية.', 'AlQasrGroup'));
    }

    $cv_file = $_FILES['cv_file'];

    if (!empty($cv_file['error'])) {
        wp_send_json_error($is_english ? __('Failed to upload file. Please try again.', 'AlQasrGroup') : __('تعذر رفع الملف، يرجى المحاولة مرة أخرى.', 'AlQasrGroup'));
    }

    // File size validation (max 5MB)
    $max_file_size = apply_filters('AlQasrGroup_job_application_max_size', 5 * 1024 * 1024);
    if ((int) $cv_file['size'] > $max_file_size) {
        wp_send_json_error($is_english ? __('File is too large. Maximum size is 5MB.', 'AlQasrGroup') : __('حجم الملف كبير جداً، الحد الأقصى 5 ميجابايت.', 'AlQasrGroup'));
    }

    // Validate mime type and extension
    $allowed_mimes = array(
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );

    $filetype = wp_check_filetype_and_ext($cv_file['tmp_name'], $cv_file['name'], $allowed_mimes);
    if (empty($filetype['ext']) || empty($filetype['type']) || !in_array($filetype['type'], $allowed_mimes, true)) {
        wp_send_json_error($is_english ? __('Unsupported file format. Allowed formats: PDF, DOC, DOCX.', 'AlQasrGroup') : __('صيغة الملف غير مدعومة. الصيغ المسموح بها: PDF, DOC, DOCX.', 'AlQasrGroup'));
    }

    // Prepare for media upload
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Upload CV securely
    add_filter('upload_mimes', 'AlQasrGroup_allow_job_application_mimes');
    $attachment_id = media_handle_upload('cv_file', 0, array(), array(
        'test_form' => false,
        'mimes'     => $allowed_mimes,
    ));
    remove_filter('upload_mimes', 'AlQasrGroup_allow_job_application_mimes');

    if (is_wp_error($attachment_id)) {
        wp_send_json_error($is_english ? __('Unable to save the uploaded file.', 'AlQasrGroup') : __('تعذر حفظ الملف المرفوع.', 'AlQasrGroup'));
    }

    // Create job application post
    $job_title = get_the_title($job_id);
    $post_title = trim(sprintf('%s %s - %s', $first_name, $last_name, $job_title));
    if (mb_strlen($post_title) > 190) {
        $post_title = mb_substr($post_title, 0, 187) . '...';
    }

    $application_post = array(
        'post_title'  => $post_title ? $post_title : sprintf(__('طلب وظيفة - %s', 'AlQasrGroup'), $job_title),
        'post_status' => 'private',
        'post_type'   => 'job_applications',
        'post_parent' => $job_id,
        'post_author' => get_current_user_id() ?: 0,
    );

    $application_id = wp_insert_post($application_post, true);

    if (is_wp_error($application_id) || !$application_id) {
        if ($attachment_id) {
            wp_delete_attachment($attachment_id, true);
        }
        wp_send_json_error($is_english ? __('Unable to save the application.', 'AlQasrGroup') : __('تعذر حفظ طلب الوظيفة.', 'AlQasrGroup'));
    }

    // Store meta data securely
    update_post_meta($application_id, 'job_application_first_name', wp_strip_all_tags($first_name));
    update_post_meta($application_id, 'job_application_last_name', wp_strip_all_tags($last_name));
    update_post_meta($application_id, 'job_application_phone', wp_strip_all_tags($phone));
    update_post_meta($application_id, 'job_application_email', $email);
    update_post_meta($application_id, 'job_application_job_id', $job_id);
    update_post_meta($application_id, 'job_application_cv_id', $attachment_id);
    update_post_meta($application_id, 'job_application_ip', $ip_address);
    update_post_meta($application_id, 'job_application_user_agent', isset($_SERVER['HTTP_USER_AGENT']) ? substr(sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])), 0, 255) : '');
    update_post_meta($application_id, 'job_application_submitted_at', current_time('mysql'));

    // Send admin notification
    AlQasrGroup_send_job_application_notification($application_id, $job_id, $first_name, $last_name, $email, $phone, $attachment_id);

    $success_message = $is_english
        ? __('Your application has been submitted successfully. Thank you!', 'AlQasrGroup')
        : __('تم استلام طلبك بنجاح، شكراً لك!', 'AlQasrGroup');

    wp_send_json_success($success_message);
}

function AlQasrGroup_allow_job_application_mimes($mimes) {
    $mimes['pdf']  = 'application/pdf';
    $mimes['doc']  = 'application/msword';
    $mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    return $mimes;
}

function AlQasrGroup_send_job_application_notification($application_id, $job_id, $first_name, $last_name, $email, $phone, $attachment_id) {
    $admin_email = sanitize_email(get_option('admin_email'));
    if (empty($admin_email) || !is_email($admin_email)) {
        return;
    }

    $job_title = get_the_title($job_id);
    $applicant_name = trim($first_name . ' ' . $last_name);
    $subject = sprintf(__('طلب وظيفة جديد من %s', 'AlQasrGroup'), $applicant_name);

    $body_lines = array(
        sprintf(__('اسم المتقدم: %s', 'AlQasrGroup'), esc_html($applicant_name)),
        sprintf(__('البريد الإلكتروني: %s', 'AlQasrGroup'), esc_html($email)),
        sprintf(__('رقم الهاتف: %s', 'AlQasrGroup'), esc_html($phone)),
        sprintf(__('الوظيفة المتقدم لها: %s', 'AlQasrGroup'), esc_html($job_title)),
        sprintf(__('رابط الطلب: %s', 'AlQasrGroup'), esc_url(get_edit_post_link($application_id))),
    );

    $body = implode("\n", $body_lines);

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail($admin_email, $subject, $body, $headers);
}

add_action('wp_ajax_AlQasrGroup_job_application', 'AlQasrGroup_handle_job_application');
add_action('wp_ajax_nopriv_AlQasrGroup_job_application', 'AlQasrGroup_handle_job_application');

