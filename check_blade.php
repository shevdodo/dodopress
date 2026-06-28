<?php
$c = file_get_contents(__DIR__ . '/resources/views/dashboard/pages/edit.blade.php');
$pos = strpos($c, 'REGULAR PAGE EDITOR');
if ($pos !== false) {
    echo "FOUND at byte $pos\n";
    // Show 200 chars around it
    echo substr($c, $pos - 50, 300);
} else {
    echo "NOT FOUND\n";
    // Find elseif
    $pos2 = strpos($c, '@elseif');
    if ($pos2 !== false) {
        echo "FOUND @elseif at byte $pos2\n";
        echo substr($c, $pos2 - 100, 300);
    }
}
