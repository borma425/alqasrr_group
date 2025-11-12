jQuery(document).ready(function($) {
    // Tab switching functionality
    $('.about-nav-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var targetTab = $(this).attr('href');
        
        // Remove active class from all tabs
        $('.about-nav-tabs .nav-tab').removeClass('nav-tab-active');
        
        // Add active class to clicked tab
        $(this).addClass('nav-tab-active');
        
        // Hide all tab contents
        $('.about-tab-content').hide().removeClass('active');
        
        // Show target tab content
        $(targetTab).show().addClass('active');
    });
    
    // Handle initial tab state
    $('.about-nav-tabs .nav-tab-active').trigger('click');
    
    // Media uploader functionality for About settings
    $(document).on('click', '.upload-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = button.data('target');
        var isDynamicField = targetInput && (targetInput.indexOf('[') !== -1);
        
        var mediaUploader = wp.media({
            title: 'اختر صورة',
            button: {
                text: 'استخدام هذه الصورة'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            
            if (isDynamicField) {
                // Handle dynamic fields with brackets
                var inputSelector = 'input[name="' + targetInput.replace(/[\[\]]/g, '\\$&') + '"]';
                $(inputSelector).val(attachment.url);
                
                // Update preview in the same container
                var previewContainer = button.siblings('.image-preview');
                if (previewContainer.length) {
                    var previewHtml = '<img src="' + attachment.url + '" style="max-width: 200px; height: auto; margin-top: 10px; border-radius: 8px;">';
                    previewContainer.html(previewHtml);
                }
            } else {
                // Regular fields
                var inputField = $('input[name="' + targetInput + '"]');
                if (inputField.length) {
                    inputField.val(attachment.url);
                    
                    var previewId = '#' + targetInput + '_preview';
                    var previewHtml = '<img src="' + attachment.url + '" style="max-width: 300px; height: auto; margin-top: 10px; border-radius: 8px;">';
                    previewHtml += '<br><button type="button" class="button button-small remove-image-btn" data-target="' + targetInput + '">حذف الصورة</button>';
                    
                    $(previewId).html(previewHtml);
                } else {
                    // Try by ID
                    $('#' + targetInput).val(attachment.url);
                    var previewId = '#' + targetInput + '_preview';
                    var previewHtml = '<img src="' + attachment.url + '" style="max-width: 300px; height: auto; margin-top: 10px; border-radius: 8px;">';
                    previewHtml += '<br><button type="button" class="button button-small remove-image-btn" data-target="' + targetInput + '">حذف الصورة</button>';
                    $(previewId).html(previewHtml);
                }
            }
        });
        
        mediaUploader.open();
    });
    
    // Remove image functionality
    $(document).on('click', '.remove-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = button.data('target');
        var isDynamicField = targetInput && (targetInput.indexOf('[') !== -1);
        
        if (isDynamicField) {
            var inputSelector = 'input[name="' + targetInput.replace(/[\[\]]/g, '\\$&') + '"]';
            $(inputSelector).val('');
            button.closest('.image-preview').html('');
        } else {
            var inputField = $('input[name="' + targetInput + '"]');
            if (inputField.length) {
                inputField.val('');
            } else {
                $('#' + targetInput).val('');
            }
            var previewId = '#' + targetInput + '_preview';
            $(previewId).html('');
        }
    });
    
    // Add dynamic item functionality
    var itemCounters = {
        'identity-items-container': 0,
        'different-items-container': 0,
        'values-items-container': 0
    };
    
    // Initialize counters based on existing items
    $('.dynamic-items-container').each(function() {
        var containerId = $(this).attr('id');
        var existingItems = $(this).find('.dynamic-item').length;
        if (itemCounters.hasOwnProperty(containerId)) {
            itemCounters[containerId] = existingItems;
        }
    });
    
    $(document).on('click', '.add-item-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var containerId = button.data('container');
        var templateId = button.data('template');
        var container = $('#' + containerId);
        
        // Get template
        var template = $('#' + templateId).html();
        
        // Get next index
        if (!itemCounters.hasOwnProperty(containerId)) {
            itemCounters[containerId] = 0;
        }
        var index = itemCounters[containerId];
        itemCounters[containerId]++;
        
        // Replace INDEX placeholder with actual index
        var newItemHtml = template.replace(/INDEX/g, index);
        
        // Create new item element
        var newItem = $(newItemHtml);
        
        // Update item number
        var itemNumber = container.find('.dynamic-item').length + 1;
        newItem.find('.item-number').text(itemNumber);
        
        // Append to container
        container.append(newItem);
        
        // Reorder item numbers
        reorderItemNumbers(containerId);
    });
    
    // Remove dynamic item functionality
    $(document).on('click', '.remove-item-btn', function(e) {
        e.preventDefault();
        
        if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            return;
        }
        
        var button = $(this);
        var item = button.closest('.dynamic-item');
        var container = item.closest('.dynamic-items-container');
        var containerId = container.attr('id');
        
        item.fadeOut(300, function() {
            $(this).remove();
            reorderItemNumbers(containerId);
        });
    });
    
    // Reorder item numbers
    function reorderItemNumbers(containerId) {
        var container = $('#' + containerId);
        container.find('.dynamic-item').each(function(index) {
            $(this).find('.item-number').text(index + 1);
            
            // Update all input names with new index
            $(this).find('input, textarea, select').each(function() {
                var $field = $(this);
                var name = $field.attr('name');
                if (name && name.indexOf('[') !== -1) {
                    // Extract the base name and field name
                    var match = name.match(/^(.+?)\[\d+\](.+)$/);
                    if (match) {
                        var newName = match[1] + '[' + index + ']' + match[2];
                        $field.attr('name', newName);
                        
                        // Update data-target for upload buttons
                        if ($field.hasClass('image-input')) {
                            var uploadBtn = $field.siblings('.upload-image-btn');
                            if (uploadBtn.length) {
                                uploadBtn.attr('data-target', newName);
                            }
                        }
                    }
                }
            });
        });
    }
    
    // Make dynamic items sortable
    $('.dynamic-items-container').each(function() {
        if ($(this).children().length > 0) {
            $(this).sortable({
                handle: '.item-header',
                axis: 'y',
                opacity: 0.6,
                update: function(event, ui) {
                    var containerId = $(this).attr('id');
                    reorderItemNumbers(containerId);
                }
            });
        }
    });
    
    // Handle form submission - disable validation for hidden fields
    $('#about-settings-form').on('submit', function(e) {
        // Remove required attribute from hidden tab fields
        $('.about-tab-content:not(:visible)').find('input[required], textarea[required], select[required]').each(function() {
            $(this).removeAttr('required');
        });
    });
});

