<?php
/**
 * Contact Form Handler
 * معالج نموذج الاتصال
 * 
 * Handles contact form submissions via AJAX
 * يتعامل مع إرسال نموذج الاتصال عبر AJAX
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handle Contact Form Submission via AJAX
 * معالجة إرسال نموذج الاتصال عبر AJAX
 */
function AlQasrGroup_handle_contact_form() {
    // Rate limiting - prevent spam
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $transient_key = 'contact_form_' . md5($ip_address);
    $last_submission = get_transient($transient_key);
    
    if ($last_submission !== false) {
        wp_send_json_error('يرجى الانتظار قبل إرسال رسالة أخرى. يمكنك المحاولة مرة أخرى بعد دقيقة.');
        return;
    }
    
    // Set transient for 60 seconds (rate limiting)
    set_transient($transient_key, time(), 60);
    
    // Verify nonce for security
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'AlQasrGroup_contact_nonce')) {
        wp_send_json_error('خطأ في التحقق من الأمان. يرجى تحديث الصفحة والمحاولة مرة أخرى.');
        return;
    }
    
    // Get current language from POST data
    $current_language = isset($_POST['current_language']) ? sanitize_text_field($_POST['current_language']) : 'ar';
    if (!in_array($current_language, ['ar', 'en'])) {
        $current_language = 'ar';
    }
    $is_english = ($current_language === 'en');
    
    // Sanitize and validate data
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $second_name = isset($_POST['second_name']) ? sanitize_text_field($_POST['second_name']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    
    // Validate required fields
    if (empty($first_name) || empty($second_name) || empty($phone) || empty($message)) {
        wp_send_json_error('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    // Validate field lengths to prevent buffer overflow attacks
    if (strlen($first_name) > 100) {
        wp_send_json_error('الأسم الأول طويل جداً (الحد الأقصى 100 حرف)');
        return;
    }
    
    if (strlen($second_name) > 100) {
        wp_send_json_error('الأسم الأخير طويل جداً (الحد الأقصى 100 حرف)');
        return;
    }
    
    if (strlen($phone) > 50) {
        wp_send_json_error('رقم الهاتف طويل جداً (الحد الأقصى 50 حرف)');
        return;
    }
    
    if (strlen($email) > 100) {
        wp_send_json_error('البريد الإلكتروني طويل جداً (الحد الأقصى 100 حرف)');
        return;
    }
    
    if (strlen($message) > 5000) {
        wp_send_json_error('الرسالة طويلة جداً (الحد الأقصى 5000 حرف)');
        return;
    }
    
    // Validate email format if provided
    if (!empty($email) && !is_email($email)) {
        wp_send_json_error('البريد الإلكتروني غير صحيح');
        return;
    }
    
    // Additional sanitization - remove any HTML tags that might have slipped through
    $first_name = wp_strip_all_tags($first_name);
    $second_name = wp_strip_all_tags($second_name);
    $phone = wp_strip_all_tags($phone);
    // Message can contain line breaks, so we sanitize differently
    $message = wp_kses_post($message); // Allow basic HTML tags for formatting
    $message = wp_strip_all_tags($message); // Then strip all tags for safety
    
    // Create HTML table for post content
    $html_table = AlQasrGroup_build_contact_submission_table($first_name, $second_name, $email, $phone, $message);
    
    // Create post
    $post_title = $first_name . ' ' . $second_name;
    // Limit title length (WordPress default is 200 characters)
    if (strlen($post_title) > 200) {
        $post_title = substr($post_title, 0, 197) . '...';
    }
    
    $post_data = array(
        'post_title' => sanitize_text_field($post_title),
        'post_content' => $html_table, // HTML table is already escaped
        'post_status' => 'private', // Private posts - only admins can view
        'post_type' => 'contact_submissions',
        'post_author' => 1 // Set default author
    );
    
    // Use wp_insert_post with proper sanitization
    $post_id = wp_insert_post($post_data, true); // Set to true to return WP_Error on failure
    
    if ($post_id && !is_wp_error($post_id)) {
        // Save meta data - WordPress automatically sanitizes meta values
        update_post_meta($post_id, 'contact_email', $email);
        update_post_meta($post_id, 'contact_phone', $phone);
        update_post_meta($post_id, 'contact_message', $message);
        update_post_meta($post_id, 'contact_first_name', $first_name);
        update_post_meta($post_id, 'contact_second_name', $second_name);
        update_post_meta($post_id, 'contact_ip_address', $ip_address); // Store IP for security tracking
        update_post_meta($post_id, 'contact_submission_date', current_time('mysql'));
        
        // Send email notification to admin
        AlQasrGroup_send_contact_notification_email($first_name, $second_name, $email, $phone, $message, $ip_address);
        
        // Success message based on language
        $success_message = $is_english ? 'Your message has been sent successfully!' : 'تم إرسال رسالتك بنجاح!';
        wp_send_json_success($success_message);
    } else {
        $error_message = is_wp_error($post_id) ? $post_id->get_error_message() : 'حدث خطأ أثناء إرسال الرسالة';
        wp_send_json_error($error_message);
    }
}

/**
 * Build HTML table for contact submission
 * بناء جدول HTML لرسالة الاتصال
 * 
 * @param string $first_name
 * @param string $second_name
 * @param string $email
 * @param string $phone
 * @param string $message
 * @return string HTML table
 */
function AlQasrGroup_build_contact_submission_table($first_name, $second_name, $email, $phone, $message) {
    // Create HTML table for post content - all user input is escaped to prevent XSS
    $html_table = '<table class="contact-submission-table" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px 0;">';
    $html_table .= '<tbody>';
    
    $html_table .= '<tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">';
    $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; width: 30%; border: 1px solid #dee2e6;">الأسم الأول</th>';
    $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6;">' . esc_html($first_name) . '</td>';
    $html_table .= '</tr>';
    
    $html_table .= '<tr style="background-color: #ffffff;">';
    $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; border: 1px solid #dee2e6;">الأسم الأخير</th>';
    $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6;">' . esc_html($second_name) . '</td>';
    $html_table .= '</tr>';
    
    if (!empty($email)) {
        // Double escape email for maximum security
        $safe_email = esc_attr($email);
        $html_table .= '<tr style="background-color: #f8f9fa;">';
        $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; border: 1px solid #dee2e6;">البريد الإلكتروني</th>';
        $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6;"><a href="mailto:' . $safe_email . '" style="color: #007bff; text-decoration: none;">' . esc_html($email) . '</a></td>';
        $html_table .= '</tr>';
    }
    
    // Escape phone number properly
    $safe_phone = esc_attr($phone);
    $html_table .= '<tr style="background-color: #ffffff;">';
    $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; border: 1px solid #dee2e6;">رقم الجوال</th>';
    $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6;"><a href="tel:' . $safe_phone . '" style="color: #007bff; text-decoration: none;">' . esc_html($phone) . '</a></td>';
    $html_table .= '</tr>';
    
    // Escape message and preserve line breaks safely
    $safe_message = esc_html($message);
    $safe_message = nl2br($safe_message); // Convert line breaks to <br> after escaping
    $html_table .= '<tr style="background-color: #f8f9fa;">';
    $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; vertical-align: top; border: 1px solid #dee2e6;">الرسالة</th>';
    $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6; white-space: pre-wrap;">' . $safe_message . '</td>';
    $html_table .= '</tr>';
    
    // Date is safe - no user input
    $safe_date = esc_html(current_time('Y-m-d H:i:s'));
    $html_table .= '<tr style="background-color: #ffffff;">';
    $html_table .= '<th style="padding: 12px 15px; text-align: right; font-weight: bold; color: #495057; border: 1px solid #dee2e6;">تاريخ الإرسال</th>';
    $html_table .= '<td style="padding: 12px 15px; text-align: right; border: 1px solid #dee2e6;">' . $safe_date . '</td>';
    $html_table .= '</tr>';
    
    $html_table .= '</tbody>';
    $html_table .= '</table>';
    
    return $html_table;
}

/**
 * Send email notification to admin
 * إرسال إشعار بريد إلكتروني للمسؤول
 * 
 * @param string $first_name
 * @param string $second_name
 * @param string $email
 * @param string $phone
 * @param string $message
 * @param string $ip_address
 */
function AlQasrGroup_send_contact_notification_email($first_name, $second_name, $email, $phone, $message, $ip_address) {
    $admin_email = sanitize_email(get_option('admin_email'));
    if (!is_email($admin_email)) {
        return;
    }
    
    $safe_subject = esc_html('رسالة اتصال جديدة من ' . $first_name);
    $body = "رسالة اتصال جديدة:\n\n";
    $body .= "الاسم: " . esc_html($first_name . ' ' . $second_name) . "\n";
    $body .= "البريد الإلكتروني: " . esc_html($email) . "\n";
    $body .= "رقم الهاتف: " . esc_html($phone) . "\n";
    $body .= "الرسالة: " . esc_html($message) . "\n";
    $body .= "عنوان IP: " . esc_html($ip_address) . "\n";
    
    // Use wp_mail with proper headers to prevent email injection
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail($admin_email, $safe_subject, $body, $headers);
}

// Register AJAX handlers
add_action('wp_ajax_AlQasrGroup_contact_form', 'AlQasrGroup_handle_contact_form');
add_action('wp_ajax_nopriv_AlQasrGroup_contact_form', 'AlQasrGroup_handle_contact_form');

