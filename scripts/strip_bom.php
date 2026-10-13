<?php
$path = $argv[1] ?? null;
if (! $path) {
    echo "Usage: php strip_bom.php <path>\n";
    exit(1);
}
$s = file_get_contents($path);
$news = preg_replace('/^\xEF\xBB\xBF/', '', $s);
if ($s !== $news) {
    file_put_contents($path, $news);
    echo "BOM removed from $path\n";
} else {
    echo "No BOM found in $path\n";
}
