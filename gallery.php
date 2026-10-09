<?php
/* Lists the photos/videos inside gallery/<folder>/ so the Acquaintance TV can load them automatically. */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$f     = isset($_GET['f']) ? $_GET['f'] : '';
$root  = realpath(__DIR__ . '/gallery');
$files = [];

if ($root && $f !== '' && $f !== '.' && $f !== '..' && preg_match('/^[A-Za-z0-9 _.\-]+$/', $f)) {
    $dir = realpath($root . '/' . $f);
    if ($dir && strpos($dir, $root) === 0 && is_dir($dir)) {
        foreach (scandir($dir) as $n) {
            if (is_file($dir . '/' . $n) && preg_match('/\.(jpe?g|png|webp|gif|avif|mp4|webm|mov|m4v)$/i', $n)) {
                $files[] = $n;
            }
        }
        natcasesort($files);
        $files = array_values($files);
    }
}
echo json_encode(['files' => $files]);
