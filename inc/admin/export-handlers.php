<?php

/**
 * Export Handlers
 * معالجات التصدير
 */

// Add Export to Excel functionality
function AlQasrGroup_add_export_button() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'contact_submissions' && $screen->base === 'edit') {
        // Add button next to page title using JavaScript
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Add export button after page title
            if ($('.wp-heading-inline').length && $('#exportExcelBtn').length === 0) {
                $('.wp-heading-inline').after('<button type="button" id="exportExcelBtn" class="page-title-action" style="margin-right: 10px;">تصدير إلى Excel</button>');
            }
            
            $('#exportExcelBtn').on('click', function() {
                var $btn = $(this);
                var originalText = $btn.text();
                
                // Show loading
                $btn.text('جاري التصدير...').prop('disabled', true);
                
                // Make AJAX request
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'AlQasrGroup_export_contact_submissions_ajax',
                        nonce: '<?php echo wp_create_nonce('AlQasrGroup_export_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            try {
                                // Create download link
                                var link = document.createElement('a');
                                
                                // Determine content type based on filename
                                var contentType = response.data.filename.endsWith('.xlsx') 
                                    ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                    : 'text/csv;charset=utf-8';
                                
                                link.href = 'data:' + contentType + ';base64,' + response.data.file;
                                link.download = response.data.filename;
                                link.style.display = 'none';
                                
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                                
                                // Show success message
                                $('<div class="notice notice-success is-dismissible"><p>تم تصدير البيانات بنجاح!</p></div>')
                                    .insertAfter('.wrap h1')
                                    .delay(3000)
                                    .fadeOut();
                                    
                            } catch (e) {
                                console.error('Download error:', e);
                                alert('حدث خطأ أثناء تحميل الملف');
                            }
                        } else {
                            $('<div class="notice notice-error is-dismissible"><p>حدث خطأ أثناء التصدير: ' + response.data + '</p></div>')
                                .insertAfter('.wrap h1');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        $('<div class="notice notice-error is-dismissible"><p>حدث خطأ في الاتصال</p></div>')
                            .insertAfter('.wrap h1');
                    },
                    complete: function() {
                        // Reset button
                        $btn.text(originalText).prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
}
add_action('admin_notices', 'AlQasrGroup_add_export_button');

// Handle Export via AJAX
function AlQasrGroup_export_contact_submissions_ajax() {
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('غير مصرح لك بالوصول إلى هذه الصفحة');
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'AlQasrGroup_export_nonce')) {
        wp_send_json_error('خطأ في التحقق من الأمان');
    }
    
    try {
        // Get export data
        $export_data = AlQasrGroup_get_contact_submissions_export_data();
        
        // Check if PhpSpreadsheet is available
        if (AlQasrGroup_check_spreadsheet_library()) {
            // Create Excel file in memory
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set sheet name
            $sheet->setTitle('رسائل الاتصال');
            
            // Add headers
            $col = 'A';
            foreach ($export_data['headers'] as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }
            
            // Add data
            $row = 2;
            foreach ($export_data['data'] as $row_data) {
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
            
            // Create Excel file in memory
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            // Capture output
            ob_start();
            $writer->save('php://output');
            $excel_data = ob_get_clean();
            
            // Convert to base64
            $base64_data = base64_encode($excel_data);
            
            wp_send_json_success(array(
                'file' => $base64_data,
                'filename' => 'contact_submissions_' . date('Y-m-d_H-i-s') . '.xlsx'
            ));
            
        } else {
            // Fallback to CSV
            $csv_data = '';
            
            // Add BOM for UTF-8
            $csv_data .= chr(0xEF).chr(0xBB).chr(0xBF);
            
            // Add headers
            $csv_data .= implode(',', array_map('addslashes', $export_data['headers'])) . "\n";
            
            // Add data
            foreach ($export_data['data'] as $row) {
                $csv_data .= implode(',', array_map('addslashes', $row)) . "\n";
            }
            
            // Convert to base64
            $base64_data = base64_encode($csv_data);
            
            wp_send_json_success(array(
                'file' => $base64_data,
                'filename' => 'contact_submissions_' . date('Y-m-d_H-i-s') . '.csv'
            ));
        }
        
    } catch (Exception $e) {
        wp_send_json_error('حدث خطأ أثناء إنشاء الملف: ' . $e->getMessage());
    }
}
add_action('wp_ajax_AlQasrGroup_export_contact_submissions_ajax', 'AlQasrGroup_export_contact_submissions_ajax');

// Add Export button for Newsletter Subscriptions
function AlQasrGroup_add_newsletter_export_button() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'newsletters') {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">اشتراكات النشرة الإخبارية</h1>
            <button type="button" id="exportNewsletterBtn" class="page-title-action">
                تصدير إلى Excel
            </button>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#exportNewsletterBtn').on('click', function() {
                var $btn = $(this);
                var originalText = $btn.text();
                
                // Show loading
                $btn.text('جاري التصدير...').prop('disabled', true);
                
                // Make AJAX request
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'AlQasrGroup_export_newsletter_subscriptions_ajax',
                        nonce: '<?php echo wp_create_nonce('AlQasrGroup_export_newsletter_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            try {
                                // Create download link
                                var link = document.createElement('a');
                                
                                // Determine content type based on filename
                                var contentType = response.data.filename.endsWith('.xlsx') 
                                    ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                    : 'text/csv;charset=utf-8';
                                
                                link.href = 'data:' + contentType + ';base64,' + response.data.file;
                                link.download = response.data.filename;
                                link.style.display = 'none';
                                
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                                
                                // Show success message
                                $('<div class="notice notice-success is-dismissible"><p>تم تصدير البيانات بنجاح!</p></div>')
                                    .insertAfter('.wrap h1')
                                    .delay(3000)
                                    .fadeOut();
                                    
                            } catch (e) {
                                console.error('Download error:', e);
                                alert('حدث خطأ أثناء تحميل الملف');
                            }
                        } else {
                            $('<div class="notice notice-error is-dismissible"><p>حدث خطأ أثناء التصدير: ' + response.data + '</p></div>')
                                .insertAfter('.wrap h1');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        $('<div class="notice notice-error is-dismissible"><p>حدث خطأ في الاتصال</p></div>')
                            .insertAfter('.wrap h1');
                    },
                    complete: function() {
                        // Reset button
                        $btn.text(originalText).prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
}
add_action('admin_notices', 'AlQasrGroup_add_newsletter_export_button');

