<?php

$microtime_start = microtime(TRUE);
$filename = 'input.txt';
$input = file_get_contents($filename);
[$dict_str, $towels_str] = explode("\n\n", trim($input));

$dict   = array_flip(explode(', ', $dict_str));
$towels = explode("\n", $towels_str);

$ans = 0;
foreach ($towels as $s) {
    $LEN = strlen($s);
    $dp = array_fill(0, $LEN+1, FALSE);
    $dp[$LEN] = TRUE; // base case

    for ($i=$LEN-1; $i>=0; $i--) {
        for ($j=$i; $j<$LEN; $j++) {
            $substr = substr($s, $i, $j-$i+1);
            if (isset($dict[$substr]) && $dp[$j+1]) {
                $dp[$i] = TRUE;
            }
        }
    }
    $ans += ($dp[0]) ? 1 : 0;
}

echo "\n==> ANSWER: $ans";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
echo "Time spent: ".microtime(TRUE) - $microtime_start."s\n";
