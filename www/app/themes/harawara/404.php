<?php

$context = Timber::context();
$context['title'] = 'Page not found';
$context['internal_title'] = 'Page not found';
$context['internal_supporting_text'] = 'Sorry, we couldn\'t find what you\'re looking for.';

Timber::render('404.twig', $context);
