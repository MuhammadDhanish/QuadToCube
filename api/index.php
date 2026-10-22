<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Vercel Serverless Function Bridge
 */

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
// Serve assets directly if requested via serverless bridge
if (preg_match('#/(?:public/)?assets/(.+)#', $uri, $matches)) {
    $filePath = __DIR__ . '/../public/assets/' . $matches[1];
    if (file_exists($filePath) && !is_dir($filePath)) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimes = [
            'css'   => 'text/css; charset=UTF-8',
            'js'    => 'application/javascript; charset=UTF-8',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
        ];
        header('Content-Type: ' . ($mimes[$ext] ?? 'text/plain'));
        header('Cache-Control: public, max-age=86400');
        readfile($filePath);
        exit;
    }
}

// Route all other requests to the public front controller
require __DIR__ . '/../public/index.php';
