<?php

$ans   = 0;
$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$grid  = [];
for ($i=0; $i<count($rows); $i++) {
    if (!$rows[$i]) continue;
    $grid[$i] = array_map('intval', str_split($rows[$i]));
}

$dirs = [
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];

$ROWS = count($grid);
$COLS = count($grid[0]);
$dp = array_fill(0, $ROWS, array_fill(0, $COLS, 0));

for ($k=9; $k>=0; $k--) {
    for ($i=0; $i<$ROWS; $i++) {
        for ($j=0; $j<$COLS; $j++) {
            if ($grid[$i][$j] === $k) {

                if ($k === 9) {
                    $dp[$i][$j] = 1;
                    continue;
                }

                $sum_rating = 0;
                foreach ($dirs as [$dr, $dc]) {
                    $r = $i + $dr;
                    $c = $j + $dc;
                    if (
                        ($r >=0 && $r < $ROWS)
                        && ($c >=0 && $c < $COLS)
                        && $grid[$r][$c] === $k + 1
                    ) {
                        $sum_rating += $dp[$r][$c];
                    }
                }
                $dp[$i][$j] = $sum_rating;

                if ($k === 0) {
                    $ans += $dp[$i][$j];
                }
            }
        }
    }
}

echo $ans;

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
