<?php

$microtime_start = microtime(TRUE);
$filename = 'input.txt';
$input = file_get_contents($filename);
$rows = explode("\n", trim($input));

$nei = [];
foreach ($rows as $edge) {
    [$u, $v] = explode('-', $edge);
    $nei[$u] ??= [];
    $nei[$v] ??= [];
    $nei[$u][] = $v;
    $nei[$v][] = $u;
}

foreach ($nei as $node => $ne1_arr) {
    if ($node[0] === 't') {
        foreach ($ne1_arr as $ne1) {
            $part1 = [$node, $ne1];
            foreach ($nei[$ne1] as $ne2) {
                $part2 = array_merge($part1, [$ne2]);
                foreach ($nei[$ne2] as $ne3) {
                    if ($ne3 === $node) {
                        sort($part2);
                        $hash = implode('-', $part2);
                        $all_paths[$hash] = TRUE;
                    }
                }
            }
        }
    }
}

echo json_encode($all_paths);
echo "\n==> ANSWER: ".count($all_paths);

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
echo "Time spent: ".microtime(TRUE) - $microtime_start."s\n";

