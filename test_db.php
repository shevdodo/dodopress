<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$c = new \App\Http\Controllers\CartController();
$data = $c->index()->getData();
echo json_encode($data['provinces']);
