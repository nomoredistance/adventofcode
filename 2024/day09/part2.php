<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$diskmap = $rows[0];
echo "length of diskmap: ".strlen($diskmap)."\n\n";

$expanded = $filesize_map = $fileidx_map = [];
$file_id = 0;
for ($i=0; $i<strlen($diskmap); $i++) {
    $fill = ($i %2 === 0) ? $file_id : '.';
    if ($fill !== '.') {
        $filesize_map[$i/2] = (int) $diskmap[$i];
        $fileidx_map[$i/2]  = count($expanded);
    }
    for ($j=0; $j<$diskmap[$i]; $j++) {
        $expanded[] = $fill;
    }
    if ($i %2 === 0) $file_id++;
}

echo "length of expanded diskmap: ".count($expanded)."\n\n";
echo "expanded diskmap: \n";
echo implode($expanded)."\n\n";

function find_free_space_with_size ($n) {
    global $expanded;
    for ($i=0; $i<count($expanded); $i++) {
        if ($expanded[$i] === '.') {
            $valid = TRUE;
            for ($j=$i; $j<$i+$n && $j<count($expanded); $j++) {
                if ($expanded[$j] !== '.') {
                    $valid = FALSE;
                    $i = $j;
                }
            }
            if ($valid) return $i;
        }
    }
    return FALSE;
}

// compact
$fileidx_map_keys = array_keys($fileidx_map);
while (count($fileidx_map) > 0) {
    $file_idx = array_pop($fileidx_map);
    $file_id  = array_pop($fileidx_map_keys);
    $filesize = $filesize_map[$file_id];
    $dest_idx = find_free_space_with_size($filesize);
    // echo "file_id: $file_id, file_idx: $file_idx, size: $filesize, dest_idx: $dest_idx\n";
    if ($dest_idx !== FALSE && $dest_idx < $file_idx) {
        // move file to destination space
        for ($i=0; $i<$filesize; $i++) {
            $expanded[$dest_idx+$i] = $file_id;
            $expanded[$file_idx+$i] = '.';
        }
    }
}

echo "expanded diskmap after compaction: \n";
echo implode($expanded)."\n\n";

// count sum
$sum = 0;
for ($i=0; $i<count($expanded); $i++) {
    if ($expanded[$i] === '.') continue;
    $sum += $expanded[$i] * $i;
}

echo "\n===> sum: $sum\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
