<?php

$source = __DIR__ . '/../www/app/plugins/badrock';
$destination = __DIR__ . '/../www/app/plugins/juicy';

if (is_dir($source)) {
    if (rename($source, $destination)) {
        echo "Renamed 'badrock' to 'juicy' successfully.\n";
    } else {
        echo "Failed to rename 'badrock' to 'juicy'.\n";
    }
} else {
    echo "'badrock' directory not found. Skipping rename.\n";
}