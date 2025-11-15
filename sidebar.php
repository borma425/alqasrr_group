<?php
/**
 * The Template for the sidebar containing the main widget area
 *
 * @package  WordPress
 * @subpackage  Timber
 */
$templates = array( 'sidebar.twig' );
if ( function_exists( 'get_language_template' ) ) {
	foreach ( $templates as $index => $template ) {
		$templates[ $index ] = get_language_template( $template );
	}
}

Timber::render( $templates, $data );
