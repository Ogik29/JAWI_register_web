<?php

/**
 * SQL Import Script
 * Imports SQL file into database
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=================================\n";
echo "SQL Import Script\n";
echo "=================================\n\n";

$sqlFile = __DIR__ . '/sql/u316980662_jawiDB (2).sql';

if (!file_exists($sqlFile)) {
    die("Error: SQL file not found at: $sqlFile\n");
}

echo "Reading SQL file...\n";
$sql = file_get_contents($sqlFile);

if ($sql === false) {
    die("Error: Could not read SQL file\n");
}

echo "File size: " . number_format(strlen($sql)) . " bytes\n";
echo "Splitting SQL statements...\n";

// Remove comments
$sql = preg_replace('/^--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Split by semicolons (but not inside quotes)
$statements = [];
$current = '';
$inString = false;
$stringChar = '';

for ($i = 0; $i < strlen($sql); $i++) {
    $char = $sql[$i];

    if (($char === "'" || $char === '"') && ($i === 0 || $sql[$i - 1] !== '\\')) {
        if (!$inString) {
            $inString = true;
            $stringChar = $char;
        } elseif ($char === $stringChar) {
            $inString = false;
        }
    }

    if ($char === ';' && !$inString) {
        $stmt = trim($current);
        if (!empty($stmt)) {
            $statements[] = $stmt;
        }
        $current = '';
    } else {
        $current .= $char;
    }
}

// Add last statement if not empty
$stmt = trim($current);
if (!empty($stmt)) {
    $statements[] = $stmt;
}

echo "Found " . count($statements) . " SQL statements\n\n";

// Disable foreign key checks
echo "Disabling foreign key checks...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');

$success = 0;
$failed = 0;
$skipped = 0;

foreach ($statements as $index => $statement) {
    // Skip empty statements and SQL mode settings
    if (
        empty(trim($statement)) ||
        stripos($statement, 'SET SQL_MODE') === 0 ||
        stripos($statement, 'SET time_zone') === 0 ||
        stripos($statement, 'START TRANSACTION') === 0 ||
        stripos($statement, 'COMMIT') === 0 ||
        stripos($statement, '/*!') === 0
    ) {
        $skipped++;
        continue;
    }

    try {
        DB::unprepared($statement);
        $success++;

        if ($success % 100 === 0) {
            echo "Processed $success statements...\n";
        }
    } catch (\Exception $e) {
        $failed++;

        // Show first 100 chars of statement for debugging
        $preview = substr($statement, 0, 100);
        echo "\nWarning: Failed statement #" . ($index + 1) . ":\n";
        echo "Preview: " . $preview . "...\n";
        echo "Error: " . $e->getMessage() . "\n\n";

        // Stop if too many failures
        if ($failed > 50) {
            echo "Too many failures, stopping import.\n";
            break;
        }
    }
}

// Re-enable foreign key checks
echo "\nRe-enabling foreign key checks...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n=================================\n";
echo "Import Summary:\n";
echo "=================================\n";
echo "Total statements: " . count($statements) . "\n";
echo "Successful: $success\n";
echo "Failed: $failed\n";
echo "Skipped: $skipped\n";
echo "=================================\n";

if ($failed === 0) {
    echo "\n✓ Import completed successfully!\n";
} else {
    echo "\n⚠ Import completed with $failed errors.\n";
}
