<?php

$ans   = 0;
$filename = 'test.txt';
$input = file_get_contents($filename);
$grid_rows = explode("\n", $input);

$grid  = [];
$start = [];
$end   = [];
$row_no = 0;
foreach ($grid_rows as $row) {
    $arr = str_split($row);
    $grid[] = $arr;
    if (($start_col = strpos($row, 'S')) !== FALSE) {
        $start = [$row_no, $start_col];
        $grid[$row_no][$start_col] = '.'; // clears mark ('S') from the grid
    }
    if (($end_col = strpos($row, 'E')) !== FALSE) {
        $end = [$row_no, $end_col];
        $grid[$row_no][$end_col] = '.'; // clears mark ('E') from the grid
    }
    $row_no++;
}

$ROWS = count($grid);
$COLS = count($grid[0]);
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



$visited = [];
$visited[$start[0].','.$start[1]] = TRUE;

$heap = new SplMinHeap();
$heap->insert([0, $start[0], $start[1], '>']); // score, row, col, direction

while (!$heap->isEmpty()) {

    [$score, $r, $c, $cdir] = $heap->extract();
    $visited[$r.','.$c] = TRUE;

    if ($r === $end[0] && $c === $end[1]) {
        echo "reached end in score: $score\n";
        break;
    }

    foreach ($dirs as $new_dir => [$dr, $dc]) {
        $r2 = $r + $dr;
        $c2 = $c + $dc;
        if (isset($grid[$r2][$c2]) && $grid[$r2][$c2] === '.' && !isset($visited[$r2.','.$c2])) {
            $new_score = ($cdir === $new_dir) ? $score + 1 : $score + 1001;
            // echo "queuing $r2, $c2, with score $new_score\n";
            $heap->insert([$new_score, $r2, $c2, $new_dir]);
        }
    }
}

// echo "\n\n===> ANSWER: $ans\n";
// echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
// echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
