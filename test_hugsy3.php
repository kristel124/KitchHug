<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$key = config('services.gemini.key');

// Try resolving the IP first
$ip = gethostbyname('generativelanguage.googleapis.com');
echo "Resolved IP: $ip\n";

// Try with explicit IP
$ch = curl_init("https://{$ip}/v1beta/models/gemini-3.8-flash:generateContent?key={$key}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['contents' => [['role' => 'user', 'parts' => [['text' => 'hi']]]]]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Host: generativelanguage.googleapis.com']);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$r = curl_exec($ch);
echo "Via IP: code=" . curl_getinfo($ch, CURLINFO_HTTP_CODE) . " err=" . curl_error($ch) . "\n";
echo substr($r, 0, 100) . "\n";

// Try hostname normally
$ch2 = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={$key}");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(['contents' => [['role' => 'user', 'parts' => [['text' => 'hi']]]]]));
curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch2, CURLOPT_TIMEOUT, 15);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch2, CURLOPT_DNS_CACHE_TIMEOUT, 0);
$r2 = curl_exec($ch2);
echo "Via hostname: code=" . curl_getinfo($ch2, CURLINFO_HTTP_CODE) . " err=" . curl_error($ch2) . "\n";
echo substr($r2, 0, 100) . "\n";
