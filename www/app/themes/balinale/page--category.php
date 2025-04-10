<?php
/**
 *  Template Name:  Category Page
 */

$context = Timber::context();

Timber::render(['page--category.twig', 'page.twig'], $context);
