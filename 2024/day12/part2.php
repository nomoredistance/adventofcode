<?php

$ans   = 0;
$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$grid  = [];
for ($i=0; $i<count($rows); $i++) {
    if (!$rows[$i]) continue;
    $grid[$i] = str_split($rows[$i]);
}

$dirs = [
    'U'  => [-1, 0],
    'D'  => [ 1, 0],
    'R'  => [ 0, 1],
    'L'  => [ 0,-1]
];

$ROWS = count($grid);
$COLS = count($grid[0]);
$visited = [];

for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {

        if (isset($grid[$i][$j]) && !isset($visited[$i.','.$j])) {

            $plant = $grid[$i][$j];
            $reg_area = $reg_corners = 0;
            $q = new SplQueue();
            $q->enqueue([$i, $j]);
            $visited[$i.','.$j] = TRUE;
            while (!$q->isEmpty()) {
                [$m, $n] = $q->dequeue();
                $reg_area++;
                $corners = 0;
                $up_same_plant = FALSE;
                $down_same_plant = FALSE;
                foreach ($dirs as $dir => [$dr, $dc]) {
                    $r = $m + $dr;
                    $c = $n + $dc;
                    if (isset($grid[$r][$c]) && $grid[$r][$c] === $plant) {
                        if (!isset($visited[$r.','.$c])) {
                            $q->enqueue([$r, $c]);
                            $visited[$r.','.$c] = TRUE;
                        }
                    }
                    // calculates corners
                    if ($dir === 'U' && isset($grid[$r][$c]) && $grid[$r][$c] === $plant) {
                        $up_same_plant = TRUE;
                    } elseif ($dir === 'D' && isset($grid[$r][$c]) && $grid[$r][$c] === $plant) {
                        $down_same_plant = TRUE;
                    } elseif ($dir === 'L' || $dir === 'R') {
                        if (!isset($grid[$r][$c]) || $grid[$r][$c] !== $plant) { // L/R empty
                            if (!$up_same_plant) $corners++;
                            if (!$down_same_plant) $corners++;
                        } else { // L/R same plant
                            if ($up_same_plant && (!isset($grid[$r-1][$c]) || $grid[$r-1][$c] !== $plant)) {
                                $corners++;
                            }
                            if ($down_same_plant && (!isset($grid[$r+1][$c]) || $grid[$r+1][$c] !== $plant)) {
                                $corners++;
                            }
                        }
                    }
                }
                $reg_corners += $corners;
            }
            echo "found region: $plant. area: $reg_area, corners: $reg_corners\n";
            $ans += $reg_area * $reg_corners;
        }
    }
}


echo "\n\n===> ANSWER: $ans\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
