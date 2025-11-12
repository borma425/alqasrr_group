jQuery(document).ready(function($) {
    // Tab switching functionality
    $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var targetTab = $(this).attr('href');
        
        // Remove active class from all tabs
        $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
        
        // Add active class to clicked tab
        $(this).addClass('nav-tab-active');
        
        // Hide all tab contents
        $('.tab-content').hide();
        
        // Show target tab content
        $(targetTab).show();
    });
    
    // Handle initial tab state based on URL hash
    if (window.location.hash) {
        var hash = window.location.hash;
        $('.nav-tab-wrapper .nav-tab[href="' + hash + '"]').trigger('click');
    }
    
    // Media uploader functionality
    $(document).on('click', '.upload-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = button.data('target');
        
        // Handle partners section with brackets in field names
        var isPartnersField = targetInput && targetInput.indexOf('partners_list') !== -1;
        
        if (isPartnersField) {
            // Partners section - handle brackets properly
            var previewId = '#' + targetInput.replace(/[\[\]]/g, '\\$&') + '_preview';
            var removeBtn = button.siblings('.remove-image-btn');
            
            var mediaUploader = wp.media({
                title: 'اختر صورة الشريك',
                button: {
                    text: 'استخدام هذه الصورة'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var inputSelector = 'input[name="' + targetInput.replace(/[\[\]]/g, '\\$&') + '"]';
                $(inputSelector).val(attachment.url);
                
                var previewHtml = '<img src="' + attachment.url + '" style="max-width: 150px; height: auto; margin-top: 10px;">';
                $(previewId).html(previewHtml);
                removeBtn.show();
            });
        } else {
            // Regular fields
            var previewId = '#' + targetInput + '_preview';
            
            var mediaUploader = wp.media({
                title: 'اختر صورة',
                button: {
                    text: 'استخدام هذه الصورة'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('input[name="' + targetInput + '"]').val(attachment.url);
                
                var previewHtml = '<img src="' + attachment.url + '" style="max-width: 150px; height: auto; margin-top: 10px;">';
                previewHtml += '<br><button type="button" class="button button-small remove-image-btn" data-target="' + targetInput + '">حذف الصورة</button>';
                
                $(previewId).html(previewHtml);
            });
        }
        
        mediaUploader.open();
    });
    
    // Remove image functionality
    $(document).on('click', '.remove-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = button.data('target');
        
        // Handle partners section with brackets in field names
        var isPartnersField = targetInput && targetInput.indexOf('partners_list') !== -1;
        
        if (isPartnersField) {
            var previewId = '#' + targetInput.replace(/[\[\]]/g, '\\$&') + '_preview';
            var inputSelector = 'input[name="' + targetInput.replace(/[\[\]]/g, '\\$&') + '"]';
            
            $(inputSelector).val('');
            $(previewId).html('');
            button.hide();
        } else {
            var previewId = '#' + targetInput + '_preview';
            $('input[name="' + targetInput + '"]').val('');
            $(previewId).html('');
        }
    });
    
    // Handle form submission - disable validation for hidden fields
    // معالجة إرسال النموذج - تعطيل التحقق من الحقول المخفية
    $('form').on('submit', function(e) {
        // Remove required attribute from hidden tab fields
        // إزالة خاصية required من الحقول في التبويبات المخفية
        $('.tab-content:not(:visible)').find('input[required], textarea[required], select[required]').each(function() {
            $(this).removeAttr('required');
        });
        
        // Clean up invalid URL values (like '#') in hidden fields
        // تنظيف قيم URL غير صالحة (مثل '#') في الحقول المخفية
        $('.tab-content:not(:visible)').find('input[type="url"], input[type="text"][name*="url"]').each(function() {
            var value = $(this).val();
            if (value === '#' || value.trim() === '') {
                $(this).val('');
            }
        });
    });
});

