<?php

/**
 * Export Functions
 * دوال التصدير العامة
 */

// Check if PhpSpreadsheet is available
function AlQasrGroup_check_spreadsheet_library() {
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        // Try to include via Composer autoload
        $autoload_path = get_template_directory() . '/vendor/autoload.php';
        if (file_exists($autoload_path)) {
            require_once $autoload_path;
        }
        
        // If still not available, show error
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return false;
        }
    }
    return true;
}

/**
 * Export data to Excel file
 * تصدير البيانات إلى ملف Excel
 * 
 * @param array $data Array of data to export
 * @param array $headers Array of column headers
 * @param string $filename Name of the file (without extension)
 * @param string $sheet_name Name of the worksheet
 * @return bool|string Returns file path on success, false on failure
 */
function AlQasrGroup_export_to_excel($data, $headers = array(), $filename = 'export', $sheet_name = 'Sheet1') {
    
    // Check if PhpSpreadsheet is available
    if (!AlQasrGroup_check_spreadsheet_library()) {
        // Fallback to CSV if PhpSpreadsheet is not available
        return AlQasrGroup_export_to_csv($data, $headers, $filename);
    }
    
    try {
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set sheet name
        $sheet->setTitle($sheet_name);
        
        // Add headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }
        
        // Add data
        $row = 2;
        foreach ($data as $row_data) {
            $col = 'A';
            foreach ($row_data as $cell_data) {
                $sheet->setCellValue($col . $row, $cell_data);
                $col++;
            }
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Set file path
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/exports/';
        
        // Create directory if it doesn't exist
        if (!file_exists($file_path)) {
            wp_mkdir_p($file_path);
        }
        
        $full_filename = $file_path . $filename . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Save file
        $writer->save($full_filename);
        
        return $full_filename;
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Export data to CSV file (fallback)
 * تصدير البيانات إلى ملف CSV (بديل)
 * 
 * @param array $data Array of data to export
 * @param array $headers Array of column headers
 * @param string $filename Name of the file (without extension)
 * @return bool|string Returns file path on success, false on failure
 */
function AlQasrGroup_export_to_csv($data, $headers = array(), $filename = 'export') {
    
    try {
        // Set file path
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/exports/';
        
        // Create directory if it doesn't exist
        if (!file_exists($file_path)) {
            wp_mkdir_p($file_path);
        }
        
        $full_filename = $file_path . $filename . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Create file
        $file = fopen($full_filename, 'w');
        
        // Add BOM for UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        if (!empty($headers)) {
            fputcsv($file, $headers);
        }
        
        // Add data
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        return $full_filename;
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Download file to browser
 * تحميل الملف إلى المتصفح
 * 
 * @param string $file_path Path to the file
 * @param string $filename Name for download
 * @return void
 */
function AlQasrGroup_download_file($file_path, $filename = '') {
    
    if (!file_exists($file_path)) {
        wp_die('الملف غير موجود');
    }
    
    // Get file info
    $file_info = pathinfo($file_path);
    $extension = $file_info['extension'];
    
    // Set content type
    $content_types = array(
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'csv' => 'text/csv',
        'pdf' => 'application/pdf'
    );
    
    $content_type = isset($content_types[$extension]) ? $content_types[$extension] : 'application/octet-stream';
    
    // Set filename for download
    if (empty($filename)) {
        $filename = $file_info['basename'];
    }
    
    // Clear any output
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers
    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    // Output file
    readfile($file_path);
    exit;
}

/**
 * Get contact submissions data for export
 * الحصول على بيانات رسائل الاتصال للتصدير
 * 
 * @return array Array of data and headers
 */
function AlQasrGroup_get_contact_submissions_export_data() {
    
    // Use WP_Query to get all contact submissions including private posts
    $query_args = array(
        'post_type' => 'contact_submissions',
        'post_status' => array('publish', 'private', 'draft'), // Include all statuses except trash
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true, // Performance optimization
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    );
    
    $query = new WP_Query($query_args);
    $submissions = $query->posts;
    
    $headers = array('الأسم الأول', 'الأسم الأخير', 'البريد الإلكتروني', 'رقم الهاتف', 'الرسالة', 'التاريخ');
    $data = array();
    
    foreach ($submissions as $submission) {
        $first_name = get_post_meta($submission->ID, 'contact_first_name', true);
        $second_name = get_post_meta($submission->ID, 'contact_second_name', true);
        
        // Fallback to post title if meta doesn't exist (for old entries)
        if (empty($first_name) && empty($second_name)) {
            $full_name = $submission->post_title;
            $name_parts = explode(' ', $full_name, 2);
            $first_name = isset($name_parts[0]) ? $name_parts[0] : $full_name;
            $second_name = isset($name_parts[1]) ? $name_parts[1] : '';
        }
        
        $data[] = array(
            $first_name,
            $second_name,
            get_post_meta($submission->ID, 'contact_email', true),
            get_post_meta($submission->ID, 'contact_phone', true),
            get_post_meta($submission->ID, 'contact_message', true),
            get_the_date('Y-m-d H:i:s', $submission->ID)
        );
    }
    
    // Clean up query
    wp_reset_postdata();
    
    return array(
        'headers' => $headers,
        'data' => $data
    );
}

/**
 * Export contact submissions to Excel
 * تصدير رسائل الاتصال إلى Excel
 * 
 * @return bool|string Returns file path on success, false on failure
 */
function AlQasrGroup_export_contact_submissions_excel() {
    
    // Get export data
    $export_data = AlQasrGroup_get_contact_submissions_export_data();
    
    // Export to Excel
    $file_path = AlQasrGroup_export_to_excel(
        $export_data['data'],
        $export_data['headers'],
        'contact_submissions',
        'رسائل الاتصال'
    );
    
    return $file_path;
}

/**
 * Clean old export files
 * تنظيف ملفات التصدير القديمة
 * 
 * @param int $days_old Number of days to keep files (default: 7)
 * @return int Number of files deleted
 */
function AlQasrGroup_clean_old_export_files($days_old = 7) {
    
    $upload_dir = wp_upload_dir();
    $export_dir = $upload_dir['basedir'] . '/exports/';
    
    if (!file_exists($export_dir)) {
        return 0;
    }
    
    $files = glob($export_dir . '*.{xlsx,csv}', GLOB_BRACE);
    $deleted_count = 0;
    $cutoff_time = time() - ($days_old * 24 * 60 * 60);
    
    foreach ($files as $file) {
        if (filemtime($file) < $cutoff_time) {
            if (unlink($file)) {
                $deleted_count++;
            }
        }
    }
    
    return $deleted_count;
}

// Clean old files weekly
add_action('wp_scheduled_delete', 'AlQasrGroup_clean_old_export_files');

/**
 * Get newsletter subscriptions data for export
 * الحصول على بيانات اشتراكات النشرة الإخبارية للتصدير
 * 
 * @return array Array of data and headers
 */
function AlQasrGroup_get_newsletter_subscriptions_export_data() {
    
    $args = array(
        'post_type' => 'newsletters',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $subscriptions = get_posts($args);
    
    $headers = array('البريد الإلكتروني', 'تاريخ الاشتراك', 'الحالة', 'تاريخ الإنشاء');
    $data = array();
    
    foreach ($subscriptions as $subscription) {
        $email = get_post_meta($subscription->ID, 'newsletter_email', true);
        $subscription_date = get_post_meta($subscription->ID, 'subscription_date', true);
        $status = get_post_meta($subscription->ID, 'subscription_status', true);
        $status_text = $status === 'unsubscribed' ? 'ملغي الاشتراك' : 'مشترك';
        
        $data[] = array(
            $email,
            $subscription_date ? date('Y-m-d H:i:s', strtotime($subscription_date)) : get_the_date('Y-m-d H:i:s', $subscription->ID),
            $status_text,
            get_the_date('Y-m-d H:i:s', $subscription->ID)
        );
    }
    
    return array(
        'headers' => $headers,
        'data' => $data
    );
}

/**
 * Export newsletter subscriptions to Excel
 * تصدير اشتراكات النشرة الإخبارية إلى Excel
 * 
 * @return bool|string Returns file path on success, false on failure
 */
function AlQasrGroup_export_newsletter_subscriptions_excel() {
    
    // Get export data
    $export_data = AlQasrGroup_get_newsletter_subscriptions_export_data();
    
    // Export to Excel
    $file_path = AlQasrGroup_export_to_excel(
        $export_data['data'],
        $export_data['headers'],
        'newsletter_subscriptions',
        'اشتراكات النشرة الإخبارية'
    );
    
    return $file_path;
}
