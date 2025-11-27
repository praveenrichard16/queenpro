<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$rows = \Illuminate\Support\Facades\DB::table('home_sliders')->get()->map(function($row){ return (array)$row; })->toArray();
echo json_encode($rows, JSON_PRETTY_PRINT);
