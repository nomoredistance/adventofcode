<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$diskmap = $rows[0];

echo "length of diskmap: ".strlen($diskmap)."\n\n";

$expanded = [];
$file_id = 0;
for ($i=0; $i<strlen($diskmap); $i++) {
    $fill = ($i %2 === 0) ? $file_id : '.';
    for ($j=0; $j<$diskmap[$i]; $j++) {
        $expanded[] = $fill;
    }
    if ($i %2 === 0) $file_id++;
}

echo "length of expanded diskmap: ".count($expanded)."\n\n";
echo "expanded diskmap: \n";
echo implode($expanded)."\n\n";

// compact
$l = 0;
$r = count($expanded) - 1;
while ($l < $r) {
    while ($expanded[$l] !== '.' && $l < $r) {
        $l++;
    } 
    while ($expanded[$r] === '.' && $l < $r) {
        $r--;
    }
    $expanded[$l] = $expanded[$r];
    $expanded[$r] = '.';
    $l++;
    $r--;
}

echo "expanded diskmap after compaction: \n";
echo implode($expanded)."\n\n";

// count sum
$sum = 0;
for ($i=0; $i<count($expanded); $i++) {
    if ($expanded[$i] === '.') break;
    $sum += $expanded[$i] * $i;
}

echo "\n===> sum: $sum\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
