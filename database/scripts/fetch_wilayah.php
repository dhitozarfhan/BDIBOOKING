<?php

/**
 * Script to fetch Indonesian provinces & cities data from emsifa API
 * and generate JSON files for seeding.
 * 
 * Usage: php database/scripts/fetch_wilayah.php
 */

$baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';

// Fetch provinces
echo "Fetching provinces...\n";
$provincesJson = file_get_contents("$baseUrl/provinces.json");
if (!$provincesJson) {
    // Fallback: use the alternative URL
    $provincesJson = file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json");
}

if (!$provincesJson) {
    echo "ERROR: Could not fetch provinces data. Trying alternative source...\n";
    // Use another well-known source
    $provincesJson = file_get_contents("https://api.cahyadsn.com/province");
}

if (!$provincesJson) {
    die("ERROR: Could not fetch provinces data from any source.\n");
}

$provinces = json_decode($provincesJson, true);
if (isset($provinces['data'])) {
    $provinces = $provinces['data']; // For cahyadsn API format
}

echo "Found " . count($provinces) . " provinces.\n";

// Save provinces
$dataDir = __DIR__ . '/../data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

file_put_contents("$dataDir/provinces.json", json_encode($provinces, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Fetch regencies for each province
$allRegencies = [];
foreach ($provinces as $province) {
    $provId = $province['id'];
    echo "Fetching regencies for {$province['name']}...\n";
    
    $regenciesJson = file_get_contents("$baseUrl/regencies/$provId.json");
    if (!$regenciesJson) {
        $regenciesJson = file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/$provId.json");
    }
    
    if ($regenciesJson) {
        $regencies = json_decode($regenciesJson, true);
        if ($regencies) {
            foreach ($regencies as $reg) {
                $allRegencies[] = $reg;
            }
        }
    } else {
        echo "  WARNING: Could not fetch regencies for province $provId\n";
    }
    
    usleep(100000); // 100ms delay to be polite
}

echo "Found " . count($allRegencies) . " regencies/cities total.\n";

file_put_contents("$dataDir/cities.json", json_encode($allRegencies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Data saved to:\n";
echo "  - $dataDir/provinces.json\n";
echo "  - $dataDir/cities.json\n";
echo "Done!\n";
