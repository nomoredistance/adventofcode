<?php

$input = file_get_contents('input.txt');
$rows  = explode("\n", $input);

$grid  = $starting_grid = [];
$pos   = $starting_pos = [];
for ($i=0; $i<count($rows); $i++) {
    if (!$rows[$i]) continue;
    $grid[$i] = str_split($rows[$i]);
    if ($j = strpos($rows[$i], '^')) $pos = [$i, $j];
}
$starting_pos = $pos; // save starting position for part 2
$grid[$pos[0]][$pos[1]] = '.'; // replaces '^' with '.'
$starting_grid = $grid;
$grid[$pos[0]][$pos[1]] = 'X';

$dirs = [
    [-1, 0],
    [ 0, 1],
    [ 1, 0],
    [ 0,-1]
];
$dir = $starting_dir = 0;

$ROWS = count($grid);
$COLS = count($grid[0]);
while (TRUE) {
    [$dr, $dc] = $dirs[$dir];
    $r = $pos[0] + $dr;
    $c = $pos[1] + $dc;
    if (!isset($grid[$r][$c])) {
        break;
    } elseif ($grid[$r][$c] === '.') {
        $grid[$r][$c] = 'X';
        $pos = [$r, $c];
    } elseif ($grid[$r][$c] === 'X') {
        $pos = [$r, $c];
    } elseif ($grid[$r][$c] === '#') {
        $dir = ($dir === count($dirs)-1) ? 0 : $dir + 1;
    }
}


$sum_cycle = 0;
for ($i=0; $i<$ROWS; $i++) {
    for ($j=0; $j<$COLS; $j++) {
        if ($grid[$i][$j] === 'X') {
            $starting_grid[$i][$j] = '#';
            if (cycle_exists($starting_grid)) {
                echo "a cycle exists if pos $i,$j is an obstacle\n";
                $sum_cycle++;
            } else {
                echo "no cycle even though pos $i,$j is an obstacle\n";
            }
            $starting_grid[$i][$j] = '.';
        }
    }
}

// echo json_encode($grid)."\n";
echo "\n\n=====> part2 answer: $sum_cycle";

function make_key ($r, $c, $dir) {
    return $r.','.$c.','.$dir;
}

function cycle_exists(&$grid) {

    global $dirs, $starting_dir, $starting_pos;
    $dir = $starting_dir;
    $pos = $starting_pos;
    $key = make_key($pos[0], $pos[1], $dir);
    $visited = [];
    $visited[$key] = TRUE;

    while (TRUE) {
        [$dr, $dc] = $dirs[$dir];
        $r = $pos[0] + $dr;
        $c = $pos[1] + $dc;
        if (!isset($grid[$r][$c])) {
            return FALSE;
        } elseif ($grid[$r][$c] === '.') {
            $key = make_key($r, $c, $dir);
            // echo "checking key $key\n";
            if (isset($visited[$key])) {
                return TRUE;
            }
            $visited[$key] = TRUE;
            $pos = [$r, $c];
            // echo "pos at $r,$c\n";
        } elseif ($grid[$r][$c] === '#') {
            $dir = ($dir === count($dirs)-1) ? 0 : $dir + 1;
        }
    }

}
