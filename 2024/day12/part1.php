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
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];

$ROWS = count($grid);
$COLS = count($grid[0]);
$visited = [];

for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {

        if (isset($grid[$i][$j]) && !isset($visited[$i.','.$j])) {

            $plant = $grid[$i][$j];
            $reg_area = $reg_perim = 0;
            $q = new SplQueue();
            $q->enqueue([$i, $j]);
            $visited[$i.','.$j] = TRUE;
            while (!$q->isEmpty()) {
                [$m, $n] = $q->dequeue();
                $reg_area++;
                $perim = 4;
                foreach ($dirs as [$dr, $dc]) {
                    $r = $m + $dr;
                    $c = $n + $dc;
                    if (isset($grid[$r][$c]) && $grid[$r][$c] === $plant) {
                        $perim--;
                        if (!isset($visited[$r.','.$c])) {
                            $q->enqueue([$r, $c]);
                            $visited[$r.','.$c] = TRUE;
                        }
                    }
                }
                $reg_perim += $perim;
            }
            echo "found region: $plant. area: $reg_area, perim: $reg_perim\n";
            $ans += $reg_area * $reg_perim;
        }
    }
}


echo "\n\n===> ANSWER: $ans\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
