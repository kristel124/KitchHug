<?php
// Check curl version and try a simple HTTP (not HTTPS) request
echo "PHP curl version: " . curl_version()['version'] . PHP_EOL;
echo "SSL version: " . curl_version()['ssl_version'] . PHP_EOL;

// Try plain HTTP first
$ch = curl_init('http://httpbin.org/get');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$r = curl_exec($ch);
echo "HTTP (no SSL) code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . " err:" . curl_error($ch) . PHP_EOL;

// Try HTTPS
$ch2 = curl_init('https://httpbin.org/get');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
$r2 = curl_exec($ch2);
echo "HTTPS code: " . curl_getinfo($ch2, CURLINFO_HTTP_CODE) . " err:" . curl_error($ch2) . PHP_EOL;
