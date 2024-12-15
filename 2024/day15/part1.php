<?php

$ans   = 0;
$filename = 'input.txt';
$input = file_get_contents($filename);

[$grid_str, $movement_str] = explode("\n\n", trim($input));
$grid_rows     = explode("\n", $grid_str);
$movement_rows = explode("\n", $movement_str);

$grid  = [];
$start = [];
$row_no = 0;
foreach ($grid_rows as $row) {
    $arr = str_split($row);
    $grid[] = $arr;
    if (($start_col = strpos($row, '@')) !== FALSE) {
        $start = [$row_no, $start_col];
        $grid[$row_no][$start_col] = '.'; // clears robot ('@') from the grid
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


$curr = $start;

foreach ($movement_rows as $row) {
    $moves = str_split($row);
    foreach ($moves as $move) {
        [$dr, $dc] = $dirs[$move];
        $r = $curr[0] + $dr;
        $c = $curr[1] + $dc;
        if ($r < 0 || $r >= $ROWS || $c < 0 || $c >= $COLS || $grid[$r][$c] === '#') {
            // do nothing
        } elseif ($grid[$r][$c] === '.') {
            $curr = [$r, $c]; // move normally
        } elseif ($grid[$r][$c] === 'O') { // stone
            [$fr, $fc] = [$r, $c]; // scan current direction for empty space
            $fr += $dr;
            $fc += $dc;
            while (isset($grid[$fr][$fc])) {
                if ($grid[$fr][$fc] === '#') {
                    break;
                } elseif ($grid[$fr][$fc] === '.') {
                    // pushing stone(s)
                    $grid[$r][$c] = '.';
                    $grid[$fr][$fc] = 'O';
                    $curr = [$r, $c];
                    break;
                }
                $fr += $dr;
                $fc += $dc;
            }
        }
        // uncomment to display the grid after every move
        // echo "execute move $move\n";
        // echo make_grid($grid)."\n\n";
    }
}

// final grid
echo make_grid($grid);

// count GPS coord (our answer)
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === 'O') {
            $ans += ($i*100) + $j;
        }
    }
}

echo "\n\n===> ANSWER: $ans\n";

echo "\n\nMemory usage: ".round(memory_get_usage()/1024, 2)." KB\n";
echo "Peak memory usage: ".round(memory_get_peak_usage()/1024/1024, 2). " MB\n";
