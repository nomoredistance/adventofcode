<?php

$ans   = 0;
$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);


$stones = explode(' ', $rows[0]);

$blinks = 25;
foreach ($stones as $stone) {
    $arr = [];
    $arr[] = $stone;
    for ($k=1; $k<=$blinks; $k++) {
        $ct = count($arr); // count only at the beginning as to not process new elements on the fly
        for ($i=0; $i<$ct; $i++) {
            if ($arr[$i] == 0) {
                $arr[$i] = 1;
            } elseif (strlen($arr[$i]) %2 == 0) {
                $arr[]   = intval(substr($arr[$i], intdiv(strlen($arr[$i]), 2)));
                $arr[$i] = intval(substr($arr[$i], 0, intdiv(strlen($arr[$i]), 2)));
            } else {
                $arr[$i] *= 2024;
            }
        }
    }
    $ans += count($arr);
}

// echo json_encode($arr);

echo "\n\n";
echo "===> ANSWER: ".$ans;

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
