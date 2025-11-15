<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

global $wp_query;

$context          = Timber::context();
$context['posts'] = Timber::get_posts();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = Timber::get_user( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
$templates = array( 'author.twig', 'archive.twig' );
if ( function_exists( 'get_language_template' ) ) {
	foreach ( $templates as $index => $template ) {
		$templates[ $index ] = get_language_template( $template );
	}
}
Timber::render( $templates, $context );
