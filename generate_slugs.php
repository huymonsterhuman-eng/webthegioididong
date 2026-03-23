<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$products = App\Models\Product::all();
$count = 0;
foreach ($products as $product) {
    if (empty($product->slug)) {
        $product->slug = Illuminate\Support\Str::slug($product->name . '-' . $product->id);
        $product->save();
        $count++;
    }
}
echo "Generated slugs for $count products.\n";
