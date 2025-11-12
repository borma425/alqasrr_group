<?php



if (!defined('ABSPATH')) exit;


add_action( 'add_meta_boxes', 'my_meta_box' );

function my_meta_box() {




}



function remove_tags_meta_box() {
    $screen = get_current_screen();
    if ( ! $screen ) return;

    $cpt = $screen->post_type;

    $allowed_cpts = ['projects', 'apps', 'tools'];

    if ( in_array( $cpt, $allowed_cpts ) ) {

        remove_meta_box('categorydiv', $cpt, 'normal');
        remove_meta_box('postcustom', $cpt, 'normal');
        remove_meta_box('authordiv', $cpt, 'normal');
        remove_meta_box('commentsdiv', $cpt, 'normal');
        remove_meta_box('commentstatusdiv', $cpt, 'normal');
        remove_meta_box('pageparentdiv', $cpt, 'normal');

        remove_post_type_support($cpt, 'editor');
    }
}
add_action('add_meta_boxes', 'remove_tags_meta_box', 999);





$currentFile = basename(__FILE__);
// Path to the directory containing PHP files
$directory = get_theme_file_path('inc/meta_boxes/') . '*.php';
// Use glob to get all PHP files in the directory
$phpFiles = glob($directory);
// Loop through each file
foreach ($phpFiles as $file) {
    // Skip the current file
    if (basename($file) !== $currentFile) {
        include_once $file;
    }
}