// Handle Newsletter Export via AJAX
function AlQasrGroup_export_newsletter_subscriptions_ajax() {
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('غير مصرح لك بالوصول إلى هذه الصفحة');
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'AlQasrGroup_export_newsletter_nonce')) {
        wp_send_json_error('خطأ في التحقق من الأمان');
    }
    
    try {
        // Get export data
        $export_data = AlQasrGroup_get_newsletter_subscriptions_export_data();
        
        // Check if PhpSpreadsheet is available
        if (AlQasrGroup_check_spreadsheet_library()) {
            // Create Excel file in memory
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set sheet name
            $sheet->setTitle('اشتراكات النشرة الإخبارية');
            
            // Add headers
            $col = 'A';
            foreach ($export_data['headers'] as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }
            
            // Add data
            $row = 2;
            foreach ($export_data['data'] as $row_data) {
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
            
            // Create Excel file in memory
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            // Capture output
            ob_start();
            $writer->save('php://output');
            $excel_data = ob_get_clean();
            
            // Convert to base64
            $base64_data = base64_encode($excel_data);
            
            wp_send_json_success(array(
                'file' => $base64_data,
                'filename' => 'newsletter_subscriptions_' . date('Y-m-d_H-i-s') . '.xlsx'
            ));
            
        } else {
            // Fallback to CSV
            $csv_data = '';
            
            // Add BOM for UTF-8
            $csv_data .= chr(0xEF).chr(0xBB).chr(0xBF);
            
            // Add headers
            $csv_data .= implode(',', array_map('addslashes', $export_data['headers'])) . "\n";
            
            // Add data
            foreach ($export_data['data'] as $row) {
                $csv_data .= implode(',', array_map('addslashes', $row)) . "\n";
            }
            
            // Convert to base64
            $base64_data = base64_encode($csv_data);
            
            wp_send_json_success(array(
                'file' => $base64_data,
                'filename' => 'newsletter_subscriptions_' . date('Y-m-d_H-i-s') . '.csv'
            ));
        }
        
    } catch (Exception $e) {
        wp_send_json_error('حدث خطأ أثناء إنشاء الملف: ' . $e->getMessage());
    }
}
add_action('wp_ajax_AlQasrGroup_export_newsletter_subscriptions_ajax', 'AlQasrGroup_export_newsletter_subscriptions_ajax');
