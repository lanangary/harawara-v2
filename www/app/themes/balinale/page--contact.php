<?php
/**
 *  Template Name:  Contact Page
 */

$context = Timber::context();

Timber::render(['page--contact.twig', 'page.twig'], $context);
