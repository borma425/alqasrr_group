jQuery(document).ready(function($) {
    // رفع الصور
    $(document).on('click', '.upload-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var target = button.data('target');
        var inputField = $('#' + target);
        var previewContainer = inputField.closest('.image-upload-field').find('.image-preview');
        if (!previewContainer.length) {
            previewContainer = inputField.siblings('.image-preview');
        }
        if (!previewContainer.length) {
            previewContainer = button.closest('.image-upload-field').find('.image-preview');
        }

        var frame = wp.media({
            title: 'اختر صورة',
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            inputField.val(attachment.url);
            previewContainer.html(
                '<div class="image-preview-container">' +
                    '<img src="' + attachment.url + '" alt="preview" />' +
                '</div>'
            );
        });
        
        frame.open();
    });
    
    // إزالة الصور
    $(document).on('click', '.remove-image-btn', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var target = button.data('target');
        var inputField = $('#' + target);
        var previewContainer = inputField.closest('.image-upload-field').find('.image-preview');
        if (!previewContainer.length) {
        previewContainer = inputField.siblings('.image-preview');
        }
        if (!previewContainer.length) {
            previewContainer = button.closest('.image-upload-field').find('.image-preview');
        }
        
        inputField.val('');
        previewContainer.html('');
    });
    
    // إزالة صورة من المعرض
    $(document).on('click', '.remove-gallery-item', function() {
        $(this).closest('.gallery-item').remove();
    });

    // معرض الصور في الميتا بوكس الجانبي
    $(document).on('click', '.add-side-gallery-images', function(e) {
        e.preventDefault();

        var button = $(this);
        var metaBox = button.closest('.projects-gallery-meta, .projects-small-gallery');
        var previewContainer = metaBox.find('.gallery-preview').first();

        var frame = wp.media({
            title: 'اختر صور للمعرض',
            multiple: true,
            library: {
                type: 'image'
            }
        });

        frame.on('select', function() {
            var selection = frame.state().get('selection');
            if (!selection) {
                return;
            }

            var attachments = selection.toJSON();

            attachments.forEach(function(attachment) {
                var imageUrl = attachment.sizes && (attachment.sizes.medium_large || attachment.sizes.large || attachment.sizes.full || attachment.sizes.thumbnail)
                    ? (attachment.sizes.medium_large || attachment.sizes.large || attachment.sizes.full || attachment.sizes.thumbnail).url
                    : attachment.url;

                var galleryItem = $('<div class="gallery-item">').append(
                    $('<img>', { src: imageUrl, alt: 'gallery image' }),
                    $('<input>', { type: 'hidden', name: 'projects_side_gallery_images[]', value: attachment.id }),
                    $('<button>', { type: 'button', class: 'button button-small remove-gallery-item', text: '×' })
                );

                previewContainer.append(galleryItem);
            });
        });

        frame.open();
    });

    $(document).on('click', '.clear-side-gallery', function(e) {
        e.preventDefault();

        var button = $(this);
        var metaBox = button.closest('.projects-gallery-meta, .projects-small-gallery');
        var previewContainer = metaBox.find('.gallery-preview').first();

        if (confirm('هل أنت متأكد من مسح جميع الصور؟')) {
            previewContainer.empty();
        }
    });


// === إدارة الإيقونات في قسم ما الذي يميزنا ===
$('.add-icon-btn').on('click', function(e) {
    e.preventDefault();
    
    var iconsPreview = $('#features-icons-preview');
    var iconCount = iconsPreview.find('.icon-item').length;
    var template = $('#icon-template').html();
    var newIcon = template.replace(/{index}/g, iconCount);
    
    iconsPreview.append(newIcon);
    updateIconsTitles();
});

// إزالة إيقونة
$(document).on('click', '.remove-icon-btn', function() {
    if (confirm('هل أنت متأكد من حذف هذه الإيقونة؟')) {
        $(this).closest('.icon-item').remove();
        updateIconsTitles();
    }
});

// رفع صورة الأيقونة
$(document).on('click', '.upload-icon-btn', function(e) {
    e.preventDefault();
    
    var button = $(this);
    var iconField = button.closest('.icon-upload-field');
    var imageUrl = iconField.find('.icon-image-url');
    var imagePreview = iconField.find('.icon-image-preview');
    
    var frame = wp.media({
        title: 'اختر الأيقونة',
        multiple: false,
        library: {
            type: 'image'
        }
    });
    
    frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        imageUrl.val(attachment.url);
        imagePreview.html('<img src="' + attachment.url + '" style="width: 50px; height: 50px; object-fit: contain;" />');
    });
    
    frame.open();
});

// إزالة صورة الأيقونة
$(document).on('click', '.remove-icon-image-btn', function(e) {
    e.preventDefault();
    
    var button = $(this);
    var iconField = button.closest('.icon-upload-field');
    var imageUrl = iconField.find('.icon-image-url');
    var imagePreview = iconField.find('.icon-image-preview');
    
    imageUrl.val('');
    imagePreview.html('');
});

// تحديث العناوين عند التغيير
$(document).on('input', 'input[name$="[title]"]', function() {
    var title = $(this).val() || 'إيقونة جديدة';
    $(this).closest('.icon-item').find('.icon-title').text(title);
});

