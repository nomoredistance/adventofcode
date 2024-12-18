<?php

$microtime_start = microtime(TRUE);
$filename = 'input.txt';
$input = file_get_contents($filename);
$byte_rows = explode("\n", trim($input));

if ($filename === 'input.txt') {
    $ROWS = $COLS = 71;
} else {
    $ROWS = $COLS = 7;
}
// 
$grid  = array_fill(0, $ROWS, array_fill(0, $COLS, '.'));
$start = [0, 0];
$end   = [$ROWS-1, $COLS-1];

$dirs = [
    '^' => [-1, 0],
    '>' => [ 0, 1],
    'v' => [ 1, 0],
    '<' => [ 0,-1]
];

function make_grid(&$grid) {
    $out = '';
    foreach ($grid as $row) {
        $out .= implode('', $row);
        $out .= "\n";
    }
    return $out;
}

function can_reach_end ($get_path_taken=FALSE) {

    global $grid, $start, $end, $dirs;

    $start_key = $start[0].','.$start[1];
    $visited = [];
    $visited[$start_key] = TRUE;
    $start_path = ($get_path_taken) ? [$start_key] : [];

    $heap = new SplMinHeap();
    $heap->insert([0, $start[0], $start[1], $start_path]); // steps, row, col, path

    while (!$heap->isEmpty()) {

        [$steps, $r, $c, $path] = $heap->extract();

        if ($r === $end[0] && $c === $end[1]) {
            return ($get_path_taken) ? $path : TRUE;
        }

        foreach ($dirs as $new_dir => [$dr, $dc]) {
            $r2 = $r + $dr;
            $c2 = $c + $dc;
            if (isset($grid[$r2][$c2]) && $grid[$r2][$c2] === '.' && !isset($visited[$r2.','.$c2])) {
                $temp = array_merge($path, [$r2.','.$c2]);
                $heap->insert([$steps+1, $r2, $c2, $temp]);
                $visited[$r2.','.$c2] = TRUE;
            }
        }
    }

    return FALSE;
}


// only search for a new path if a byte falls somewhere on our current path
$path_taken = array_flip(can_reach_end(TRUE));
for ($k=0; $k<count($byte_rows); $k++) {
    [$j, $i] = explode(',', $byte_rows[$k]);
    $grid[$i][$j] = '#';

    if (isset($path_taken[$i.','.$j])) {
        $temp = can_reach_end(TRUE);
        if ($temp === FALSE) {
            echo "after row:$k [$j,$i] there is no path to reach the end!\n";
            break;
        }
        $path_taken = array_flip($temp);
    }
}

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
echo "Time spent: ".microtime(TRUE) - $microtime_start." seconds\n";
