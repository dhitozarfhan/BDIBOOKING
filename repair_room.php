<?php
use App\Models\Property;
use App\Models\Room;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = Property::first();
    $r = Room::first();
    if ($p && $r) {
        $r->update([
            'property_id' => $p->id,
            'room_number' => '101',
            'status' => 'available'
        ]);
        echo "Repaired room ID: " . $r->id . "\n";
    } else {
        echo "Property or Room not found. P: " . ($p ? 'Yes' : 'No') . ", R: " . ($r ? 'Yes' : 'No') . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
