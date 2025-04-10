<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

Timber::render(["single--{$post->ID}.twig", "single--{$post->post_type}.twig", "single.twig"], $context);
