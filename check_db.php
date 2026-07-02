<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = \App\Models\Page::find(6);
echo "Content length: " . strlen($p->content) . "\n";
echo "Builder Data NULL? " . (is_null($p->builder_data) ? 'YES' : 'NO') . "\n";