// مسح جميع الإيقونات
$('.clear-icons-btn').on('click', function() {
    if (confirm('هل أنت متأكد من مسح جميع الإيقونات؟')) {
        $('#features-icons-preview').empty();
    }
});

// تحديث عناوين الإيقونات
function updateIconsTitles() {
    $('#features-icons-preview .icon-item').each(function(index) {
        var titleInput = $(this).find('input[name$="[title]"]');
        var currentTitle = titleInput.val() || 'إيقونة جديدة';
        $(this).find('.icon-title').text(currentTitle);
    });
}

// تحديث العناوين عند التحميل
updateIconsTitles();



// === إدارة قسم المرافق والخدمات ===
$(document).on('click', '.add-amenity-btn', function(e) {
    e.preventDefault();

    var metaBox = $(this).closest('.amenities-meta');
    var amenitiesPreview = metaBox.find('#amenities-preview');
    var amenityCount = amenitiesPreview.find('.amenity-item').length;
    var template = $('#amenity-template').html();
    var newAmenity = template.replace(/{index}/g, amenityCount);

    amenitiesPreview.append(newAmenity);
});

$(document).on('click', '.remove-amenity-btn', function() {
    if (confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
        $(this).closest('.amenity-item').remove();
    }
});

$(document).on('click', '.upload-amenity-image-btn', function(e) {
    e.preventDefault();

    var button = $(this);
    var amenityField = button.closest('.amenity-upload-field');
    var imageInput = amenityField.find('.amenity-image-url');
    var imagePreview = amenityField.find('.amenity-image-preview');

    var frame = wp.media({
        title: 'اختر صورة للمرفق',
        multiple: false,
        library: {
            type: 'image'
        }
    });

    frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        var imageUrl = attachment.sizes && (attachment.sizes.medium || attachment.sizes.thumbnail || attachment.sizes.full)
            ? (attachment.sizes.medium || attachment.sizes.thumbnail || attachment.sizes.full).url
            : attachment.url;

        imageInput.val(imageUrl);
        imagePreview.html('<img src="' + imageUrl + '" alt="amenity image" />');
    });

    frame.open();
});

$(document).on('click', '.remove-amenity-image-btn', function(e) {
    e.preventDefault();

    var amenityField = $(this).closest('.amenity-upload-field');
    amenityField.find('.amenity-image-url').val('');
    amenityField.find('.amenity-image-preview').html('');
});

$(document).on('click', '.clear-amenities-btn', function(e) {
    e.preventDefault();

    var metaBox = $(this).closest('.amenities-meta');
    if (confirm('هل أنت متأكد من مسح جميع المرافق؟')) {
        metaBox.find('#amenities-preview').empty();
    }
});

$(document).on('input', '.amenity-title-input', function() {
    var title = $(this).val() || 'مرفق جديد';
    $(this).closest('.amenity-item').attr('data-title', title);
});

// عناصر سرعة التنقل – الأقسام
$(document).on('click', '.add-speed-section-btn', function(e) {
    e.preventDefault();

    var metaBox = $(this).closest('.speed-travel-meta');
    var sectionsWrapper = metaBox.find('#speed-sections');
    var sectionTemplate = $('#speed-section-template').html();

    var maxIndex = -1;
    sectionsWrapper.find('.speed-section').each(function() {
        var value = parseInt($(this).attr('data-section'), 10);
        if (!isNaN(value)) {
            maxIndex = Math.max(maxIndex, value);
        }
    });
    var newIndex = maxIndex + 1;

    var newSection = sectionTemplate.replace(/\{section\}/g, newIndex);

    sectionsWrapper.append(newSection);
});

$(document).on('click', '.remove-speed-section-btn', function() {
    if (confirm('هل أنت متأكد من حذف هذا القسم؟')) {
        $(this).closest('.speed-section').remove();
    }
});

$(document).on('click', '.clear-speed-sections-btn', function(e) {
    e.preventDefault();

    var metaBox = $(this).closest('.speed-travel-meta');
    if (confirm('هل أنت متأكد من مسح جميع الأقسام؟')) {
        metaBox.find('#speed-sections').empty();
    }
});

// عناصر سرعة التنقل – المحطات داخل الأقسام
$(document).on('click', '.add-speed-item-btn', function(e) {
    e.preventDefault();

    var section = $(this).closest('.speed-section');
    var itemsWrapper = section.find('[data-items]');
    var sectionIndex = section.attr('data-section');
    var itemCount = itemsWrapper.find('.speed-item').length;
    var template = $('#speed-item-template').html();
    var newSpeedItem = template
        .replace(/\{section\}/g, sectionIndex)
        .replace(/\{index\}/g, itemCount);

    itemsWrapper.append(newSpeedItem);
});

$(document).on('click', '.remove-speed-item-btn', function() {
    if (confirm('هل أنت متأكد من حذف هذه المحطة؟')) {
        $(this).closest('.speed-item').remove();
    }
});

$(document).on('click', '.clear-speed-items-btn', function(e) {
    e.preventDefault();

    var section = $(this).closest('.speed-section');
    if (confirm('هل أنت متأكد من مسح محطات هذا القسم؟')) {
        section.find('[data-items]').empty();
    }
});


});