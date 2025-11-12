<?php
// Add a custom meta box for the 'roms' post type
function my_custom_meta_boxes() {
    add_meta_box(
        'my_gallery_meta_box', // ID
        'Gallery Images', // Title
        'my_gallery_meta_box_callback', // Callback function
        'roms', // Post type
        'normal' // Context
    );
}
add_action('add_meta_boxes', 'my_custom_meta_boxes');

// Callback function for the meta box
function my_gallery_meta_box_callback($post) {
    // Retrieve existing image IDs
    $images = get_post_meta($post->ID, 'gallery_images', true);
    ?>
    <div id="my-gallery" style="display: flex; flex-wrap: wrap;">
        <?php if ($images): ?>
            <?php foreach ($images as $image_id): ?>
                <div class="gallery-image" style="margin: 5px; position: relative;">
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <input type="hidden" name="gallery_images[]" value="<?php echo esc_attr($image_id); ?>">
                    <button class="remove-image button" style="position: absolute; top: 0; right: 0;">&times;</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="button" id="upload_gallery_image">Add Image</button>
    <script>
        jQuery(document).ready(function($) {
            var file_frame;

            $('#upload_gallery_image').on('click', function(event) {
                event.preventDefault();
                
                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }
                
                // Create a new media frame.
                file_frame = wp.media({
                    title: 'Select Images',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: true // Set to true to allow multiple images to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    var attachments = file_frame.state().get('selection').toJSON();
                    attachments.forEach(function(attachment) {
                        // Use the full image URL if the thumbnail is not available
                        var imgSrc = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                        $('#my-gallery').append('<div class="gallery-image" style="margin: 5px; position: relative;"><img src="' + imgSrc + '" style="width: 100px; height: auto;"/><input type="hidden" name="gallery_images[]" value="' + attachment.id + '"><button class="remove-image button" style="position: absolute; top: 0; right: 0;">&times;</button></div>');
                    });
                });

                // Finally, open the modal on click.
                file_frame.open();
            });

            // Remove image functionality
            $(document).on('click', '.remove-image', function() {
                $(this).closest('.gallery-image').remove();
            });
        });
    </script>
    <?php
}

// Save the meta box data
function my_save_gallery_meta_box_data($post_id) {
    if (array_key_exists('gallery_images', $_POST)) {
        update_post_meta($post_id, 'gallery_images', $_POST['gallery_images']);
    }
}
add_action('save_post', 'my_save_gallery_meta_box_data');

// Enqueue necessary scripts
function my_enqueue_scripts() {
    wp_enqueue_media(); // Enqueue WordPress media
    wp_enqueue_script('jquery'); // Enqueue jQuery
}
add_action('admin_enqueue_scripts', 'my_enqueue_scripts');
