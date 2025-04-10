<?php
/**
 * The template for displaying all pages.
 *
 */

$context = Timber::context();

Timber::render(["page--{$post->post_name}.twig", "page.twig"], $context);
