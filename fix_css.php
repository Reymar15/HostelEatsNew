<?php
$cssFile = __DIR__ . '/resources/css/app.css';
$blockFile = __DIR__ . '/fc_block.css';

$lines = file($cssFile);
$newBlock = file_get_contents($blockFile);

$start = $end = -1;
foreach ($lines as $i => $l) {
    if (strpos($l, '.menu-grid {') !== false && $i > 3000 && $start === -1) {
        $start = $i;
    }
    if (strpos($l, 'Branch cards on dashboard') !== false && $start > 0 && $end === -1) {
        $end = $i;
    }
}

echo "start=$start end=$end\n";
echo "Line start: " . trim($lines[$start]) . "\n";
echo "Line end  : " . trim($lines[$end]) . "\n";

if ($start < 0 || $end < 0) {
    echo "ERROR: could not find block boundaries\n";
    exit(1);
}

$before = array_slice($lines, 0, $start);
$after  = array_slice($lines, $end);

$result = implode('', $before) . $newBlock . "\n" . implode('', $after);
file_put_contents($cssFile, $result);
echo "Done. New file size: " . strlen($result) . " bytes\n";
