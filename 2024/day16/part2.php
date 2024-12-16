<?php

$filename = 'input.txt';
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
$visited[$start[0].','.$start[1]] = [['>' => 0]];

$start_path = [$start[0].','.$start[1]];
$heap = new SplMinHeap();
$heap->insert([0, $start[0], $start[1], '>', $start_path]); // score, row, col, direction, path arr

$all_visited = [];

while (!$heap->isEmpty()) {

    [$score, $r, $c, $cdir, $path] = $heap->extract();

    $visited[$r.','.$c] ??= [];
    $cell_score = PHP_INT_MAX;
    $cell_score = $visited[$r.','.$c][$cdir] ?? $cell_score;

    if ($score > $cell_score) {
        // echo "on $r, $c, $score is larger than $cell_score\n";
        continue;
    }
    $visited[$r.','.$c][$cdir] = $score;
    $path[] = $r.','.$c;

    if ($r === $end[0] && $c === $end[1]) {
        foreach ($dirs as $dir => $delta) {
            $visited[$r.','.$c][$dir] = $score;
        }
        echo "reached the end with score: $score, dir: $cdir\n";

        // 
        if (TRUE) { // set this to TRUE to display the path taken
            $temp_grid = $grid;
            foreach ($path as $coord) {
                [$i, $j] = explode(',', $coord);
                $temp_grid[$i][$j] = 'O';
            }
            $temp_grid[$start[0]][$start[1]] = 'S';
            $temp_grid[$end[0]][$end[1]] = 'E';
            echo make_grid($temp_grid);
        }

        $all_visited = array_merge($all_visited, $path);
    }

    foreach ($dirs as $new_dir => [$dr, $dc]) {

        if ( // disallows turning backwards
            ($cdir === '^' && $new_dir === 'v')
            || ($cdir === 'v' && $new_dir === '^')
            || ($cdir === '<' && $new_dir === '>')
            || ($cdir === '>' && $new_dir === '<')
        ) {
            continue;
        }
        $r2 = $r + $dr;
        $c2 = $c + $dc;
        if (isset($grid[$r2][$c2]) && $grid[$r2][$c2] === '.') {
            $new_score = ($cdir === $new_dir) ? $score + 1 : $score + 1001;
            $heap->insert([$new_score, $r2, $c2, $new_dir, $path]);
        }
    }
}

echo "\n\n===> PART2 ANSWER: ".count(array_unique($all_visited))."\n";
echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
