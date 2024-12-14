<?php

$ans   = 0;
$filename = 'input.txt';
$input = file_get_contents($filename);
$rows  = explode("\n", trim($input));

if ($filename === 'input.txt') {
    $ROWS = 103;
    $COLS = 101;
} else {
    $ROWS = 7;
    $COLS = 11;
}
$grid  = array_fill(0, $ROWS, array_fill(0, $COLS, 0));

/*
$dirs = [
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];
 */

$SECONDS = 100;
foreach ($rows as $row) {
    if ($row) {
        [$p, $v] = explode(' ', $row);
        [$py, $px] = explode(',', ltrim($p, 'p='));
        [$vy, $vx] = explode(',', ltrim($v, 'v='));

        // note to self:
        // the addition with ROWS or COLS times SECONDS is needed due to how
        // php handles negative modulus
        $px = ($px + ($vx*$SECONDS) + ($ROWS*$SECONDS)) % $ROWS;
        $py = ($py + ($vy*$SECONDS) + ($COLS*$SECONDS)) % $COLS;
        $grid[$px][$py]++;
    }
}

// echo json_encode($grid);

$q1 = $q2 = $q3 = $q4 = 0;
$RHALF = intdiv($ROWS, 2);
$CHALF = intdiv($COLS, 2);
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] > 0) {
            if ($i < $RHALF && $j < $CHALF) {
                $q1 += $grid[$i][$j];
            } elseif ($i < $RHALF && $j > $CHALF) {
                $q2 += $grid[$i][$j];
            } elseif ($i > $RHALF && $j < $CHALF) {
                $q3 += $grid[$i][$j];
            } elseif ($i > $RHALF && $j > $CHALF) {
                $q4 += $grid[$i][$j];
            }
        }
    }
}

$ans = $q1 * $q2 * $q3 * $q4;
echo "\n\n===> ANSWER: $ans\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
