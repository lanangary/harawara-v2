<?php
/**
 *  Template Name:  Home Page
 */

$context = Timber::context();

Timber::render(['page--home-page.twig', 'page.twig'], $context);
