<?php

$ans   = 0;
$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);


$stones = explode(' ', $rows[0]);

$blinks = 75;
foreach ($stones as $stone) {
    $arr = [];
    $arr[$stone] = 1;
    for ($k=1; $k<=$blinks; $k++) {
        $clone = $arr;
        foreach ($arr as $num => $freq) {
            if ($num === 0) {
                $clone[$num] -= $freq;
                $clone[1] ??= 0;
                $clone[1] += $freq;
            } elseif (strlen($num) %2 == 0) {
                $new1 = intval(substr($num, intdiv(strlen($num), 2)));
                $new2 = intval(substr($num, 0, intdiv(strlen($num), 2)));
                $clone[$num] -= $freq;
                $clone[$new1] ??= 0;
                $clone[$new2] ??= 0;
                $clone[$new1] += $freq;
                $clone[$new2] += $freq;
            } else {
                $mult_res = $num * 2024;
                $clone[$num] -= $freq;
                $clone[$mult_res] ??= 0;
                $clone[$mult_res] += $freq;
            }
        }
        $arr = $clone;
    }
    foreach ($arr as $num => $freq) {
        $ans += $freq;
    }
}

// echo json_encode($arr);
// echo "\n\n";

echo "===> ANSWER: ".$ans;
echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
